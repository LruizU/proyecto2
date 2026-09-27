@extends('layouts.admin')

@section('title', 'Usuarios con preferencia')
@section('breadcrumb', 'Administración › Usuarios')

@section('content')
<div class="row g-4">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @if(session('bulk_result'))
            @php($bulk = session('bulk_result'))
            <div class="alert alert-success" role="status">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle fs-5"></i>
                    <div class="flex-grow-1">
                        <strong>Creación masiva completada.</strong>
                        <div class="small mt-1">
                            Se crearon {{ count($bulk['created']) }} usuarios de {{ $bulk['type'] }}.
                            @if($bulk['skipped'] > 0) Se omitieron {{ $bulk['skipped'] }} registros. @endif
                            @if(count($bulk['errors']) > 0) {{ count($bulk['errors']) }} no pudieron procesarse. @endif
                        </div>
                        @if(count($bulk['created']) > 0)
                            <details class="mt-3">
                                <summary class="fw-semibold">Ver credenciales iniciales</summary>
                                <div class="table-responsive mt-2">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead><tr><th>Nombre</th><th>Usuario</th><th>Correo</th><th>Contraseña inicial</th></tr></thead>
                                        <tbody>
                                        @foreach($bulk['created'] as $credential)
                                            <tr>
                                                <td>{{ $credential['name'] }}</td>
                                                <td><code>{{ $credential['username'] }}</code></td>
                                                <td>{{ $credential['email'] }}</td>
                                                <td><code>{{ $credential['password'] }}</code></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-text">Guarda estas credenciales de forma segura. La contraseña inicial no volverá a mostrarse después de salir de esta página.</div>
                            </details>
                        @endif
                        @if(count($bulk['errors']) > 0)
                            <details class="mt-2"><summary>Registros con error</summary><div class="small mt-1">{{ implode(', ', $bulk['errors']) }}</div></details>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">Usuarios del sistema</h5>
                    <p class="text-muted small mb-0">Administra credenciales, perfiles y grupos de seguridad.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkUsersModal">
                        <i class="bi bi-people me-1"></i> Crear usuarios masivamente
                    </button>
                    <a href="{{ route('preferencia.usuarios.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i> Crear usuario
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-4">
                    <div class="col-md-6">
                        <label for="q" class="visually-hidden">Buscar usuario</label>
                        <input id="q" name="q" value="{{ $search }}" class="form-control" placeholder="Buscar por nombre, usuario o correo">
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="visually-hidden">Tipo</label>
                        <select id="type" name="type" class="form-select">
                            <option value="">Todos los perfiles</option>
                            <option value="employee" @selected($type === 'employee')>Empleados</option>
                            <option value="professor" @selected($type === 'professor')>Profesores</option>
                            <option value="unassigned" @selected($type === 'unassigned')>Sin perfil</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-outline-primary flex-grow-1"><i class="bi bi-search me-1"></i>Buscar</button>
                        <a href="{{ route('preferencia.usuarios.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
                    </div>
                </form>
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Usuario</th>
                                    <th>Correo</th>
                                    <th>Tipo</th>
                                    <th>Perfil y grupos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $loop->index }}</td>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                    <td><code>{{ $user->username ?: 'Sin username' }}</code></td>
                                    <td>{{ e($user->email) }}</td>
                                    <td>
                                        @if($user->employee)
                                            <span class="badge bg-primary">Empleado</span>
                                        @elseif($user->professor)
                                            <span class="badge bg-secondary">Profesor</span>
                                        @else
                                            <span class="badge bg-secondary">Sin perfil</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->employee)
                                            <div>{{ $user->employee->numero_empleado ?: $user->employee->user_id ?: 'Sin número' }}</div>
                                            <small class="text-muted">{{ $user->employee->permissionGroups->pluck('name')->join(', ') ?: 'Sin grupos' }}</small>
                                        @elseif($user->professor)
                                            <div>{{ $user->professor->clave_profesor }}</div>
                                            <small class="text-muted">{{ $user->professor->permissionGroups->pluck('name')->join(', ') ?: 'Sin grupos' }}</small>
                                        @else
                                            <span class="text-muted">Sin perfil asignado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('preferencia.usuarios.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Editar usuario">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if($user->employee)
                                            <a href="{{ route('employees.edit', $user->employee) }}" class="btn btn-sm btn-outline-secondary" title="Ver empleado">
                                                <i class="bi bi-person-badge"></i>
                                            </a>
                                        @elseif($user->professor)
                                            <a href="{{ route('academia.profesores.show', $user->professor) }}" class="btn btn-sm btn-outline-secondary" title="Ver profesor">
                                                <i class="bi bi-mortarboard"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i> No hay usuarios registrados con preferencia.
                        <br><small>Crea tu primer usuario usando el botón de arriba.</small>
                    </div>
                @endif
                <div class="mt-3">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bulkUsersModal" tabindex="-1" aria-labelledby="bulkUsersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('preferencia.usuarios.bulk-store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkUsersModalLabel">Crear usuarios masivamente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary">Se creará una cuenta únicamente para cada perfil que todavía no tenga usuario. Los perfiles con cuenta existente no se modifican.</p>
                    <fieldset>
                        <legend class="form-label fw-semibold">Tipo de perfil</legend>
                        <div class="d-flex gap-3">
                            <label class="form-check"><input class="form-check-input" type="radio" name="preference_type" value="employee" checked> Empleados</label>
                            <label class="form-check"><input class="form-check-input" type="radio" name="preference_type" value="professor"> Profesores</label>
                        </div>
                    </fieldset>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="active_only" value="1" id="bulkActiveOnly" checked>
                        <label class="form-check-label" for="bulkActiveOnly">Incluir únicamente perfiles activos</label>
                    </div>
                    <div class="alert alert-warning small mt-3 mb-0">
                        <i class="bi bi-shield-exclamation me-1"></i>
                        Las credenciales iniciales se mostrarán una sola vez al terminar. Deberás entregarlas por un medio seguro.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i> Crear cuentas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection