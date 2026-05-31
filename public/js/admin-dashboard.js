document.addEventListener('DOMContentLoaded', function () {
    const unemploymentCanvas = document.getElementById('unemploymentChart');
    const regionalCanvas = document.getElementById('regionalChart');

    if (typeof Chart === 'undefined') {
        return;
    }

    if (unemploymentCanvas) {
        const data = unemploymentCanvas.dataset.chart ? JSON.parse(unemploymentCanvas.dataset.chart) : null;
        if (data) {
            const ctx1 = unemploymentCanvas.getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Unemployed Graduates',
                        data: data.data,
                        borderColor: '#f5576c',
                        backgroundColor: 'rgba(245, 87, 108, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: true }
            });
        }
    }

    if (regionalCanvas) {
        const data = regionalCanvas.dataset.chart ? JSON.parse(regionalCanvas.dataset.chart) : null;
        if (data) {
            const ctx2 = regionalCanvas.getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: true }
            });
        }
    }
});
