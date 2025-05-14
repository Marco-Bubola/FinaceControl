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
                        display: true,
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
// ...demais funções JS (updateCategoryChart, graficoFinanceiro, etc)...