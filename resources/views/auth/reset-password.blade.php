<x-guest-layout>
<div class="auth-header">
    <div class="auth-icon-wrap auth-icon-purple"><i class="fas fa-lock-open"></i></div>
    <h1 class="auth-title">Nueva Contraseña</h1>
    <p class="auth-subtitle">Elige una contraseña segura para tu cuenta</p>
</div>

<form method="POST" action="{{ route('password.store') }}" class="auth-form">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="auth-field">
        <label class="auth-label" for="email">
            <i class="fas fa-envelope"></i> Correo electrónico
        </label>
        <input class="auth-input @error('email') auth-input-error @enderror"
               type="email" id="email" name="email" value="{{ old('email', $request->email) }}"
               placeholder="tucorreo@ejemplo.com" required autofocus autocomplete="username">
        @error('email')
            <span class="auth-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    <div class="auth-field">
        <label class="auth-label" for="password">
            <i class="fas fa-lock"></i> Nueva contraseña
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
            <i class="fas fa-lock"></i> Confirmar nueva contraseña
        </label>
        <input class="auth-input"
               type="password" id="password_confirmation" name="password_confirmation"
               placeholder="Repite tu nueva contraseña" required autocomplete="new-password">
    </div>

    <button type="submit" class="auth-btn auth-btn-primary">
        <i class="fas fa-check"></i> Restablecer Contraseña
    </button>
</form>

<div class="auth-footer">
    <a href="{{ route('login') }}" class="auth-link">
        <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
    </a>
</div>
</x-guest-layout>
