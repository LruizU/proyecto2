<?php $__env->startSection('title', $grupo->codigo_grupo . ' - ' . $ciclo->label); ?>
<?php $__env->startSection('breadcrumb', 'Academia › Grupos › ' . $grupo->codigo_grupo); ?>

<?php $__env->startSection('content'); ?>
<?php
    $partesCodigo = $grupo->codigo_grupo_partes;
?>
<?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => ''.e($grupo->codigo_grupo).'','subtitle' => ''.e($grupo->grado).'° · '.e($grupo->turno_nombre).' · '.e($grupo->nivelRel?->descripcion ?? $grupo->nivel).' · '.e($grupo->modalidad_nombre).' · '.e($grupo->inscritos).' inscritos · '.e($grupo->sede?->descripcion).'','hideTitle' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($grupo->codigo_grupo).'','subtitle' => ''.e($grupo->grado).'° · '.e($grupo->turno_nombre).' · '.e($grupo->nivelRel?->descripcion ?? $grupo->nivel).' · '.e($grupo->modalidad_nombre).' · '.e($grupo->inscritos).' inscritos · '.e($grupo->sede?->descripcion).'','hide-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
    <?php $__env->slot('actions'); ?>
        <?php if(auth()->user()->canAccessModule('academia.grupos', 'asistencia')): ?>
            <div class="btn-group btn-group-sm">
                <a href="<?php echo e(route('academia.grupos.asistencia', $grupo)); ?>" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> Asistencia
                </a>
            </div>
        <?php endif; ?>
    <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $attributes = $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e)): ?>
<?php $component = $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e; ?>
<?php unset($__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e); ?>
<?php endif; ?>

<p class="text-muted small mb-4">
    Plan <?php echo e($partesCodigo['anio_plan'] ?? '—'); ?> · Nivel <?php echo e($partesCodigo['nivel'] ?? $grupo->nivel); ?> ·
    Sede código <?php echo e($partesCodigo['sede'] ?? '—'); ?> · Modelo <?php echo e($partesCodigo['modelo'] ?? '—'); ?> ·
    Grado/grupo <?php echo e($partesCodigo['grado_grupo'] ?? '—'); ?>

    <?php if($partesCodigo['nivel_superior']): ?> · Ingeniería/Licenciatura (<?php echo e($partesCodigo['nivel_superior']); ?>) <?php endif; ?>
</p>


<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-alumnos">Alumnos (<?php echo e($alumnos->total()); ?>)</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-horarios">Horarios</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-conflictos">Conflictos Aula</button></li>
</ul>

<div class="tab-content">
    
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
                            <?php $__currentLoopData = $alumnos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alumnoGrupo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo e($alumnoGrupo->numero_alumno); ?></td>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($alumnoGrupo->nombre_completo); ?></div>
                                        <small class="text-muted">CURP: <?php echo e($alumnoGrupo->curp ?: 'No registrada'); ?></small>
                                    </td>
                                    <td>
                                        <div><?php echo e($alumnoGrupo->nivelRel?->descripcion ?? $alumnoGrupo->nivel ?? '—'); ?></div>
                                        <small class="text-muted"><?php echo e($alumnoGrupo->carrera ?: 'Carrera no registrada'); ?></small>
                                    </td>
                                    <td class="small">
                                        <div><?php echo e($alumnoGrupo->telefono ?: 'Sin teléfono'); ?></div>
                                        <div class="text-muted"><?php echo e($alumnoGrupo->email ?: 'Sin correo'); ?></div>
                                    </td>
                                    <td>
                                        <?php
                                            $estatusAcademico = strtoupper((string) $alumnoGrupo->estatus);
                                            $estatusAcademicoClase = $estatusAcademico === 'ACTIVO' ? 'badge--active' : 'badge--inactive';
                                        ?>
                                        <span class="badge badge--status <?php echo e($estatusAcademicoClase); ?>">
                                            <?php echo e($alumnoGrupo->estatus ?: '—'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge--status <?php echo e(($alumnoGrupo->pivot_estatus ?? null) === 'INSCRITO' ? 'badge--active' : 'badge--inactive'); ?>">
                                            <?php echo e($alumnoGrupo->pivot_estatus ?? '—'); ?>

                                        </span>
                                    </td>
                                    <td class="small text-muted"><?php echo e($alumnoGrupo->pivot_fecha_inscripcion ? \Carbon\Carbon::parse($alumnoGrupo->pivot_fecha_inscripcion)->format('d/m/Y') : '—'); ?></td>
                                    <td class="text-end">
                                        <a href="<?php echo e(route('academia.alumnos.show', $alumnoGrupo)); ?>" class="btn btn-sm btn-outline-primary">Ver</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php echo e($alumnos->links()); ?>

        </div>
    </div>

    
    <div class="tab-pane fade" id="tab-horarios">
        <?php if($horarios->isEmpty()): ?>
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-calendar-x fs-1 mb-2"></i>
                    <p>No hay horarios programados para este grupo</p>
                </div>
            </div>
        <?php else: ?>
            <?php
                $diasSemana = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
                $sesionesHorario = $horarios->flatten(1)
                    ->sortBy(fn ($clase) => $clase->sesion)
                    ->pluck('sesion')
                    ->filter()
                    ->unique()
                    ->values();
                $horarioGrid = $horarios->flatten(1)->groupBy(fn ($clase) => $clase->dia . '-' . $clase->sesion);
            ?>
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h2 class="h6 mb-1">Horario semanal</h2>
                        <span class="small text-muted">Distribución de clases por sesión y día</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary"><?php echo e($sesionesHorario->count()); ?> sesiones</span>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="table-responsive horario-semanal-wrap">
                        <table class="table horario-semanal-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="horario-hora-col">Sesión / hora</th>
                                    <?php $__currentLoopData = $diasSemana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $numeroDia => $nombreDia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center <?php echo e($numeroDia >= 6 ? 'horario-fin-semana' : ''); ?>"><?php echo e($nombreDia); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $sesionesHorario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sesion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $sesionClase = $horarios->flatten(1)->firstWhere('sesion', $sesion);
                                        $horaInicio = $sesionClase?->sesionBase?->hora_inicio?->format('H:i');
                                        $horaFin = $sesionClase?->sesionBase?->hora_fin?->format('H:i');
                                    ?>
                                    <tr>
                                        <th class="horario-hora-cell">
                                            <span class="fw-semibold">Ses. <?php echo e($sesion); ?></span>
                                            <small><?php echo e($horaInicio); ?> - <?php echo e($horaFin); ?></small>
                                        </th>
                                        <?php $__currentLoopData = $diasSemana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $numeroDia => $nombreDia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <td class="<?php echo e($numeroDia >= 6 ? 'horario-fin-semana' : ''); ?>">
                                                <?php $__empty_1 = true; $__currentLoopData = $horarioGrid->get($numeroDia . '-' . $sesion, collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <div class="horario-clase">
                                                        <div class="horario-materia"><?php echo e($clase->materia?->nombre_asignatura ?? 'Materia no asignada'); ?></div>
                                                        <div class="horario-codigo"><?php echo e($clase->clave_asignatura ?: 'Sin código'); ?></div>
                                                        <div class="horario-detalle">
                                                            <?php echo e($clase->profesor?->nombre_completo ?? 'Sin docente'); ?>

                                                            <span><?php echo e($clase->ubicacion ?: 'Aula no asignada'); ?></span>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <span class="horario-vacio">—</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="tab-pane fade" id="tab-conflictos">
        <?php if(empty($conflictos)): ?>
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
                    <p>No se detectaron conflictos de aula</p>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-header bg-danger-subtle">
                    <span class="fw-bold text-danger">Se detectaron <?php echo e(count($conflictos)); ?> conflicto(s) de aula</span>
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
                                <?php $__currentLoopData = $conflictos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'][$c->dia-1]); ?></td>
                                        <td><?php echo e($c->sesion); ?></td>
                                        <td><?php echo e($c->id_campus); ?></td>
                                        <td><?php echo e($c->edificio); ?></td>
                                        <td><?php echo e($c->aula); ?></td>
                                        <td><span class="badge bg-danger"><?php echo e($c->total); ?></span></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ProyectoUTE\resources\views\academia\grupos\show.blade.php ENDPATH**/ ?>