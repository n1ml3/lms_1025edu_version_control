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

    new Chart(revenueCtx, {
        type: 'line',
        data: defaultData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { family: 'Inter', size: 12 }, boxWidth: 22, padding: 16 }
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
                    ticks: { font: { family: 'Inter', size: 12 }, color: '#64748b' }
                },
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { family: 'Inter', size: 12 },
                        color: '#64748b',
                        callback: v => (v / 1000000).toFixed(0) + 'M'
                    }
                }
            }
        }
    });
};
