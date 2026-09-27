<?php ($code = 500); ?>
<?php ($title = 'Error interno del sistema'); ?>
<?php ($description = 'Ocurrió un problema inesperado. Intenta nuevamente o regresa al inicio.'); ?>
<?php echo $__env->make('errors.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\xampp\htdocs\ProyectoUTE\resources\views\errors\500.blade.php ENDPATH**/ ?>