/* perfil.js — cascading selects Colombia + toggle password */

(function () {
    var geo    = window.COLOMBIA_GEO || {};
    var config = JSON.parse(document.getElementById('geoConfig').textContent);

    var deptoSel = document.getElementById('departamento');
    var munSel   = document.getElementById('municipio');

    /* Poblar departamentos */
    Object.keys(geo).sort().forEach(function (dep) {
        var opt = document.createElement('option');
        opt.value = dep;
        opt.textContent = dep;
        deptoSel.appendChild(opt);
    });

    function poblarMunicipios(dep) {
        munSel.innerHTML = '';
        if (!dep || !geo[dep]) {
            munSel.innerHTML = '<option value="">Seleccionar departamento primero</option>';
            munSel.disabled = true;
            return;
        }

        var placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Seleccionar municipio...';
        munSel.appendChild(placeholder);

        if (dep === 'Bogotá D.C.') {
            var opt = document.createElement('option');
            opt.value = 'Bogotá D.C.';
            opt.textContent = 'Bogotá D.C.';
            munSel.appendChild(opt);
            munSel.value = 'Bogotá D.C.';
            munSel.disabled = true;
        } else {
            geo[dep].sort().forEach(function (m) {
                var opt = document.createElement('option');
                opt.value = m;
                opt.textContent = m;
                munSel.appendChild(opt);
            });
            munSel.disabled = false;
        }
    }

    /* Restaurar valores guardados */
    if (config.departamento) {
        deptoSel.value = config.departamento;
        poblarMunicipios(config.departamento);
        if (config.municipio) munSel.value = config.municipio;
    }

    deptoSel.addEventListener('change', function () {
        poblarMunicipios(this.value);
    });

    /* Toggle mostrar/ocultar contraseña */
    document.querySelectorAll('.form-eye').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = document.getElementById(btn.dataset.target);
            var icon   = btn.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
})();
