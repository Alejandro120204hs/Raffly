<x-guest-layout>
<div class="auth-header">
    <div class="auth-icon-wrap auth-icon-blue"><i class="fas fa-key"></i></div>
    <h1 class="auth-title">Recuperar Contraseña</h1>
    <p class="auth-subtitle">Te enviamos un enlace a tu correo para restablecerla</p>
</div>

@if (session('status'))
    <div class="auth-alert auth-alert-success">
        <i class="fas fa-check-circle"></i> {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="auth-form">
    @csrf

    <div class="auth-field">
        <label class="auth-label" for="email">
            <i class="fas fa-envelope"></i> Correo electrónico
        </label>
        <input class="auth-input @error('email') auth-input-error @enderror"
               type="email" id="email" name="email" value="{{ old('email') }}"
               placeholder="tucorreo@ejemplo.com" required autofocus>
        @error('email')
            <span class="auth-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="auth-btn auth-btn-primary">
        <i class="fas fa-paper-plane"></i> Enviar Enlace de Recuperación
    </button>
</form>

<div class="auth-footer">
    <a href="{{ route('login') }}" class="auth-link">
        <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
    </a>
</div>
</x-guest-layout>
