/* rifas-create.js — preview en vivo + lógica de loterías */

(function () {
    const $ = id => document.getElementById(id);
    const fmt = n => n.toLocaleString('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 });

    /* ── Datos de loterías: días válidos (0=Dom, 1=Lun … 6=Sáb) ── */
    const LOTERIAS = {
        'Lotería del Tolima':       [1],
        'Lotería de Cundinamarca':  [1],
        'Lotería de Nariño':        [1],
        'Lotería de Córdoba':       [1],
        'Pijao de Oro':             [2],
        'La Primera del Vichada':   [2],
        'Lotería del Huila':        [3],
        'Lotería de Manizales':     [3],
        'Lotería del Valle':        [3],
        'Lotería de Bogotá':        [4],
        'Lotería del Meta':         [4],
        'Lotería del Quindío':      [4],
        'Lotería de Medellín':      [5],
        'Lotería de Santander':     [5],
        'Lotería del Chocó':        [5],
        'Lotería del Risaralda':    [5],
        'Lotería del Norte':        [5],
        'Lotería de Boyacá':        [6],
        'Lotería del Cauca':        [6],
        'Baloto':                   [3, 6],
        'Chance':                   [1, 2, 3, 4, 5, 6],
        'Super Astro':              [0, 1, 2, 3, 4, 5, 6],
    };

    const DIAS_NOMBRE = { 0:'Domingos', 1:'Lunes', 2:'Martes', 3:'Miércoles', 4:'Jueves', 5:'Viernes', 6:'Sábados' };
    const DIAS_NUM    = { 'Domingos':0, 'Lunes':1, 'Martes':2, 'Miércoles':3, 'Jueves':4, 'Viernes':5, 'Sábados':6 };

    /* ── Tipo de premio ── */
    const tipoCards   = document.querySelectorAll('.tipo-card');
    const tipoInputs  = document.querySelectorAll('input[name="tipo"]');
    const premioLabel = $('premioLabel');
    const premioHint  = $('premioHint');
    const pvPremioIcon = $('pvPremioIcon');

    tipoInputs.forEach(radio => {
        radio.addEventListener('change', () => {
            tipoCards.forEach(c => c.classList.remove('tipo-card--active'));
            radio.closest('.tipo-card').classList.add('tipo-card--active');
            if (radio.value === 'efectivo') {
                premioLabel.innerHTML = 'Premio (efectivo) <span class="required">*</span>';
                premioHint.textContent = 'Ej: $1.000.000 — escribe el monto tal como se mostrará';
                pvPremioIcon.innerHTML = '<i class="fas fa-money-bill-wave" style="color:#10B981"></i>';
                $('premio').placeholder = 'Ej: 1.000.000';
            } else {
                premioLabel.innerHTML = 'Premio (objeto) <span class="required">*</span>';
                premioHint.textContent = 'Describe exactamente lo que recibe el ganador';
                pvPremioIcon.innerHTML = '<i class="fas fa-gift" style="color:#7C3AED"></i>';
                $('premio').placeholder = 'Ej: iPhone 15 Pro Max 256GB';
            }
            actualizarFormateadorPremio(radio.value);
            syncPreview();
        });
    });
    document.querySelector('.tipo-card').classList.add('tipo-card--active');

    /* ── Cifras ── */
    const cifrasCards  = document.querySelectorAll('.cifras-card');
    const cifrasInputs = document.querySelectorAll('input[name="cifras"]');

    cifrasInputs.forEach(radio => {
        radio.addEventListener('change', () => {
            cifrasCards.forEach(c => c.classList.remove('cifras-card--active'));
            radio.closest('.cifras-card').classList.add('cifras-card--active');
            $('tipCifras').style.display = radio.value === '2' ? 'flex' : 'none';
            syncPreview();
        });
    });
    document.querySelector('.cifras-card').classList.add('cifras-card--active');

    /* ── Lotería + día + fecha ── */
    const loteriaSelect = $('loteria');
    const juegaSelect   = $('juega');
    const fechaInput    = $('fecha');
    const juegaHint     = $('juegaHint');
    const fechaHint     = $('fechaHint');

    function getDiasValidos() {
        return LOTERIAS[loteriaSelect.value] || [];
    }

    /* Filtra el select de días según los días válidos de la lotería */
    function filtrarDias() {
        const validDias = getDiasValidos();

        Array.from(juegaSelect.options).forEach(opt => {
            if (!opt.value) return;
            const num = DIAS_NUM[opt.value];
            opt.disabled = validDias.length > 0 && !validDias.includes(num);
        });

        if (validDias.length === 0) {
            juegaSelect.disabled = false;
            juegaSelect.value = '';
            juegaHint.textContent = 'Se autocompleta al elegir la lotería';
            return;
        }

        if (validDias.length === 1) {
            juegaSelect.value    = DIAS_NOMBRE[validDias[0]];
            juegaSelect.disabled = true;
            juegaHint.textContent = 'Único día de sorteo para esta lotería';
        } else {
            juegaSelect.disabled = false;
            /* Si el día actual no es válido, seleccionar el primero */
            const actual = DIAS_NUM[juegaSelect.value];
            if (!validDias.includes(actual)) {
                juegaSelect.value = DIAS_NOMBRE[validDias[0]];
            }
            const nombresValidos = validDias.map(d => DIAS_NOMBRE[d]).join(', ');
            juegaHint.textContent = 'Esta lotería juega: ' + nombresValidos;
        }
    }

    /* Próxima fecha que cae en el día indicado (≥ hoy) */
    function proximaFechaValida(dowTarget, desde) {
        const d = desde ? new Date(desde + 'T00:00:00') : new Date();
        /* Si la fecha es pasada, comenzar desde hoy */
        const hoy = new Date(); hoy.setHours(0,0,0,0);
        if (d < hoy) d.setTime(hoy.getTime());

        const diff = (dowTarget - d.getDay() + 7) % 7;
        d.setDate(d.getDate() + (diff === 0 && fechaInput.value ? 0 : diff || 7));
        return d;
    }

    function dateToInputVal(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    /* Ajusta la fecha si no coincide con el día seleccionado */
    function ajustarFecha() {
        const validDias = getDiasValidos();
        const diaNombre = juegaSelect.value;
        if (!diaNombre || validDias.length === 0) {
            fechaHint.textContent = 'Selecciona primero la lotería para filtrar fechas válidas';
            return;
        }

        const dowTarget = DIAS_NUM[diaNombre];
        if (dowTarget === undefined) return;

        if (fechaInput.value) {
            const [y, m, d] = fechaInput.value.split('-').map(Number);
            const sel = new Date(y, m - 1, d);
            const hoy = new Date(); hoy.setHours(0,0,0,0);

            if (sel < hoy || sel.getDay() !== dowTarget) {
                const proxima = proximaFechaValida(dowTarget, null);
                fechaInput.value = dateToInputVal(proxima);
                fechaHint.textContent = 'Fecha ajustada al próximo ' + diaNombre.toLowerCase();
            } else {
                fechaHint.textContent = '';
            }
        } else {
            /* Sin fecha: mostrar próxima válida como sugerencia */
            const proxima = proximaFechaValida(dowTarget, null);
            fechaHint.textContent = 'Próxima fecha válida: ' + proxima.toLocaleDateString('es-CO', { weekday:'long', day:'numeric', month:'short' });
        }
    }

    loteriaSelect.addEventListener('change', () => {
        filtrarDias();
        ajustarFecha();
        syncPreview();
    });

    juegaSelect.addEventListener('change', () => {
        ajustarFecha();
        syncPreview();
    });

    fechaInput.addEventListener('change', () => {
        const validDias = getDiasValidos();
        const diaNombre = juegaSelect.value;
        if (!diaNombre || validDias.length === 0) { syncPreview(); return; }

        const dowTarget = DIAS_NUM[diaNombre];
        const [y, m, d] = fechaInput.value.split('-').map(Number);
        const sel = new Date(y, m - 1, d);

        if (sel.getDay() !== dowTarget) {
            /* Auto-ajustar a la próxima fecha válida desde la seleccionada */
            const diff = (dowTarget - sel.getDay() + 7) % 7 || 7;
            sel.setDate(sel.getDate() + diff);
            fechaInput.value = dateToInputVal(sel);
            fechaHint.textContent = 'Fecha ajustada al próximo ' + diaNombre.toLowerCase();
        } else {
            fechaHint.textContent = '';
        }
        syncPreview();
    });

    /* ── Premio efectivo: formato automático con puntos de miles ── */
    const premioInput = $('premio');
    let tipoActual = 'objeto';

    function formatearPremioEfectivo(e) {
        const input = e.target;
        const start  = input.selectionStart;
        const oldLen = input.value.length;
        const digits = input.value.replace(/\D/g, '');
        input.value  = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        const diff   = input.value.length - oldLen;
        input.setSelectionRange(start + diff, start + diff);
        syncPreview();
    }

    function actualizarFormateadorPremio(tipo) {
        tipoActual = tipo;
        if (tipo === 'efectivo') {
            premioInput.addEventListener('input', formatearPremioEfectivo);
            /* Formatear valor existente */
            const digits = premioInput.value.replace(/\D/g, '');
            premioInput.value = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        } else {
            premioInput.removeEventListener('input', formatearPremioEfectivo);
        }
    }

    /* ── Precio: formato automático con puntos de miles ── */
    const precioInput = $('precio');

    function rawPrecio() {
        return parseInt(precioInput.value.replace(/\./g, '')) || 0;
    }

    precioInput.addEventListener('input', function () {
        const start = this.selectionStart;
        const oldLen = this.value.length;
        const digits = this.value.replace(/\D/g, '');
        this.value = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        const newLen = this.value.length;
        const diff = newLen - oldLen;
        this.setSelectionRange(start + diff, start + diff);
        syncPreview();
    });

    /* ── Ingresos potenciales ── */
    function calcIngreso() {
        const cifras = parseInt(document.querySelector('input[name="cifras"]:checked')?.value || 2);
        const total  = Math.pow(10, cifras);
        const precio = rawPrecio();
        const ingreso = total * precio;
        const totalFmt = total.toLocaleString('es-CO');
        $('ingresoValue').textContent   = precio > 0 ? fmt(ingreso) : '$0';
        $('ingresoFormula').textContent = totalFmt + ' × ' + (precio > 0 ? fmt(precio) : '$0');
        return { total, precio, ingreso };
    }

    /* ── Fecha legible ── */
    function fechaLegible(val) {
        if (!val) return 'Fecha del sorteo';
        const [y, m, d] = val.split('-');
        const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        return d + ' ' + meses[parseInt(m) - 1] + ' ' + y;
    }

    /* ── Sync preview ── */
    function syncPreview() {
        const { total, precio, ingreso } = calcIngreso();
        const cifras = parseInt(document.querySelector('input[name="cifras"]:checked')?.value || 2);
        const premioVal = $('premio').value.trim() || 'Premio';
        $('pvNombre').textContent  = premioVal;
        $('pvPremio').textContent  = premioVal;
        $('pvPrecio').textContent  = precio > 0 ? fmt(precio) : '$—';
        $('pvTotal').textContent   = total.toLocaleString('es-CO');
        $('pvCifras').textContent  = cifras;
        $('pvLoteria').textContent = loteriaSelect.options[loteriaSelect.selectedIndex]?.text?.replace(/\s*\(.*\)/, '') || 'Selecciona una lotería';
        $('pvJuega').textContent   = juegaSelect.value || '—';
        $('pvFecha').textContent   = fechaLegible(fechaInput.value);
        $('pvIngreso').textContent = ingreso > 0 ? fmt(ingreso) : '$0';
    }

    $('premio').addEventListener('input', syncPreview);

    /* Antes de enviar: habilitar selects deshabilitados y limpiar puntos del precio */
    document.getElementById('rifaForm').addEventListener('submit', () => {
        juegaSelect.disabled  = false;
        precioInput.value     = precioInput.value.replace(/\./g, '');
    });

    syncPreview();
})();
