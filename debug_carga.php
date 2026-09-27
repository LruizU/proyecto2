<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Aguilar Lucio PF6205 - HORARIOS en todos los ciclos ===\n";
$todos = App\Models\Academia\HorarioDet::where('clave_profesor', 'PF6205')
    ->select('inicial', 'final', 'periodo', 'codigo_grupo', 'clave_asignatura', 'horas_semanales', 'horas_teoria_practica', 'activo')
    ->orderBy('inicial', 'desc')->get();
echo 'Total registros: ' . $todos->count() . "\n";
foreach ($todos as $h) {
    echo $h->inicial . '-' . $h->final . '-' . $h->periodo . ' | grupo=' . $h->codigo_grupo . ' | mat=' . $h->clave_asignatura . ' | hrs_sem=' . $h->horas_semanales . ' | HT=' . $h->horas_teoria_practica . ' | activo=' . $h->activo . "\n";
}

echo "\n=== Aguilar Lucio PF6205 - CURSOS en todos los ciclos ===\n";
$cursos = App\Models\Academia\Curso::where('clave_profesor', 'PF6205')
    ->select('inicial', 'final', 'periodo', 'clave_curso', 'codigo_grupo', 'sesiones', 'activo')
    ->orderBy('inicial', 'desc')->get();
echo 'Total cursos: ' . $cursos->count() . "\n";
foreach ($cursos as $c) {
    echo $c->inicial . '-' . $c->final . '-' . $c->periodo . ' | curso=' . $c->clave_curso . ' | grupo=' . $c->codigo_grupo . ' | sesiones=' . $c->sesiones . ' | activo=' . $c->activo . "\n";
}

echo "\n=== MATERIAS con HT+HP para materias de Aguilar ===\n";
$mats = App\Models\Academia\Materia::whereIn('clave_asignatura', $todos->pluck('clave_asignatura')->unique())->get();
echo 'Materias encontradas: ' . $mats->count() . "\n";
foreach ($mats->take(10) as $m) {
    echo $m->clave_asignatura . ' | ' . $m->nombre_asignatura . ' | HT=' . $m->horas_teoria . ' | HP=' . $m->horas_practica . ' | Total=' . ($m->horas_teoria + $m->horas_practica) . "\n";
}
