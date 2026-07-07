/* dashboard.js — animaciones del panel cliente */

(function () {
    /* Animar KPI valores */
    document.querySelectorAll('.dash-kpi-val').forEach(function (el) {
        var raw = parseInt(el.textContent.trim()) || 0;
        if (raw === 0) return;
        var steps = 30, step = 0;
        var timer = setInterval(function () {
            step++;
            el.textContent = Math.round((step / steps) * raw);
            if (step >= steps) { clearInterval(timer); el.textContent = raw; }
        }, 600 / steps);
    });

    /* Animar barras de progreso */
    document.querySelectorAll('.prog-fill').forEach(function (el) {
        var target = el.style.width;
        el.style.width = '0%';
        setTimeout(function () { el.style.width = target; }, 150);
    });
})();
