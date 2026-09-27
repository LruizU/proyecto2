

<?php $__env->startSection('title', 'Bienvenido'); ?>
<?php $__env->startSection('breadcrumb', 'Inicio'); ?>

<?php $__env->startSection('content'); ?>
<section class="welcome-screen">
    <div class="welcome-screen__icon"><i class="bi bi-hand-wave"></i></div>
    <p class="welcome-screen__eyebrow">Sistema de control de asistencia</p>
    <h1>Bienvenido, <?php echo e(auth()->user()->name); ?></h1>
    <p class="welcome-screen__text">
        Has iniciado sesión correctamente. Desde el menú lateral puedes consultar las áreas del sistema
        autorizadas para tu cuenta.
    </p>
    <div class="welcome-screen__actions">
        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary">
            <i class="bi bi-speedometer2 me-1"></i> Ir al panel de control
        </a>
        <a href="<?php echo e(route('attendances.index')); ?>" class="btn btn-outline-primary">
            <i class="bi bi-calendar-check me-1"></i> Consultar asistencias
        </a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ProyectoUTE\resources\views\welcome.blade.php ENDPATH**/ ?>