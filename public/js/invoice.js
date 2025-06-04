

$(document).ready(function() {
    // Usa variáveis globais definidas no Blade
    var initialCategories = window.INITIAL_CATEGORIES || [];
    var totalInvoices = window.TOTAL_INVOICES || 0;
    var eventsData = window.EVENTS_DATA || [];
    var bankId = window.BANK_ID || '';

    // Atualiza o gráfico na carga inicial
    if (!window.categoryChart) {
        updateCategoryChart(initialCategories, totalInvoices);
    }

    // Função para atualizar os dados do mês, incluindo o gráfico de categorias
    function updateMonthData(month) {
        $.ajax({
            url: window.INVOICES_INDEX_URL,
            method: "GET",
            data: {
                bank_id: bankId,
                month: month
            },
            success: function(response) {
                if (!response.dailyLabels || !response.dailyValues) {
                    console.error('Dados diários não encontrados no response.');
                    return;
                }

                addLineChart({
                    labels: response.dailyLabels,
                    values: response.dailyValues
                });

                $('#transactions-container').html(response.transactionsHtml);

                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', response.eventsDetailed);

                $('#total-invoices').text(`R$ ${response.totalInvoices.toFixed(2)}`);
                $('#highest-invoice').text(`R$ ${response.highestInvoice}`);
                $('#lowest-invoice').text(`R$ ${response.lowestInvoice}`);
                $('#total-transactions').text(response.totalTransactions);

                $('#current-month-name').text(response.currentMonthName);
                $('#previous-month-name').text(response.previousMonthName);
                $('#next-month-name').text(response.nextMonthName);
                $('#previous-month-btn-name').text(response.previousMonthName);
                $('#next-month-btn-name').text(response.nextMonthName);
                $('#current-month-range').text(response.currentMonthRange);
                $('#current-month-title').text(response.currentMonthTitle);

                $('#previous-month').data('month', response.previousMonth);
                $('#next-month').data('month', response.nextMonth);

                $('#transactions-container').html(response.transactionsHtml);

                initInvoiceExpanders();
                updateCategoryChart(response.categories, response.totalInvoices);
                addLineChart({
                    labels: response.dailyLabels,
                    values: response.dailyValues
                });

                // Reativa o JS de expansão dos cards de categoria
                document.querySelectorAll('.category-card-header').forEach(function(header) {
                    header.addEventListener('click', function() {
                        const card = header.closest('.category-card');
                        const body = card.querySelector('.invoices-list');
                        const icon = header.querySelector('.toggle-icon');
                        body.classList.toggle('d-none');
                        icon.classList.toggle('fa-chevron-down');
                        icon.classList.toggle('fa-chevron-up');
                    });
                });

                // Atualiza os totais dos meses nos cards
                $('#previous-month-total').text(`R$ ${parseFloat(response.previousMonthTotal).toFixed(2)}`);
                $('#current-month-total').text(`R$ ${parseFloat(response.currentMonthTotal).toFixed(2)}`);
                $('#next-month-total').text(`R$ ${parseFloat(response.nextMonthTotal).toFixed(2)}`);
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', xhr.responseText);
                alert('Erro ao carregar os dados do mês. Verifique os logs para mais detalhes.');
            }
        });
    }

    // Troca de mês ao clicar no card
    $(document).on('click', '.month-card-change', function() {
        var month = $(this).data('month');
        if (month) {
            updateMonthData(month);
            if (window.fp) {
                window.fp.setDate(month, true, "Y-m-d");
            }
        }
    });

    // Torna updateMonthData global para ser chamada pelo calendário
    window.updateMonthData = updateMonthData;

    function initInvoiceExpanders() {
        document.querySelectorAll('.show-more-invoices-btn').forEach(function(btn) {
            var monthKey = btn.getAttribute('data-month');
            var extraInvoices = document.querySelectorAll('.extra-invoice-' + monthKey);
            btn.onclick = function() {
                var expanded = btn.classList.toggle('expanded');
                extraInvoices.forEach(function(el) {
                    el.classList.toggle('d-none', !expanded);
                });
                btn.querySelector('.show-more-label').classList.toggle('d-none', expanded);
                btn.querySelector('.show-less-label').classList.toggle('d-none', !expanded);
            };
        });
    }

    function updateCategoryChart(categories, totalInvoices) {
        if (categories && typeof categories === 'object' && !Array.isArray(categories)) {
            categories = Object.values(categories);
        }

        const ctx = document.getElementById('updateCategoryChart');
        if (!ctx) {
            console.error('Elemento canvas para o gráfico não encontrado.');
            return;
        }
        if (!categories || categories.length === 0) {
            document.getElementById('no-data-message').style.display = 'block';
            categories = [{ label: 'Nenhuma Categoria', value: 0 }];
        } else {
            document.getElementById('no-data-message').style.display = 'none';
        }
        if (window.categoryChart) {
            window.categoryChart.destroy();
        }

        const categoryData = categories.map(category => category.value);
        const labels = categories.map(category => category.label);

        try {
            window.categoryChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: categoryData,
                        backgroundColor: [
                            categoryData[0] === 0 ? '#FF6384' : '#36A2EB', '#FF6384',
                            '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF5733',
                            '#C70039', '#900C3F', '#DAF7A6', '#FFC300',
                            '#581845', '#FF6F61', '#1E90FF', '#00FA9A',
                            '#FF1493'
                        ],
                        hoverBackgroundColor: [
                            categoryData[0] === 0 ? '#FF6384' : '#36A2EB', '#FF6384',
                            '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF5733',
                            '#C70039', '#900C3F', '#DAF7A6', '#FFC300',
                            '#581845', '#FF6F61', '#1E90FF', '#00FA9A',
                            '#FF1493'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: false,
                        },
                        title: {
                            display: true,
                            text: 'Distribuição por Categoria'
                        }
                    },
                    layout: {
                        padding: 30
                    },
                    backgroundColor: 'transparent'
                },
                plugins: [{
                    id: 'centerText',
                    beforeDraw: (chart) => {
                        const { width } = chart;
                        const ctx = chart.ctx;
                        ctx.save();
                        ctx.font = 'bold 20px Arial';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillStyle = '#333';
                        ctx.fillText('Total de Faturas', width / 2, chart.chartArea.top + (chart.chartArea.bottom - chart.chartArea.top) / 2 - 10);
                        ctx.fillStyle = '#4caf50';
                        ctx.fillText(`R$ ${totalInvoices.toFixed(2)}`, width / 2, chart.chartArea.top + (chart.chartArea.bottom - chart.chartArea.top) / 2 + 15);
                        ctx.restore();
                    }
                }]
            });
        } catch (error) {
            console.error('Erro ao criar o gráfico:', error);
        }
    }

    function addLineChart(dailyData) {
        const ctx = document.getElementById('lineChart');
        if (!ctx) {
            console.error('Elemento canvas para o gráfico de linha não encontrado.');
            return;
        }

        const labels = dailyData.labels;
        const dataset = dailyData.values;
        const colors = labels.map((_, index) => `hsl(${(index * 360) / labels.length}, 70%, 50%)`);

        const chartData = {
            labels: labels,
            datasets: [{
                label: 'Faturas por Dia',
                data: dataset,
                borderColor: colors,
                backgroundColor: colors.map(color => color.replace('70%', '90%')),
                borderWidth: 2,
                pointRadius: 5,
                pointBackgroundColor: colors,
                fill: true,
                tension: 0.4
            }]
        };

        const config = {
            type: 'line',
            data: chartData,
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Faturas por Dia no Mês'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `R$ ${context.raw.toFixed(2)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        if (window.lineChart && typeof window.lineChart.destroy === 'function') {
            window.lineChart.destroy();
        }

        window.lineChart = new Chart(ctx.getContext('2d'), config);
    }
});
