

<?php $__env->startSection('title', 'Excepciones de puntualidad'); ?>
<?php $__env->startSection('breadcrumb', 'Administración › Configuración'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Excepciones de puntualidad','subtitle' => 'Configuración administrativa de permisos especiales para empleados.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Excepciones de puntualidad','subtitle' => 'Configuración administrativa de permisos especiales para empleados.']); ?>
    <?php $__env->slot('actions'); ?>
        <a href="<?php echo e(route('attendances.index')); ?>" class="btn btn-outline-secondary">Volver a asistencia</a>
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

<div class="alert alert-warning">
    <i class="bi bi-shield-lock me-1"></i>
    Esta configuración no modifica ni borra la hora biométrica. Solo ajusta el horario base usado para evaluar la puntualidad y conserva el motivo de autorización.
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header fw-semibold">Asignar permiso especial</div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('configuracion.asistencia-especial.store')); ?>" class="row g-3">
            <?php echo csrf_field(); ?>
            <div class="col-md-4">
                <label class="form-label" for="employee_id">Empleado</label>
                <select id="employee_id" name="employee_id" class="form-select" required>
                    <option value="">Seleccionar empleado</option>
                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($employee->id); ?>" <?php if(old('employee_id') == $employee->id): echo 'selected'; endif; ?>>
                            <?php echo e($employee->name); ?> · PIN <?php echo e($employee->user_id); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="minutes">Compensación</label>
                <select id="minutes" name="minutes" class="form-select" required>
                    <option value="20">20 minutos</option>
                    <option value="30">30 minutos</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="starts_at">Desde</label>
                <input id="starts_at" name="starts_at" type="date" class="form-control" value="<?php echo e(old('starts_at')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="ends_at">Hasta</label>
                <input id="ends_at" name="ends_at" type="date" class="form-control" value="<?php echo e(old('ends_at')); ?>">
            </div>
            <div class="col-md-8">
                <label class="form-label" for="reason">Motivo de autorización</label>
                <input id="reason" name="reason" class="form-control" maxlength="500" required value="<?php echo e(old('reason')); ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>Asignar excepción</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header fw-semibold">Excepciones registradas</div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Empleado</th><th>Compensación</th><th>Vigencia</th><th>Motivo</th><th>Registró</th><th></th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($rule->employee?->name ?? 'Empleado eliminado'); ?></td>
                    <td><span class="badge bg-info-subtle text-info">-<?php echo e($rule->minutes); ?> min</span></td>
                    <td><?php echo e($rule->starts_at?->format('d/m/Y') ?? 'Sin inicio'); ?> — <?php echo e($rule->ends_at?->format('d/m/Y') ?? 'Sin vencimiento'); ?></td>
                    <td><?php echo e($rule->reason); ?></td>
                    <td><?php echo e($rule->creator?->name ?? 'Administrador eliminado'); ?><small class="d-block text-muted"><?php echo e($rule->created_at?->format('d/m/Y H:i')); ?></small></td>
                    <td class="text-end">
                        <form method="POST" action="<?php echo e(route('configuracion.asistencia-especial.destroy', $rule)); ?>" onsubmit="return confirm('¿Eliminar esta excepción?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No hay excepciones registradas.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ProyectoUTE\resources\views/configuracion/asistencia-especial.blade.php ENDPATH**/ ?>