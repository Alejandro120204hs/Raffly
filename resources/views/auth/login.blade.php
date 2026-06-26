<x-guest-layout>
<div class="auth-header">
    <div class="auth-icon-wrap auth-icon-purple"><i class="fas fa-sign-in-alt"></i></div>
    <h1 class="auth-title">Bienvenido de nuevo</h1>
    <p class="auth-subtitle">Ingresa a tu cuenta para participar</p>
</div>

@if (session('status'))
    <div class="auth-alert auth-alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}" class="auth-form">
    @csrf

    <div class="auth-field">
        <label class="auth-label" for="email">
            <i class="fas fa-envelope"></i> Correo electrónico
        </label>
        <input class="auth-input @error('email') auth-input-error @enderror"
               type="email" id="email" name="email" value="{{ old('email') }}"
               placeholder="tucorreo@ejemplo.com" required autofocus autocomplete="username">
        @error('email')
            <span class="auth-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    <div class="auth-field">
        <label class="auth-label" for="password">
            <i class="fas fa-lock"></i> Contraseña
        </label>
        <input class="auth-input @error('password') auth-input-error @enderror"
               type="password" id="password" name="password"
               placeholder="••••••••" required autocomplete="current-password">
        @error('password')
            <span class="auth-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    <div class="auth-remember">
        <label class="auth-checkbox-label">
            <input type="checkbox" name="remember" id="remember_me" class="auth-checkbox">
            <span>Recordarme</span>
        </label>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="auth-link">¿Olvidaste tu contraseña?</a>
        @endif
    </div>

    <button type="submit" class="auth-btn auth-btn-primary">
        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
    </button>
</form>

<div class="auth-footer">
    ¿No tienes cuenta? <a href="{{ route('register') }}" class="auth-link">Regístrate gratis</a>
</div>
</x-guest-layout>
