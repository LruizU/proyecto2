@extends('layouts.admin')

@section('title', 'Editar área')
@section('breadcrumb', 'Operación › Áreas › Editar')

@section('content')
<x-page-header title="Editar área" subtitle="Actualiza la información del área." :hide-title="false">
    @slot('actions')
        <a href="{{ route('areas.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Volver</a>
    @endslot
</x-page-header>

<div class="card">
    <div class="card-body">
        <form action="{{ route('areas.update', $area) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="identificador">ID de área</label>
                    <input type="text" id="identificador" name="identificador" class="form-control" value="{{ old('identificador', $area->identificador) }}" maxlength="50" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control" value="{{ old('descripcion', $area->descripcion) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="parent_id">Área superior</label>
                    <select id="parent_id" name="parent_id" class="form-select">
                        <option value="">Sin área superior (nivel rectoría)</option>
                        @foreach ($areas as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id', $area->parent_id) == $parent->id)>
                                {{ $parent->identificador }} — {{ $parent->descripcion ?: $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="head_employee_id">Jefe que autoriza</label>
                    <select id="head_employee_id" name="head_employee_id" class="form-select">
                        <option value="">Sin jefe asignado</option>
                        @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id }}" @selected(old('head_employee_id', $area->head_employee_id) == $empleado->id)>
                                {{ $empleado->name }} ({{ $empleado->user_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="empleado_responsable_id">Encargado</label>
                    <select id="empleado_responsable_id" name="empleado_responsable_id" class="form-select">
                        <option value="">Sin responsable</option>
                        @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id }}" {{ old('empleado_responsable_id', $area->empleado_responsable_id) == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->name }} ({{ $empleado->user_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('areas.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar área</button>
            </div>
        </form>
    </div>
</div>
@endsection
