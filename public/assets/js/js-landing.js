/* ── Fade-in on scroll ── */
const fadeObserver = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('in');
            fadeObserver.unobserve(e.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-up').forEach(el => fadeObserver.observe(el));

/* ── Stats counter animation ── */
function animateCount(el, target, prefix) {
    const duration = 1800;
    const start    = performance.now();

    const update = now => {
        const progress = Math.min((now - start) / duration, 1);
        const eased    = 1 - Math.pow(1 - progress, 3);
        const value    = Math.floor(eased * target);

        el.textContent = prefix + value.toLocaleString('es-CO') + (target > 0 ? '+' : '');

        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            el.textContent = prefix + target.toLocaleString('es-CO') + (target > 0 ? '+' : '');
        }
    };

    requestAnimationFrame(update);
}

const statsObserver = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (!e.isIntersecting) return;

        e.target.querySelectorAll('.stat-num[data-target]').forEach(el => {
            const target = parseInt(el.dataset.target) || 0;
            const prefix = el.dataset.prefix || '';
            animateCount(el, target, prefix);
        });

        statsObserver.unobserve(e.target);
    });
}, { threshold: 0.3 });

const statsGrid = document.getElementById('statsGrid');
if (statsGrid) statsObserver.observe(statsGrid);
