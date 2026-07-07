/* mis-numeros.js */

(function () {
    var PAGE_SIZE = 8;
    var currentPage = 1;

    var btnCards  = document.getElementById('btnViewCards');
    var btnTable  = document.getElementById('btnViewTable');
    var viewCards = document.getElementById('mnViewCards');
    var viewTable = document.getElementById('mnViewTable');

    var tabs     = document.querySelectorAll('.mn-tab');
    var tarjetas = document.querySelectorAll('.mn-rifa');
    var filas    = document.querySelectorAll('.mn-tabla-row');

    /* ── Paginación ── */
    function getFilteredRows() {
        return Array.from(filas).filter(function (r) {
            return !r.classList.contains('mn-rifa--hidden');
        });
    }

    function applyPagination() {
        var filtered   = getFilteredRows();
        var totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
        if (currentPage > totalPages) currentPage = 1;

        var start = (currentPage - 1) * PAGE_SIZE;

        /* Limpiar display inline de todas las filas */
        filas.forEach(function (r) { r.style.display = ''; });

        /* Ocultar filas fuera de la página actual (solo entre las no filtradas) */
        filtered.forEach(function (row, i) {
            if (i < start || i >= start + PAGE_SIZE) {
                row.style.display = 'none';
            }
        });

        var info    = document.getElementById('mnPageInfo');
        var btnPrev = document.getElementById('mnPagePrev');
        var btnNext = document.getElementById('mnPageNext');
        var pag     = document.getElementById('mnPagination');

        if (info) {
            var from = filtered.length === 0 ? 0 : start + 1;
            var to   = Math.min(filtered.length, start + PAGE_SIZE);
            info.innerHTML = '<strong>' + from + '–' + to + '</strong> de ' + filtered.length;
        }
        if (btnPrev) btnPrev.disabled = currentPage <= 1;
        if (btnNext) btnNext.disabled = currentPage >= totalPages;
        if (pag) pag.style.display = totalPages <= 1 ? 'none' : 'flex';
    }

    if (document.getElementById('mnPagePrev')) {
        document.getElementById('mnPagePrev').addEventListener('click', function () {
            if (currentPage > 1) { currentPage--; applyPagination(); }
        });
        document.getElementById('mnPageNext').addEventListener('click', function () {
            var filtered   = getFilteredRows();
            var totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
            if (currentPage < totalPages) { currentPage++; applyPagination(); }
        });
    }

    /* ── Vista toggle ── */
    function applyView(mode) {
        if (!viewCards || !viewTable) return;
        if (mode === 'table') {
            viewCards.style.display = 'none';
            viewTable.style.display = 'block';
            if (btnCards) btnCards.classList.remove('active');
            if (btnTable) btnTable.classList.add('active');
            applyPagination();
        } else {
            viewCards.style.display = 'block';
            viewTable.style.display = 'none';
            if (btnCards) btnCards.classList.add('active');
            if (btnTable) btnTable.classList.remove('active');
        }
        localStorage.setItem('mn-view', mode);
    }

    if (btnCards) btnCards.addEventListener('click', function () { applyView('cards'); });
    if (btnTable) btnTable.addEventListener('click', function () { applyView('table'); });

    /* ── Tabs de filtro ── */
    function evalItem(item, filtro) {
        var estado = item.dataset.estado;
        var gane   = item.dataset.gane === 'true';
        return filtro === 'all'
            || (filtro === 'activa'     && estado === 'activa')
            || (filtro === 'ganada'     && gane)
            || (filtro === 'finalizada' && estado === 'finalizada' && !gane);
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');
            var filtro = tab.dataset.filter;
            tarjetas.forEach(function (c) { c.classList.toggle('mn-rifa--hidden', !evalItem(c, filtro)); });
            filas.forEach(function (r)    { r.classList.toggle('mn-rifa--hidden', !evalItem(r, filtro)); });
            currentPage = 1;
            applyPagination();
        });
    });

    /* ── Inicializar vista (por defecto: tabla) ── */
    var savedView = localStorage.getItem('mn-view') || 'table';
    applyView(savedView);

    /* ── Animar entrada de tarjetas ── */
    tarjetas.forEach(function (el, i) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(12px)';
        setTimeout(function () {
            el.style.transition = 'opacity .3s ease, transform .3s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, i * 80);
    });

    /* ── Animar entrada de números ── */
    document.querySelectorAll('.mn-num').forEach(function (el, i) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(8px) scale(.9)';
        setTimeout(function () {
            el.style.transition = 'opacity .2s ease, transform .2s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0) scale(1)';
        }, 60 + i * 25);
    });
})();
