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
                        borderColor: '#1a3c5e',
                        backgroundColor: 'rgba(26,60,94,0.08)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#1a3c5e',
                        pointRadius: 4,
                    },
                    {
                        label: 'Books Returned',
                        data: window.chartMonthlyData.map(d => d.returned),
                        borderColor: '#1b7a4e',
                        backgroundColor: 'rgba(27,122,78,0.08)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#1b7a4e',
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
                        '#1a3c5e','#2a5a8c','#1b7a4e','#c8873a',
                        '#b03a3a','#1d6fa4','#b07730','#6b7794',
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
                        backgroundColor: 'rgba(26,60,94,0.8)',
                        borderRadius: 4,
                    },
                    {
                        label: 'Returned',
                        data: window.chartAnnualData.map(d => d.returned),
                        backgroundColor: 'rgba(27,122,78,0.8)',
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