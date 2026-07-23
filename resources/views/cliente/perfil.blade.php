@extends('cliente.layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/cliente/perfil.css') }}">
@endpush

@section('title', 'Mi Perfil')
@section('page-title', 'Mi Perfil')

@section('content')

{{-- Header de perfil ────────────────────────────────── --}}
<div class="profile-header">
    <div class="profile-cover"></div>
    <div class="profile-hero">
        <div class="profile-avatar-lg">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="profile-hero-info">
            <h2 class="profile-hero-name">{{ $user->name }}</h2>
            <span class="profile-hero-role"><i class="fas fa-user-circle"></i> Cliente</span>
        </div>
    </div>
</div>

{{-- Grid de formularios ────────────────────────────── --}}
<div class="profile-grid">

    {{-- Información personal ────────────────────────── --}}
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title"><i class="fas fa-user-edit"></i> Información Personal</h2>
            <span class="panel-badge">Datos de cuenta</span>
        </div>
        <div class="panel-body panel-form">

            @if(session('success'))
            <div class="form-alert form-alert--success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('cliente.perfil.update') }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label class="form-label" for="name">
                        <i class="fas fa-user"></i> Nombre completo
                    </label>
                    <input id="name" name="name" type="text"
                           class="form-input @error('name') is-error @enderror"
                           value="{{ old('name', $user->name) }}" required autofocus>
                    @error('name')
                        <span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">
                        <i class="fas fa-envelope"></i> Correo electrónico
                    </label>
                    <input id="email" type="email"
                           class="form-input form-input--readonly"
                           value="{{ $user->email }}" readonly>
                    <span class="form-hint">El correo no puede modificarse.</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="celular">
                        <i class="fas fa-phone"></i> Celular
                    </label>
                    <input id="celular" name="celular" type="tel"
                           class="form-input @error('celular') is-error @enderror"
                           value="{{ old('celular', $user->celular) }}"
                           placeholder="Ej: 3001234567">
                    @error('celular')
                        <span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label" for="departamento">
                            <i class="fas fa-map"></i> Departamento
                        </label>
                        <select id="departamento" name="departamento"
                                class="form-input form-select @error('departamento') is-error @enderror">
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="municipio">
                            <i class="fas fa-map-marker-alt"></i> Municipio
                        </label>
                        <select id="municipio" name="municipio"
                                class="form-input form-select @error('municipio') is-error @enderror" disabled>
                            <option value="">Primero selecciona departamento</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-tag"></i> Rol</label>
                    <div class="form-input-static">
                        <span class="role-badge"><i class="fas fa-user-circle"></i> Cliente</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Seguridad ───────────────────────────────────── --}}
    <div class="panel">
        <div class="panel-header">
            <h2 class="panel-title"><i class="fas fa-lock"></i> Seguridad</h2>
            <span class="panel-badge">Contraseña</span>
        </div>
        <div class="panel-body panel-form">

            <div class="security-hint">
                <i class="fas fa-info-circle"></i>
                Usa una contraseña larga y segura que no uses en otros sitios.
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="current_password">
                        <i class="fas fa-key"></i> Contraseña actual
                    </label>
                    <input id="current_password" name="current_password" type="password"
                           class="form-input @error('current_password', 'updatePassword') is-error @enderror"
                           autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="fas fa-lock"></i> Nueva contraseña
                    </label>
                    <input id="password" name="password" type="password"
                           class="form-input @error('password', 'updatePassword') is-error @enderror"
                           autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">
                        <i class="fas fa-check-double"></i> Confirmar contraseña
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           class="form-input" autocomplete="new-password">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-shield-alt"></i> Actualizar Contraseña
                    </button>
                    @if(session('status') === 'password-updated')
                        <span class="form-success">
                            <i class="fas fa-check-circle"></i> Contraseña actualizada
                        </span>
                    @endif
                </div>
            </form>
        </div>
    </div>

</div>

<script type="application/json" id="geoConfig">
{
    "departamento": {{ json_encode(old('departamento', $user->departamento)) }},
    "municipio":    {{ json_encode(old('municipio',    $user->municipio)) }}
}
</script>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/colombia-geo.js') }}"></script>
<script src="{{ asset('assets/js/cliente/perfil.js') }}"></script>
@endpush
