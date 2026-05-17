/* ============================================================
   Library Management System — Dashboard Charts JS
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
    // ---- Monthly Borrow/Return Line Chart ----
    const borrowCtx = document.getElementById('borrowChart');
    if (borrowCtx && window.chartMonthlyData) {
        new Chart(borrowCtx, {
            type: 'line',
            data: {
                labels: window.chartMonthlyData.map(d => d.month),
                datasets: [
                    {
                        label: 'Books Borrowed',
                        data: window.chartMonthlyData.map(d => d.borrowed),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.08)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 4,
                    },
                    {
                        label: 'Books Returned',
                        data: window.chartMonthlyData.map(d => d.returned),
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22,163,74,0.08)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#16a34a',
                        pointRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } },
                    tooltip: { mode: 'index', intersect: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: '#f1f5f9' },
                    },
                    x: { grid: { display: false } },
                },
            },
        });
    }

    // ---- Category Donut Chart ----
    const catCtx = document.getElementById('categoryChart');
    if (catCtx && window.chartCategoryData) {
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: window.chartCategoryData.map(d => d.name),
                datasets: [{
                    data: window.chartCategoryData.map(d => d.books),
                    backgroundColor: [
                        '#2563eb','#16a34a','#d97706','#dc2626',
                        '#7c3aed','#0891b2','#ea580c','#64748b',
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8, font: { size: 12 } } },
                },
                cutout: '65%',
            },
        });
    }

    // ---- Annual Borrow Bar Chart (for Reports) ----
    const annualCtx = document.getElementById('annualChart');
    if (annualCtx && window.chartAnnualData) {
        new Chart(annualCtx, {
            type: 'bar',
            data: {
                labels: window.chartAnnualData.map(d => d.month),
                datasets: [
                    {
                        label: 'Borrowed',
                        data: window.chartAnnualData.map(d => d.borrowed),
                        backgroundColor: 'rgba(37,99,235,0.8)',
                        borderRadius: 4,
                    },
                    {
                        label: 'Returned',
                        data: window.chartAnnualData.map(d => d.returned),
                        backgroundColor: 'rgba(22,163,74,0.8)',
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } },
                },
            },
        });
    }
});