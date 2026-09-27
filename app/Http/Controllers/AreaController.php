<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AreaController extends Controller
{
    public function index(): View
    {
        $areas = Area::query()
            ->with(['empleadoResponsable', 'head', 'parent', 'children'])
            ->orderBy('identificador')
            ->get();

        return view('areas.index', compact('areas'));
    }

    public function create(): View
    {
        $areas = Area::query()->orderBy('identificador')->get();
        $empleados = Employee::query()
            ->orderByRaw('LOWER(name)')
            ->get(['id', 'name', 'user_id']);

        return view('areas.create', compact('empleados', 'areas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'identificador' => ['required', 'string', 'max:50', 'unique:areas,identificador'],
            'descripcion' => ['nullable', 'string', 'max:150'],
            'empleado_responsable_id' => ['nullable', 'exists:employees,id'],
            'parent_id' => ['nullable', 'exists:areas,id'],
            'head_employee_id' => ['nullable', 'exists:employees,id'],
        ], [
            'identificador.unique' => 'Ya existe un área con ese identificador.',
            'empleado_responsable_id.exists' => 'El empleado responsable seleccionado no existe.',
            'parent_id.exists' => 'El área superior seleccionada no existe.',
            'head_employee_id.exists' => 'El jefe seleccionado no existe.',
        ]);

        Area::create($data);

        return redirect()->route('areas.index')->with('success', 'Área creada correctamente.');
    }

    public function show(Area $area): View
    {
        $area->load(['empleadoResponsable', 'puestos.area']);

        return view('areas.show', compact('area'));
    }

    public function edit(Area $area): View
    {
        $area->load('empleadoResponsable');

        $areas = Area::query()
            ->where('id', '<>', $area->id)
            ->orderBy('identificador')
            ->get();
        $empleados = Employee::query()
            ->orderByRaw('LOWER(name)')
            ->get(['id', 'name', 'user_id']);

        return view('areas.edit', compact('area', 'empleados', 'areas'));
    }

    public function update(Request $request, Area $area): RedirectResponse
    {
        $data = $request->validate([
            'identificador' => ['required', 'string', 'max:50', 'unique:areas,identificador,'.$area->id],
            'descripcion' => ['nullable', 'string', 'max:150'],
            'empleado_responsable_id' => ['nullable', 'exists:employees,id'],
            'parent_id' => ['nullable', 'exists:areas,id', 'not_in:'.$area->id],
            'head_employee_id' => ['nullable', 'exists:employees,id'],
        ], [
            'identificador.unique' => 'Ya existe un área con ese identificador.',
            'empleado_responsable_id.exists' => 'El empleado responsable seleccionado no existe.',
            'parent_id.exists' => 'El área superior seleccionada no existe.',
            'parent_id.not_in' => 'Un área no puede depender de sí misma.',
            'head_employee_id.exists' => 'El jefe seleccionado no existe.',
        ]);

        $area->update($data);

        return redirect()->route('areas.index')->with('success', 'Área actualizada correctamente.');
    }

    public function destroy(Area $area): RedirectResponse
    {
        $area->delete();

        return redirect()->route('areas.index')->with('success', 'Área eliminada correctamente.');
    }
}
