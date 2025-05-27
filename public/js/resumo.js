// public/js/resumo.js

document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Categorias
    if (typeof updateCategoryChart === 'function') {
        const categories = window.resumoCategories || [];
        const totalInvoices = window.resumoTotalInvoices || 0;
        updateCategoryChart(categories, totalInvoices);
    }

    // Gráfico Financeiro
    if (typeof graficoFinanceiro === 'function') {
        const totals = window.resumoTotals || {};
        graficoFinanceiro(totals);
    }
});
  

    // Função para atualizar o gráfico de categorias
    function updateCategoryChart(categories, totalInvoices) {
        // Converte o objeto de categorias em um array, se necessário
        if (categories && typeof categories === 'object' && !Array.isArray(categories)) {
            categories = Object.values(categories);
        }

        const ctx = document.getElementById('updateCategoryChart');
        if (!ctx) {
            console.error('Elemento canvas para o gráfico não encontrado.');
            return;
        }
        // Se não houver dados, mostra a mensagem "Sem dados" e cria o gráfico com valor zero
        if (!categories || categories.length === 0) {
            console.warn('Nenhum dado encontrado para o gráfico de categorias.');
            document.getElementById('no-data-message').style.display = 'block'; // Mostra a mensagem "Sem dados"
            categories = [{
                label: 'Nenhuma Categoria',
                value: 0
            }];
        } else {
            document.getElementById('no-data-message').style.display = 'none'; // Esconde a mensagem "Sem dados"
        }
        // Se já existir um gráfico, destrua-o antes de criar um novo
        if (window.categoryChart) {
            window.categoryChart.destroy();
        }

        // Processa os dados para o gráfico
        const categoryData = categories.map(category => category.value);
        const labels = categories.map(category => category.label);

        // Cria o gráfico
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
                            display: false,
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
                        const {
                            width
                        } = chart;
                        const ctx = chart.ctx;
                        ctx.save();
                        ctx.font = 'bold 20px Arial';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        // Exibe o texto "Total de Faturas"
                        ctx.fillStyle = '#333';
                        ctx.fillText('Total de Faturas', width / 2, chart.chartArea.top + (chart
                            .chartArea.bottom - chart.chartArea.top) / 2 - 10);

                        // Exibe o valor total no centro
                        ctx.fillStyle = '#4caf50';
                        ctx.fillText(`R$ ${totalInvoices.toFixed(2)}`, width / 2, chart.chartArea
                            .top +
                            (chart.chartArea.bottom - chart.chartArea.top) / 2 + 15);
                        ctx.restore();
                    }
                }]
            });
        } catch (error) {
            console.error('Erro ao criar o gráfico:', error);
        }
    }

  

    // Atualizar o gráfico de pizza com saldo no centro
    function graficoFinanceiro(totals) {
        if (window.myChart) {
            window.myChart.destroy();
        }

        const ctx = document.getElementById('transaction-pie-chart').getContext('2d');
        const chartData = {
            labels: ['Receitas', 'Despesas'],
            datasets: [{
                data: [totals.income, totals.expense],
                backgroundColor: ['#4caf50', '#f44336'], // Cores vibrantes
                hoverBackgroundColor: ['#66bb6a', '#e57373'], // Cores ao passar o mouse
                borderColor: ['#4caf50', '#f44336'], // Borda preta
                borderWidth: 3,
            }]
        };

        const config = {
            type: 'doughnut',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: false,
                        text: 'Distribuição de Receitas e Despesas'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': R$ ' + tooltipItem.raw.toFixed(2);
                            }
                        }
                    },
                },
                cutout: '70%', // Criar espaço no centro
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: (chart) => {
                    const {
                        width
                    } = chart;
                    const ctx = chart.ctx;
                    ctx.save();
                    ctx.font = 'bold 20px Arial';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#333';
                    ctx.fillText('Saldo', width / 2, chart.chartArea.top + (chart.chartArea.bottom -
                        chart
                        .chartArea.top) / 2 - 10);
                    ctx.fillStyle = totals.balance >= 0 ? '#4caf50' : '#f44336';
                    ctx.fillText(`R$ ${totals.balance.toFixed(2)}`, width / 2, chart.chartArea.top + (
                        chart
                        .chartArea.bottom - chart.chartArea.top) / 2 + 15);
                    ctx.restore();
                }
            }]
        };

        window.myChart = new Chart(ctx, config);
    }
     // Função para atualizar os cartões de resumo
        function atualizarCards(data) {
            const cards = [
                { selector: '.card .text-danger', value: data.totalFaturas },
                { selector: '.card .text-success', value: data.totalRecebido },
                { selector: '.card .text-warning', value: data.totalEnviado },
                { selector: '.card .text-info', value: data.saldoAtual }
            ];
            cards.forEach(function(card, idx) {
                const el = document.querySelector(card.selector);
                if (el) {
                    el.innerHTML = 'R$ ' + Number(card.value).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            });
        }

        // Função para atualizar os gráficos (exemplo para gráfico de categorias)
        function atualizarGraficos(data) {
            window.resumoCategories = data.categories;
            window.resumoTotalInvoices = data.totalFaturas;
            window.resumoTotals = data.totals;
            // Se você usa Chart.js, chame update() nos gráficos aqui
            if (window.updateCategoryChartInstance) {
                window.updateCategoryChartInstance.data.labels = data.categories.map(c => c.label);
                window.updateCategoryChartInstance.data.datasets[0].data = data.categories.map(c => c.value);
                window.updateCategoryChartInstance.update();
            }
            // Atualize outros gráficos conforme necessário
        }

        // Paginação AJAX das faturas
        document.addEventListener('DOMContentLoaded', function() {
            function bindFaturaPagination() {
                // Atualize para buscar links tanto no container quanto na paginação do header
                document.querySelectorAll('#faturas-pagination .pagination a, #faturas-container .pagination a').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        let url = this.getAttribute('href');
                        if (!url) return;
                        fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById('faturas-container').innerHTML = data.faturas;
                                document.getElementById('faturas-pagination').innerHTML = data.pagination;
                                bindFaturaPagination();
                                bindDividirCheckbox();
                            });
                    });
                });
            }

            function bindDividirCheckbox() {
                document.querySelectorAll('.dividir-checkbox').forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        const invoiceId = this.getAttribute('data-id');
                        const checked = this.checked ? 'true' : 'false';
                        fetch(`/invoices/${invoiceId}/toggle-dividida`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ dividida: checked })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                document.getElementById('valor-fatura-' + invoiceId).innerHTML = 'R$ ' + data.valor;
                                atualizarCards(data);
                                atualizarGraficos(data);
                            }
                        });
                    });
                });
            }

            function bindEnviadasPagination() {
                document.querySelectorAll('#enviadas-pagination .pagination a, #enviadas-container .pagination a').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        let url = this.getAttribute('href');
                        if (!url) return;
                        fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById('enviadas-container').innerHTML = data.enviadas;
                                document.getElementById('enviadas-pagination').innerHTML = data.pagination;
                                bindEnviadasPagination();
                            });
                    });
                });
            }

            function bindRecebidasPagination() {
                document.querySelectorAll('#recebidas-pagination .pagination a, #recebidas-container .pagination a').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        let url = this.getAttribute('href');
                        if (!url) return;
                        fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                            .then(res => res.json())
                            .then(data => {
                                document.getElementById('recebidas-container').innerHTML = data.recebidas;
                                document.getElementById('recebidas-pagination').innerHTML = data.pagination;
                                bindRecebidasPagination();
                            });
                    });
                });
            }

            bindFaturaPagination();
            bindDividirCheckbox();
            bindEnviadasPagination();
            bindRecebidasPagination();
        });
// ...demais funções JS (updateCategoryChart, graficoFinanceiro, etc)...