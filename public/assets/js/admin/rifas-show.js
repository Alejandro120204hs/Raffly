/* rifas-show.js — lógica del detalle de rifa */

(function () {
    const { total, cifras, vendidos, flyerSlug } = JSON.parse(
        document.getElementById('rifaConfig').textContent
    );
    const VENDIDOS  = new Set(vendidos);
    const PER_PAGE  = 210;
    const PAGINATED = total > 100;

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

            if (VENDIDOS.has(val)) {
                result.className = 'num-search-result num-search-result--sold';
                result.innerHTML = '<i class="fas fa-times-circle"></i> El número <strong>' + padded + '</strong> está <strong>vendido</strong>';
            } else {
                result.className = 'num-search-result num-search-result--free';
                result.innerHTML = '<i class="fas fa-check-circle"></i> El número <strong>' + padded + '</strong> está <strong>disponible</strong>';
            }

            if (PAGINATED) {
                const page = Math.floor(val / PER_PAGE) + 1;
                if (page !== currentPage) { currentPage = page; renderGrid(); }
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

    if (!PAGINATED) return;

    /* ── Grid paginado (3 y 4 cifras) ── */
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
            b.className   = 'num-bubble num-bubble--sm ' + (VENDIDOS.has(i) ? 'num-bubble--sold' : 'num-bubble--free');
            b.dataset.n   = i;
            b.title       = VENDIDOS.has(i) ? 'Vendido' : 'Disponible';
            b.textContent = String(i).padStart(cifras, '0');
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

    function navBtn(html, disabled) {
        const b     = document.createElement('button');
        b.className = 'page-btn page-btn--nav' + (disabled ? ' page-btn--disabled' : '');
        b.innerHTML = html;
        b.disabled  = disabled;
        return b;
    }

    function pageRange(current, total, delta) {
        const pages = [];
        const left  = Math.max(2, current - delta);
        const right = Math.min(total - 1, current + delta);
        pages.push(1);
        if (left > 2)        pages.push('...');
        for (let i = left; i <= right; i++) pages.push(i);
        if (right < total - 1) pages.push('...');
        if (total > 1)       pages.push(total);
        return pages;
    }

    renderGrid();
})();
