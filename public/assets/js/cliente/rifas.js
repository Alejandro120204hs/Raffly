/* rifas.js — animaciones de progreso */

(function () {
    document.querySelectorAll('.prog-bar-fill').forEach(function (el) {
        var target = el.style.width;
        el.style.width = '0%';
        setTimeout(function () { el.style.width = target; }, 100);
    });
})();
