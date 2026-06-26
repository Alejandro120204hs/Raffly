<x-guest-layout>
<div class="auth-header">
    <div class="auth-icon-wrap auth-icon-gold"><i class="fas fa-user-plus"></i></div>
    <h1 class="auth-title">Crea tu cuenta</h1>
    <p class="auth-subtitle">Únete gratis y comienza a participar hoy</p>
</div>

<form method="POST" action="{{ route('register') }}" class="auth-form">
    @csrf

    <div class="auth-field">
        <label class="auth-label" for="name">
            <i class="fas fa-user"></i> Nombre completo
        </label>
        <input class="auth-input @error('name') auth-input-error @enderror"
               type="text" id="name" name="name" value="{{ old('name') }}"
               placeholder="Tu nombre" required autofocus autocomplete="name">
        @error('name')
            <span class="auth-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    <div class="auth-field">
        <label class="auth-label" for="email">
            <i class="fas fa-envelope"></i> Correo electrónico
        </label>
        <input class="auth-input @error('email') auth-input-error @enderror"
               type="email" id="email" name="email" value="{{ old('email') }}"
               placeholder="tucorreo@ejemplo.com" required autocomplete="username">
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
               placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
        @error('password')
            <span class="auth-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    <div class="auth-field">
        <label class="auth-label" for="password_confirmation">
            <i class="fas fa-lock"></i> Confirmar contraseña
        </label>
        <input class="auth-input"
               type="password" id="password_confirmation" name="password_confirmation"
               placeholder="Repite tu contraseña" required autocomplete="new-password">
    </div>

    <button type="submit" class="auth-btn auth-btn-gold">
        <i class="fas fa-rocket"></i> Crear Cuenta Gratis
    </button>
</form>

<div class="auth-footer">
    ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="auth-link">Iniciar sesión</a>
</div>
</x-guest-layout>
