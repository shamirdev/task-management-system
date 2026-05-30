(function($) {
    "use strict"

    new quixSettings({
        typography: "roboto",
        version: "light",
        layout: "vertical",
        headerBg: "color_1",
        navheaderBg: "color_1",
        sidebarBg: "color_1",
        sidebarStyle: "full",
        sidebarPosition: "fixed",
        headerPosition: "fixed",
        containerLayout: "wide",
        direction: "ltr"
    });


})(jQuery);

/* Mobile sidebar drawer — toggled by the hamburger inside .nav-control */
document.addEventListener('DOMContentLoaded', function () {
    var hamburger = document.querySelector('.nav-control');
    var sidebar = document.querySelector('.focus-sidebar, .quixnav');
    if (!hamburger || !sidebar) return;

    function isMobile() {
        return window.innerWidth <= 991.98;
    }

    hamburger.addEventListener('click', function (e) {
        if (!isMobile()) return;
        e.stopPropagation();
        document.body.classList.toggle('sidebar-open');
    });

    document.addEventListener('click', function (e) {
        if (!document.body.classList.contains('sidebar-open')) return;
        if (sidebar.contains(e.target) || hamburger.contains(e.target)) return;
        document.body.classList.remove('sidebar-open');
    });

    window.addEventListener('resize', function () {
        if (!isMobile()) document.body.classList.remove('sidebar-open');
    });
});