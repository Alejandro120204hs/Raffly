/* ingresos-index.js — animaciones de la página de ingresos */

(function () {
    /* Animar barras del gráfico al cargar */
    const barras = document.querySelectorAll('.mes-bar');
    barras.forEach((bar, i) => {
        const targetH = bar.style.height;
        bar.style.height = '0%';
        setTimeout(() => { bar.style.height = targetH; }, 80 + i * 60);
    });

    /* Animar KPI valores con contador */
    document.querySelectorAll('.ingreso-kpi-val').forEach(el => {
        const text  = el.textContent.trim();
        const isNum = /^[\d.,]+$/.test(text.replace(/[$\s]/g, ''));
        if (!isNum) return;

        const raw    = parseInt(text.replace(/\D/g, '')) || 0;
        const prefix = text.startsWith('$') ? '$' : '';
        const dur    = 900;
        const steps  = 40;
        let step     = 0;

        const timer = setInterval(() => {
            step++;
            const val = Math.round((step / steps) * raw);
            el.textContent = prefix + val.toLocaleString('es-CO');
            if (step >= steps) {
                clearInterval(timer);
                el.textContent = text;
            }
        }, dur / steps);
    });
})();
