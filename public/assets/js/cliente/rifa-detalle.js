/* rifa-detalle.js — paginación + selección de números */

(function () {
    var cfg        = JSON.parse(document.getElementById('rifaConfig').textContent);
    var overlay    = document.getElementById('modalOverlay');
    var modalNumEl = document.getElementById('modalNumero');
    var btnConf    = document.getElementById('btnConfirmar');
    var btnCan     = document.getElementById('btnCancelar');
    var toast      = document.getElementById('rdToast');
    var toastMsg   = document.getElementById('rdToastMsg');
    var csrf       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var IS_MOBILE    = window.innerWidth <= 768;
    var PER_PAGE     = IS_MOBILE ? 50 : 90;
    var paginaActual = 1;
    var numeroSeleccionado = null;

    var todos        = Array.from(document.querySelectorAll('.rd-num'));
    var total        = todos.length;
    var paginado     = total > PER_PAGE;
    var totalPaginas = Math.ceil(total / PER_PAGE);

    /* ── Paginación ──────────────────────────────── */
    function mostrarPagina(p) {
        paginaActual = p;
        var desde = (p - 1) * PER_PAGE;
        var hasta = Math.min(desde + PER_PAGE, total);

        todos.forEach(function (el, i) {
            el.style.display = (i >= desde && i < hasta) ? '' : 'none';
        });

        actualizarInfo(desde + 1, hasta, total);
        renderPaginacion();
        window.scrollTo({ top: document.getElementById('numerosGrid').offsetTop - 80, behavior: 'smooth' });
    }

    function actualizarInfo(desde, hasta, tot) {
        var el = document.getElementById('numerosInfo');
        if (el) el.textContent = 'Mostrando ' + desde + ' – ' + hasta + ' de ' + tot + ' números';
    }

    function renderPaginacion() {
        var cont = document.getElementById('paginacion');
        if (!cont || !paginado) return;
        cont.innerHTML = '';

        function btn(label, page, disabled, active) {
            var b = document.createElement('button');
            b.className = 'rd-page-btn' + (active ? ' active' : '') + (disabled ? ' disabled' : '');
            b.innerHTML = label;
            b.disabled  = disabled;
            if (!disabled && !active) b.addEventListener('click', function () { mostrarPagina(page); });
            return b;
        }

        cont.appendChild(btn('<i class="fas fa-chevron-left"></i>', paginaActual - 1, paginaActual === 1, false));

        var rango = paginasVisibles(paginaActual, totalPaginas);
        rango.forEach(function (p) {
            if (p === '...') {
                var s = document.createElement('span');
                s.className = 'rd-page-sep';
                s.textContent = '…';
                cont.appendChild(s);
            } else {
                cont.appendChild(btn(p, p, false, p === paginaActual));
            }
        });

        cont.appendChild(btn('<i class="fas fa-chevron-right"></i>', paginaActual + 1, paginaActual === totalPaginas, false));
    }

    function paginasVisibles(actual, total) {
        if (total <= 7) return Array.from({ length: total }, function (_, i) { return i + 1; });
        var pages = [];
        if (actual <= 4) {
            pages = [1, 2, 3, 4, 5, '...', total];
        } else if (actual >= total - 3) {
            pages = [1, '...', total - 4, total - 3, total - 2, total - 1, total];
        } else {
            pages = [1, '...', actual - 1, actual, actual + 1, '...', total];
        }
        return pages;
    }

    /* Inicializar */
    if (paginado) {
        mostrarPagina(1);
    } else {
        actualizarInfo(1, total, total);
        /* Sin paginación: animar todos */
        todos.forEach(function (el, i) {
            el.style.opacity = '0';
            setTimeout(function () {
                el.style.transition = 'opacity .12s ease';
                el.style.opacity = '1';
            }, Math.floor(i / 15) * 15 + 20);
        });
    }

    /* ── Modal de confirmación ───────────────────── */
    document.getElementById('numerosGrid').addEventListener('click', function (e) {
        var btn = e.target.closest('.rd-num--disponible:not(:disabled)');
        if (!btn) return;
        numeroSeleccionado = btn.dataset.numero;
        modalNumEl.textContent = numeroSeleccionado;
        overlay.classList.add('open');
    });

    function cerrarModal() {
        overlay.classList.remove('open');
        numeroSeleccionado = null;
    }

    btnCan.addEventListener('click', cerrarModal);
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) cerrarModal();
    });

    btnConf.addEventListener('click', function () {
        if (!numeroSeleccionado) return;

        btnConf.disabled = true;
        btnConf.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

        fetch(cfg.reservarUrl + '/' + numeroSeleccionado, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.ok) {
                var chip = document.querySelector('.rd-num[data-numero="' + data.numero + '"]');
                if (chip) {
                    chip.classList.remove('rd-num--disponible');
                    chip.classList.add('rd-num--mio');
                    chip.disabled = true;
                    chip.dataset.estado = 'pendiente';
                }
                cerrarModal();
                mostrarToast('¡Número ' + data.numero + ' apartado! Pendiente de verificación de pago.');
            } else {
                cerrarModal();
                mostrarToast('Este número ya no está disponible.', true);
            }
        })
        .catch(function () {
            cerrarModal();
            mostrarToast('Error de conexión. Intenta de nuevo.', true);
        })
        .finally(function () {
            btnConf.disabled = false;
            btnConf.innerHTML = '<i class="fas fa-check"></i> Confirmar';
        });
    });

    function mostrarToast(msg, error) {
        toastMsg.textContent = msg;
        toast.querySelector('i').style.color = error ? '#F87171' : '#4ADE80';
        toast.classList.add('show');
        setTimeout(function () { toast.classList.remove('show'); }, 3500);
    }
})();
