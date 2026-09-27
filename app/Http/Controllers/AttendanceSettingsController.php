<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceSettingsController extends Controller
{
    public function edit(): View
    {
        $graceMinutes = SystemSetting::integer('attendance.grace_minutes', 10);

        return view('configuracion.asistencia', compact('graceMinutes'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'grace_minutes' => ['required', 'integer', 'min:0', 'max:180'],
        ]);

        SystemSetting::updateOrCreate(
            ['key' => 'attendance.grace_minutes'],
            ['value' => (string) $data['grace_minutes']],
        );

        return back()->with('success', 'Configuración de asistencia actualizada correctamente.');
    }
}
