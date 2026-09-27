@extends('layouts.admin')

@section('title', 'Configuración de asistencia')
@section('breadcrumb', 'Administración › Configuración › Asistencia')

@section('content')
<x-page-header title="Configuración de asistencia" subtitle="Define la tolerancia permitida para la hora de entrada.">
    @slot('actions')
        <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary">Volver a asistencia</a>
    @endslot
</x-page-header>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('configuracion.asistencia.update') }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <label for="grace_minutes" class="form-label">Tolerancia de entrada (minutos)</label>
                <input id="grace_minutes" name="grace_minutes" type="number" min="0" max="180"
                    value="{{ old('grace_minutes', $graceMinutes) }}"
                    class="form-control @error('grace_minutes') is-invalid @enderror" required>
                <div class="form-text">
                    Con 10 minutos, una entrada a las 08:10 para un horario de 08:00 se marca como “A tiempo”.
                    A las 08:14 se muestra “llegó tarde en 14 min”.
                </div>
                @error('grace_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <button class="btn btn-primary">Guardar configuración</button>
            </div>
        </form>
    </div>
</div>
@endsection
