/* Sys Admin — main.js */

$(function () {
    var $body = $('body');
    var $html  = $('html');

    // Remove AdminLTE's hold-transition so animations kick in after first paint
    $body.removeClass('hold-transition');

    // ── Auto-dismiss toasts after 5 s ──
    setTimeout(function () {
        $('.alert-toast').fadeOut(400, function () { $(this).remove(); });
    }, 5000);

    // ── Select2 ──
    if ($.fn.select2) {
        $('select:not(.no-select2)').select2({ theme: 'default', width: '100%' });
    }

    // ── Confirm delete ──
    $(document).on('click', '[data-confirm]', function (e) {
        var msg = $(this).data('confirm') || 'Are you sure?';
        if (!confirm(msg)) { e.preventDefault(); }
    });

    // ── Tooltips (Bootstrap 4) ──
    $('[data-toggle="tooltip"]').tooltip();

    // ════════════════════════════════════════════
    // Sidebar — custom pushmenu (no AdminLTE JS)
    // ════════════════════════════════════════════

    // Inject overlay once
    if (!$('.sidebar-overlay').length) {
        $('<div class="sidebar-overlay"></div>').appendTo('.wrapper');
    }

    function isMobile() {
        return $(window).width() < 992;
    }

    function isRTL() {
        return $html.attr('dir') === 'rtl';
    }

    function openSidebar() {
        $body.addClass('sidebar-open');
    }

    function closeSidebar() {
        $body.removeClass('sidebar-open');
    }

    function toggleDesktop() {
        $body.toggleClass('sidebar-collapse');
        try {
            localStorage.setItem('sidebarCollapsed',
                $body.hasClass('sidebar-collapse') ? '1' : '0');
        } catch (e) {}
    }

    // Restore desktop collapse preference
    if (!isMobile()) {
        try {
            if (localStorage.getItem('sidebarCollapsed') === '1') {
                $body.addClass('sidebar-collapse');
            }
        } catch (e) {}
    }

    // Hamburger / pushmenu click
    $(document).on('click', '[data-widget="pushmenu"]', function (e) {
        e.preventDefault();
        if (isMobile()) {
            $body.hasClass('sidebar-open') ? closeSidebar() : openSidebar();
        } else {
            toggleDesktop();
        }
    });

    // Overlay click closes sidebar
    $(document).on('click', '.sidebar-overlay', function () {
        closeSidebar();
    });

    // Close sidebar when a nav link is clicked on mobile
    $(document).on('click', '.main-sidebar .nav-link', function () {
        if (isMobile()) { closeSidebar(); }
    });

    // Clean up mobile state on resize to desktop
    var resizeTimer;
    $(window).on('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (!isMobile()) { closeSidebar(); }
        }, 150);
    });

    // ── Touch swipe to open / close sidebar ──
    var touchStartX = 0;
    var touchStartY = 0;

    document.addEventListener('touchstart', function (e) {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
    }, { passive: true });

    document.addEventListener('touchend', function (e) {
        if (!isMobile()) { return; }
        var dx = e.changedTouches[0].clientX - touchStartX;
        var dy = e.changedTouches[0].clientY - touchStartY;

        // Ignore short or mostly-vertical swipes
        if (Math.abs(dx) < 40 || Math.abs(dy) > Math.abs(dx)) { return; }

        var rtl = isRTL();

        if (!rtl) {
            // LTR: swipe right from left edge → open; swipe left → close
            if (dx > 0 && touchStartX < 30 && !$body.hasClass('sidebar-open')) {
                openSidebar();
            } else if (dx < 0 && $body.hasClass('sidebar-open')) {
                closeSidebar();
            }
        } else {
            // RTL: swipe left from right edge → open; swipe right → close
            var vw = window.innerWidth;
            if (dx < 0 && touchStartX > vw - 30 && !$body.hasClass('sidebar-open')) {
                openSidebar();
            } else if (dx > 0 && $body.hasClass('sidebar-open')) {
                closeSidebar();
            }
        }
    }, { passive: true });
});
