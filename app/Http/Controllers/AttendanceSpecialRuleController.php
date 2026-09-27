<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AttendanceSpecialRule;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceSpecialRuleController extends Controller
{
    public function index(): View
    {
        return view('configuracion.asistencia-especial', [
            'rules' => AttendanceSpecialRule::with(['employee', 'creator'])->latest()->get(),
            'employees' => Employee::activos()->orderBy('name')->get(['id', 'name', 'user_id', 'numero_empleado']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'minutes' => ['required', 'integer', 'in:20,30'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'reason' => ['required', 'string', 'max:500'],
        ]);
        $data['created_by'] = $request->user()->id;

        AttendanceSpecialRule::create($data);

        return back()->with('success', 'Excepción especial de puntualidad asignada.');
    }

    public function destroy(AttendanceSpecialRule $attendanceSpecialRule): RedirectResponse
    {
        $attendanceSpecialRule->delete();

        return back()->with('success', 'Excepción especial eliminada.');
    }
}
