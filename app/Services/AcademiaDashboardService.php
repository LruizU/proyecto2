<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Academia\AlumnoGrupo;
use App\Models\Academia\Ciclo;
use App\Models\Academia\Curso;
use App\Models\Academia\Grupo;
use App\Models\Academia\HorarioDet;
use App\Models\Academia\Materia;
use App\Models\Academia\Sede;
use Illuminate\Support\Collection;

class AcademiaDashboardService
{
    /**
     * @return array<string, mixed>
     */
    public function build(Ciclo $ciclo): array
    {
        $cycle = [$ciclo->inicial, $ciclo->final, $ciclo->periodo];
        $groups = Grupo::porCiclo(...$cycle)->activo();
        $enrollments = AlumnoGrupo::query()
            ->where('inicial', $ciclo->inicial)
            ->where('final', $ciclo->final)
            ->where('periodo', $ciclo->periodo);
        $courses = Curso::porCiclo(...$cycle)->activo();
        $schedules = HorarioDet::porCiclo(...$cycle)->activo();

        $materiaKeys = (clone $schedules)->distinct()->pluck('clave_asignatura');
        $cursoMateriaKeys = (clone $courses)->distinct()->pluck('clave_asignatura');
        $allMateriaKeys = $materiaKeys->merge($cursoMateriaKeys)->unique()->filter();

        $planIds = (clone $courses)->whereNotNull('id_plan')->distinct()->pluck('id_plan');
        if ($allMateriaKeys->isNotEmpty()) {
            $planIdsFromMaterias = Materia::whereIn('clave_asignatura', $allMateriaKeys)
                ->whereNotNull('id_plan')
                ->distinct()
                ->pluck('id_plan');
            $planIds = $planIds->merge($planIdsFromMaterias)->unique()->filter();
        }

        $turnosCollection = $this->turnosNormalizados($ciclo);
        $sedesMap = Sede::query()->pluck('descripcion', 'id_campus')->toArray();

        $kpis = [
            'alumnos' => (clone $enrollments)->distinct()->count('numero_alumno'),
            'grupos' => (clone $groups)->count(),
            'profesores' => (clone $schedules)->distinct()->count('clave_profesor'),
            'horarios' => (clone $schedules)->distinct()->count('codigo_grupo'),
            'cursos' => (clone $courses)->count(),
            'planes' => $planIds->count() ?: (clone $courses)->distinct()->count('id_plan'),
            'materias' => $allMateriaKeys->count() ?: (clone $courses)->distinct()->count('clave_asignatura'),
            'niveles' => (clone $groups)->distinct()->count('nivel'),
            'turnos' => $turnosCollection->count(),
        ];

        return [
            'kpis' => $kpis,
            'sedesMap' => $sedesMap,
            'alumnosPorGrupo' => $this->studentsByGroup($ciclo),
            'cursosPorSede' => $this->coursesByCampus($ciclo),
            'profesoresPorOrigen' => $this->professorsByOrigin($ciclo),
            'horasPorOrigen' => $this->hoursByOrigin($ciclo),
            'cursosPorOrigen' => $this->coursesByOrigin($ciclo),
            'cargaPorMaestro' => $this->cargaPorMaestro($ciclo),
            'niveles' => $this->groupsByColumn($ciclo, 'nivel'),
            'turnos' => $turnosCollection,
            'dataQuality' => [
                'hoursCaptured' => (clone $schedules)->where(function ($query): void {
                    $query->where('horas_semanales', '>', 0)
                        ->orWhere('horas_teoria_practica', '>', 0);
                })->exists(),
            ],
        ];
    }

    private function studentsByGroup(Ciclo $ciclo): Collection
    {
        $sedesMap = Sede::query()->pluck('descripcion', 'id_campus')->toArray();

        $rows = AlumnoGrupo::query()
            ->leftJoin('grupos', function ($join) use ($ciclo): void {
                $join->on('grupos.codigo_grupo', '=', 'alumnos_grupos.codigo_grupo')
                    ->where('grupos.inicial', $ciclo->inicial)
                    ->where('grupos.final', $ciclo->final)
                    ->where('grupos.periodo', $ciclo->periodo)
                    ->where('grupos.activo', true);
            })
            ->where('alumnos_grupos.inicial', $ciclo->inicial)
            ->where('alumnos_grupos.final', $ciclo->final)
            ->where('alumnos_grupos.periodo', $ciclo->periodo)
            ->select('grupos.codigo_grupo', 'grupos.grado', 'grupos.tipo_grupo', 'grupos.id_campus')
            ->selectRaw('COUNT(DISTINCT alumnos_grupos.numero_alumno) AS alumnos')
            ->groupBy('grupos.codigo_grupo', 'grupos.grado', 'grupos.tipo_grupo', 'grupos.id_campus')
            ->orderBy('grupos.codigo_grupo')
            ->get();

        return $rows->map(function ($r) use ($sedesMap): object {
            $partes = explode('-', strtoupper(trim((string) $r->codigo_grupo)));
            $mod = $partes[2] ?? ($r->tipo_grupo ?: 'TR');
            $sedeId = (int) ($partes[1] ?? ($r->id_campus ?: 1));
            $is3C = isset($partes[4]) && $partes[4] === '3C';

            $modName = match ($mod) {
                'I' => 'INTENSIVO',
                'B' => 'BIS',
                'D' => 'DESPRESURIZADO',
                'M' => 'MIXTO',
                default => 'TRADICIONAL',
            };

            $sedeName = $sedesMap[$sedeId] ?? ($sedeId ? "Sede {$sedeId}" : 'Campus Principal');

            return (object) [
                'codigo_grupo'   => $r->codigo_grupo,
                'grado'          => $r->grado,
                'tipo_grupo'     => $mod,
                'modalidad_corta'=> $mod,
                'modalidad'      => $modName,
                'id_campus'      => $sedeId,
                'sede'           => $sedeName,
                'es_tercer_ciclo'=> $is3C,
                'alumnos'        => (int) $r->alumnos,
                'grupos_count'   => 1,
            ];
        })->values();
    }

    private function coursesByCampus(Ciclo $ciclo): Collection
    {
        $sedesMap = Sede::query()->pluck('descripcion', 'id_campus')->toArray();

        return Curso::porCiclo($ciclo->inicial, $ciclo->final, $ciclo->periodo)
            ->activo()
            ->select('id_campus')
            ->selectRaw('COUNT(*) AS cursos')
            ->selectRaw('COUNT(DISTINCT id_plan) AS planes')
            ->selectRaw('COUNT(DISTINCT clave_asignatura) AS materias')
            ->groupBy('id_campus')
            ->orderBy('id_campus')
            ->get()
            ->map(function ($c) use ($sedesMap) {
                $c->sede_nombre = $sedesMap[$c->id_campus] ?? ($c->id_campus ? "Sede {$c->id_campus}" : 'Campus Principal');

                return $c;
            });
    }

    private function professorsByOrigin(Ciclo $ciclo): Collection
    {
        return HorarioDet::query()
            ->join('profesores', 'profesores.clave_profesor', '=', 'horarios_det.clave_profesor')
            ->porCiclo($ciclo->inicial, $ciclo->final, $ciclo->periodo)
            ->activo()
            ->selectRaw("COALESCE(profesores.origen_horario, 'SIN_DEFINIR') AS origen")
            ->selectRaw('COUNT(DISTINCT profesores.clave_profesor) AS profesores')
            ->groupBy('profesores.origen_horario')
            ->get();
    }

    private function hoursByOrigin(Ciclo $ciclo): Collection
    {
        return HorarioDet::query()
            ->join('profesores', 'profesores.clave_profesor', '=', 'horarios_det.clave_profesor')
            ->porCiclo($ciclo->inicial, $ciclo->final, $ciclo->periodo)
            ->activo()
            ->selectRaw("COALESCE(profesores.origen_horario, 'SIN_DEFINIR') AS origen")
            ->selectRaw('COUNT(*) AS clases')
            ->selectRaw('SUM(COALESCE(NULLIF(horarios_det.horas_semanales, 0), horarios_det.horas_teoria_practica, 0)) AS horas')
            ->groupBy('profesores.origen_horario')
            ->get();
    }

    private function coursesByOrigin(Ciclo $ciclo): Collection
    {
        $sedesMap = Sede::query()->pluck('descripcion', 'id_campus')->toArray();

        return Curso::query()
            ->leftJoin('profesores', 'profesores.clave_profesor', '=', 'cursos.clave_profesor')
            ->porCiclo($ciclo->inicial, $ciclo->final, $ciclo->periodo)
            ->activo()
            ->select('cursos.clave_curso', 'cursos.nombre_curso', 'cursos.id_campus')
            ->selectRaw("COALESCE(profesores.origen_horario, 'SIN_DEFINIR') AS origen")
            ->selectRaw('COALESCE(cursos.sesiones, 0) AS sesiones')
            ->orderBy('cursos.clave_curso')
            ->get()
            ->map(function ($c) use ($sedesMap) {
                $c->sede_nombre = $sedesMap[$c->id_campus] ?? ($c->id_campus ? "Sede {$c->id_campus}" : 'Campus Principal');

                return $c;
            });
    }

    private function turnosNormalizados(Ciclo $ciclo): Collection
    {
        return Grupo::porCiclo($ciclo->inicial, $ciclo->final, $ciclo->periodo)
            ->activo()
            ->whereNotNull('turno')
            ->selectRaw("CASE 
                WHEN UPPER(turno) LIKE 'M%' THEN 'MATUTINO' 
                WHEN UPPER(turno) LIKE 'V%' THEN 'VESPERTINO' 
                ELSE 'OTRO' 
            END AS turno")
            ->selectRaw('COUNT(*) AS grupos')
            ->groupBy('turno')
            ->orderBy('turno')
            ->get();
    }

    private function groupsByColumn(Ciclo $ciclo, string $column): Collection
    {
        return Grupo::porCiclo($ciclo->inicial, $ciclo->final, $ciclo->periodo)
            ->activo()
            ->select($column)
            ->selectRaw('COUNT(*) AS grupos')
            ->groupBy($column)
            ->orderBy($column)
            ->get();
    }

    public function cargaPorMaestro(Ciclo $ciclo): Collection
    {
        $sedesMap = Sede::query()->pluck('descripcion', 'id_campus')->toArray();

        $horarios = HorarioDet::query()
            ->where('horarios_det.inicial', $ciclo->inicial)
            ->where('horarios_det.final', $ciclo->final)
            ->where('horarios_det.periodo', $ciclo->periodo)
            ->where('horarios_det.activo', true)
            ->leftJoin('grupos', function ($join) use ($ciclo): void {
                $join->on('grupos.codigo_grupo', '=', 'horarios_det.codigo_grupo')
                    ->where('grupos.inicial', $ciclo->inicial)
                    ->where('grupos.final', $ciclo->final)
                    ->where('grupos.periodo', $ciclo->periodo);
            })
            ->join('profesores', 'profesores.clave_profesor', '=', 'horarios_det.clave_profesor')
            ->leftJoin('materias', 'materias.clave_asignatura', '=', 'horarios_det.clave_asignatura')
            ->leftJoin('planes', 'planes.id_plan', '=', 'materias.id_plan')
            ->selectRaw('horarios_det.clave_profesor')
            ->selectRaw('profesores.nombre_profesor')
            ->selectRaw('profesores.paterno')
            ->selectRaw('profesores.materno')
            ->selectRaw('profesores.origen_horario')
            ->selectRaw('profesores.id_campus')
            ->selectRaw("COALESCE(NULLIF(planes.nombre_plan, ''), NULLIF(grupos.carrera, '')) AS carrera")
            ->selectRaw('grupos.nivel')
            ->selectRaw('grupos.turno')
            ->selectRaw('grupos.id_campus AS grupo_id_campus')
            ->selectRaw('SUM(COALESCE(NULLIF(horarios_det.horas_semanales, 0), NULLIF(horarios_det.horas_teoria_practica, 0), 1)) AS horas_clase')
            ->groupBy('horarios_det.clave_profesor', 'profesores.nombre_profesor', 'profesores.paterno', 'profesores.materno', 'profesores.origen_horario', 'profesores.id_campus', 'planes.nombre_plan', 'grupos.carrera', 'grupos.nivel', 'grupos.turno', 'grupos.id_campus')
            ->get();

        $cursos = Curso::query()
            ->where('cursos.inicial', $ciclo->inicial)
            ->where('cursos.final', $ciclo->final)
            ->where('cursos.periodo', $ciclo->periodo)
            ->where('cursos.activo', true)
            ->whereNotNull('cursos.clave_profesor')
            ->leftJoin('profesores', 'profesores.clave_profesor', '=', 'cursos.clave_profesor')
            ->leftJoin('grupos', function ($join) use ($ciclo): void {
                $join->on('grupos.codigo_grupo', '=', 'cursos.codigo_grupo')
                    ->where('grupos.inicial', $ciclo->inicial)
                    ->where('grupos.final', $ciclo->final)
                    ->where('grupos.periodo', $ciclo->periodo);
            })
            ->leftJoin('materias', 'materias.clave_asignatura', '=', 'cursos.clave_asignatura')
            ->leftJoin('planes', 'planes.id_plan', '=', 'materias.id_plan')
            ->selectRaw('cursos.clave_profesor')
            ->selectRaw('profesores.nombre_profesor')
            ->selectRaw('profesores.paterno')
            ->selectRaw('profesores.materno')
            ->selectRaw('profesores.origen_horario')
            ->selectRaw("COALESCE(NULLIF(planes.nombre_plan, ''), NULLIF(grupos.carrera, '')) AS carrera")
            ->selectRaw("COALESCE(NULLIF(grupos.nivel, ''), NULLIF(cursos.nivel, '')) AS nivel")
            ->selectRaw("COALESCE(NULLIF(grupos.turno, ''), NULLIF(cursos.turno, '')) AS turno")
            ->selectRaw('COALESCE(grupos.id_campus, cursos.id_campus, 1) AS id_campus')
            ->selectRaw('SUM(COALESCE(NULLIF(cursos.sesiones, 0), 1)) AS horas_cursos')
            ->groupBy('cursos.clave_profesor', 'profesores.nombre_profesor', 'profesores.paterno', 'profesores.materno', 'profesores.origen_horario', 'planes.nombre_plan', 'grupos.carrera', 'grupos.nivel', 'cursos.nivel', 'grupos.turno', 'cursos.turno', 'grupos.id_campus', 'cursos.id_campus')
            ->get();

        $rows = [];
        foreach ($horarios as $h) {
            $sedeId = $h->grupo_id_campus ?: $h->id_campus ?: 1;
            $turno = $this->normalizarTurno($h->turno);
            $blockKey = implode('|', [$sedeId, $turno]);
            $teacherKey = (string) $h->clave_profesor;

            if (! isset($rows[$teacherKey])) {
                $rows[$teacherKey] = [
                    'clave_profesor' => $h->clave_profesor,
                    'nombre' => trim("{$h->paterno} {$h->materno} {$h->nombre_profesor}"),
                    'origen' => $h->origen_horario ?? 'SIN_DEFINIR',
                    'blocks' => [],
                    'carreras' => [],
                    'niveles' => [],
                ];
            }

            $rows[$teacherKey]['blocks'][$blockKey]['sede_id'] = $sedeId;
            $rows[$teacherKey]['blocks'][$blockKey]['turno'] = $turno;
            $rows[$teacherKey]['blocks'][$blockKey]['horas_grupo'] =
                ($rows[$teacherKey]['blocks'][$blockKey]['horas_grupo'] ?? 0) + (int) $h->horas_clase;
            $rows[$teacherKey]['blocks'][$blockKey]['horas_estadia'] =
                $rows[$teacherKey]['blocks'][$blockKey]['horas_estadia'] ?? 0;

            if (! empty($h->carrera)) {
                $rows[$teacherKey]['carreras'][$h->carrera] = true;
            }
            if (! empty($h->nivel)) {
                $rows[$teacherKey]['niveles'][$h->nivel] = true;
            }
        }

        foreach ($cursos as $c) {
            $teacherKey = (string) $c->clave_profesor;
            $sedeId = $c->id_campus ?: 1;
            $turno = $this->normalizarTurno($c->turno);
            $blockKey = implode('|', [$sedeId, $turno]);

            if (! isset($rows[$teacherKey])) {
                $rows[$teacherKey] = [
                    'clave_profesor' => $c->clave_profesor,
                    'nombre' => trim("{$c->paterno} {$c->materno} {$c->nombre_profesor}"),
                    'origen' => $c->origen_horario ?? 'SIN_DEFINIR',
                    'blocks' => [],
                    'carreras' => [],
                    'niveles' => [],
                ];
            }

            $rows[$teacherKey]['blocks'][$blockKey]['sede_id'] = $sedeId;
            $rows[$teacherKey]['blocks'][$blockKey]['turno'] = $turno;
            $rows[$teacherKey]['blocks'][$blockKey]['horas_grupo'] =
                $rows[$teacherKey]['blocks'][$blockKey]['horas_grupo'] ?? 0;
            $rows[$teacherKey]['blocks'][$blockKey]['horas_estadia'] =
                ($rows[$teacherKey]['blocks'][$blockKey]['horas_estadia'] ?? 0) + (int) $c->horas_cursos;

            if (! empty($c->carrera)) {
                $rows[$teacherKey]['carreras'][$c->carrera] = true;
            }
            if (! empty($c->nivel)) {
                $rows[$teacherKey]['niveles'][$c->nivel] = true;
            }
        }

        return collect($rows)->map(function (array $row) use ($sedesMap): object {
            $blocks = collect($row['blocks'])->map(function (array $block) use ($sedesMap): object {
                $block['sede'] = $sedesMap[$block['sede_id']] ?? "Sede {$block['sede_id']}";
                $block['total'] = $block['horas_grupo'] + $block['horas_estadia'];

                return (object) $block;
            })->sortBy(['sede', 'turno'])->values();
            $horasGrupo = $blocks->sum('horas_grupo');
            $horasEstadia = $blocks->sum('horas_estadia');

            return (object) [
                'clave_profesor' => $row['clave_profesor'],
                'nombre' => $row['nombre'],
                'origen' => $row['origen'],
                'carreras' => implode(', ', array_keys($row['carreras'])),
                'niveles' => implode(', ', array_keys($row['niveles'])),
                'blocks' => $blocks,
                'horas_grupo' => $horasGrupo,
                'horas_estadia' => $horasEstadia,
                'total_rh' => $horasGrupo + $horasEstadia,
                'otro_turno' => $blocks->pluck('turno')->unique()->count() > 1 ? 'Sí' : 'No',
                'observaciones' => '',
            ];
        })->sortBy('nombre')->values();
    }

    private function normalizarTurno(?string $turno): string
    {
        return match (strtoupper(substr(trim((string) $turno), 0, 1))) {
            'M' => 'MATUTINO',
            'V' => 'VESPERTINO',
            default => strtoupper($turno ?: 'N/D'),
        };
    }
}
