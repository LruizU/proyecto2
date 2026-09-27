

<?php $__env->startSection('title', 'Nueva área'); ?>
<?php $__env->startSection('breadcrumb', 'Operación › Áreas › Nueva área'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Nueva área','subtitle' => 'Registra el identificador, la descripción y el encargado del área.','hideTitle' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Nueva área','subtitle' => 'Registra el identificador, la descripción y el encargado del área.','hide-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
    <?php $__env->slot('actions'); ?>
        <a href="<?php echo e(route('areas.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Volver</a>
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

<div class="card">
    <div class="card-body">
        <form action="<?php echo e(route('areas.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="identificador">ID de área</label>
                    <input type="text" id="identificador" name="identificador" class="form-control" value="<?php echo e(old('identificador')); ?>" maxlength="50" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control" value="<?php echo e(old('descripcion')); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="parent_id">Área superior</label>
                    <select id="parent_id" name="parent_id" class="form-select">
                        <option value="">Sin área superior (nivel rectoría)</option>
                        <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($parent->id); ?>" <?php if(old('parent_id') == $parent->id): echo 'selected'; endif; ?>>
                                <?php echo e($parent->identificador); ?> — <?php echo e($parent->descripcion ?: $parent->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="form-text">Ejemplo: un área operativa puede depender de Administración.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="head_employee_id">Jefe que autoriza</label>
                    <select id="head_employee_id" name="head_employee_id" class="form-select">
                        <option value="">Sin jefe asignado</option>
                        <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($empleado->id); ?>" <?php if(old('head_employee_id') == $empleado->id): echo 'selected'; endif; ?>>
                                <?php echo e($empleado->name); ?> (<?php echo e($empleado->user_id); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="empleado_responsable_id">Encargado</label>
                    <select id="empleado_responsable_id" name="empleado_responsable_id" class="form-select">
                        <option value="">Sin responsable</option>
                        <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($empleado->id); ?>" <?php echo e(old('empleado_responsable_id') == $empleado->id ? 'selected' : ''); ?>>
                                <?php echo e($empleado->name); ?> (<?php echo e($empleado->user_id); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?php echo e(route('areas.index')); ?>" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar área</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ProyectoUTE\resources\views\areas\create.blade.php ENDPATH**/ ?>