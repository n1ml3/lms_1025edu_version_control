/**
 * LMS Admin — Dashboard Charts (Chart.js 4)
 * Called from admin/index.php after DOM ready
 */

window.initDashboardCharts = function (revenueData) {
    /* ── Revenue Line Chart ─────────────────────────────── */
    const revenueCtx = document.getElementById('revenueChart');
    if (!revenueCtx) return;

    const defaultData = revenueData || {
        labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
        datasets: [
            {
                label: 'Doanh thu',
                data: [12000000, 19000000, 15000000, 25000000, 22000000, 30000000, 18000000],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,.08)',
                borderWidth: 2.5,
                tension: 0.45,
                fill: true,
                pointBackgroundColor: '#4f46e5',
                pointRadius: 4,
                pointHoverRadius: 6,
            },
            {
                label: 'Mục tiêu',
                data: [15000000, 20000000, 18000000, 28000000, 25000000, 32000000, 22000000],
                borderColor: '#06b6d4',
                backgroundColor: 'transparent',
                borderWidth: 2,
                borderDash: [6, 4],
                tension: 0.45,
                fill: false,
                pointRadius: 0,
            }
        ]
    };

    window.revenueChartInstance = new Chart(revenueCtx, {
        type: 'line',
        data: defaultData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { family: 'Inter', size: 12 },
                        boxWidth: 22,
                        padding: 16,
                        color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#94a3b8' : '#64748b'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { family: 'Inter', size: 13 },
                    bodyFont: { family: 'Inter', size: 12 },
                    callbacks: {
                        label: function (ctx) {
                            return ' ' + ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Inter', size: 12 },
                        color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#94a3b8' : '#64748b'
                    }
                },
                y: {
                    grid: { color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#334155' : '#f1f5f9' },
                    ticks: {
                        font: { family: 'Inter', size: 12 },
                        color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#94a3b8' : '#64748b',
                        callback: v => (v / 1000000).toFixed(0) + 'M'
                    }
                }
            }
        }
    });

    // Listen to theme change
    $(document).on('themeChanged', function (e, theme) {
        if (window.revenueChartInstance) {
            const isDark = theme === 'dark';
            const tickColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? '#334155' : '#f1f5f9';

            window.revenueChartInstance.options.plugins.legend.labels.color = tickColor;
            window.revenueChartInstance.options.scales.x.ticks.color = tickColor;
            window.revenueChartInstance.options.scales.y.ticks.color = tickColor;
            window.revenueChartInstance.options.scales.y.grid.color = gridColor;

            window.revenueChartInstance.update();
        }
    });
};

$(function () {
    // Filter AJAX
    $('#btnFilter, #btnRefreshStats').on('click', function () {
        const data = {
            branch: $('#filterBranch').val(),
            source: $('#filterSource').val(),
            staff: $('#filterStaff').val(),
            dateFrom: $('#filterDateFrom').val(),
            dateTo: $('#filterDateTo').val(),
        };
        if (typeof lmsAjax === 'function') {
            lmsAjax('/lms1025edu/admin/api/dashboard_stats.php', data, function (res) {
                if (typeof lmsToast === 'function') lmsToast('success', 'Đã cập nhật thống kê!');
                // TODO: update mini stat values from res
            });
        }
    });
});
