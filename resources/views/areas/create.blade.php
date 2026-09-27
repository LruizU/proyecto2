@extends('layouts.admin')

@section('title', 'Nueva área')
@section('breadcrumb', 'Operación › Áreas › Nueva área')

@section('content')
<x-page-header title="Nueva área" subtitle="Registra el identificador, la descripción y el encargado del área." :hide-title="false">
    @slot('actions')
        <a href="{{ route('areas.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Volver</a>
    @endslot
</x-page-header>

<div class="card">
    <div class="card-body">
        <form action="{{ route('areas.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="identificador">ID de área</label>
                    <input type="text" id="identificador" name="identificador" class="form-control" value="{{ old('identificador') }}" maxlength="50" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control" value="{{ old('descripcion') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="parent_id">Área superior</label>
                    <select id="parent_id" name="parent_id" class="form-select">
                        <option value="">Sin área superior (nivel rectoría)</option>
                        @foreach ($areas as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>
                                {{ $parent->identificador }} — {{ $parent->descripcion ?: $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Ejemplo: un área operativa puede depender de Administración.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="head_employee_id">Jefe que autoriza</label>
                    <select id="head_employee_id" name="head_employee_id" class="form-select">
                        <option value="">Sin jefe asignado</option>
                        @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id }}" @selected(old('head_employee_id') == $empleado->id)>
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
                            <option value="{{ $empleado->id }}" {{ old('empleado_responsable_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->name }} ({{ $empleado->user_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('areas.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar área</button>
            </div>
        </form>
    </div>
</div>
@endsection
