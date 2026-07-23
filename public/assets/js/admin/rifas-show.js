/* rifas-show.js — lógica del detalle de rifa */

(function () {
    const { total, cifras, vendidos, pendientes, updateUrl, flyerSlug, clientes, compradores } = JSON.parse(
        document.getElementById('rifaConfig').textContent
    );
    const VENDIDOS    = new Set(vendidos);
    const PENDIENTES  = new Set(pendientes);
    const COMPRADORES = compradores || {};   // { "5": { nombre, celular, ubicacion } }
    const IS_MOBILE   = window.innerWidth <= 768;
    const PER_PAGE    = IS_MOBILE ? 64 : 210;
    const PAGINATED   = total > 100;
    const CLIENTES    = clientes || [];

    /* ── Modal registrar resultado ── */
    const btnRegistrar   = document.getElementById('btnRegistrarResultado');
    const modalResultado = document.getElementById('modalResultado');
    const btnCerrar      = document.getElementById('btnCerrarModal');
    const inputResultado = document.getElementById('resultadoInput');

    if (btnRegistrar) {
        btnRegistrar.addEventListener('click', () => {
            modalResultado.style.display = 'flex';
            inputResultado.focus();
        });
    }
    if (btnCerrar) {
        btnCerrar.addEventListener('click', () => { modalResultado.style.display = 'none'; });
    }
    if (modalResultado) {
        modalResultado.addEventListener('click', (e) => {
            if (e.target === modalResultado) modalResultado.style.display = 'none';
        });
        inputResultado.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, cifras);
        });
    }

    /* ── Popup de estado ── */
    const popup      = document.getElementById('numPopup');
    const popupNum   = document.getElementById('numPopupNum');
    const popupClose = document.getElementById('numPopupClose');
    const overrides  = {};   // { num: 'free' | 'pending' | 'sold' }

    function bubbleClass(n) {
        const state = overrides[n] ?? (VENDIDOS.has(n) ? 'sold' : PENDIENTES.has(n) ? 'pending' : 'free');
        return 'num-bubble' + (PAGINATED ? ' num-bubble--sm' : '') + ' num-bubble--' + state;
    }

    const verDetalleSection = document.getElementById('numPopupVerDetalle');
    const btnVerComprador   = document.getElementById('btnVerComprador');

    function openPopup(bubble, n) {
        popupNum.textContent = String(n).padStart(cifras, '0');
        popup.dataset.n = n;

        const currentState = overrides[n] ?? (VENDIDOS.has(n) ? 'sold' : PENDIENTES.has(n) ? 'pending' : 'free');
        verDetalleSection.style.display = (COMPRADORES[n] && currentState !== 'free') ? 'block' : 'none';

        popup.style.display = 'block';

        const rect = bubble.getBoundingClientRect();
        const pw = 210;
        let top  = rect.bottom + window.scrollY + 6;
        let left = rect.left  + window.scrollX - pw / 2 + rect.width / 2;
        if (left + pw > window.innerWidth - 8) left = window.innerWidth - pw - 8;
        if (left < 8) left = 8;
        popup.style.top  = top  + 'px';
        popup.style.left = left + 'px';
    }

    function closePopup() { popup.style.display = 'none'; }

    popupClose.addEventListener('click', closePopup);
    document.addEventListener('click', function (e) {
        if (!popup.contains(e.target) && !e.target.closest('.num-bubble')) closePopup();
    });

    /* ── Modal detalle comprador ── */
    const modalDetalle    = document.getElementById('modalDetalleComprador');
    const detalleNumLabel = document.getElementById('detalleNumLabel');
    const detalleNombre   = document.getElementById('detalleNombre');
    const detalleCelular  = document.getElementById('detalleCelular');
    const detalleUbicacion = document.getElementById('detalleUbicacion');
    const detalleUbicRow  = document.getElementById('detalleUbicacionRow');
    const btnCerrarDetalle = document.getElementById('btnCerrarDetalle');

    if (btnVerComprador) {
        btnVerComprador.addEventListener('click', () => {
            const n = parseInt(popup.dataset.n);
            const data = COMPRADORES[n];
            if (!data) return;
            closePopup();

            detalleNumLabel.textContent   = String(n).padStart(cifras, '0');
            detalleNombre.textContent     = data.nombre || '—';
            detalleCelular.textContent    = data.celular || 'No registrado';
            detalleUbicacion.textContent  = data.ubicacion || '—';
            detalleUbicRow.style.display  = data.ubicacion ? 'flex' : 'none';

            modalDetalle.style.display = 'flex';
        });
    }

    if (btnCerrarDetalle) {
        btnCerrarDetalle.addEventListener('click', () => { modalDetalle.style.display = 'none'; });
    }
    if (modalDetalle) {
        modalDetalle.addEventListener('click', e => {
            if (e.target === modalDetalle) modalDetalle.style.display = 'none';
        });
    }

    /* ── Modal Comprador ── */
    const modalComprador    = document.getElementById('modalComprador');
    const compradorNumLabel = document.getElementById('compradorNumLabel');
    const btnCerrarComp     = document.getElementById('btnCerrarComprador');
    const btnConfirmarComp  = document.getElementById('btnConfirmarComprador');
    const tabBtns           = document.querySelectorAll('.comprador-tab');
    const tabRegistrado     = document.getElementById('tabRegistrado');
    const tabExterno        = document.getElementById('tabExterno');
    const buscarInput       = document.getElementById('buscarCliente');
    const listaEl           = document.getElementById('listaClientes');
    const selectedUserIdEl  = document.getElementById('selectedUserId');
    const selectDepto       = document.getElementById('extDepartamento');
    const selectMunicipio   = document.getElementById('extMunicipio');

    const GEO = window.COLOMBIA_GEO || {};
    Object.keys(GEO).sort().forEach(dep => {
        const opt = document.createElement('option');
        opt.value = dep;
        opt.textContent = dep;
        selectDepto.appendChild(opt);
    });

    selectDepto.addEventListener('change', () => {
        const dep  = selectDepto.value;
        const munis = GEO[dep] || [];
        selectMunicipio.innerHTML = '<option value="">Seleccionar municipio...</option>';
        munis.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m;
            opt.textContent = m;
            selectMunicipio.appendChild(opt);
        });
        selectMunicipio.disabled = munis.length === 0;
        if (munis.length === 1) {
            selectMunicipio.value = munis[0];
            selectMunicipio.disabled = true;
        }
    });

    let pendingN     = null;
    let pendingState = null;
    let activeTab    = 'registrado';

    function renderClientes(query) {
        const q = (query || '').toLowerCase();
        const filtered = CLIENTES.filter(c =>
            c.name.toLowerCase().includes(q) || c.email.toLowerCase().includes(q)
        );

        listaEl.innerHTML = '';
        if (filtered.length === 0) {
            listaEl.innerHTML = '<div class="cliente-empty">No se encontraron clientes</div>';
            return;
        }

        filtered.forEach(c => {
            const item = document.createElement('div');
            item.className = 'cliente-item';
            item.dataset.id = c.id;
            const initials = c.name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
            item.innerHTML = `
                <div class="cliente-avatar">${initials}</div>
                <div class="cliente-info">
                    <span class="cliente-name">${c.name}</span>
                    <span class="cliente-email">${c.email}</span>
                </div>`;
            item.addEventListener('click', () => {
                listaEl.querySelectorAll('.cliente-item').forEach(i => i.classList.remove('cliente-item--active'));
                item.classList.add('cliente-item--active');
                selectedUserIdEl.value = c.id;
            });
            listaEl.appendChild(item);
        });
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            activeTab = btn.dataset.tab;
            tabBtns.forEach(b => b.classList.remove('comprador-tab--active'));
            btn.classList.add('comprador-tab--active');
            if (activeTab === 'registrado') {
                tabRegistrado.style.display = 'flex';
                tabExterno.style.display    = 'none';
            } else {
                tabRegistrado.style.display = 'none';
                tabExterno.style.display    = 'flex';
            }
        });
    });

    if (buscarInput) {
        buscarInput.addEventListener('input', () => renderClientes(buscarInput.value));
    }

    function openModalComprador(n, state) {
        pendingN     = n;
        pendingState = state;

        const padded = String(n).padStart(cifras, '0');
        compradorNumLabel.textContent = padded;

        activeTab = 'registrado';
        tabBtns.forEach(b => b.classList.toggle('comprador-tab--active', b.dataset.tab === 'registrado'));
        tabRegistrado.style.display = 'flex';
        tabExterno.style.display    = 'none';

        selectedUserIdEl.value = '';
        if (buscarInput) buscarInput.value = '';
        listaEl.querySelectorAll('.cliente-item').forEach(i => i.classList.remove('cliente-item--active'));
        ['extNombre','extApellido','extCelular'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        selectDepto.value = '';
        selectMunicipio.innerHTML = '<option value="">Primero selecciona un departamento</option>';
        selectMunicipio.disabled = true;

        renderClientes('');
        modalComprador.style.display = 'flex';
    }

    function closeModalComprador() {
        modalComprador.style.display = 'none';
        pendingN     = null;
        pendingState = null;
    }

    if (btnCerrarComp) {
        btnCerrarComp.addEventListener('click', closeModalComprador);
    }
    if (modalComprador) {
        modalComprador.addEventListener('click', e => {
            if (e.target === modalComprador) closeModalComprador();
        });
    }

    if (btnConfirmarComp) {
        btnConfirmarComp.addEventListener('click', () => {
            if (pendingN === null) return;

            const n      = pendingN;
            const state  = pendingState;
            const padded = String(n).padStart(cifras, '0');

            let body = { estado: { free: 'disponible', pending: 'pendiente', sold: 'vendido' }[state] };

            if (activeTab === 'registrado') {
                const uid = selectedUserIdEl.value;
                if (!uid) { alert('Selecciona un cliente de la lista.'); return; }
                body.user_id = uid;
            } else {
                const nombre   = document.getElementById('extNombre')?.value.trim();
                const apellido = document.getElementById('extApellido')?.value.trim();
                const celular  = document.getElementById('extCelular')?.value.trim();
                if (!nombre || !apellido || !celular) {
                    alert('Nombre, apellido y celular son obligatorios.');
                    return;
                }
                const depto     = selectDepto.value;
                const municipio = selectMunicipio.value;
                body.comprador_nombre    = nombre;
                body.comprador_apellido  = apellido;
                body.comprador_ubicacion = (municipio && depto) ? municipio + ', ' + depto : (municipio || depto || null);
                body.comprador_celular   = celular;
            }

            closeModalComprador();

            if (state !== 'free') {
                if (activeTab === 'registrado') {
                    const uid  = selectedUserIdEl.value;
                    const cli  = CLIENTES.find(c => String(c.id) === String(uid));
                    COMPRADORES[n] = { nombre: cli ? cli.name : 'Usuario registrado', celular: null, ubicacion: null };
                } else {
                    COMPRADORES[n] = {
                        nombre:    (document.getElementById('extNombre')?.value.trim() || '') + ' ' + (document.getElementById('extApellido')?.value.trim() || ''),
                        celular:   document.getElementById('extCelular')?.value.trim() || null,
                        ubicacion: (selectMunicipio.value && selectDepto.value) ? selectMunicipio.value + ', ' + selectDepto.value : null,
                    };
                }
            } else {
                delete COMPRADORES[n];
            }

            overrides[n] = state;
            const target = document.querySelector('.num-bubble[data-n="' + n + '"]');
            if (target) target.className = bubbleClass(n);

            const flyerCell = document.querySelector('#rifaFlyer .flyer-cell[data-n="' + n + '"]');
            if (flyerCell) {
                flyerCell.classList.remove('flyer-cell--sold', 'flyer-cell--pending');
                if (state === 'sold')    flyerCell.classList.add('flyer-cell--sold');
                if (state === 'pending') flyerCell.classList.add('flyer-cell--pending');
            }

            if (updateUrl) {
                fetch(updateUrl.replace('__NUM__', padded), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(body),
                });
            }
        });
    }

    /* ── Popup buttons ── */
    popup.querySelectorAll('.num-popup-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const n     = parseInt(popup.dataset.n);
            const state = this.dataset.state;
            closePopup();

            if (state === 'free') {
                overrides[n] = state;
                const target = document.querySelector('.num-bubble[data-n="' + n + '"]');
                if (target) target.className = bubbleClass(n);

                const flyerCell = document.querySelector('#rifaFlyer .flyer-cell[data-n="' + n + '"]');
                if (flyerCell) {
                    flyerCell.classList.remove('flyer-cell--sold', 'flyer-cell--pending');
                }

                if (updateUrl) {
                    fetch(updateUrl.replace('__NUM__', String(n).padStart(cifras, '0')), {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ estado: 'disponible' }),
                    });
                }
            } else {
                openModalComprador(n, state);
            }
        });
    });

    function attachBubbleClick(el) {
        el.addEventListener('click', function () {
            openPopup(this, parseInt(this.dataset.n));
        });
    }

    /* ── Helpers de paginación (usados por 2, 3 y 4 cifras) ── */
    function navBtn(html, disabled) {
        const b     = document.createElement('button');
        b.className = 'page-btn page-btn--nav' + (disabled ? ' page-btn--disabled' : '');
        b.innerHTML = html;
        b.disabled  = disabled;
        return b;
    }

    function pageRange(current, tot, delta) {
        const pages = [];
        const left  = Math.max(2, current - delta);
        const right = Math.min(tot - 1, current + delta);
        pages.push(1);
        if (left > 2)         pages.push('...');
        for (let i = left; i <= right; i++) pages.push(i);
        if (right < tot - 1)  pages.push('...');
        if (tot > 1)          pages.push(tot);
        return pages;
    }

    /* ── Estado de paginación móvil 2 cifras (accesible por el buscador) ── */
    let mobilePage2  = 1;
    let renderPage2  = null;

    /* ── Buscador ── */
    const input  = document.getElementById('numSearch');
    const result = document.getElementById('numSearchResult');

    if (input) {
        input.addEventListener('input', function () {
            const raw = this.value.trim();
            if (raw === '') { result.style.display = 'none'; return; }

            const val = parseInt(raw, 10);
            result.style.display = 'flex';

            if (isNaN(val) || val < 0 || val >= total) {
                result.className = 'num-search-result num-search-result--invalid';
                result.innerHTML = '<i class="fas fa-exclamation-circle"></i> Fuera de rango — del '
                    + '0'.padStart(cifras, '0') + ' al ' + String(total - 1).padStart(cifras, '0');
                return;
            }

            const padded = String(val).padStart(cifras, '0');
            const currentState = overrides[val]
                ?? (VENDIDOS.has(val) ? 'sold' : PENDIENTES.has(val) ? 'pending' : 'free');

            if (currentState === 'sold') {
                result.className = 'num-search-result num-search-result--sold';
                result.innerHTML = '<i class="fas fa-times-circle"></i> El número <strong>' + padded + '</strong> está <strong>vendido</strong>';
            } else if (currentState === 'pending') {
                result.className = 'num-search-result num-search-result--pending';
                result.innerHTML = '<i class="fas fa-clock"></i> El número <strong>' + padded + '</strong> está <strong>falta por pagar</strong>';
            } else {
                result.className = 'num-search-result num-search-result--free';
                result.innerHTML = '<i class="fas fa-check-circle"></i> El número <strong>' + padded + '</strong> está <strong>disponible</strong>';
            }

            if (PAGINATED) {
                const page = Math.floor(val / PER_PAGE) + 1;
                if (page !== currentPage) { currentPage = page; renderGrid(); }
                setTimeout(() => highlightBubble(val), 50);
            } else if (IS_MOBILE && renderPage2) {
                /* 2 cifras en móvil: navegar a la página correcta */
                const page = Math.floor(val / 64) + 1;
                if (page !== mobilePage2) { mobilePage2 = page; renderPage2(); }
                setTimeout(() => highlightBubble(val), 50);
            } else {
                highlightBubble(val);
            }
        });
    }

    function highlightBubble(val) {
        document.querySelectorAll('#numGrid .num-bubble').forEach(b => b.classList.remove('num-bubble--highlight'));
        const target = document.querySelector('#numGrid .num-bubble[data-n="' + val + '"]');
        if (target) { target.classList.add('num-bubble--highlight'); target.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
    }

    /* ── Generar imagen flyer (solo 2 cifras) ── */
    const btnFlyer = document.getElementById('btnGenerarFlyer');
    if (btnFlyer) {
        btnFlyer.addEventListener('click', function () {
            const btn   = this;
            const flyer = document.getElementById('rifaFlyer');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando…';

            html2canvas(flyer, { scale: 2, useCORS: true, logging: false })
                .then(canvas => {
                    const link    = document.createElement('a');
                    link.download = 'rifa-' + flyerSlug + '.png';
                    link.href     = canvas.toDataURL('image/png');
                    link.click();
                })
                .catch(() => {})
                .finally(() => {
                    btn.disabled  = false;
                    btn.innerHTML = '<i class="fas fa-image"></i> Generar Imagen';
                });
        });
    }

    /* Enganchar bubbles PHP (2 cifras) */
    document.querySelectorAll('#numGrid .num-bubble').forEach(attachBubbleClick);

    if (!PAGINATED) {
        /* ── Paginación móvil para grid de 2 cifras ── */
        if (IS_MOBILE) {
            const allBubbles    = Array.from(document.querySelectorAll('#numGrid .num-bubble'));
            const panelBody     = document.getElementById('numGrid').closest('.panel-body');
            const mobilePerPage = 64;
            const mobileTotalPg = Math.ceil(allBubbles.length / mobilePerPage);

            /* Inyectar footer de paginación */
            const footer = document.createElement('div');
            footer.className = 'table-footer';
            footer.innerHTML = '<span class="table-info" id="gridInfo"></span>'
                             + '<div class="pagination" id="gridPagination"></div>';
            panelBody.appendChild(footer);

            const infoEl = document.getElementById('gridInfo');
            const pagEl  = document.getElementById('gridPagination');

            renderPage2 = function () {
                const start = (mobilePage2 - 1) * mobilePerPage;
                const end   = Math.min(start + mobilePerPage, allBubbles.length);

                allBubbles.forEach((b, i) => {
                    b.style.display = (i >= start && i < end) ? '' : 'none';
                });

                infoEl.textContent = 'Mostrando ' + String(start).padStart(cifras, '0')
                    + ' – ' + String(end - 1).padStart(cifras, '0')
                    + ' de ' + String(total - 1).padStart(cifras, '0');

                pagEl.innerHTML = '';

                const prev = navBtn('<i class="fas fa-chevron-left"></i>', mobilePage2 === 1);
                prev.addEventListener('click', () => {
                    if (mobilePage2 > 1) { mobilePage2--; renderPage2(); }
                });
                pagEl.appendChild(prev);

                pageRange(mobilePage2, mobileTotalPg, 3).forEach(p => {
                    if (p === '...') {
                        const dots = document.createElement('span');
                        dots.className   = 'page-dots';
                        dots.textContent = '…';
                        pagEl.appendChild(dots);
                        return;
                    }
                    const b = document.createElement('button');
                    b.className   = 'page-btn' + (p === mobilePage2 ? ' page-btn--active' : '');
                    b.textContent = p;
                    b.addEventListener('click', () => { mobilePage2 = p; renderPage2(); });
                    pagEl.appendChild(b);
                });

                const next = navBtn('<i class="fas fa-chevron-right"></i>', mobilePage2 === mobileTotalPg);
                next.addEventListener('click', () => {
                    if (mobilePage2 < mobileTotalPg) { mobilePage2++; renderPage2(); }
                });
                pagEl.appendChild(next);
            };

            renderPage2();
        }
        return;
    }

    /* ── Grid paginado (3 y 4 cifras) — PER_PAGE ya es 64 en móvil ── */
    let currentPage  = 1;
    const grid       = document.getElementById('numGrid');
    const infoEl     = document.getElementById('gridInfo');
    const pagEl      = document.getElementById('gridPagination');
    const totalPages = Math.ceil(total / PER_PAGE);

    function renderGrid() {
        const start = (currentPage - 1) * PER_PAGE;
        const end   = Math.min(start + PER_PAGE, total);

        grid.innerHTML = '';
        for (let i = start; i < end; i++) {
            const b       = document.createElement('div');
            b.className   = bubbleClass(i);
            b.dataset.n   = i;
            b.textContent = String(i).padStart(cifras, '0');
            attachBubbleClick(b);
            grid.appendChild(b);
        }

        infoEl.textContent = 'Mostrando ' + String(start).padStart(cifras, '0')
            + ' – ' + String(end - 1).padStart(cifras, '0')
            + ' de ' + String(total - 1).padStart(cifras, '0');

        renderPagination();
    }

    function renderPagination() {
        pagEl.innerHTML = '';

        const prev = navBtn('<i class="fas fa-chevron-left"></i>', currentPage === 1);
        prev.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderGrid(); } });
        pagEl.appendChild(prev);

        pageRange(currentPage, totalPages, 5).forEach(p => {
            if (p === '...') {
                const dots = document.createElement('span');
                dots.className   = 'page-dots';
                dots.textContent = '…';
                pagEl.appendChild(dots);
                return;
            }
            const b = document.createElement('button');
            b.className   = 'page-btn' + (p === currentPage ? ' page-btn--active' : '');
            b.textContent = p;
            b.addEventListener('click', () => { currentPage = p; renderGrid(); });
            pagEl.appendChild(b);
        });

        const next = navBtn('<i class="fas fa-chevron-right"></i>', currentPage === totalPages);
        next.addEventListener('click', () => { if (currentPage < totalPages) { currentPage++; renderGrid(); } });
        pagEl.appendChild(next);
    }

    renderGrid();
})();
