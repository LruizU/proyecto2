<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title ?? 'Error'); ?> · <?php echo e(config('app.name')); ?></title>
    <style>
        :root { color-scheme: light; font-family: system-ui, -apple-system, sans-serif; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #f1f5f9; color: #172033; }
        main { width: min(100%, 560px); padding: 42px 36px; background: #fff; border: 1px solid #dbe3ec; border-radius: 18px; box-shadow: 0 18px 48px rgba(15, 23, 42, .1); text-align: center; }
        .code { color: #ea580c; font-size: 64px; font-weight: 800; line-height: 1; letter-spacing: -.06em; }
        h1 { margin: 18px 0 10px; font-size: 24px; }
        p { margin: 0; color: #64748b; line-height: 1.6; }
        .actions { display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; margin-top: 28px; }
        a { display: inline-flex; align-items: center; justify-content: center; padding: 10px 16px; border-radius: 9px; background: #ea580c; color: #fff; text-decoration: none; font-weight: 600; }
        a.secondary { background: #fff; border: 1px solid #cbd5e1; color: #334155; }
        @media (max-width: 480px) { main { padding: 32px 22px; } .code { font-size: 52px; } }
    </style>
</head>
<body>
<main>
    <div class="code"><?php echo e($code ?? '!'); ?></div>
    <h1><?php echo e($title ?? 'No se pudo completar la solicitud'); ?></h1>
    <p><?php echo e($description ?? 'Ocurrió un problema al procesar la solicitud.'); ?></p>
    <div class="actions">
        <a href="<?php echo e(url('/')); ?>">Ir al inicio</a>
        <a class="secondary" href="<?php echo e(route('login')); ?>">Iniciar sesión</a>
    </div>
</main>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\ProyectoUTE\resources\views\errors\layout.blade.php ENDPATH**/ ?>