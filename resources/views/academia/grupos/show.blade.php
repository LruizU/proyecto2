@extends('layouts.admin')

@section('title', $grupo->codigo_grupo . ' - ' . $ciclo->label)
@section('breadcrumb', 'Academia › Grupos › ' . $grupo->codigo_grupo)

@section('content')
@php
    $partesCodigo = $grupo->codigo_grupo_partes;
@endphp
<x-page-header title="{{ $grupo->codigo_grupo }}" subtitle="{{ $grupo->grado }}° · {{ $grupo->turno_nombre }} · {{ $grupo->nivelRel?->descripcion ?? $grupo->nivel }} · {{ $grupo->modalidad_nombre }} · {{ $grupo->inscritos }} inscritos · {{ $grupo->sede?->descripcion }}" :hide-title="false">
    @slot('actions')
        @if (auth()->user()->canAccessModule('academia.grupos', 'asistencia'))
            <div class="btn-group btn-group-sm">
                <a href="{{ route('academia.grupos.asistencia', $grupo) }}" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> Asistencia
                </a>
            </div>
        @endif
    @endslot
</x-page-header>

<p class="text-muted small mb-4">
    Plan {{ $partesCodigo['anio_plan'] ?? '—' }} · Nivel {{ $partesCodigo['nivel'] ?? $grupo->nivel }} ·
    Sede código {{ $partesCodigo['sede'] ?? '—' }} · Modelo {{ $partesCodigo['modelo'] ?? '—' }} ·
    Grado/grupo {{ $partesCodigo['grado_grupo'] ?? '—' }}
    @if ($partesCodigo['nivel_superior']) · Ingeniería/Licenciatura ({{ $partesCodigo['nivel_superior'] }}) @endif
</p>

{{-- Tabs --}}
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-alumnos">Alumnos ({{ $alumnos->total() }})</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-horarios">Horarios</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-conflictos">Conflictos Aula</button></li>
</ul>

<div class="tab-content">
    {{-- Alumnos --}}
    <div class="tab-pane fade show active" id="tab-alumnos">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Nombre</th>
                                <th>Nivel / Carrera</th>
                                <th>Contacto</th>
                                <th>Estatus académico</th>
                                <th>Estatus en grupo</th>
                                <th>Inscripción</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alumnos as $alumnoGrupo)
                                <tr>
                                    <td class="fw-semibold">{{ $alumnoGrupo->numero_alumno }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $alumnoGrupo->nombre_completo }}</div>
                                        <small class="text-muted">CURP: {{ $alumnoGrupo->curp ?: 'No registrada' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $alumnoGrupo->nivelRel?->descripcion ?? $alumnoGrupo->nivel ?? '—' }}</div>
                                        <small class="text-muted">{{ $alumnoGrupo->carrera ?: 'Carrera no registrada' }}</small>
                                    </td>
                                    <td class="small">
                                        <div>{{ $alumnoGrupo->telefono ?: 'Sin teléfono' }}</div>
                                        <div class="text-muted">{{ $alumnoGrupo->email ?: 'Sin correo' }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $estatusAcademico = strtoupper((string) $alumnoGrupo->estatus);
                                            $estatusAcademicoClase = $estatusAcademico === 'ACTIVO' ? 'badge--active' : 'badge--inactive';
                                        @endphp
                                        <span class="badge badge--status {{ $estatusAcademicoClase }}">
                                            {{ $alumnoGrupo->estatus ?: '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge--status {{ ($alumnoGrupo->pivot_estatus ?? null) === 'INSCRITO' ? 'badge--active' : 'badge--inactive' }}">
                                            {{ $alumnoGrupo->pivot_estatus ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">{{ $alumnoGrupo->pivot_fecha_inscripcion ? \Carbon\Carbon::parse($alumnoGrupo->pivot_fecha_inscripcion)->format('d/m/Y') : '—' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('academia.alumnos.show', $alumnoGrupo) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $alumnos->links() }}
        </div>
    </div>

    {{-- Horarios --}}
    <div class="tab-pane fade" id="tab-horarios">
        @if ($horarios->isEmpty())
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-calendar-x fs-1 mb-2"></i>
                    <p>No hay horarios programados para este grupo</p>
                </div>
            </div>
        @else
            @php
                $diasSemana = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
                $sesionesHorario = $horarios->flatten(1)
                    ->sortBy(fn ($clase) => $clase->sesion)
                    ->pluck('sesion')
                    ->filter()
                    ->unique()
                    ->values();
                $horarioGrid = $horarios->flatten(1)->groupBy(fn ($clase) => $clase->dia . '-' . $clase->sesion);
            @endphp
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h2 class="h6 mb-1">Horario semanal</h2>
                        <span class="small text-muted">Distribución de clases por sesión y día</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary">{{ $sesionesHorario->count() }} sesiones</span>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="table-responsive horario-semanal-wrap">
                        <table class="table horario-semanal-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="horario-hora-col">Sesión / hora</th>
                                    @foreach ($diasSemana as $numeroDia => $nombreDia)
                                        <th class="text-center {{ $numeroDia >= 6 ? 'horario-fin-semana' : '' }}">{{ $nombreDia }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sesionesHorario as $sesion)
                                    @php
                                        $sesionClase = $horarios->flatten(1)->firstWhere('sesion', $sesion);
                                        $horaInicio = $sesionClase?->sesionBase?->hora_inicio?->format('H:i');
                                        $horaFin = $sesionClase?->sesionBase?->hora_fin?->format('H:i');
                                    @endphp
                                    <tr>
                                        <th class="horario-hora-cell">
                                            <span class="fw-semibold">Ses. {{ $sesion }}</span>
                                            <small>{{ $horaInicio }} - {{ $horaFin }}</small>
                                        </th>
                                        @foreach ($diasSemana as $numeroDia => $nombreDia)
                                            <td class="{{ $numeroDia >= 6 ? 'horario-fin-semana' : '' }}">
                                                @forelse ($horarioGrid->get($numeroDia . '-' . $sesion, collect()) as $clase)
                                                    <div class="horario-clase">
                                                        <div class="horario-materia">{{ $clase->materia?->nombre_asignatura ?? 'Materia no asignada' }}</div>
                                                        <div class="horario-codigo">{{ $clase->clave_asignatura ?: 'Sin código' }}</div>
                                                        <div class="horario-detalle">
                                                            {{ $clase->profesor?->nombre_completo ?? 'Sin docente' }}
                                                            <span>{{ $clase->ubicacion ?: 'Aula no asignada' }}</span>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <span class="horario-vacio">—</span>
                                                @endforelse
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Conflictos de aula --}}
    <div class="tab-pane fade" id="tab-conflictos">
        @if (empty($conflictos))
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
                    <p>No se detectaron conflictos de aula</p>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-header bg-danger-subtle">
                    <span class="fw-bold text-danger">Se detectaron {{ count($conflictos) }} conflicto(s) de aula</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Día</th>
                                    <th>Sesión</th>
                                    <th>Sede</th>
                                    <th>Edificio</th>
                                    <th>Aula</th>
                                    <th>Clases en conflicto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($conflictos as $c)
                                    <tr>
                                        <td>{{ ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'][$c->dia-1] }}</td>
                                        <td>{{ $c->sesion }}</td>
                                        <td>{{ $c->id_campus }}</td>
                                        <td>{{ $c->edificio }}</td>
                                        <td>{{ $c->aula }}</td>
                                        <td><span class="badge bg-danger">{{ $c->total }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .horario-semanal-wrap { overflow-x: auto; }
    .horario-semanal-table { min-width: 980px; --bs-table-bg: var(--surface-1); --bs-table-color: var(--text); --bs-table-border-color: var(--border); }
    .horario-semanal-table th,
    .horario-semanal-table td { border-color: var(--border); }
    .horario-semanal-table thead th { background: var(--surface-2); color: var(--text-secondary); font-size: .72rem; text-transform: uppercase; letter-spacing: .04em; padding: .75rem .65rem; }
    .horario-hora-col { width: 125px; }
    .horario-hora-cell { background: var(--surface-2); color: var(--text); padding: .75rem .65rem; vertical-align: top; }
    .horario-hora-cell small { display: block; color: var(--text-secondary); font-weight: 400; margin-top: .25rem; white-space: nowrap; }
    .horario-semanal-table td { width: 125px; min-width: 125px; height: 92px; padding: .45rem; vertical-align: top; }
    .horario-clase { min-height: 76px; padding: .55rem; border: 1px solid color-mix(in srgb, var(--primary) 35%, var(--border)); border-left: 3px solid var(--primary); border-radius: .5rem; background: color-mix(in srgb, var(--primary) 8%, var(--surface-1)); }
    .horario-materia { color: var(--text); font-weight: 700; font-size: .78rem; line-height: 1.25; }
    .horario-codigo { color: var(--primary); font-family: 'JetBrains Mono', monospace; font-size: .68rem; margin-top: .2rem; }
    .horario-detalle { color: var(--text-secondary); font-size: .68rem; line-height: 1.3; margin-top: .45rem; }
    .horario-detalle span { display: block; color: var(--text-tertiary); margin-top: .15rem; }
    .horario-vacio { color: var(--text-tertiary); display: block; text-align: center; padding-top: 1.4rem; }
    .horario-fin-semana { background: color-mix(in srgb, var(--text) 3%, var(--surface-1)) !important; }
    @media (max-width: 640px) {
        .horario-semanal-table { min-width: 860px; }
        .horario-semanal-table td { height: 84px; }
    }
</style>
@endpush