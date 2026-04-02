/**
 * LMS Admin — Global jQuery Logic
 * Sidebar toggle, AJAX helpers, global UI
 */
$(function () {

    /* ── Sidebar Toggle ─────────────────────────────────── */
    const $body = $('body');
    const $toggle = $('#sidebarToggle');
    const $sidebar = $('#sidebar');

    $toggle.on('click', function () {
        if (window.innerWidth < 992) {
            $body.toggleClass('sidebar-open');
        } else {
            $body.toggleClass('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', $body.hasClass('sidebar-collapsed'));
        }
    });

    // Restore collapsed state on desktop
    if (window.innerWidth >= 992 && localStorage.getItem('sidebar-collapsed') === 'true') {
        $body.addClass('sidebar-collapsed');
    }

    // Overlay click closes mobile sidebar
    $(document).on('click', '.sidebar-overlay', function () {
        $body.removeClass('sidebar-open');
    });

    /* ── Sidebar Scroll Position Preservation ───────────── */
    // Save sidebar scroll position before leaving page
    $(document).on('click', '.sidebar-link:not(.sidebar-link-toggle)', function () {
        const scrollTop = $sidebar.scrollTop();
        localStorage.setItem('sidebar-scroll-position', scrollTop);

        // Close mobile sidebar if open
        if (window.innerWidth < 992) {
            $body.removeClass('sidebar-open');
        }
    });

    // Restore sidebar scroll position on page load
    const savedScrollPosition = localStorage.getItem('sidebar-scroll-position');
    if (savedScrollPosition !== null) {
        // Wait for DOM to be fully ready
        setTimeout(function () {
            $sidebar.scrollTop(parseInt(savedScrollPosition, 10));
        }, 100);

        // Clear the saved position after restoring (one-time use)
        localStorage.removeItem('sidebar-scroll-position');
    }

    // Keep active link in view - scroll sidebar to show active item
    const $activeLink = $('.sidebar-link.active');
    if ($activeLink.length > 0) {
        setTimeout(function () {
            const sidebarHeight = $sidebar.outerHeight();
            const linkTop = $activeLink.position().top + $sidebar.scrollTop();
            const linkBottom = linkTop + $activeLink.outerHeight();
            const currentScroll = $sidebar.scrollTop();

            // Only scroll if active link is not visible
            if (linkTop < currentScroll || linkBottom > currentScroll + sidebarHeight) {
                // Center the active link in the sidebar view
                const scrollTo = linkTop - (sidebarHeight / 2) + ($activeLink.outerHeight() / 2);
                $sidebar.animate({
                    scrollTop: scrollTo
                }, 300);
            }
        }, 200);
    }

    /* ── AJAX Helper ────────────────────────────────────── */
    window.lmsAjax = function (url, data, onSuccess, onError) {
        return $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json',
            beforeSend: function () {
                // optional: show global loader
            },
            success: function (res) {
                if (typeof onSuccess === 'function') onSuccess(res);
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.error || 'Lỗi kết nối, thử lại sau.';
                if (typeof onError === 'function') onError(msg);
                else lmsToast('danger', msg);
            }
        });
    };

    /* ── Toast Notifications ────────────────────────────── */
    window.lmsToast = function (type, message) {
        const icons = { success: 'bx-check-circle', danger: 'bx-error-circle', warning: 'bx-info-circle', info: 'bx-info-circle' };
        const colors = { success: '#10b981', danger: '#ef4444', warning: '#f59e0b', info: '#06b6d4' };

        const id = 'toast-' + Date.now();
        const html = `
        <div id="${id}" class="lms-toast" style="
            position:fixed; bottom:24px; right:24px; z-index:9999;
            background:#fff; border-radius:14px;
            box-shadow:0 8px 32px rgba(0,0,0,.14);
            padding:14px 18px; display:flex; align-items:center; gap:10px;
            max-width:360px; border-left:4px solid ${colors[type]};
            animation: slideInToast .3s ease;
        ">
            <i class='bx ${icons[type]}' style="font-size:22px;color:${colors[type]};flex-shrink:0"></i>
            <span style="font-size:13.5px;font-weight:500;color:#1e293b">${message}</span>
            <button onclick="$('#${id}').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;font-size:18px;color:#94a3b8">&times;</button>
        </div>`;
        $('body').append(html);
        setTimeout(() => $('#' + id).fadeOut(300, function () { $(this).remove(); }), 4000);
    };

    // Inject toast animation
    if (!$('#lms-toast-style').length) {
        $('<style id="lms-toast-style">@keyframes slideInToast{from{transform:translateX(120%);opacity:0}to{transform:translateX(0);opacity:1}}</style>').appendTo('head');
    }

    /* ── Confirm Delete ─────────────────────────────────── */
    $(document).on('click', '[data-confirm]', function (e) {
        const msg = $(this).data('confirm') || 'Bạn chắc chắn muốn xóa?';
        if (!confirm(msg)) e.preventDefault();
    });

    /* ── Sidebar overlay append ─────────────────────────── */
    if (!$('.sidebar-overlay').length) {
        $('<div class="sidebar-overlay"></div>').appendTo('body');
    }

    /* ── Dark Mode Toggle ───────────────────────────────── */
    const $darkModeBtn = $('#darkModeToggle');
    const $darkModeIcon = $darkModeBtn.find('i');

    // Set initial icon
    if (document.documentElement.getAttribute('data-theme') === 'dark') {
        $darkModeIcon.removeClass('bx-moon').addClass('bx-sun');
    }

    $darkModeBtn.on('click', function () {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        if (isDark) {
            document.documentElement.removeAttribute('data-theme');
            localStorage.setItem('lms_theme', 'light');
            $darkModeIcon.removeClass('bx-sun').addClass('bx-moon');
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('lms_theme', 'dark');
            $darkModeIcon.removeClass('bx-moon').addClass('bx-sun');
        }

        // Custom event for charts to listen to
        $(document).trigger('themeChanged', [isDark ? 'light' : 'dark']);
    });

});
