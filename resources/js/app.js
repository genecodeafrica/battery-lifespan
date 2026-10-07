import './bootstrap';
import 'bootstrap';

import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
    Tooltip,
    Legend
} from 'chart.js';

Chart.register(
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
    Tooltip,
    Legend
);

window.Chart = Chart;

document.addEventListener('DOMContentLoaded', () => {

    const canvas = document.getElementById('capacityChart');

    if (canvas) {
        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const values = JSON.parse(canvas.dataset.values || '[]');

        new Chart(canvas, {
            type: 'line',

            data: {
                labels: labels,

                datasets: [{
                    label: 'Capacity (mAh)',
                    data: values,

                    borderColor: '#4ade80',
                    backgroundColor: 'rgba(74, 222, 128, 0.08)',

                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,

                    pointRadius: 3,
                    pointHoverRadius: 5,

                    pointBackgroundColor: '#4ade80',
                    pointBorderColor: '#0b1220'
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: '#111c31',
                        titleColor: '#ffffff',
                        bodyColor: '#cbd5e1',
                        borderColor: '#24344d',
                        borderWidth: 1
                    }
                },

                scales: {
                    x: {
                        grid: {
                            color: 'rgba(148, 163, 184, 0.08)'
                        },

                        ticks: {
                            color: '#7f8da3'
                        }
                    },

                    y: {
                        grid: {
                            color: 'rgba(148, 163, 184, 0.08)'
                        },

                        ticks: {
                            color: '#7f8da3'
                        }
                    }
                }
            }
        });
    }


    /*
    ---------------------------------------------------------
    SIDEBAR MOBILE TOGGLE
    ---------------------------------------------------------
    */

    const menuButton = document.getElementById('mobileMenuButton');
    const sidebar = document.getElementById('appSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (menuButton && sidebar) {

        menuButton.addEventListener('click', () => {
            sidebar.classList.toggle('show');

            if (overlay) {
                overlay.classList.toggle('show');
            }
        });

        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
    }


    /*
    ---------------------------------------------------------
    SIDEBAR ACTIVE LINK
    ---------------------------------------------------------
    */

    document.querySelectorAll('.sidebar-link').forEach(link => {

        link.addEventListener('click', () => {

            if (window.innerWidth < 992 && sidebar) {
                sidebar.classList.remove('show');

                if (overlay) {
                    overlay.classList.remove('show');
                }
            }

        });

    });

});