document.addEventListener('DOMContentLoaded', function () {
    const PER_PAGE = 10;
    let currentPage = 1;
    let currentFilter = 'todas';

    const tabs      = document.querySelectorAll('.filter-tab');
    const allRows   = Array.from(document.querySelectorAll('.table-row'));
    const info      = document.getElementById('tableInfo');
    const pagEl     = document.getElementById('pagination');

    function visibleRows() {
        return allRows.filter(row =>
            currentFilter === 'todas' || row.dataset.estado === currentFilter
        );
    }

    function render() {
        const rows      = visibleRows();
        const total     = rows.length;
        const totalPages = Math.ceil(total / PER_PAGE) || 1;

        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * PER_PAGE;
        const end   = start + PER_PAGE;

        allRows.forEach(r => r.style.display = 'none');
        rows.forEach((r, i) => {
            r.style.display = (i >= start && i < end) ? '' : 'none';
        });

        const from = total === 0 ? 0 : start + 1;
        const to   = Math.min(end, total);
        info.textContent = `Mostrando ${from}–${to} de ${total} rifas`;

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        pagEl.innerHTML = '';

        if (totalPages <= 1) return;

        const prev = btn('<i class="fas fa-chevron-left"></i>', currentPage === 1);
        prev.addEventListener('click', () => { if (currentPage > 1) { currentPage--; render(); } });
        pagEl.appendChild(prev);

        for (let p = 1; p <= totalPages; p++) {
            const b = document.createElement('button');
            b.className = 'page-btn' + (p === currentPage ? ' page-btn--active' : '');
            b.textContent = p;
            b.addEventListener('click', () => { currentPage = p; render(); });
            pagEl.appendChild(b);
        }

        const next = btn('<i class="fas fa-chevron-right"></i>', currentPage === totalPages);
        next.addEventListener('click', () => { if (currentPage < totalPages) { currentPage++; render(); } });
        pagEl.appendChild(next);
    }

    function btn(html, disabled) {
        const b = document.createElement('button');
        b.className = 'page-btn page-btn--nav' + (disabled ? ' page-btn--disabled' : '');
        b.innerHTML = html;
        b.disabled  = disabled;
        return b;
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            currentPage   = 1;
            render();
        });
    });

    render();
});
