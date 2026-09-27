@extends('layouts.admin')

@section('title', 'Excepciones de puntualidad')
@section('breadcrumb', 'Administración › Configuración')

@section('content')
<x-page-header title="Excepciones de puntualidad" subtitle="Configuración administrativa de permisos especiales para empleados.">
    @slot('actions')
        <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary">Volver a asistencia</a>
    @endslot
</x-page-header>

<div class="alert alert-warning">
    <i class="bi bi-shield-lock me-1"></i>
    Esta configuración no modifica ni borra la hora biométrica. Solo ajusta el horario base usado para evaluar la puntualidad y conserva el motivo de autorización.
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card mb-4">
    <div class="card-header fw-semibold">Asignar permiso especial</div>
    <div class="card-body">
        <form method="POST" action="{{ route('configuracion.asistencia-especial.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label" for="employee_id">Empleado</label>
                <select id="employee_id" name="employee_id" class="form-select" required>
                    <option value="">Seleccionar empleado</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                            {{ $employee->name }} · PIN {{ $employee->user_id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="minutes">Compensación</label>
                <select id="minutes" name="minutes" class="form-select" required>
                    <option value="20">20 minutos</option>
                    <option value="30">30 minutos</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="starts_at">Desde</label>
                <input id="starts_at" name="starts_at" type="date" class="form-control" value="{{ old('starts_at') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="ends_at">Hasta</label>
                <input id="ends_at" name="ends_at" type="date" class="form-control" value="{{ old('ends_at') }}">
            </div>
            <div class="col-md-8">
                <label class="form-label" for="reason">Motivo de autorización</label>
                <input id="reason" name="reason" class="form-control" maxlength="500" required value="{{ old('reason') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100"><i class="bi bi-plus-lg me-1"></i>Asignar excepción</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header fw-semibold">Excepciones registradas</div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Empleado</th><th>Compensación</th><th>Vigencia</th><th>Motivo</th><th>Registró</th><th></th></tr></thead>
            <tbody>
            @forelse ($rules as $rule)
                <tr>
                    <td>{{ $rule->employee?->name ?? 'Empleado eliminado' }}</td>
                    <td><span class="badge bg-info-subtle text-info">-{{ $rule->minutes }} min</span></td>
                    <td>{{ $rule->starts_at?->format('d/m/Y') ?? 'Sin inicio' }} — {{ $rule->ends_at?->format('d/m/Y') ?? 'Sin vencimiento' }}</td>
                    <td>{{ $rule->reason }}</td>
                    <td>{{ $rule->creator?->name ?? 'Administrador eliminado' }}<small class="d-block text-muted">{{ $rule->created_at?->format('d/m/Y H:i') }}</small></td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('configuracion.asistencia-especial.destroy', $rule) }}" onsubmit="return confirm('¿Eliminar esta excepción?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No hay excepciones registradas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
