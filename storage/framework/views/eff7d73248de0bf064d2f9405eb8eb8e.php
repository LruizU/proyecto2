<?php $__env->startSection('title', 'Materias - Academia'); ?>
<?php $__env->startSection('breadcrumb', 'Academia › Materias'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf8d4ea307ab1e58d4e472a43c8548d8e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-header','data' => ['title' => 'Catálogo de Materias','subtitle' => 'Materias y asignaturas vinculadas a los planes de estudio y ciclos académicos.','hideTitle' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Catálogo de Materias','subtitle' => 'Materias y asignaturas vinculadas a los planes de estudio y ciclos académicos.','hide-title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
    <?php $__env->slot('actions'); ?>
        <a href="<?php echo e(route('academia.dashboard')); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver al Dashboard
        </a>
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

<div class="card shadow-sm border mb-4">
    <div class="card-body p-4 text-center">
        <div class="mb-3">
            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary" style="width: 56px; height: 56px;">
                <i class="bi bi-journal-bookmark fs-3"></i>
            </span>
        </div>
        <h5 class="fw-bold mb-2">Materias del Ciclo <?php echo e($ciclo->label); ?></h5>
        <p class="text-secondary mb-3">
            El catálogo completo de materias y su programación operativa se gestiona directamente desde el 
            <strong>Dashboard de Académica</strong> y la asignación en cursos y horarios.
        </p>
        <div class="d-flex justify-content-center gap-2">
            <a href="<?php echo e(route('academia.dashboard')); ?>#seccion-materias" class="btn btn-primary btn-sm">
                <i class="bi bi-table me-1"></i> Ver Tabla de Materias en Dashboard
            </a>
            <a href="<?php echo e(route('academia.cursos.index', ['ciclo_principal' => $ciclo->label])); ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-book me-1"></i> Ver Cursos Asignados
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ProyectoUTE\resources\views\academia\dashboard\materias-module.blade.php ENDPATH**/ ?>