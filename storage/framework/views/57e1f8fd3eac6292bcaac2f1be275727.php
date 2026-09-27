<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formato de incidencia - <?php echo e($numero); ?></title>
    <style>
        :root { color: #111; font-family: Arial, Helvetica, sans-serif; }
        body { margin: 0; background: #ececec; }
        .page { box-sizing: border-box; width: 210mm; min-height: 297mm; margin: 20px auto; padding: 18mm 16mm; background: #fff; }
        .header { display: grid; grid-template-columns: 105px 1fr 105px; align-items: center; gap: 12px; text-align: center; }
        .logo { max-width: 95px; max-height: 95px; object-fit: contain; }
        .institution { font-size: 13px; line-height: 1.55; }
        .institution strong { display: block; font-size: 17px; margin-bottom: 8px; }
        .title { margin: 25px 0 5px; text-align: center; font-size: 17px; font-weight: 700; }
        .subtitle { text-align: center; font-size: 13px; margin-bottom: 28px; }
        .subject { font-size: 13px; margin: 0 0 25px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { border: 1px solid #222; padding: 10px 12px; vertical-align: top; }
        th { width: 24%; font-weight: 400; background: #fafafa; }
        .personal { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px 20px; }
        .check { display: flex; align-items: center; gap: 7px; }
        .box { display: inline-block; width: 13px; height: 13px; border: 1px solid #111; }
        .box.checked { background: #111; box-shadow: inset 0 0 0 3px #fff; }
        .metadata { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-top: 18px; font-size: 12px; }
        .metadata strong { display: block; margin-bottom: 3px; }
        .justification { min-height: 85px; white-space: pre-wrap; }
        .signatures { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-top: 65px; text-align: center; font-size: 12px; }
        .signature { border-top: 1px solid #111; padding-top: 7px; min-height: 48px; }
        .actions { width: 210mm; margin: 12px auto; text-align: right; }
        button { border: 1px solid #555; background: #fff; border-radius: 4px; padding: 8px 14px; cursor: pointer; }
        @media print {
            @page { size: A4; margin: 0; }
            body { background: #fff; }
            .page { width: auto; min-height: auto; margin: 0; }
            .actions { display: none; }
        }
        @media (max-width: 800px) {
            body { background: #fff; }
            .page, .actions { width: 100%; margin: 0; }
            .page { padding: 24px; }
        }
    </style>
</head>
<body>
<div class="actions"><button type="button" onclick="window.print()">Imprimir formato</button></div>
<main class="page">
    <header class="header">
        <div>
            <div style="font-size: 28px; font-weight: 700; color: #f5a55b;">UTE</div>
            <div style="font-size: 9px;">Universidad Tecnológica</div>
        </div>
        <div class="institution">
            <strong>FORMATO DE INCIDENCIAS DE PERSONAL</strong>
            UNIVERSIDAD TECNOLÓGICA GRAL. MARIANO ESCOBEDO
        </div>
        <div></div>
    </header>

    <div class="title">SOLICITUD DE JUSTIFICACIÓN</div>
    <div class="subtitle">General Escobedo, Nuevo León, <?php echo e(now()->format('d/m/Y')); ?></div>
    <p class="subject">
        <strong>Asunto:</strong> <?php echo e($incidencia->asunto); ?>

        <br><strong>Fecha de elaboración:</strong> <?php echo e($incidencia->fecha_creacion?->format('d/m/Y H:i') ?? '—'); ?>

        <br><strong>Fecha de aplicación:</strong> <?php echo e(($incidencia->fecha_falta_programada ?? $incidencia->fecha_justificacion)?->format('d/m/Y') ?? '—'); ?>

    </p>

    <table>
        <tr>
            <th>Solicitante:</th>
            <td><?php echo e($nombre ?: '—'); ?></td>
        </tr>
        <tr>
            <th>Personal:</th>
            <td>
                <?php ($esDocente = (bool) $incidencia->profesor_clave); ?>
                <div class="personal">
                    <span class="check"><span class="box <?php echo e($esDocente ? 'checked' : ''); ?>"></span> Docente</span>
                    <span class="check"><span class="box <?php echo e(! $esDocente ? 'checked' : ''); ?>"></span> Administrativo</span>
                    <span class="check"><span class="box"></span> Mantenimiento</span>
                    <span class="check"><span class="box"></span> Mecatrónica</span>
                    <span class="check"><span class="box"></span> Negocios</span>
                    <span class="check"><span class="box"></span> Educación</span>
                </div>
            </td>
        </tr>
        <tr>
            <th>N.º de empleado:</th>
            <td><?php echo e($numero ?: '—'); ?></td>
        </tr>
        <tr>
            <th>Área / puesto:</th>
            <td><?php echo e($incidencia->area?->descripcion ?? 'Sin área'); ?> / <?php echo e($incidencia->puesto?->descripcion ?? 'Sin puesto'); ?></td>
        </tr>
        <tr>
            <th>Fecha y duración:</th>
            <td>
                <?php echo e(($incidencia->fecha_falta_programada ?? $incidencia->fecha_justificacion)?->format('d/m/Y') ?? '—'); ?>

                ·
                <?php echo e($incidencia->tipo_duracion === 'horario' ? substr((string) $incidencia->hora_inicio, 0, 5).' a '.substr((string) $incidencia->hora_fin, 0, 5) : 'Día completo'); ?>

            </td>
        </tr>
        <tr>
            <th>Justificación:</th>
            <td class="justification"><?php echo e($incidencia->motivo); ?></td>
        </tr>
        <?php if($incidencia->comentarios || $incidencia->solicitud): ?>
            <tr>
                <th>Comentarios / solicitud:</th>
                <td class="justification"><?php echo e(trim(($incidencia->comentarios ?? '')."\n".($incidencia->solicitud ?? ''))); ?></td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="metadata">
        <div><strong>Responsable de área / jefe directo</strong><?php echo e($incidencia->responsableArea?->name ?? 'Pendiente'); ?></div>
        <div><strong>Jefe directo adicional</strong><?php echo e($incidencia->director?->name ?? 'No asignado'); ?></div>
        <div><strong>Estado</strong><?php echo e(ucfirst($incidencia->estado)); ?></div>
    </div>

    <table style="margin-top: 18px;">
        <tr><th>Firmas y autorizaciones:</th><td>
            <?php $__empty_1 = true; $__currentLoopData = $incidencia->approvals->sortBy('sequence'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $approval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php ($esRector = $approval->approver_employee_id === null); ?>
                <div style="margin-bottom: 8px;">
                    <strong><?php echo e($esRector ? 'Rectoría / autorización institucional' : 'Jefe directo '.($approval->sequence). ' / autorización de área'); ?></strong>:
                    <?php if($approval->area): ?>
                        <span>(<?php echo e($approval->area->descripcion ?: $approval->area->identificador); ?>)</span>
                    <?php endif; ?>
                    <?php echo e($approval->approverEmployee?->name ?? ($approval->approverUser?->name ?? 'Pendiente de asignar')); ?>

                    — <?php echo e($approval->status === 'approved' ? 'Firmada' : ($approval->status === 'rejected' ? 'Rechazada' : 'Pendiente')); ?>

                    <?php if($approval->approved_at): ?> (<?php echo e($approval->approved_at->format('d/m/Y H:i')); ?>) <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <span>Pendiente de generar ruta de autorización.</span>
            <?php endif; ?>
        </td></tr>
    </table>

    <section class="signatures">
        <?php ($jefesDirectos = $incidencia->approvals->whereNotNull('approver_employee_id')->sortBy('sequence')->values()); ?>
        <div><div class="signature"><?php echo e($nombre ?: 'Pendiente'); ?><br>Solicitante</div></div>
        <div><div class="signature"><?php echo e($incidencia->responsableArea?->name ?? 'Pendiente'); ?><br>Jefe directo 1 / responsable de área</div></div>
        <div><div class="signature"><?php echo e($jefesDirectos->get(0)?->approverEmployee?->name ?? 'No asignado'); ?><br>Jefe directo 2</div></div>
        <div><div class="signature"><?php echo e($incidencia->autorizadoPor?->name ?? 'Pendiente'); ?><br>Rectoría</div></div>
    </section>
</main>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\ProyectoUTE\resources\views\incidencias\formato.blade.php ENDPATH**/ ?>