/* usuarios-index.js — filtros y búsqueda de clientes */

(function () {
    const rows      = Array.from(document.querySelectorAll('.usuario-row'));
    const searchEl  = document.getElementById('usuariosSearch');
    const filterBtns = document.querySelectorAll('.filter-tab');
    const infoEl    = document.getElementById('usuariosInfo');

    let filtroActivo = 'todos';
    let busqueda     = '';

    function aplicarFiltros() {
        let visibles = 0;

        rows.forEach(row => {
            const filtro = row.dataset.filter;   // 'compras' | 'sin'
            const gano   = row.dataset.gano;     // 'si' | 'no'
            const search = row.dataset.search;

            const pasaFiltro =
                filtroActivo === 'todos'      ||
                (filtroActivo === 'compras'   && filtro === 'compras') ||
                (filtroActivo === 'sin'       && filtro === 'sin')     ||
                (filtroActivo === 'ganadores' && gano === 'si');

            const pasaBusqueda = busqueda === '' || search.includes(busqueda);

            if (pasaFiltro && pasaBusqueda) {
                row.classList.remove('usuario-row--hidden');
                visibles++;
            } else {
                row.classList.add('usuario-row--hidden');
            }
        });

        infoEl.textContent = visibles === rows.length
            ? rows.length + ' clientes'
            : visibles + ' de ' + rows.length + ' clientes';
    }

    /* Tabs de filtro */
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filtroActivo = btn.dataset.filter;
            aplicarFiltros();
        });
    });

    /* Búsqueda */
    if (searchEl) {
        searchEl.addEventListener('input', () => {
            busqueda = searchEl.value.trim().toLowerCase();
            aplicarFiltros();
        });
    }

    aplicarFiltros();
})();
