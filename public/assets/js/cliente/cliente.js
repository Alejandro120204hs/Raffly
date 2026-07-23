/* cliente.js — sidebar toggle + user dropdown */

(function () {
    const sidebar        = document.getElementById('sidebar');
    const overlay        = document.getElementById('sidebarOverlay');
    const toggleBtn      = document.getElementById('sidebarToggle');
    const closeBtn       = document.getElementById('sidebarClose');
    const userTrigger    = document.getElementById('userMenuTrigger');
    const userDropdown   = document.getElementById('userDropdown');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('open');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn)  closeBtn.addEventListener('click', closeSidebar);
    if (overlay)   overlay.addEventListener('click', closeSidebar);

    if (userTrigger && userDropdown) {
        userTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            userDropdown.classList.toggle('open');
        });
        document.addEventListener('click', function () {
            userDropdown.classList.remove('open');
        });
    }
})();
