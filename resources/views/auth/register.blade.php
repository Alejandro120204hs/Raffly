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
               placeholder="Tu nombre completo" required autofocus autocomplete="name">
        @error('name')
            <span class="auth-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    <div class="auth-field">
        <label class="auth-label" for="celular">
            <i class="fas fa-phone"></i> Celular
        </label>
        <input class="auth-input @error('celular') auth-input-error @enderror"
               type="tel" id="celular" name="celular" value="{{ old('celular') }}"
               placeholder="Ej: 3001234567" required autocomplete="tel">
        @error('celular')
            <span class="auth-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    <div class="auth-field">
        <label class="auth-label" for="regDepartamento">
            <i class="fas fa-map-marker-alt"></i> Departamento
        </label>
        <select class="auth-input auth-select @error('departamento') auth-input-error @enderror"
                id="regDepartamento" name="departamento" required>
            <option value="">Seleccionar departamento...</option>
        </select>
        @error('departamento')
            <span class="auth-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
        @enderror
    </div>

    <div class="auth-field">
        <label class="auth-label" for="regMunicipio">
            <i class="fas fa-city"></i> Municipio
        </label>
        <select class="auth-input auth-select @error('municipio') auth-input-error @enderror"
                id="regMunicipio" name="municipio" required disabled>
            <option value="">Primero selecciona un departamento</option>
        </select>
        @error('municipio')
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

@push('scripts')
<script src="{{ asset('assets/js/admin/colombia-geo.js') }}"></script>
<script>
(function () {
    const GEO      = window.COLOMBIA_GEO || {};
    const selDepto = document.getElementById('regDepartamento');
    const selMuni  = document.getElementById('regMunicipio');

    Object.keys(GEO).sort().forEach(dep => {
        const opt = document.createElement('option');
        opt.value = dep; opt.textContent = dep;
        selDepto.appendChild(opt);
    });

    selDepto.addEventListener('change', () => {
        const munis = GEO[selDepto.value] || [];
        selMuni.innerHTML = '<option value="">Seleccionar municipio...</option>';
        munis.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m; opt.textContent = m;
            selMuni.appendChild(opt);
        });
        selMuni.disabled = munis.length === 0;
        if (munis.length === 1) { selMuni.value = munis[0]; selMuni.disabled = true; }
    });

    /* Restaurar selección tras error de validación */
    const oldDepto = @json(old('departamento'));
    const oldMuni  = @json(old('municipio'));
    if (oldDepto) {
        selDepto.value = oldDepto;
        selDepto.dispatchEvent(new Event('change'));
        if (oldMuni) selMuni.value = oldMuni;
    }
})();
</script>
@endpush
</x-guest-layout>
