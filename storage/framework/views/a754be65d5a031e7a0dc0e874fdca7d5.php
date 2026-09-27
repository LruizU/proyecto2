<?php $__env->startSection('title', 'Academia - Dashboard'); ?>
<?php $__env->startSection('breadcrumb', 'Academia › Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="academia-dashboard">

<section aria-labelledby="ciclo-heading" class="card shadow-sm border mb-4 dashboard-header-card">
  <div class="card-body p-3 p-md-4">
    <div class="row align-items-center g-3">
      <div class="col-12 col-lg-6">
        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
          <span class="badge <?php echo e($ciclo->activo ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle'); ?> px-2 py-1">
            <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i><?php echo e($ciclo->activo ? 'Ciclo Activo' : 'Ciclo Inactivo'); ?>

          </span>
          <span class="badge bg-light text-secondary border px-2 py-1" style="font-family:'JetBrains Mono',monospace">
            <i class="bi bi-calendar-event me-1"></i><?php echo e($ciclo->fechaInicialFormateada); ?> &mdash; <?php echo e($ciclo->fechaFinalFormateada); ?>

          </span>
        </div>
        <h1 id="ciclo-heading" class="h5 fw-bold mb-1 d-flex align-items-center gap-2" data-cycle-label>
          <i class="bi bi-mortarboard text-primary"></i>
          <span><?php echo e($ciclo->label); ?> &mdash; <?php echo e($ciclo->descripcion ?: 'Ciclo Institucional'); ?></span>
        </h1>
        <span class="visually-hidden">Dashboard de Académica</span>
<p class="small text-secondary mb-0">
          Supervisión integral de matrícula, oferta de cursos, planes de estudio y plantilla docente en este ciclo.
        </p>
      </div>

      <div class="col-12 col-lg-6">
        <form id="ciclo-switcher" method="GET" action="<?php echo e(route('academia.dashboard')); ?>" class="d-flex flex-wrap align-items-center justify-content-lg-end gap-2">
          <div class="input-group input-group-sm" style="max-width: 320px;">
            <span class="input-group-text bg-surface-2 border-end-0">
              <i class="bi bi-calendar3 text-muted"></i>
            </span>
            <select name="ciclo_principal" data-cycle-select class="form-select form-select-sm border-start-0" aria-label="Seleccionar ciclo escolar" onchange="this.form.submit()">
              <?php $__currentLoopData = $ciclosDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c->label); ?>" <?php echo e($ciclo->label === $c->label ? 'selected' : ''); ?>>
                  <?php echo e($c->label); ?> · <?php echo e(Str::limit($c->descripcion ?: 'Sin descripción', 25)); ?> <?php echo e($c->activo ? '(Activo)' : ''); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <div class="btn-group btn-group-sm">
            <a href="<?php echo e(route('academia.ciclos.show', $ciclo)); ?>" class="btn btn-outline-primary btn-sm" title="Ver detalles y configuración del ciclo">
              <i class="bi bi-info-circle me-1"></i> Detalle
            </a>
            <a href="<?php echo e(route('academia.ciclos.index')); ?>" class="btn btn-outline-secondary btn-sm" title="Administrar catálogo de ciclos">
              <i class="bi bi-sliders me-1"></i> Ciclos
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="card-footer bg-surface-2 px-3 px-md-4 py-2 small text-tertiary-token d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <i class="bi bi-info-circle text-primary"></i>
      <span>Los indicadores de este panel corresponden a la programación académica del ciclo <strong><?php echo e($ciclo->label); ?></strong>.</span>
    </div>
    <div class="d-flex align-items-center gap-3">
      <a href="<?php echo e(route('academia.grupos.index', ['ciclo_principal' => $ciclo->label])); ?>" class="text-decoration-none small text-secondary hover-primary">
        <i class="bi bi-people me-1"></i>Grupos
      </a>
      <a href="<?php echo e(route('academia.alumnos.index', ['ciclo_principal' => $ciclo->label])); ?>" class="text-decoration-none small text-secondary hover-primary">
        <i class="bi bi-mortarboard me-1"></i>Alumnos
      </a>
      <a href="<?php echo e(route('academia.profesores.index')); ?>" class="text-decoration-none small text-secondary hover-primary">
        <i class="bi bi-person-badge me-1"></i>Profesores
      </a>
      <a href="<?php echo e(route('academia.horarios.clase', ['ciclo_principal' => $ciclo->label])); ?>" class="text-decoration-none small text-secondary hover-primary">
        <i class="bi bi-calendar-week me-1"></i>Horarios
      </a>
    </div>
  </div>
</section>


<?php if(($kpis['grupos'] ?? 0) === 0 && ($kpis['horarios'] ?? 0) === 0): ?>
  <div class="alert alert-warning border-warning-subtle shadow-sm d-flex align-items-center gap-3 mb-4" role="status">
    <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
    <div class="flex-grow-1">
      <div class="fw-semibold">Ciclo sin programación académica registrada</div>
      <div class="small">
        El ciclo <strong><?php echo e($ciclo->label); ?></strong> aún no contiene grupos ni horarios asignados. 
        Puede <a href="<?php echo e(route('academia.grupos.index', ['ciclo_principal' => $ciclo->label])); ?>" class="alert-link">crear grupos</a> 
        o seleccionar otro ciclo en el selector superior.
      </div>
    </div>
  </div>
<?php endif; ?>


<section aria-labelledby="kpis-heading" class="mb-4">
  <h2 id="kpis-heading" class="visually-hidden">Indicadores Clave de Desempeño</h2>
  
  <div class="row g-3" id="kpi-region" aria-live="polite" aria-busy="false" data-kpis-url="<?php echo e(route('academia.kpisJson')); ?>" data-cycle="<?php echo e($ciclo->label); ?>">
    
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card h-100 shadow-sm border kpi-card" style="border-left: 4px solid var(--cat-blue) !important;">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <span class="text-uppercase small text-secondary fw-semibold" style="font-size:11px;letter-spacing:0.05em">Alumnos Inscritos</span>
              <div class="fs-3 fw-bold mt-1 text-primary" data-stat-value style="font-family:'JetBrains Mono',monospace">
                <?php echo e(number_format($kpis['alumnos'])); ?>

              </div>
            </div>
            <div class="kpi-icon rounded-3 d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px;background:rgba(59,130,246,0.12);color:var(--cat-blue)">
              <i class="bi bi-mortarboard fs-5"></i>
            </div>
          </div>
          <div class="d-flex align-items-center justify-content-between small pt-2 border-top">
            <span class="text-secondary" data-kpi-caption>
              <?php if(isset($totales['alumnos']) && $totales['alumnos'] > 0): ?>
                de <?php echo e(number_format($totales['alumnos'])); ?> activos
              <?php else: ?>
                en este ciclo
              <?php endif; ?>
            </span>
            <a href="<?php echo e(route('academia.alumnos.index', ['ciclo_principal' => $ciclo->label])); ?>" class="text-primary text-decoration-none small" title="Ver lista de alumnos">
              Ver <i class="bi bi-chevron-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card h-100 shadow-sm border kpi-card" style="border-left: 4px solid var(--cat-purple) !important;">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <span class="text-uppercase small text-secondary fw-semibold" style="font-size:11px;letter-spacing:0.05em">Grupos Formados</span>
              <div class="fs-3 fw-bold mt-1" data-stat-value style="font-family:'JetBrains Mono',monospace;color:var(--cat-purple)">
                <?php echo e(number_format($kpis['grupos'])); ?>

              </div>
            </div>
            <div class="kpi-icon rounded-3 d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px;background:rgba(147,51,234,0.12);color:var(--cat-purple)">
              <i class="bi bi-people fs-5"></i>
            </div>
          </div>
          <div class="d-flex align-items-center justify-content-between small pt-2 border-top">
            <span class="text-secondary" data-kpi-caption>
              <?php if(isset($totales['grupos']) && $totales['grupos'] > 0): ?>
                de <?php echo e(number_format($totales['grupos'])); ?> en total
              <?php else: ?>
                en este ciclo
              <?php endif; ?>
            </span>
            <a href="<?php echo e(route('academia.grupos.index', ['ciclo_principal' => $ciclo->label])); ?>" class="text-decoration-none small" style="color:var(--cat-purple)" title="Ver grupos">
              Ver <i class="bi bi-chevron-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card h-100 shadow-sm border kpi-card" style="border-left: 4px solid var(--cat-green) !important;">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <span class="text-uppercase small text-secondary fw-semibold" style="font-size:11px;letter-spacing:0.05em">Profesores Asignados</span>
              <div class="fs-3 fw-bold mt-1" data-stat-value style="font-family:'JetBrains Mono',monospace;color:var(--cat-green)">
                <?php echo e(number_format($kpis['profesores_ciclo'] ?? $kpis['profesores'])); ?>

              </div>
            </div>
            <div class="kpi-icon rounded-3 d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px;background:rgba(16,185,129,0.12);color:var(--cat-green)">
              <i class="bi bi-person-badge fs-5"></i>
            </div>
          </div>
          <div class="d-flex align-items-center justify-content-between small pt-2 border-top">
            <span class="text-secondary" data-kpi-caption>
              <?php if(isset($totales['profesores']) && $totales['profesores'] > 0): ?>
                de <?php echo e(number_format($totales['profesores'])); ?> registrados
              <?php else: ?>
                con carga académica
              <?php endif; ?>
            </span>
            <a href="<?php echo e(route('academia.profesores.index')); ?>" class="text-decoration-none small" style="color:var(--cat-green)" title="Ver profesores">
              Ver <i class="bi bi-chevron-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card h-100 shadow-sm border kpi-card" style="border-left: 4px solid var(--cat-orange) !important;">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <span class="text-uppercase small text-secondary fw-semibold" style="font-size:11px;letter-spacing:0.05em">Horarios Programados</span>
              <div class="fs-3 fw-bold mt-1" data-stat-value style="font-family:'JetBrains Mono',monospace;color:var(--cat-orange)">
                <?php echo e(number_format($kpis['horarios'])); ?>

              </div>
            </div>
            <div class="kpi-icon rounded-3 d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px;background:rgba(249,115,22,0.12);color:var(--cat-orange)">
              <i class="bi bi-calendar-week fs-5"></i>
            </div>
          </div>
          <div class="d-flex align-items-center justify-content-between small pt-2 border-top">
            <span class="text-secondary" data-kpi-caption>
              <?php if(isset($totales['horarios']) && $totales['horarios'] > 0): ?>
                de <?php echo e(number_format($totales['horarios'])); ?> totales
              <?php else: ?>
                sesiones semanales
              <?php endif; ?>
            </span>
            <a href="<?php echo e(route('academia.horarios.clase', ['ciclo_principal' => $ciclo->label])); ?>" class="text-decoration-none small" style="color:var(--cat-orange)" title="Ver horarios">
              Ver <i class="bi bi-chevron-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <div class="row g-2 mt-1">
    <div class="col-6 col-md-3">
      <div class="card bg-surface-2 border py-2 px-3 d-flex flex-row align-items-center justify-content-between">
        <span class="small text-secondary"><i class="bi bi-book me-1 text-teal"></i>Cursos Ofertados:</span>
        <span class="fw-bold mono text-teal" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($kpis['cursos'])); ?></span>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card bg-surface-2 border py-2 px-3 d-flex flex-row align-items-center justify-content-between">
        <span class="small text-secondary"><i class="bi bi-collection me-1 text-purple"></i>Planes Activos:</span>
        <span class="fw-bold mono text-purple" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($kpis['planes'])); ?></span>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card bg-surface-2 border py-2 px-3 d-flex flex-row align-items-center justify-content-between">
        <span class="small text-secondary"><i class="bi bi-journal-bookmark me-1 text-amber"></i>Materias Ciclo:</span>
        <span class="fw-bold mono text-amber" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($kpis['materias'])); ?></span>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card bg-surface-2 border py-2 px-3 d-flex flex-row align-items-center justify-content-between">
        <span class="small text-secondary"><i class="bi bi-file-earmark-text me-1 text-pink"></i>Evaluaciones Kárdex:</span>
        <span class="fw-bold mono text-pink" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($kpis['kardex'] ?? 0)); ?></span>
      </div>
    </div>
  </div>
</section>


<section aria-labelledby="resumen-heading" class="mb-4">
  <div class="section-heading mb-3 d-flex align-items-center justify-content-between">
    <div>
      <h2 id="resumen-heading" class="h6 fw-bold mb-0">Resumen Académico &amp; Carga Horaria</h2>
      <span class="small text-secondary">Programación semanal de horarios y distribución por tipo de plaza</span>
    </div>
    <span class="badge bg-surface-2 text-secondary border">Ciclo <?php echo e($ciclo->label); ?></span>
  </div>

  <div class="row g-3">
    
    <div class="col-12 col-lg-8">
      <div class="card h-100 shadow-sm border" data-chart="horarios-dia" data-cycle="<?php echo e($ciclo->label); ?>">
        <div class="card-header bg-surface-1 border-bottom d-flex justify-content-between align-items-center py-2 px-3">
          <span class="fw-bold small text-uppercase" style="letter-spacing:0.04em">
            <i class="bi bi-graph-up text-primary me-1"></i> Horarios Programados por Día
          </span>
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle" data-chart-total>
            <?php echo e(number_format(array_sum($horariosPorDia))); ?> asignaciones
          </span>
        </div>
        <div class="card-body p-3 position-relative" style="min-height: 180px;">
          <?php
            $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
            $data = [];
            foreach ($dias as $d => $label) {
                $data[] = ['label' => $label, 'short' => substr($label, 0, 3), 'value' => $horariosPorDia[$d] ?? 0];
            }
            $points = array_column($data, 'value');
            $max = max(1, ...$points);
            $w = 700; $h = 130; $padX = 20; $padT = 16; $padB = 22;
            $n = count($points);
            $stepX = $n > 1 ? ($w - $padX * 2) / ($n - 1) : 0;
            $coords = [];
            foreach ($points as $i => $v) {
                $x = $n > 1 ? round($padX + $i * $stepX, 1) : $w / 2;
                $y = round($padT + ($h - $padT - $padB) * (1 - ($v / $max)), 1);
                $coords[] = [$x, $y];
            }
            $linePath = 'M ' . implode(' L ', array_map(fn ($c) => $c[0] . ',' . $c[1], $coords));
            $first = $coords[0];
            $last = $coords[$n - 1];
            $areaPath = $n > 1
                ? "M {$first[0]},{$first[1]} " . implode(' ', array_map(fn ($c) => "L {$c[0]},{$c[1]}", array_slice($coords, 1))) . " L {$last[0]}," . ($h - $padB) . " L {$first[0]}," . ($h - $padB) . " Z"
                : '';
          ?>

          <?php if(array_sum($horariosPorDia) === 0): ?>
            <div class="text-center py-4 text-secondary">
              <i class="bi bi-calendar-x fs-2 d-block mb-2 text-muted"></i>
              <div class="fw-semibold">Sin horarios programados en este ciclo</div>
              <div class="small text-muted mb-2">No se han registrado asignaciones de horario en el ciclo seleccionado.</div>
              <a href="<?php echo e(route('academia.horarios.clase', ['ciclo_principal' => $ciclo->label])); ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-calendar-plus me-1"></i> Ir a Horarios
              </a>
            </div>
          <?php else: ?>
            <svg class="trend-chart w-100" viewBox="0 0 <?php echo e($w); ?> <?php echo e($h); ?>" preserveAspectRatio="none" role="img" aria-label="Horarios por día en ciclo <?php echo e($ciclo->label); ?>">
              <defs>
                <linearGradient id="trendGradientAcademia" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="var(--primary)" stop-opacity="0.32"/>
                  <stop offset="100%" stop-color="var(--primary)" stop-opacity="0.02"/>
                </linearGradient>
              </defs>
              
              <line x1="<?php echo e($padX); ?>" y1="<?php echo e($h - $padB); ?>" x2="<?php echo e($w - $padX); ?>" y2="<?php echo e($h - $padB); ?>" stroke="var(--border)" stroke-width="1" stroke-dasharray="3 3"/>
              <?php if($areaPath): ?> 
                <path class="area-fill" d="<?php echo e($areaPath); ?>" fill="url(#trendGradientAcademia)"/> 
              <?php endif; ?>
              <path class="area-line" d="<?php echo e($linePath); ?>" stroke="var(--primary)" stroke-width="2.5" fill="none"/>
              <?php $__currentLoopData = $coords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => [$x, $y]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <circle class="area-dot" cx="<?php echo e($x); ?>" cy="<?php echo e($y); ?>" r="4.5" fill="var(--primary)" stroke="var(--surface-1)" stroke-width="1.5">
                  <title><?php echo e($data[$i]['label']); ?>: <?php echo e(number_format($data[$i]['value'])); ?> horarios</title>
                </circle>
                <text x="<?php echo e($x); ?>" y="<?php echo e($h - 5); ?>" font-size="11" font-weight="600" fill="var(--text-secondary)" text-anchor="middle">
                  <?php echo e($data[$i]['short']); ?> (<?php echo e($data[$i]['value']); ?>)
                </text>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </svg>

            <table class="visually-hidden">
              <caption>Horarios programados por día de la semana</caption>
              <thead><tr><th scope="col">Día</th><th scope="col">Horarios</th></tr></thead>
              <tbody>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr><td><?php echo e($d['label']); ?></td><td><?php echo e($d['value']); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>

    
    <div class="col-12 col-lg-4">
      <div class="card h-100 shadow-sm border">
        <div class="card-header bg-surface-1 border-bottom d-flex justify-content-between align-items-center py-2 px-3">
          <span class="fw-bold small text-uppercase" style="letter-spacing:0.04em">
            <i class="bi bi-pie-chart text-primary me-1"></i> Tipo de Contratación
          </span>
          <span class="badge bg-secondary-subtle text-secondary">PA vs PTC</span>
        </div>
        <div class="card-body p-3 d-flex flex-column justify-content-between">
          <?php
            $hdCount = $porOrigen['HD'] ?? 0;
            $caCount = $porOrigen['CA'] ?? 0;
            $sinDefCount = $porOrigen['SIN_DEFINIR'] ?? 0;
            $totalOrigen = $hdCount + $caCount + $sinDefCount;
            $hdPct = $totalOrigen > 0 ? round(($hdCount / $totalOrigen) * 100) : 0;
            $caPct = $totalOrigen > 0 ? round(($caCount / $totalOrigen) * 100) : 0;
          ?>

          <?php if($totalOrigen === 0): ?>
            <div class="text-center py-4 text-secondary">
              <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
              <div class="small">Sin registros de origen docente en este ciclo</div>
            </div>
          <?php else: ?>
            <div>
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small text-secondary">Proporción de Asignación Docente</span>
                <span class="small fw-semibold"><?php echo e(number_format($totalOrigen)); ?> clases</span>
              </div>
              <div class="progress mb-3" style="height: 10px; border-radius: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($hdPct); ?>%" aria-valuenow="<?php echo e($hdPct); ?>" aria-valuemin="0" aria-valuemax="100" title="PTC: <?php echo e($hdPct); ?>%"></div>
                <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo e($caPct); ?>%" aria-valuenow="<?php echo e($caPct); ?>" aria-valuemin="0" aria-valuemax="100" title="PA: <?php echo e($caPct); ?>%"></div>
              </div>

              <div class="list-group list-group-flush border rounded-3 mb-2">
                <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                  <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle me-1">PTC</span>
                    <span class="fw-semibold small">Tiempo Completo (HD)</span>
                  </div>
                  <div class="text-end">
                    <span class="fw-bold mono small" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($hdCount)); ?></span>
                    <span class="text-muted small">(<?php echo e($hdPct); ?>%)</span>
                  </div>
                </div>

                <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                  <div>
                    <span class="badge bg-info-subtle text-info border border-info-subtle me-1">PA</span>
                    <span class="fw-semibold small">Asignatura (CA)</span>
                  </div>
                  <div class="text-end">
                    <span class="fw-bold mono small" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($caCount)); ?></span>
                    <span class="text-muted small">(<?php echo e($caPct); ?>%)</span>
                  </div>
                </div>

                <?php if($sinDefCount > 0): ?>
                  <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                    <div>
                      <span class="badge bg-secondary-subtle text-secondary me-1">N/D</span>
                      <span class="small text-secondary">Sin definir</span>
                    </div>
                    <div class="text-end">
                      <span class="fw-bold mono small"><?php echo e(number_format($sinDefCount)); ?></span>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <div class="p-2 rounded bg-surface-2 border small text-secondary d-flex align-items-center gap-2">
              <i class="bi bi-shield-check text-success"></i>
              <span>Carga académica activa vinculada a la nómina institucional.</span>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>


<section aria-labelledby="curricula-heading" class="mb-4">
  <div class="section-heading mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h2 id="curricula-heading" class="h6 fw-bold mb-0">Oferta Curricular: Materias y Planes de Estudio</h2>
      <span class="small text-secondary">Tablas institucionales con búsqueda dinámica, selector de registros y exportación</span>
    </div>
  </div>

  <div class="row g-4">
    
    <div class="col-12 col-xl-6" id="seccion-materias">
      <div class="card h-100 shadow-sm border dashboard-table-card">
        
        <div class="card-header bg-surface-1 border-bottom p-3">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <div class="d-flex align-items-center gap-2">
              <div class="p-2 rounded bg-amber-subtle text-amber d-inline-flex" style="background:rgba(245,158,11,0.12);color:var(--cat-amber)">
                <i class="bi bi-journal-bookmark fs-5"></i>
              </div>
              <div>
                <h3 class="h6 fw-bold mb-0">Materias</h3>
                <span class="small text-secondary">Asignaturas de este ciclo</span>
              </div>
            </div>
            <span class="badge bg-surface-2 text-secondary border px-2 py-1 js-table-count-badge">
              <?php echo e(number_format($materias->count())); ?> registros
            </span>
          </div>

          
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top">
            <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 200px;">
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-surface-2 border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control form-control-sm border-start-0 js-table-search" data-table-id="materias-dashboard-table" placeholder="Buscar materia, clave o carrera..." aria-label="Buscar materia">
              </div>
            </div>

            <div class="d-flex align-items-center gap-2">
              <div class="d-flex align-items-center gap-1">
                <label class="small text-muted mb-0 me-1 d-none d-sm-inline">Ver:</label>
                <select class="form-select form-select-sm js-table-size" data-table-id="materias-dashboard-table" style="width: 70px;" aria-label="Cantidad de filas">
                  <option value="20" selected>20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
              </div>

              <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-success js-export-excel" data-table-id="materias-dashboard-table" data-filename="materias-ciclo-<?php echo e($ciclo->label); ?>" title="Exportar a Excel">
                  <i class="bi bi-file-earmark-excel me-1"></i>Excel
                </button>
                <button type="button" class="btn btn-outline-primary js-export-csv" data-table-id="materias-dashboard-table" data-filename="materias-ciclo-<?php echo e($ciclo->label); ?>" title="Exportar a CSV">
                  <i class="bi bi-file-earmark-text me-1"></i>CSV
                </button>
                <button type="button" class="btn btn-outline-danger js-export-pdf" data-table-id="materias-dashboard-table" data-filename="materias-ciclo-<?php echo e($ciclo->label); ?>" title="Imprimir / Guardar como PDF">
                  <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                </button>
              </div>
            </div>
          </div>
        </div>

        
        <div class="table-responsive" style="min-height: 280px; max-height: 480px;">
          <table id="materias-dashboard-table" class="table table-hover table-sm align-middle mb-0 dashboard-data-table">
            <thead class="table-light sticky-top">
              <tr>
                <th scope="col" class="sortable text-nowrap" data-sort="string" style="cursor:pointer;" title="Ordenar por clave/materia">
                  Materia / Clave <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable" data-sort="string" style="cursor:pointer;" title="Ordenar por carrera">
                  Carrera / Plan <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-center" data-sort="string" style="cursor:pointer; width: 75px;" title="Ordenar por grado o semestre">
                  Grado <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-center" data-sort="number" style="cursor:pointer; width: 55px;" title="Horas Teoría (HT)">
                  HT <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-center" data-sort="number" style="cursor:pointer; width: 55px;" title="Horas Práctica (HP)">
                  HP <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-end" data-sort="number" style="cursor:pointer; width: 75px;" title="Total de Horas">
                  Total <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-center" data-sort="number" style="cursor:pointer; width: 70px;" title="Créditos Académicos">
                  Créd. <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="text-center" style="width: 75px;">Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $materias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                  $plan = $materia->plan;
                  $carrera = $plan?->nivelRel?->descripcion ?? $plan?->nivel ?? 'Sin plan asignado';
                  $horasTotales = $materia->horas_totales ?: (($materia->horas_teoria ?? 0) + ($materia->horas_practica ?? 0));
                ?>
                <tr>
                  <td>
                    <div class="fw-semibold text-truncate" style="max-width: 200px;" title="<?php echo e($materia->nombre_asignatura); ?>">
                      <?php echo e($materia->nombre_asignatura); ?>

                    </div>
                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 10px;">
                      <?php echo e($materia->clave_asignatura); ?>

                    </span>
                  </td>
                  <td>
                    <div class="small text-truncate" style="max-width: 160px;" title="<?php echo e($carrera); ?>">
                      <?php echo e($carrera); ?>

                    </div>
                    <?php if($plan): ?>
                      <span class="text-muted small" style="font-size: 10px;">Plan: <?php echo e($plan->nombre_plan); ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <?php if($materia->semestre): ?>
                      <span class="badge bg-surface-2 text-secondary border"><?php echo e($materia->semestre); ?>°</span>
                    <?php else: ?>
                      <span class="text-muted small" title="Semestre no registrado en catálogo">N/D</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center mono" style="font-family:'JetBrains Mono',monospace">
                    <?php echo e($materia->horas_teoria ?? 0); ?>

                  </td>
                  <td class="text-center mono" style="font-family:'JetBrains Mono',monospace">
                    <?php echo e($materia->horas_practica ?? 0); ?>

                  </td>
                  <td class="text-end fw-semibold mono" style="font-family:'JetBrains Mono',monospace">
                    <?php echo e($horasTotales); ?> hrs
                  </td>
                  <td class="text-center">
                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 11px;">
                      <?php echo e($materia->creditos ?? 0); ?>

                    </span>
                  </td>
                  <td class="text-center">
                    <span class="badge <?php echo e($materia->activa ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border'); ?>">
                      <?php echo e($materia->activa ? 'Activa' : 'Inactiva'); ?>

                    </span>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="8" class="text-center py-5 text-secondary">
                    <i class="bi bi-journal-x fs-3 d-block mb-2 text-muted"></i>
                    <div>Sin materias programadas para este ciclo</div>
                    <div class="small text-muted mt-1">Seleccione otro ciclo escolar para consultar asignaturas.</div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        
        <div class="card-footer bg-surface-1 border-top py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div class="small text-secondary js-table-info" data-table-id="materias-dashboard-table">
            Mostrando 1 a 20 de <?php echo e(number_format($materias->count())); ?> materias
          </div>
          <div class="js-table-pagination" data-table-id="materias-dashboard-table"></div>
        </div>
      </div>
    </div>

    
    <div class="col-12 col-xl-6" id="seccion-planes">
      <div class="card h-100 shadow-sm border dashboard-table-card">
        
        <div class="card-header bg-surface-1 border-bottom p-3">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <div class="d-flex align-items-center gap-2">
              <div class="p-2 rounded bg-purple-subtle text-purple d-inline-flex" style="background:rgba(147,51,234,0.12);color:var(--cat-purple)">
                <i class="bi bi-collection fs-5"></i>
              </div>
              <div>
                <h3 class="h6 fw-bold mb-0">Planes de Estudio</h3>
                <span class="small text-secondary">Programas educativos vinculados</span>
              </div>
            </div>
            <span class="badge bg-surface-2 text-secondary border px-2 py-1 js-table-count-badge">
              <?php echo e(number_format($planes->count())); ?> registros
            </span>
          </div>

          
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top">
            <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 200px;">
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-surface-2 border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control form-control-sm border-start-0 js-table-search" data-table-id="planes-dashboard-table" placeholder="Buscar plan o nivel..." aria-label="Buscar plan de estudio">
              </div>
            </div>

            <div class="d-flex align-items-center gap-2">
              <div class="d-flex align-items-center gap-1">
                <label class="small text-muted mb-0 me-1 d-none d-sm-inline">Ver:</label>
                <select class="form-select form-select-sm js-table-size" data-table-id="planes-dashboard-table" style="width: 70px;" aria-label="Cantidad de filas">
                  <option value="20" selected>20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
              </div>

              <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-success js-export-excel" data-table-id="planes-dashboard-table" data-filename="planes-ciclo-<?php echo e($ciclo->label); ?>" title="Exportar a Excel">
                  <i class="bi bi-file-earmark-excel me-1"></i>Excel
                </button>
                <button type="button" class="btn btn-outline-primary js-export-csv" data-table-id="planes-dashboard-table" data-filename="planes-ciclo-<?php echo e($ciclo->label); ?>" title="Exportar a CSV">
                  <i class="bi bi-file-earmark-text me-1"></i>CSV
                </button>
                <button type="button" class="btn btn-outline-danger js-export-pdf" data-table-id="planes-dashboard-table" data-filename="planes-ciclo-<?php echo e($ciclo->label); ?>" title="Imprimir / Guardar como PDF">
                  <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                </button>
              </div>
            </div>
          </div>
        </div>

        
        <div class="table-responsive" style="min-height: 280px; max-height: 480px;">
          <table id="planes-dashboard-table" class="table table-hover table-sm align-middle mb-0 dashboard-data-table">
            <thead class="table-light sticky-top">
              <tr>
                <th scope="col" class="sortable text-center" data-sort="number" style="cursor:pointer; width: 70px;" title="Ordenar por ID">
                  ID <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable" data-sort="string" style="cursor:pointer;" title="Ordenar por nombre de plan">
                  Plan de Estudio <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable" data-sort="string" style="cursor:pointer;" title="Ordenar por carrera">
                  Nivel / Carrera <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-center" data-sort="number" style="cursor:pointer; width: 100px;" title="Ordenar por materias">
                  Materias <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="text-center" style="width: 80px;">Estado</th>
                <th scope="col" class="text-end" style="width: 70px;">Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $planes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td class="text-center font-monospace fw-semibold small">
                    <?php echo e($plan->id_plan); ?>

                  </td>
                  <td>
                    <div class="fw-semibold text-truncate" style="max-width: 200px;" title="<?php echo e($plan->nombre_plan); ?>">
                      <?php echo e($plan->nombre_plan); ?>

                    </div>
                  </td>
                  <td>
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                      <?php if($plan->nivel): ?>
                        <span class="badge bg-light text-secondary border font-monospace" style="font-size:10px;"><?php echo e($plan->nivel); ?></span>
                      <?php endif; ?>
                      <span class="small text-truncate" style="max-width: 170px;" title="<?php echo e($plan->nivelRel?->descripcion ?? 'Sin descripción'); ?>">
                        <?php echo e($plan->nivelRel?->descripcion ?? 'N/D'); ?>

                      </span>
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                      <?php echo e($plan->materias_count); ?> mat.
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="badge <?php echo e($plan->activo ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border'); ?>">
                      <?php echo e($plan->activo ? 'Activo' : 'Inactivo'); ?>

                    </span>
                  </td>
                  <td class="text-end">
                    <a href="<?php echo e(route('academia.planes.show', $plan)); ?>" class="btn btn-outline-primary btn-sm p-1" title="Ver plan de estudio">
                      <i class="bi bi-eye"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="6" class="text-center py-5 text-secondary">
                    <i class="bi bi-collection fs-3 d-block mb-2 text-muted"></i>
                    <div>Sin planes de estudio registrados en este ciclo</div>
                    <div class="small text-muted mt-1">Seleccione otro ciclo o cree un nuevo plan.</div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        
        <div class="card-footer bg-surface-1 border-top py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div class="small text-secondary js-table-info" data-table-id="planes-dashboard-table">
            Mostrando 1 a 20 de <?php echo e(number_format($planes->count())); ?> planes
          </div>
          <div class="js-table-pagination" data-table-id="planes-dashboard-table"></div>
        </div>
      </div>
    </div>
  </div>
</section>


<section aria-labelledby="desglose-heading" class="mb-4">
  <div class="section-heading mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h2 id="desglose-heading" class="h6 fw-bold mb-0">Desglose Operativo Institucional</h2>
      <span class="small text-secondary">Distribución de matrícula, oferta por sede y programación por docente</span>
    </div>
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Operaciones del Ciclo</span>
  </div>

  
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="card h-100 shadow-sm border p-3">
        <div class="small text-secondary fw-semibold text-uppercase" style="font-size:10px;letter-spacing:0.05em">Matrícula por Grupos</div>
        <div class="h5 fw-bold mb-1 text-primary mono mt-1"><?php echo e(number_format($kpis['alumnos'])); ?> alumnos</div>
        <div class="small text-muted"><?php echo e($dashboardSummary['alumnosPorGrupo']->count()); ?> combinaciones grado/sede</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card h-100 shadow-sm border p-3">
        <div class="small text-secondary fw-semibold text-uppercase" style="font-size:10px;letter-spacing:0.05em">Campus y Sedes</div>
        <div class="h5 fw-bold mb-1 text-success mono mt-1"><?php echo e($dashboardSummary['cursosPorSede']->count()); ?> sedes activas</div>
        <div class="small text-muted"><?php echo e($dashboardSummary['cursosPorSede']->sum('cursos')); ?> cursos distribuidos</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card h-100 shadow-sm border p-3">
        <div class="small text-secondary fw-semibold text-uppercase" style="font-size:10px;letter-spacing:0.05em">Niveles &amp; Turnos</div>
        <div class="h5 fw-bold mb-1 text-purple mono mt-1"><?php echo e($dashboardSummary['niveles']->count()); ?> niveles</div>
        <div class="small text-muted"><?php echo e($dashboardSummary['turnos']->count()); ?> turnos de operación</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card h-100 shadow-sm border p-3">
        <div class="small text-secondary fw-semibold text-uppercase" style="font-size:10px;letter-spacing:0.05em">Carga Horaria</div>
        <div class="h5 fw-bold mb-1 text-orange mono mt-1"><?php echo e(number_format($dashboardSummary['horasPorOrigen']->sum('clases'))); ?> clases</div>
        <div class="small text-muted"><?php echo e(number_format($dashboardSummary['profesoresPorOrigen']->sum('profesores'))); ?> profesores asignados</div>
      </div>
    </div>
  </div>

  
  <div class="row g-4">
    
    <div class="col-12 col-lg-7">
      <div class="card h-100 shadow-sm border dashboard-table-card">
        <div class="card-header bg-surface-1 border-bottom p-3">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <div>
              <h3 class="h6 fw-bold mb-0">Alumnos por Grado, Modalidad y Sede</h3>
              <span class="small text-secondary">Distribución de la matrícula institucional</span>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 js-table-count-badge">
              <?php echo e(number_format($dashboardSummary['alumnosPorGrupo']->count())); ?> grupos
            </span>
          </div>

          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top">
            <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 180px;">
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-surface-2 border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control form-control-sm border-start-0 js-table-search" data-table-id="students-breakdown-table" placeholder="Filtrar grado, modalidad o sede..." aria-label="Filtrar distribución de alumnos">
              </div>
            </div>

            <div class="d-flex align-items-center gap-2">
              <select class="form-select form-select-sm js-table-size" data-table-id="students-breakdown-table" style="width: 70px;" aria-label="Cantidad de filas">
                <option value="20" selected>20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>

              <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-success js-export-excel" data-table-id="students-breakdown-table" data-filename="alumnos-distribucion-<?php echo e($ciclo->label); ?>" title="Exportar a Excel">
                  <i class="bi bi-file-earmark-excel me-1"></i>Excel
                </button>
                <button type="button" class="btn btn-outline-primary js-export-csv" data-table-id="students-breakdown-table" data-filename="alumnos-distribucion-<?php echo e($ciclo->label); ?>" title="Exportar a CSV">
                  <i class="bi bi-file-earmark-text me-1"></i>CSV
                </button>
                <button type="button" class="btn btn-outline-danger js-export-pdf" data-table-id="students-breakdown-table" data-filename="alumnos-distribucion-<?php echo e($ciclo->label); ?>" title="Imprimir / PDF">
                  <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive" style="min-height: 260px; max-height: 420px;">
          <table id="students-breakdown-table" class="table table-hover table-sm align-middle mb-0 dashboard-data-table">
            <thead class="table-light sticky-top">
              <tr>
                <th scope="col" class="sortable" data-sort="string" style="cursor:pointer; width: 160px;" title="Ordenar por código de grupo">
                  Código Grupo <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable" data-sort="string" style="cursor:pointer;" title="Ordenar por modalidad">
                  Modalidad <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-center" data-sort="string" style="cursor:pointer; width: 100px;" title="Ordenar por sede">
                  Sede <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-end" data-sort="number" style="cursor:pointer; width: 110px;" title="Ordenar por alumnos">
                  Alumnos <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="text-end" style="width: 100px;">% Matrícula</th>
              </tr>
            </thead>
            <tbody>
              <?php $totalAlumnosCalc = max(1, $kpis['alumnos'] ?? 1); ?>
              <?php $__empty_1 = true; $__currentLoopData = $dashboardSummary['alumnosPorGrupo']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php 
                  $pct = round(($row->alumnos / $totalAlumnosCalc) * 100, 1);
                  $modBadgeClass = match($row->modalidad_corta ?? '') {
                    'I' => 'bg-primary-subtle text-primary border-primary-subtle',
                    'B' => 'bg-info-subtle text-info border-info-subtle',
                    'D' => 'bg-warning-subtle text-warning border-warning-subtle',
                    'M' => 'bg-purple-subtle text-purple border-purple-subtle',
                    default => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                  };
                ?>
                <tr>
                  <td class="font-monospace fw-semibold small"><?php echo e($row->codigo_grupo); ?></td>
                  <td>
                    <span class="badge <?php echo e($modBadgeClass); ?> font-monospace me-1"><?php echo e($row->modalidad_corta ?? 'TR'); ?></span>
                    <span class="fw-semibold small text-uppercase"><?php echo e($row->modalidad ?? 'TRADICIONAL'); ?></span>
                    <?php if(!empty($row->es_tercer_ciclo)): ?>
                      <span class="badge bg-warning text-dark border border-warning-subtle ms-1" style="font-size:10px" title="Tercer Ciclo (Ingeniería/Licenciatura - NME)">
                        3C · NME
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="fw-semibold small text-truncate" style="max-width: 250px;" title="<?php echo e($row->sede ?? ($sedesMap[$row->id_campus] ?? 'Sede ' . $row->id_campus)); ?>">
                      <i class="bi bi-geo-alt text-muted me-1"></i><?php echo e($row->sede ?? ($sedesMap[$row->id_campus] ?? 'Sede ' . $row->id_campus)); ?>

                    </div>
                  </td>
                  <td class="text-end fw-semibold mono" style="font-family:'JetBrains Mono',monospace">
                    <?php echo e(number_format($row->alumnos)); ?>

                  </td>
                  <td class="text-end">
                    <div class="d-flex align-items-center justify-content-end gap-1">
                      <div class="progress flex-grow-1" style="height: 6px; max-width: 45px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo e(min(100, $pct * 3)); ?>%"></div>
                      </div>
                      <span class="small text-muted mono" style="font-size: 11px;"><?php echo e($pct); ?>%</span>
                    </div>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="5" class="text-center py-5 text-secondary">
                    <i class="bi bi-people fs-3 d-block mb-2 text-muted"></i>
                    Sin alumnos inscritos registrados en este ciclo
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="card-footer bg-surface-1 border-top py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div class="small text-secondary js-table-info" data-table-id="students-breakdown-table">
            Mostrando 1 a 20 de <?php echo e(number_format($dashboardSummary['alumnosPorGrupo']->count())); ?> grupos
          </div>
          <div class="js-table-pagination" data-table-id="students-breakdown-table"></div>
        </div>
      </div>
    </div>

    
    <div class="col-12 col-lg-5">
      <div class="card h-100 shadow-sm border dashboard-table-card">
        <div class="card-header bg-surface-1 border-bottom p-3">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
              <h3 class="h6 fw-bold mb-0">Oferta Académica por Sede</h3>
              <span class="small text-secondary">Distribución de cursos y planes</span>
            </div>
            <div class="btn-group btn-group-sm">
              <button type="button" class="btn btn-outline-success js-export-excel" data-table-id="courses-campus-table" data-filename="oferta-sedes-<?php echo e($ciclo->label); ?>" title="Exportar a Excel">
                <i class="bi bi-file-earmark-excel"></i>
              </button>
              <button type="button" class="btn btn-outline-primary js-export-csv" data-table-id="courses-campus-table" data-filename="oferta-sedes-<?php echo e($ciclo->label); ?>" title="Exportar a CSV">
                <i class="bi bi-file-earmark-text"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table id="courses-campus-table" class="table table-hover table-sm align-middle mb-0 dashboard-data-table">
            <thead class="table-light">
              <tr>
                <th scope="col">Sede / Campus</th>
                <th scope="col" class="text-end">Cursos</th>
                <th scope="col" class="text-end">Planes</th>
                <th scope="col" class="text-end">Materias</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $dashboardSummary['cursosPorSede']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td>
                    <div class="fw-semibold small text-truncate" style="max-width: 220px;" title="<?php echo e($row->sede_nombre ?? ($sedesMap[$row->id_campus] ?? 'Campus Principal')); ?>">
                      <i class="bi bi-building text-primary me-1"></i><?php echo e($row->sede_nombre ?? ($sedesMap[$row->id_campus] ?? 'Campus Principal')); ?>

                    </div>
                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 10px;">
                      Sede ID: <?php echo e($row->id_campus ?: '1'); ?>

                    </span>
                  </td>
                  <td class="text-end fw-semibold mono" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($row->cursos)); ?></td>
                  <td class="text-end mono" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($row->planes)); ?></td>
                  <td class="text-end mono" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($row->materias)); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="4" class="text-center py-4 text-secondary">Sin cursos registrados por sede</td>
                </tr>
              <?php endif; ?>
            </tbody>
            <?php if($dashboardSummary['cursosPorSede']->isNotEmpty()): ?>
              <tfoot class="table-light border-top">
                <tr class="fw-bold">
                  <td>Total</td>
                  <td class="text-end mono" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($dashboardSummary['cursosPorSede']->sum('cursos'))); ?></td>
                  <td class="text-end mono" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($dashboardSummary['cursosPorSede']->sum('planes'))); ?></td>
                  <td class="text-end mono" style="font-family:'JetBrains Mono',monospace"><?php echo e(number_format($dashboardSummary['cursosPorSede']->sum('materias'))); ?></td>
                </tr>
              </tfoot>
            <?php endif; ?>
          </table>
        </div>

        
        <div class="card-footer bg-surface-1 border-top p-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small fw-semibold text-secondary">Catálogos Operativos del Ciclo</span>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <div class="p-2 border rounded bg-surface-2">
                <div class="small text-muted" style="font-size:11px;">Niveles Educativos:</div>
                <div class="fw-bold fs-6 mono text-primary" style="font-family:'JetBrains Mono',monospace">
                  <?php echo e(number_format($dashboardSummary['niveles']->count())); ?> programas
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="p-2 border rounded bg-surface-2">
                <div class="small text-muted" style="font-size:11px;">Turnos Activos:</div>
                <div class="fw-bold fs-6 mono text-success" style="font-family:'JetBrains Mono',monospace">
                  <?php echo e(number_format($dashboardSummary['turnos']->count())); ?> turnos
                </div>
                <div class="text-muted" style="font-size:10px;">MATUTINO / VESPERTINO</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    
    <div class="col-12">
          <div class="card shadow-sm border dashboard-table-card">
        <div class="card-header bg-surface-1 border-bottom p-3">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <div>
              <h3 class="h6 fw-bold mb-0">Cursos y Asignaciones por Profesor</h3>
              <span class="small text-secondary">Detalle de oferta académica y sesiones asignadas</span>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 js-table-count-badge">
              <?php echo e(number_format($dashboardSummary['cursosPorOrigen']->count())); ?> cursos
            </span>
          </div>

          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-2 border-top">
            <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 200px;">
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-surface-2 border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control form-control-sm border-start-0 js-table-search" data-table-id="courses-origin-table" placeholder="Buscar curso, profesor o sede..." aria-label="Buscar curso o profesor">
              </div>
            </div>

            <div class="d-flex align-items-center gap-2">
              <select class="form-select form-select-sm js-table-size" data-table-id="courses-origin-table" style="width: 70px;" aria-label="Cantidad de filas">
                <option value="20" selected>20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>

              <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-success js-export-excel" data-table-id="courses-origin-table" data-filename="cursos-profesores-<?php echo e($ciclo->label); ?>" title="Exportar a Excel">
                  <i class="bi bi-file-earmark-excel me-1"></i>Excel
                </button>
                <button type="button" class="btn btn-outline-primary js-export-csv" data-table-id="courses-origin-table" data-filename="cursos-profesores-<?php echo e($ciclo->label); ?>" title="Exportar a CSV">
                  <i class="bi bi-file-earmark-text me-1"></i>CSV
                </button>
                <button type="button" class="btn btn-outline-danger js-export-pdf" data-table-id="courses-origin-table" data-filename="cursos-profesores-<?php echo e($ciclo->label); ?>" title="Imprimir / PDF">
                  <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive" style="min-height: 260px; max-height: 440px;">
          <table id="courses-origin-table" class="table table-hover table-sm align-middle mb-0 dashboard-data-table">
            <thead class="table-light sticky-top">
              <tr>
                <th scope="col" class="sortable text-nowrap" data-sort="string" style="cursor:pointer; width: 180px;" title="Ordenar por clave de curso">
                  Clave de Curso <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable" data-sort="string" style="cursor:pointer;" title="Ordenar por profesor/asignación">
                  Profesor / Asignación <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-center" data-sort="string" style="cursor:pointer; width: 100px;" title="Ordenar por tipo de contrato">
                  Tipo <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-center" data-sort="string" style="cursor:pointer; width: 140px;" title="Ordenar por sede">
                  Sede <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
                <th scope="col" class="sortable text-end" data-sort="number" style="cursor:pointer; width: 100px;" title="Ordenar por sesiones">
                  Sesiones <i class="bi bi-arrow-down-up text-muted ms-1" style="font-size:10px;"></i>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $dashboardSummary['cursosPorOrigen']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php 
                  $originLabel = match($row->origen) { 'CA' => 'PA', 'HD' => 'PTC', default => $row->origen };
                  $originBadge = match($originLabel) {
                    'PTC' => 'bg-success-subtle text-success border border-success-subtle',
                    'PA' => 'bg-info-subtle text-info border border-info-subtle',
                    default => 'bg-secondary-subtle text-secondary border'
                  };
                ?>
                <tr>
                  <td>
                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 11px;">
                      <?php echo e($row->clave_curso); ?>

                    </span>
                  </td>
                  <td>
                    <div class="fw-semibold text-truncate" style="max-width: 320px;" title="<?php echo e($row->nombre_curso ?: 'Sin profesor asignado'); ?>">
                      <?php echo e($row->nombre_curso ?: 'Sin profesor asignado'); ?>

                    </div>
                  </td>
                  <td class="text-center">
                    <span class="badge <?php echo e($originBadge); ?>">
                      <?php echo e($originLabel); ?>

                    </span>
                  </td>
                  <td>
                    <div class="small text-truncate" style="max-width: 200px;" title="<?php echo e($row->sede_nombre ?? ($sedesMap[$row->id_campus] ?? 'Campus Principal')); ?>">
                      <i class="bi bi-geo-alt text-muted me-1"></i><?php echo e($row->sede_nombre ?? ($sedesMap[$row->id_campus] ?? 'Campus Principal')); ?>

                    </div>
                  </td>
                  <td class="text-end fw-semibold mono" style="font-family:'JetBrains Mono',monospace">
                    <?php echo e(number_format($row->sesiones)); ?>

                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="5" class="text-center py-5 text-secondary">
                    <i class="bi bi-book fs-3 d-block mb-2 text-muted"></i>
                    Sin cursos registrados en este ciclo
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="card-footer bg-surface-1 border-top py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div class="small text-secondary js-table-info" data-table-id="courses-origin-table">
            Mostrando 1 a 20 de <?php echo e(number_format($dashboardSummary['cursosPorOrigen']->count())); ?> cursos ofertados
          </div>
          <div class="js-table-pagination" data-table-id="courses-origin-table"></div>
        </div>
      </div>
    </div>
  </div>
</section>


<?php
  $cargaBloques = $cargaPorMaestro->flatMap(fn ($maestro) => $maestro->blocks)
    ->map(fn ($block) => $block->sede_id . '|' . $block->turno . '|' . $block->sede)
    ->unique()
    ->sort()
    ->values();
  $cargaBloquesPorSede = $cargaBloques->groupBy(function ($blockLabel) {
    [, , $sede] = explode('|', $blockLabel, 3);
    return $sede;
  });
?>
<section aria-labelledby="carga-maestro-heading" class="mb-4">
  <div class="section-heading mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h2 id="carga-maestro-heading" class="h6 fw-bold mb-0">Carga Académica Total por Maestro</h2>
      <span class="small text-secondary">Horas de grupo y horas de estadía por sede y turno</span>
    </div>
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Ciclo <?php echo e($ciclo->label); ?></span>
  </div>

  <div class="card shadow-sm border dashboard-table-card">
    <div class="print-report-meta" hidden>
      <strong>Carga Académica Total por Maestro</strong>
      <span>Ciclo <?php echo e($ciclo->label); ?> · Generado <?php echo e(now()->format('d/m/Y H:i')); ?></span>
    </div>
    <div class="card-header bg-surface-1 border-bottom p-3">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <span class="small text-secondary">Puede combinar filtros por docente, tipo, sede, turno o cualquier bloque de horas.</span>
        <div class="d-flex gap-2">
          <input type="search" class="form-control form-control-sm js-table-search" data-table-id="carga-maestro-table" placeholder="Buscar en la tabla..." aria-label="Buscar en carga académica">
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle align-self-center d-none js-active-filters" data-table-id="carga-maestro-table" aria-live="polite"></span>
          <button type="button" class="btn btn-sm btn-outline-secondary js-clear-column-filters" data-table-id="carga-maestro-table">Limpiar</button>
          <div class="dropdown">
            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-download me-1"></i>Exportar
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><button type="button" class="dropdown-item js-export-excel" data-table-id="carga-maestro-table" data-filename="carga-academica-<?php echo e($ciclo->label); ?>"><i class="bi bi-file-earmark-excel text-success me-2"></i>Excel</button></li>
              <li><button type="button" class="dropdown-item js-export-csv" data-table-id="carga-maestro-table" data-filename="carga-academica-<?php echo e($ciclo->label); ?>"><i class="bi bi-file-earmark-text text-primary me-2"></i>CSV</button></li>
              <li><button type="button" class="dropdown-item js-export-pdf" data-table-id="carga-maestro-table" data-filename="carga-academica-<?php echo e($ciclo->label); ?>"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>PDF / imprimir</button></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive" style="min-height: 300px; max-height: 620px;">
      <table id="carga-maestro-table" class="table table-bordered table-hover table-sm align-middle mb-0 dashboard-data-table carga-academica-table">
        <thead class="table-light sticky-top">
          <tr>
            <th rowspan="3" class="sortable text-nowrap" data-sort="string">Docente</th>
            <th rowspan="3" class="sortable text-center" data-sort="string">Tipo</th>
            <?php $__currentLoopData = $cargaBloquesPorSede; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blockSede => $blocksForSede): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <th colspan="<?php echo e($blocksForSede->count() * 2); ?>" class="text-center carga-block-header carga-block-col"><?php echo e($blockSede); ?></th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <th rowspan="3" class="sortable text-end" data-sort="number" data-sort-column="<?php echo e(2 + ($cargaBloques->count() * 2)); ?>">Total horas clase frente a grupo por semana</th>
            <th rowspan="3" class="sortable text-end" data-sort="number" data-sort-column="<?php echo e(3 + ($cargaBloques->count() * 2)); ?>">Total RH</th>
            <th rowspan="3" class="sortable text-center" data-sort-column="<?php echo e(4 + ($cargaBloques->count() * 2)); ?>">Otro turno</th>
            <th rowspan="3" class="sortable" data-sort-column="<?php echo e(5 + ($cargaBloques->count() * 2)); ?>">Observaciones</th>
          </tr>
          <tr>
            <?php $__currentLoopData = $cargaBloques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blockLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php [, $blockTurno] = explode('|', $blockLabel, 3); ?>
              <th colspan="2" class="text-center carga-block-col"><?php echo e($blockTurno); ?></th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tr>
          <tr>
            <?php $__currentLoopData = $cargaBloques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blockLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <th class="text-end carga-block-col">Horas Grupo</th>
              <th class="text-end carga-block-col">Horas Estadía</th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tr>
          <tr class="column-filters">
            <th><input type="search" class="form-control form-control-sm js-column-filter" data-filter-column="0" placeholder="Docente" aria-label="Filtrar docente"></th>
            <th><input type="search" class="form-control form-control-sm js-column-filter" data-filter-column="1" placeholder="Tipo" aria-label="Filtrar tipo"></th>
            <?php $__currentLoopData = $cargaBloques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $blockLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <th class="carga-block-col"><input type="search" class="form-control form-control-sm js-column-filter" data-filter-column="<?php echo e(2 + ($index * 2)); ?>" placeholder="Grupo" aria-label="Filtrar horas grupo"></th>
              <th class="carga-block-col"><input type="search" class="form-control form-control-sm js-column-filter" data-filter-column="<?php echo e(3 + ($index * 2)); ?>" placeholder="Estadía" aria-label="Filtrar horas estadía"></th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <th><input type="search" class="form-control form-control-sm js-column-filter" data-filter-column="<?php echo e(2 + ($cargaBloques->count() * 2)); ?>" placeholder="Total" aria-label="Filtrar total clase"></th>
            <th><input type="search" class="form-control form-control-sm js-column-filter" data-filter-column="<?php echo e(3 + ($cargaBloques->count() * 2)); ?>" placeholder="RH" aria-label="Filtrar total RH"></th>
            <th><input type="search" class="form-control form-control-sm js-column-filter" data-filter-column="<?php echo e(4 + ($cargaBloques->count() * 2)); ?>" placeholder="Turno" aria-label="Filtrar otro turno"></th>
            <th><input type="search" class="form-control form-control-sm js-column-filter" data-filter-column="<?php echo e(5 + ($cargaBloques->count() * 2)); ?>" placeholder="Notas" aria-label="Filtrar observaciones"></th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $cargaPorMaestro; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maestro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
              $originLabel = match($maestro->origen) { 'HD' => 'PTC', 'CA' => 'PA', default => 'N/D' };
              $originBadge = $maestro->origen === 'HD' ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info';
              $blocks = $maestro->blocks->keyBy(fn ($block) => $block->sede_id . '|' . $block->turno . '|' . $block->sede);
            ?>
            <tr>
              <td>
                <div class="fw-semibold"><?php echo e($maestro->nombre); ?></div>
                <small class="text-muted font-monospace"><?php echo e($maestro->clave_profesor); ?></small>
                <?php if($maestro->carreras): ?><small class="d-block text-muted" title="Carrera"><?php echo e($maestro->carreras); ?></small><?php endif; ?>
                <?php if($maestro->niveles): ?><small class="d-block text-muted" title="Niveles">Nivel: <?php echo e($maestro->niveles); ?></small><?php endif; ?>
              </td>
              <td class="text-center"><span class="badge <?php echo e($originBadge); ?>"><?php echo e($originLabel); ?></span></td>
              <?php $__currentLoopData = $cargaBloques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blockLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $block = $blocks->get($blockLabel); ?>
                <td class="text-end mono carga-block-col" data-total-role="grupo" data-value="<?php echo e($block?->horas_grupo ?? 0); ?>"><?php echo e(number_format($block?->horas_grupo ?? 0)); ?></td>
                <td class="text-end mono carga-block-col" data-total-role="estadia" data-value="<?php echo e($block?->horas_estadia ?? 0); ?>"><?php echo e(number_format($block?->horas_estadia ?? 0)); ?></td>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <td class="text-end fw-bold mono" data-total-role="clase" data-value="<?php echo e($maestro->horas_grupo); ?>"><?php echo e(number_format($maestro->horas_grupo)); ?></td>
              <td class="text-end fw-bold mono" data-total-role="rh" data-value="<?php echo e($maestro->total_rh); ?>"><?php echo e(number_format($maestro->total_rh)); ?></td>
              <td class="text-center"><?php echo e($maestro->otro_turno); ?></td>
              <td><?php echo e($maestro->observaciones); ?></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="<?php echo e(6 + ($cargaBloques->count() * 2)); ?>" class="text-center py-5 text-secondary">Sin carga académica registrada para este ciclo.</td></tr>
          <?php endif; ?>
        </tbody>
        <tfoot class="table-light border-top">
          <tr class="fw-bold">
            <td colspan="2">Totales filtrados</td>
            <?php $__currentLoopData = $cargaBloques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blockLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <td class="text-end mono js-total-grupo carga-block-col">0</td><td class="text-end mono js-total-estadia carga-block-col">0</td>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <td class="text-end mono js-total-clase">0</td><td class="text-end mono js-total-rh">0</td><td></td><td></td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="card-footer bg-surface-1 border-top py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="small text-secondary js-table-info" data-table-id="carga-maestro-table"></div>
      <div class="js-table-pagination" data-table-id="carga-maestro-table"></div>
    </div>
  </div>
</section>


<section aria-labelledby="graficos-heading" class="mb-4">
  <div class="section-heading mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h2 id="graficos-heading" class="h6 fw-bold mb-0">Distribución Visual de la Carga Académica</h2>
      <span class="small text-secondary">Gráficos interactivos de niveles, turnos, horas y sedes</span>
    </div>
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Ciclo <?php echo e($ciclo->label); ?></span>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-6">
      <div class="card h-100 shadow-sm border">
        <div class="card-header bg-surface-1 border-bottom py-2 px-3">
          <span class="fw-bold small text-uppercase" style="letter-spacing:0.04em;">
            <i class="bi bi-bar-chart text-primary me-1"></i> Grupos por Nivel Educativo
          </span>
        </div>
        <div class="card-body p-3" style="min-height: 250px;">
          <canvas id="chart-niveles" role="img" aria-label="Gráfico de grupos por nivel educativo"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card h-100 shadow-sm border">
        <div class="card-header bg-surface-1 border-bottom py-2 px-3">
          <span class="fw-bold small text-uppercase" style="letter-spacing:0.04em;">
            <i class="bi bi-pie-chart text-primary me-1"></i> Distribución por Turno
          </span>
        </div>
        <div class="card-body p-3 d-flex align-items-center justify-content-center" style="min-height: 250px;">
          <canvas id="chart-turnos" role="img" aria-label="Gráfico de distribución por turno"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card h-100 shadow-sm border">
        <div class="card-header bg-surface-1 border-bottom py-2 px-3">
          <span class="fw-bold small text-uppercase" style="letter-spacing:0.04em;">
            <i class="bi bi-clock-history text-primary me-1"></i> Horas por Tipo de Contratación
          </span>
        </div>
        <div class="card-body p-3" style="min-height: 250px;">
          <canvas id="chart-horas-origen" role="img" aria-label="Gráfico de horas por origen de contratación"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card h-100 shadow-sm border">
        <div class="card-header bg-surface-1 border-bottom py-2 px-3">
          <span class="fw-bold small text-uppercase" style="letter-spacing:0.04em;">
            <i class="bi bi-building text-primary me-1"></i> Cursos por Sede
          </span>
        </div>
        <div class="card-body p-3" style="min-height: 250px;">
          <canvas id="chart-cursos-sede" role="img" aria-label="Gráfico de cursos por sede"></canvas>
        </div>
      </div>
    </div>
  </div>
</section>

</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('styles'); ?>
<style>
  .academia-dashboard {
    --dashboard-card: var(--surface-1, var(--surface));
    --dashboard-card-soft: var(--surface-2, var(--surface-elevated));
    --dashboard-line: var(--border);
    --dashboard-muted: var(--text-secondary);
    --dashboard-accent: var(--primary);
  }

  .academia-dashboard .card {
    border-color: var(--dashboard-line) !important;
    background: var(--dashboard-card);
    box-shadow: 0 8px 24px rgba(15, 23, 42, .06) !important;
  }
  html[data-theme="dark"] .academia-dashboard .card {
    box-shadow: 0 12px 30px rgba(0, 0, 0, .18) !important;
  }
  .academia-dashboard .card-header,
  .academia-dashboard .card-footer {
    border-color: var(--dashboard-line) !important;
  }

  .dashboard-header-card {
    border-left: 5px solid var(--primary) !important;
    background:
      linear-gradient(135deg, color-mix(in srgb, var(--primary) 8%, transparent), transparent 42%),
      var(--dashboard-card);
  }
  .academia-dashboard h1,
  .academia-dashboard h2,
  .academia-dashboard h3 {
    color: var(--text);
    letter-spacing: -.015em;
  }
  .academia-dashboard .section-heading {
    padding: .25rem .15rem;
  }
  .academia-dashboard .section-heading h2::before {
    content: "";
    display: inline-block;
    width: 4px;
    height: 1.1rem;
    margin-right: .55rem;
    vertical-align: -.15rem;
    border-radius: 999px;
    background: var(--dashboard-accent);
  }
  .academia-dashboard .text-secondary,
  .academia-dashboard .text-muted {
    color: var(--dashboard-muted) !important;
  }
  .academia-dashboard .badge.bg-light {
    color: var(--text-secondary) !important;
    background: var(--dashboard-card-soft) !important;
    border-color: var(--dashboard-line) !important;
  }
  .academia-dashboard .form-control,
  .academia-dashboard .form-select,
  .academia-dashboard .input-group-text {
    color: var(--text);
    background-color: var(--dashboard-card);
    border-color: var(--dashboard-line);
  }
  .academia-dashboard .form-control::placeholder {
    color: var(--text-tertiary);
  }
  .academia-dashboard .form-control:focus,
  .academia-dashboard .form-select:focus {
    color: var(--text);
    background-color: var(--dashboard-card);
    border-color: var(--dashboard-accent);
    box-shadow: 0 0 0 .2rem var(--primary-ring);
  }
  .academia-dashboard .btn-outline-secondary {
    --bs-btn-color: var(--text-secondary);
    --bs-btn-border-color: var(--dashboard-line);
    --bs-btn-hover-color: var(--text);
    --bs-btn-hover-bg: var(--dashboard-card-soft);
    --bs-btn-hover-border-color: var(--border-strong);
  }

  .dashboard-table-card {
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
  }
  .dashboard-table-card .card-header {
    background: var(--dashboard-card) !important;
  }
  .dashboard-data-table {
    font-size: 13px;
    --bs-table-bg: var(--dashboard-card);
    --bs-table-striped-bg: var(--dashboard-card-soft);
    --bs-table-hover-bg: color-mix(in srgb, var(--dashboard-accent) 8%, var(--dashboard-card));
    --bs-table-color: var(--text);
    --bs-table-border-color: var(--dashboard-line);
  }
  .dashboard-data-table th {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
    background-color: var(--dashboard-card-soft) !important;
    border-bottom: 2px solid var(--border);
    padding: 8px 12px;
    white-space: nowrap;
  }
  .dashboard-data-table td {
    padding: 8px 12px;
    color: var(--text);
    border-bottom: 1px solid var(--border);
  }
  .dashboard-data-table tbody tr:hover {
    background-color: var(--bs-table-hover-bg) !important;
  }
  .dashboard-data-table th.sortable:hover {
    color: var(--primary);
  }
  .dashboard-data-table th.sorted-asc,
  .dashboard-data-table th.sorted-desc {
    color: var(--primary);
    background-color: color-mix(in srgb, var(--primary) 8%, var(--surface-2)) !important;
  }
  .dashboard-data-table .column-filters th {
    padding: 4px 8px;
    background: var(--dashboard-card) !important;
  }
  .dashboard-data-table .column-filters input {
    min-width: 58px;
    font-size: 11px;
  }
  .carga-academica-table {
    min-width: 1120px;
  }
  .carga-academica-table thead tr:first-child th {
    background: var(--primary) !important;
    color: #fff !important;
    border-color: color-mix(in srgb, var(--primary) 70%, #000) !important;
  }
  .carga-academica-table thead tr:nth-child(2) th {
    color: var(--text);
    background: color-mix(in srgb, var(--dashboard-accent) 12%, var(--dashboard-card-soft)) !important;
  }
  .carga-academica-table thead tr:nth-child(3) th {
    color: var(--text-secondary);
    background: var(--dashboard-card-soft) !important;
  }
  .carga-academica-table .column-filters th {
    position: sticky;
    top: 91px;
    z-index: 3;
  }
  .carga-academica-table tbody td {
    vertical-align: middle;
  }
  .carga-academica-table tbody td[data-total-role="grupo"],
  .carga-academica-table tbody td[data-total-role="estadia"] {
    font-weight: 600;
    color: var(--text);
  }
  .carga-academica-table tbody td[data-total-role="clase"] {
    color: var(--cat-blue);
  }
  .carga-academica-table tbody td[data-total-role="rh"] {
    color: var(--cat-green);
  }
  html[data-theme="dark"] .carga-academica-table tbody td[data-total-role="clase"] {
    color: #93c5fd;
  }
  html[data-theme="dark"] .carga-academica-table tbody td[data-total-role="rh"] {
    color: #6ee7b7;
  }
  .academia-dashboard .table-responsive {
    scrollbar-color: var(--scrollbar) transparent;
  }

  .kpi-card {
    transition: transform .15s ease, box-shadow .15s ease;
    position: relative;
    overflow: hidden;
  }
  .kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.06) !important;
  }
  html[data-theme="dark"] .kpi-card:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,0.3) !important;
  }

  .kpi-flash {
    animation: kpiFlashAnim .8s ease-out 1;
  }
  @keyframes kpiFlashAnim {
    0% { transform: scale(1.1); color: var(--primary); }
    100% { transform: scale(1); }
  }

  .trend-chart .area-dot {
    transition: r .2s, fill .2s;
    cursor: pointer;
  }
  .trend-chart .area-dot:hover {
    r: 7;
    fill: var(--primary);
  }

  .hover-primary:hover {
    color: var(--primary) !important;
  }

  @media print {
    body * { visibility: hidden; }
    .print-active-area, .print-active-area * { visibility: visible; }
    .print-active-area { position: absolute; left: 0; top: 0; width: 100%; }
    .btn, .input-group, .form-select, .js-table-pagination { display: none !important; }
  }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function() {
  'use strict';

  /**
   * AcademiaTableEngine
   * Motor de gestión de tablas: Búsqueda dinámica, Ordenamiento, Paginación y Exportaciones (Excel, CSV, PDF)
   */
  function AcademiaTableEngine(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const tbody = table.tBodies[0];
    if (!tbody) return;

    const originalRows = Array.from(tbody.querySelectorAll('tr:not(.empty-row)'));
    if (originalRows.length === 0) return;

    const searchInput = document.querySelector(`.js-table-search[data-table-id="${tableId}"]`);
    const sizeSelect = document.querySelector(`.js-table-size[data-table-id="${tableId}"]`);
    const paginationContainer = document.querySelector(`.js-table-pagination[data-table-id="${tableId}"]`);
    const infoContainer = document.querySelector(`.js-table-info[data-table-id="${tableId}"]`);
    const countBadge = document.querySelector(`.dashboard-table-card:has(#${tableId}) .js-table-count-badge`);
    const columnFilters = Array.from(table.querySelectorAll('.js-column-filter'));
    const activeFiltersBadge = document.querySelector(`.js-active-filters[data-table-id="${tableId}"]`);

    let filteredRows = [...originalRows];
    let currentPage = 1;
    let pageSize = sizeSelect ? parseInt(sizeSelect.value, 10) || 20 : 20;
    let sortColumnIndex = -1;
    let sortDirection = 1; // 1 = asc, -1 = desc

    function render() {
      const totalFiltered = filteredRows.length;
      const totalPages = Math.ceil(totalFiltered / pageSize) || 1;
      if (currentPage > totalPages) currentPage = totalPages;
      if (currentPage < 1) currentPage = 1;

      const startIndex = (currentPage - 1) * pageSize;
      const endIndex = Math.min(startIndex + pageSize, totalFiltered);

      // Limpiar tbody
      tbody.innerHTML = '';

      if (totalFiltered === 0) {
        const colCount = table.querySelectorAll('thead tr:first-child th').length || 5;
        const emptyRow = document.createElement('tr');
        emptyRow.className = 'empty-search-row';
        emptyRow.innerHTML = `<td colspan="${colCount}" class="text-center py-4 text-secondary">
          <i class="bi bi-search fs-4 d-block mb-2 text-muted"></i>
          <div>No se encontraron registros que coincidan con la búsqueda.</div>
          <button type="button" class="btn btn-sm btn-outline-secondary mt-2 js-clear-search">Limpiar filtro</button>
        </td>`;
        emptyRow.querySelector('.js-clear-search').onclick = function() {
          if (searchInput) { searchInput.value = ''; searchInput.dispatchEvent(new Event('input')); }
          columnFilters.forEach(function(input) { input.value = ''; });
          applyFilters();
        };
        tbody.appendChild(emptyRow);
      } else {
        for (let i = startIndex; i < endIndex; i++) {
          tbody.appendChild(filteredRows[i]);
        }
      }

      // Actualizar info
      if (infoContainer) {
        if (totalFiltered === 0) {
          infoContainer.textContent = '0 registros encontrados';
        } else {
          infoContainer.textContent = `Mostrando ${startIndex + 1} a ${endIndex} de ${totalFiltered} registros` + 
            (totalFiltered !== originalRows.length ? ` (filtrados de ${originalRows.length})` : '');
        }
      }

      // Actualizar badge
      if (countBadge) {
        countBadge.textContent = `${totalFiltered} registros`;
      }

      updateTotals();

      // Renderizar paginador
      renderPagination(totalPages);
    }

    function updateTotals() {
      if (tableId !== 'carga-maestro-table') return;

      const totals = {};
      filteredRows.forEach(function(row) {
        Array.from(row.cells).forEach(function(cell, columnIndex) {
          if (cell.dataset.totalRole) {
            totals[columnIndex] = (totals[columnIndex] || 0) + Number(cell.dataset.value || 0);
          }
        });
      });

      const footerCells = table.tFoot?.rows[0]?.cells || [];
      const blockCount = Math.max(0, (footerCells.length - 5) / 2);
      let footerIndex = 1;
      for (let block = 0; block < blockCount; block++) {
        footerCells[footerIndex++].textContent = (totals[2 + (block * 2)] || 0).toLocaleString('es-MX');
        footerCells[footerIndex++].textContent = (totals[3 + (block * 2)] || 0).toLocaleString('es-MX');
      }
      const classColumn = 2 + (blockCount * 2);
      footerCells[footerIndex++].textContent = (totals[classColumn] || 0).toLocaleString('es-MX');
      footerCells[footerIndex++].textContent = (totals[classColumn + 1] || 0).toLocaleString('es-MX');
    }

    function applyFilters() {
      const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
      const activeColumnFilters = columnFilters
        .map(function(input) {
          return {
            column: Number(input.dataset.filterColumn),
            value: input.value.toLowerCase().trim(),
          };
        })
        .filter(function(filter) { return filter.value !== ''; });

      filteredRows = originalRows.filter(function(row) {
        const matchesGlobal = !query || row.innerText.toLowerCase().includes(query);
        const matchesColumns = activeColumnFilters.every(function(filter) {
          return (row.cells[filter.column]?.innerText || '').toLowerCase().includes(filter.value);
        });
        return matchesGlobal && matchesColumns;
      });
      if (activeFiltersBadge) {
        const activeCount = activeColumnFilters.length + (query ? 1 : 0);
        activeFiltersBadge.textContent = activeCount ? `${activeCount} filtro${activeCount === 1 ? '' : 's'} activo${activeCount === 1 ? '' : 's'}` : '';
        activeFiltersBadge.classList.toggle('d-none', activeCount === 0);
      }
      currentPage = 1;
      render();
    }

    function renderPagination(totalPages) {
      if (!paginationContainer) return;
      if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
      }

      let html = '<div class="btn-group btn-group-sm" role="navigation" aria-label="Paginación">';
      
      // Botón anterior
      html += `<button type="button" class="btn btn-outline-secondary ${currentPage === 1 ? 'disabled' : ''}" data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''} aria-label="Página anterior">
        <i class="bi bi-chevron-left"></i>
      </button>`;

      // Botones numéricos (máximo 5 botones)
      const maxButtons = 5;
      let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
      let endPage = Math.min(totalPages, startPage + maxButtons - 1);
      if (endPage - startPage + 1 < maxButtons) {
        startPage = Math.max(1, endPage - maxButtons + 1);
      }

      if (startPage > 1) {
        html += `<button type="button" class="btn btn-outline-secondary" data-page="1">1</button>`;
        if (startPage > 2) html += `<span class="btn btn-outline-secondary disabled">…</span>`;
      }

      for (let p = startPage; p <= endPage; p++) {
        html += `<button type="button" class="btn ${p === currentPage ? 'btn-primary active' : 'btn-outline-secondary'}" data-page="${p}">${p}</button>`;
      }

      if (endPage < totalPages) {
        if (endPage < totalPages - 1) html += `<span class="btn btn-outline-secondary disabled">…</span>`;
        html += `<button type="button" class="btn btn-outline-secondary" data-page="${totalPages}">${totalPages}</button>`;
      }

      // Botón siguiente
      html += `<button type="button" class="btn btn-outline-secondary ${currentPage === totalPages ? 'disabled' : ''}" data-page="${currentPage + 1}" ${currentPage === totalPages ? 'disabled' : ''} aria-label="Página siguiente">
        <i class="bi bi-chevron-right"></i>
      </button>`;

      html += '</div>';
      paginationContainer.innerHTML = html;

      paginationContainer.querySelectorAll('button[data-page]').forEach(function(btn) {
        btn.onclick = function() {
          const target = parseInt(btn.getAttribute('data-page'), 10);
          if (target >= 1 && target <= totalPages && target !== currentPage) {
            currentPage = target;
            render();
          }
        };
      });
    }

    // Evento de búsqueda instantánea
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        applyFilters();
      });
    }

    columnFilters.forEach(function(input) {
      input.addEventListener('input', applyFilters);
      input.addEventListener('click', function(event) { event.stopPropagation(); });
    });

    // Selector de tamaño de página
    if (sizeSelect) {
      sizeSelect.addEventListener('change', function() {
        pageSize = parseInt(sizeSelect.value, 10) || 20;
        currentPage = 1;
        render();
      });
    }

    // Ordenamiento por encabezados
    table.querySelectorAll('thead th.sortable').forEach(function(th) {
      th.addEventListener('click', function() {
        const index = Number(th.dataset.sortColumn ?? th.cellIndex);
        const type = th.getAttribute('data-sort') || 'string';
        if (sortColumnIndex === index) {
          sortDirection = -sortDirection;
        } else {
          sortColumnIndex = index;
          sortDirection = 1;
        }

        // Limpiar clases de sort
        table.querySelectorAll('thead th.sortable').forEach(function(header) {
          header.classList.remove('sorted-asc', 'sorted-desc');
          const icon = header.querySelector('i');
          if (icon) icon.className = 'bi bi-arrow-down-up text-muted ms-1';
        });

        th.classList.add(sortDirection === 1 ? 'sorted-asc' : 'sorted-desc');
        const icon = th.querySelector('i');
        if (icon) {
          icon.className = sortDirection === 1 ? 'bi bi-sort-down-alt text-primary ms-1' : 'bi bi-sort-up text-primary ms-1';
        }

        filteredRows.sort(function(a, b) {
          const aText = (a.cells[index]?.innerText || '').trim();
          const bText = (b.cells[index]?.innerText || '').trim();

          if (type === 'number') {
            const aNum = parseFloat(aText.replace(/[^0-9.-]+/g, '')) || 0;
            const bNum = parseFloat(bText.replace(/[^0-9.-]+/g, '')) || 0;
            return (aNum - bNum) * sortDirection;
          } else {
            return aText.localeCompare(bText, 'es', { numeric: true }) * sortDirection;
          }
        });

        currentPage = 1;
        render();
      });
    });

    // Inicializar render
    render();
  }

  // Inicializar todos los motores de tabla en la página
  document.addEventListener('DOMContentLoaded', function() {
    const tableIds = ['materias-dashboard-table', 'planes-dashboard-table', 'students-breakdown-table', 'courses-origin-table', 'carga-maestro-table'];
    tableIds.forEach(function(id) {
      AcademiaTableEngine(id);
    });

    document.querySelectorAll('.js-clear-column-filters').forEach(function(button) {
      button.addEventListener('click', function() {
        const tableId = button.dataset.tableId;
        document.querySelectorAll(`.js-column-filter[data-table-id="${tableId}"], #${tableId} .js-column-filter`).forEach(function(input) {
          input.value = '';
          input.dispatchEvent(new Event('input'));
        });
        const search = document.querySelector(`.js-table-search[data-table-id="${tableId}"]`);
        if (search) {
          search.value = '';
          search.dispatchEvent(new Event('input'));
        }
      });
    });

    // ── Exportación a CSV con UTF-8 BOM (Soporta acentos para Excel) ──
    document.querySelectorAll('.js-export-csv').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const tableId = btn.getAttribute('data-table-id');
        const table = document.getElementById(tableId);
        if (!table) return;

        const filename = (btn.getAttribute('data-filename') || tableId) + '.csv';
        let csvContent = '\uFEFF'; // BOM para que Excel respete acentos y caracteres UTF-8

        // Encabezados
        const headers = Array.from(table.querySelectorAll('thead tr th'))
          .filter(th => !th.classList.contains('text-end') || th.innerText.toLowerCase() !== 'acción')
          .map(th => `"${th.innerText.replace(/"/g, '""').replace(/\s+/g, ' ').trim()}"`);
        csvContent += headers.join(',') + '\r\n';

        // Filas
        Array.from(table.querySelectorAll('tbody tr:not(.empty-search-row)')).forEach(function(row) {
          const cells = Array.from(row.cells)
            .filter((cell, idx) => headers[idx] !== undefined)
            .map(cell => `"${cell.innerText.replace(/"/g, '""').replace(/\s+/g, ' ').trim()}"`);
          csvContent += cells.join(',') + '\r\n';
        });

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
        URL.revokeObjectURL(link.href);
      });
    });

    // ── Exportación a Excel (.xls nativo legible) ──
    document.querySelectorAll('.js-export-excel').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const tableId = btn.getAttribute('data-table-id');
        const table = document.getElementById(tableId);
        if (!table) return;

        const filename = (btn.getAttribute('data-filename') || tableId) + '.xls';
        const title = btn.getAttribute('data-filename') || 'Exportación Academia';

        const closeHead = '<' + '/head>';
        const openBody = '<' + 'body>';
        const closeBody = '<' + '/body>';
        const closeHtml = '<' + '/html>';

        let html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' +
          '<head><meta charset="utf-8"/><title>' + title + '<' + '/title>' +
          '<style>' +
          'table { border-collapse:collapse; width:100%; font-family:sans-serif; } ' +
          'th { background-color:#2563eb; color:#ffffff; font-weight:bold; border:1px solid #1d4ed8; padding:8px; } ' +
          'td { border:1px solid #e2e8f0; padding:6px; font-size:12px; } ' +
          '.mono { font-family:monospace; } ' +
          '</style>' + closeHead + openBody +
          '<h3>' + title + '</h3>' +
          '<table border="1">';

        // Thead
        html += '<thead><tr>';
        table.querySelectorAll('thead tr th').forEach(function(th) {
          if (th.innerText.trim().toLowerCase() !== 'acción') {
            html += '<th>' + th.innerText.replace(/\s+/g, ' ').trim() + '</th>';
          }
        });
        html += '</tr></thead><tbody>';

        // Tbody
        table.querySelectorAll('tbody tr:not(.empty-search-row)').forEach(function(row) {
          html += '<tr>';
          Array.from(row.cells).forEach(function(cell, idx) {
            html += '<td>' + cell.innerText.replace(/\s+/g, ' ').trim() + '</td>';
          });
          html += '</tr>';
        });

        html += '</tbody></table>' + closeBody + closeHtml;

        const blob = new Blob(['\uFEFF' + html], { type: 'application/vnd.ms-excel;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
        URL.revokeObjectURL(link.href);
      });
    });

    // ── Exportación / Impresión a PDF ──
    document.querySelectorAll('.js-export-pdf').forEach(function(btn) {
      btn.addEventListener('click', function() {
        const tableId = btn.getAttribute('data-table-id');
        const card = document.querySelector(`.dashboard-table-card:has(#${tableId})`);
        if (!card) return;

        const originalTitle = document.title;
        document.title = (btn.getAttribute('data-filename') || 'Reporte-Academia') + ' — ' + new Date().toLocaleDateString();

        card.classList.add('print-active-area');
        window.print();
        card.classList.remove('print-active-area');
        document.title = originalTitle;
      });
    });

    // ── Gráficos Chart.js ──
    initAcademiaCharts();
  });

  function initAcademiaCharts() {
    if (typeof Chart === 'undefined') return;

    const primaryColor = '#3b82f6';
    const purpleColor = '#9333ea';
    const greenColor = '#10b981';
    const orangeColor = '#f97316';
    const pinkColor = '#ec4899';
    const blueColor = '#0ea5e9';
    const grayColor = '#6b7280';

    const defaultFont = { family: "'Inter', system-ui, sans-serif", size: 11 };
    const defaultColor = '#64748b';

    // ── Grupos por Nivel ──
    const nivelesData = <?php echo json_encode($dashboardSummary['niveles'] ?? collect(), 15, 512) ?>;
    if (nivelesData.length > 0) {
      const ctx = document.getElementById('chart-niveles');
      if (ctx) {
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: nivelesData.map(n => n.nivel),
            datasets: [{
              label: 'Grupos',
              data: nivelesData.map(n => n.grupos),
              backgroundColor: [primaryColor, purpleColor, greenColor, orangeColor, pinkColor, blueColor],
              borderRadius: 6,
              barThickness: 28,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { display: false },
              tooltip: {
                backgroundColor: '#1e293b',
                padding: 10,
                cornerRadius: 8,
                callbacks: {
                  label: (ctx) => ` ${ctx.parsed.y} grupos`
                }
              }
            },
            scales: {
              x: { grid: { display: false }, ticks: { font: defaultFont, color: defaultColor } },
              y: { beginAtZero: true, grid: { color: '#e2e8f0' }, ticks: { font: defaultFont, color: defaultColor, stepSize: 1 } }
            }
          }
        });
      }
    }

    // ── Distribución por Turno ──
    const turnosData = <?php echo json_encode($dashboardSummary['turnos'] ?? collect(), 15, 512) ?>;
    if (turnosData.length > 0) {
      const ctx = document.getElementById('chart-turnos');
      if (ctx) {
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: turnosData.map(t => t.turno),
            datasets: [{
              data: turnosData.map(t => t.grupos),
              backgroundColor: [orangeColor, blueColor, grayColor],
              borderWidth: 2,
              borderColor: '#fff',
              hoverOffset: 8,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
              legend: { position: 'bottom', labels: { font: defaultFont, color: defaultColor, padding: 12, usePointStyle: true } },
              tooltip: {
                backgroundColor: '#1e293b',
                padding: 10,
                cornerRadius: 8,
                callbacks: {
                  label: (ctx) => ` ${ctx.label}: ${ctx.parsed} grupos`
                }
              }
            }
          }
        });
      }
    }

    // ── Horas por Tipo de Contratación ──
    const horasOrigenData = <?php echo json_encode($dashboardSummary['horasPorOrigen'] ?? collect(), 15, 512) ?>;
    if (horasOrigenData.length > 0) {
      const ctx = document.getElementById('chart-horas-origen');
      if (ctx) {
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: horasOrigenData.map(h => {
              const label = h.origen || 'SIN_DEFINIR';
              return label === 'HD' ? 'PTC (Tiempo Completo)' : label === 'CA' ? 'PA (Asignatura)' : label;
            }),
            datasets: [{
              label: 'Horas',
              data: horasOrigenData.map(h => parseInt(h.horas) || 0),
              backgroundColor: [greenColor, blueColor, grayColor],
              borderRadius: 6,
              barThickness: 40,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { display: false },
              tooltip: {
                backgroundColor: '#1e293b',
                padding: 10,
                cornerRadius: 8,
                callbacks: {
                  label: (ctx) => ` ${ctx.parsed.y.toLocaleString()} horas`
                }
              }
            },
            scales: {
              x: { grid: { display: false }, ticks: { font: defaultFont, color: defaultColor } },
              y: { beginAtZero: true, grid: { color: '#e2e8f0' }, ticks: { font: defaultFont, color: defaultColor } }
            }
          }
        });
      }
    }

    // ── Cursos por Sede ──
    const cursosSedeData = <?php echo json_encode($dashboardSummary['cursosPorSede'] ?? collect(), 15, 512) ?>;
    if (cursosSedeData.length > 0) {
      const ctx = document.getElementById('chart-cursos-sede');
      if (ctx) {
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: cursosSedeData.map(c => c.sede_nombre || `Sede ${c.id_campus}`),
            datasets: [{
              label: 'Cursos',
              data: cursosSedeData.map(c => parseInt(c.cursos) || 0),
              backgroundColor: primaryColor,
              borderRadius: 6,
              barThickness: 32,
            }, {
              label: 'Planes',
              data: cursosSedeData.map(c => parseInt(c.planes) || 0),
              backgroundColor: purpleColor,
              borderRadius: 6,
              barThickness: 32,
            }, {
              label: 'Materias',
              data: cursosSedeData.map(c => parseInt(c.materias) || 0),
              backgroundColor: greenColor,
              borderRadius: 6,
              barThickness: 32,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { position: 'bottom', labels: { font: defaultFont, color: defaultColor, padding: 12, usePointStyle: true } },
              tooltip: {
                backgroundColor: '#1e293b',
                padding: 10,
                cornerRadius: 8,
                callbacks: {
                  label: (ctx) => ` ${ctx.dataset.label}: ${ctx.parsed.y}`
                }
              }
            },
            scales: {
              x: { grid: { display: false }, ticks: { font: defaultFont, color: defaultColor } },
              y: { beginAtZero: true, grid: { color: '#e2e8f0' }, ticks: { font: defaultFont, color: defaultColor } }
            }
          }
        });
      }
    }
  }
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ProyectoUTE\resources\views\academia\dashboard\index.blade.php ENDPATH**/ ?>