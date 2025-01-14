import Chart from 'chart.js/auto';

if(document.getElementById('orders-by-delivery-method')){
    var $months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    document.addEventListener('DOMContentLoaded', function () {
        /* Histórico */
        var history = document.getElementById('history').getContext('2d');

        new Chart(history, {
            type: 'line',
            data: {
                labels: $months,
                datasets: [{
                    label: 'Vendas',
                    data: [400, 350, 600, 412, 355, 617, 1000, 1100, 1200, 1300, 1200, 1250], // Dados no eixo Y
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }); // Histórico

        /* Pedidos completos */
        var completedOrders = document.getElementById('completed-orders').getContext('2d');


        new Chart(completedOrders, {
            type: 'doughnut',
            data: {
                labels: [
                    'Online',
                    'Balcão',
                ],
                datasets: [{
                    label: 'Pedidos concluídos',
                    data: [300, 50],
                    backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }); /* Pedidos completos */


        /* Pedidos por hora */
        var ordersPerHour = document.getElementById('orders-per-hour').getContext('2d');

        let listHours = [];

        for(var h = 0; h < 24; h++){
            listHours.push(h + 'h');
        }

        new Chart(ordersPerHour, {
            type: 'line',
            data: {
                labels: listHours,
                datasets: [{
                    label: 'Pedidos por hora',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 6, 15, 8, 17, 22, 12],
                    backgroundColor: [
                    'rgb(54, 162, 235)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }); /* Pedidos por hora */

        /* Faturamento */
        var invoicing = document.getElementById('invoicing').getContext('2d');


        new Chart(invoicing, {
            type: 'doughnut',
            data: {
                labels: [
                    'Online',
                    'Balcão',
                ],
                datasets: [{
                    label: 'Faturamento',
                    data: [12000, 4000],
                    backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }); /* Faturamento */

        /* Ticket médio */
        var averageTicket = document.getElementById('average-ticket').getContext('2d');


        new Chart(averageTicket, {
            type: 'bar',
            data: {
                labels: [
                    'Online',
                    'Balcão',
                ],
                datasets: [{
                    label: 'Faturamento',
                    data: [70.26, 53.87],
                    backgroundColor: [
                    'rgb(75, 192, 192)',
                    'rgb(255, 159, 64)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }); /* Ticket médio */

        /* Pedidos por novos clientes */
        var ordersFromNewCustomers = document.getElementById('orders-from-new-customers').getContext('2d');


        new Chart(ordersFromNewCustomers, {
            type: 'pie',
            data: {
                labels: [
                    'Novos clientes',
                    'Clientes antigos',
                ],
                datasets: [{
                    label: 'Faturamento',
                    data: [62.26, 37.4],
                    backgroundColor: [
                    'rgb(118, 167, 85)',
                    'rgb(222, 40, 18)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }); /* Pedidos por novos clientes */

        /* Pedidos por dia da semana */
        var orderByDayOfTheWeek = document.getElementById('orders-by-day-of-the-week').getContext('2d');


        new Chart(orderByDayOfTheWeek, {
            type: 'bar',
            data: {
                labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Dias da semana',
                    data: [40, 48, 56, 67, 75, 180, 125],
                    backgroundColor: [
                    'rgb(118, 167, 85)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }); /* Pedidos por dia da semana */

        /* Pedidos por meio de pagamento */
        var orderByPaymentMethod = document.getElementById('orders-by-payment-method').getContext('2d');


        new Chart(orderByPaymentMethod, {
            type: 'doughnut',
            data: {
                labels: ['Crédito', 'Débito', 'PIX', 'Dinheiro'],
                datasets: [{
                    label: 'Pedidos por meio de pagamento',
                    data: [67, 75, 180, 125],
                    backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 205, 86)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }); /* Pedidos por meio de pagamento */

        /* Pedidos por meio de entrega */
        var ordersByDeliveryMethod = document.getElementById('orders-by-delivery-method').getContext('2d');


        new Chart(ordersByDeliveryMethod, {
            type: 'doughnut',
            data: {
                labels: ['Entrega', 'Retirada', 'Consumir no local'],
                datasets: [{
                    label: 'Pedidos por meio de entrega',
                    data: [ 75, 180, 125],
                    backgroundColor: [
                    'rgb(255, 159, 64)',
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }); /* Pedidos por meio de entrega */

    });
}