/**
 * LMS Admin — Global jQuery Logic
 * Sidebar toggle, AJAX helpers, global UI
 */
$(function () {

    /* ── Sidebar Toggle ─────────────────────────────────── */
    const $body    = $('body');
    const $toggle  = $('#sidebarToggle');

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

    /* ── Auto-close sidebar on mobile when link clicked ─── */
    $(document).on('click', '.sidebar-link:not(.sidebar-link-toggle)', function () {
        if (window.innerWidth < 992) $body.removeClass('sidebar-open');
    });

    /* ── Sidebar overlay append ─────────────────────────── */
    if (!$('.sidebar-overlay').length) {
        $('<div class="sidebar-overlay"></div>').appendTo('body');
    }
});
