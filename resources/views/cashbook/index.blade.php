@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid" style=" border: none;">
    @include('message.alert')

    <div class="row" style=" border: none;">
        <div class="col-md-12">
            <div class="card h-100 mb-4" style="background-color: transparent; border: none;">
                <div class="card-header" style="background-color: transparent; border-bottom: none;">
                    <div class="row align-items-center">
                        <!-- Título -->
                        <div class="col-md-3 d-flex justify-content-center align-items-center">
                            <i class="fas fa-wallet text-primary me-2" style="font-size: 1.7rem;"></i>
                            <h5 class="mb-0">Suas Transações</h6>
                        </div>

                        <!-- Navegação de Mês -->
                        <div class="col-md-6 d-flex justify-content-center align-items-center">
                            <button class="btn btn-outline-primary me-4 d-flex align-items-center"
                                style="width: 150px; height: 50px;" onclick="loadMonth('previous')">
                                <i class="fas fa-arrow-left me-1"></i> Mês Anterior
                            </button>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt text-info me-2" style="font-size: 1.7rem;"></i>
                                <h5 class="mb-0"><span id="month-name">{{ $monthName ?? '' }}</span></h5>
                            </div>
                            <button class="btn btn-outline-primary ms-4  d-flex align-items-center"
                                onclick="loadMonth('next')">
                                <i class="fas fa-arrow-right me-1"></i> Próximo Mês
                            </button>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="col-md-3 d-flex justify-content-end align-items-center">
                            <button class="btn btn-outline-success me-2 d-flex align-items-center"
                                data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                                <i class="fas fa-plus me-1"></i> Adicionar
                            </button>
                            <button class="btn btn-outline-info d-flex align-items-center" data-bs-toggle="modal"
                                data-bs-target="#uploadCashbookModal">
                                <i class="fas fa-upload me-1"></i> Upload
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 p-4">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center" style="border: 3px solid var(--bs-success);">
                                <div class="card-body">
                                    <h6 class="text-sm">Receitas</h6>
                                    <span class="text-success">+ R$
                                        {{ number_format(abs($totals['income']), 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center" style="border: 3px solid var(--bs-danger);">
                                <div class="card-body">
                                    <h6 class="text-sm">Despesas</h6>
                                    <span class="text-danger">- R$
                                        {{ number_format(abs($totals['expense']), 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center teste {{ $totals['balance'] >= 0 ? 'border-success' : 'border-danger' }}"
                                style="border-width: 3px;">
                                <div class="card-body">
                                    <h6 class="text-sm">Saldo</h6>
                                    <span
                                        class="text-{{ $totals['balance'] >= 0 ? 'success' : 'danger' }} text-lg font-weight-bold">
                                        {{ $totals['balance'] >= 0 ? '+' : '-' }} R$
                                        {{ number_format(abs($totals['balance']), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Coluna da Esquerda - Transações -->
                        <div class="col-lg-8" id="transactions-container">
                            @if($transactions->isEmpty())
                            <div class="text-center">
                                <h6 class="text-muted">Nenhuma transação encontrada para o mês selecionado.</h6>
                            </div>
                            @else
                            <!-- Conteúdo do mês será carregado dinamicamente -->
                            @endif
                        </div>

                        <!-- Coluna da Direita - Gráfico -->
                        <div class="col-lg-4" id="chart-container">
                            <!-- Gráfico de pizza será inserido aqui -->
                            <canvas id="transaction-pie-chart" width="400" height="400"></canvas>
                            <div class="mt-4">
                                <h6 class="text-center">Receitas por Categoria</h6>
                                <canvas id="income-bar-chart" width="400" height="400"></canvas>
                            </div>
                            <div class="mt-4">
                                <h6 class="text-center">Despesas por Categoria</h6>
                                <canvas id="expense-bar-chart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('cashbook.uploadCashbook')
@include('cashbook.create')
@include('cashbook.edit')
@include('cashbook.delete')

<script>
let currentMonth = "{{ $currentMonth }}";
let myChart, incomeBarChart, expenseBarChart; // Variáveis globais para armazenar os gráficos

// Função para carregar os dados do mês
function loadMonth(direction) {
    const container = document.getElementById('transactions-container');
    const monthNameElement = document.getElementById('month-name');
    const incomeElement = document.querySelector('.text-success');
    const expenseElement = document.querySelector('.text-danger');
    const balanceElement = document.querySelector(
        '.text-lg.font-weight-bold.text-success, .text-lg.font-weight-bold.text-danger'
    );

    fetch(`/cashbook/month/${direction}?currentMonth=${currentMonth}`)
        .then(response => response.json())
        .then(data => {
            // Atualizar o mês atual e os valores exibidos
            updateMonthData(data, monthNameElement, incomeElement, expenseElement, balanceElement);

            // Atualizar as transações
            updateTransactions(container, data.transactionsByDay);

            // Atualizar os gráficos
            updateChart(data.totals);
            updateBarChart('income-bar-chart', data.categories.income, 'Receitas por Categoria');
            updateBarChart('expense-bar-chart', data.categories.expense, 'Despesas por Categoria');
        })
        .catch(error => {
            console.error('Erro ao carregar os dados do mês:', error);
        });
}

// Atualizar os dados do mês
function updateMonthData(data, monthNameElement, incomeElement, expenseElement, balanceElement) {
    currentMonth = data.currentMonth;
    monthNameElement.textContent = data.monthName;

    incomeElement.textContent = `+ R$ ${data.totals.income.toFixed(2)}`;
    expenseElement.textContent = `- R$ ${data.totals.expense.toFixed(2)}`;
    balanceElement.textContent =
        `${data.totals.balance >= 0 ? '+' : '-'} R$ ${Math.abs(data.totals.balance).toFixed(2)}`;
    balanceElement.className = `text-lg font-weight-bold text-${data.totals.balance >= 0 ? 'success' : 'danger'}`;

    // Atualizar a borda do card de saldo dinamicamente
    const balanceCard = document.querySelector('.card.text-center.teste');
    if (balanceCard) {
        balanceCard.classList.remove('border-success', 'border-danger');
        balanceCard.classList.add(`border-${data.totals.balance >= 0 ? 'success' : 'danger'}`);
    }
}

// Atualizar as transações exibidas
function updateTransactions(container, transactionsByDay) {
    const allTransactions = Object.values(transactionsByDay).flat();

    if (allTransactions.length > 0) {
        container.innerHTML = `
            <div class="row">
                ${allTransactions.map(transaction => renderTransactionCard(transaction)).join('')}
            </div>
        `;
    } else {
        container.innerHTML = `
            <div class="text-center">
                <h6 class="text-muted">Nenhuma transação encontrada para o mês selecionado.</h6>
            </div>
        `;
    }
}

// Renderizar o cartão de uma transação
function renderTransactionCard(transaction) {
    const borderColor = transaction.type_id == 2 ? 'danger' : 'success';
    const arrowDirection = transaction.type_id == 2 ? 'down' : 'up';
    const textColor = transaction.type_id == 2 ? 'danger' : 'success';
    const categoryColor = transaction.category_hexcolor_category || '#cccccc';
    const categoryIcon = transaction.category_icone || 'fas fa-question';
    const categoryName = transaction.category_name || 'Categoria não definida';
    const transactionValue = `${transaction.type_id == 2 ? '-' : '+'} R$ ${Math.abs(transaction.value).toFixed(2)}`;

    return `
        <div class="col-md-4 mb-4">
            <div class="card border-${borderColor}" style="border: 3px solid var(--bs-${borderColor});">
                <div class="card-body">
                    <div class="row">
                        <!-- Ícones -->
                        <div class="col-md-2 d-flex flex-column align-items-center">
                            <button class="btn btn-icon-only btn-rounded btn-outline-${borderColor} mb-2" style="width: 50px; height: 50px;">
                                <i class="fas fa-arrow-${arrowDirection}"></i>
                            </button>
                            <button class="btn btn-icon-only btn-rounded btn-sm d-flex align-items-center justify-content-center"
                                style="border: 3px solid ${categoryColor}; width: 50px; height: 50px;"
                                title="${categoryName}" data-bs-toggle="tooltip" data-bs-placement="top">
                                <i class="${categoryIcon}" style="font-size: 1.5rem;"></i>
                            </button>
                        </div>
                        <!-- Detalhes -->
                        <div class="col-md-10">
                            <h6 class="mb-1 text-dark text-sm">${transaction.description}</h6>
                            <span class="text-xs">Data: ${transaction.time}</span><br>
                            <span class="text-xs">Categoria: ${categoryName}</span>
                            <div class="d-flex justify-content-between align-items-center ">
                                <div class="text-${textColor} text-gradient text-sm font-weight-bold">
                                    ${transactionValue}
                                </div>
                                <div class="d-flex">
                                    <button class="btn btn-warning d-flex align-items-center justify-content-center" 
                                        style="width: 30px; height: 30px;" 
                                        data-bs-toggle="modal" data-bs-target="#editTransactionModal" 
                                        onclick="loadEditModal(${transaction.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger  d-flex align-items-center justify-content-center" 
                                        style="width: 30px; height: 30px;" 
                                        data-bs-toggle="modal" data-bs-target="#deleteTransactionModal" 
                                        onclick="loadDeleteModal(${transaction.id}, '${transaction.description}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}


// Atualizar o gráfico de pizza com saldo no centro
function updateChart(totals) {
    if (myChart) {
        myChart.destroy();
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
                ctx.fillText('Saldo', width / 2, chart.chartArea.top + (chart.chartArea.bottom - chart
                    .chartArea.top) / 2 - 10);
                ctx.fillStyle = totals.balance >= 0 ? '#4caf50' : '#f44336';
                ctx.fillText(`R$ ${totals.balance.toFixed(2)}`, width / 2, chart.chartArea.top + (chart
                    .chartArea.bottom - chart.chartArea.top) / 2 + 15);
                ctx.restore();
            }
        }]
    };

    myChart = new Chart(ctx, config);
}

// Atualizar os gráficos de barras com cores das categorias
function updateBarChart(chartId, categoryData, title) {
    const ctx = document.getElementById(chartId).getContext('2d');

    // Destruir o gráfico existente, se houver
    if (chartId === 'income-bar-chart' && incomeBarChart) {
        incomeBarChart.destroy();
    } else if (chartId === 'expense-bar-chart' && expenseBarChart) {
        expenseBarChart.destroy();
    }

    // Verificar se categoryData contém dados válidos
    if (!categoryData || categoryData.length === 0) {
        console.error(`Dados de categorias não encontrados para o gráfico: ${chartId}`);
        return;
    }

    const labels = categoryData.map(item => item.name);
    const values = categoryData.map(item => item.total);
    const colors = categoryData.map(item => item.hexcolor_category || '#cccccc'); // Usar a cor da categoria ou padrão

    const chartData = {
        labels: labels,
        datasets: [{
            label: title,
            data: values,
            backgroundColor: colors, // Usar as cores das categorias
            borderColor: colors.map(color => color + '80'), // Borda com opacidade
            borderWidth: 1,
        }]
    };

    const config = {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: title
                },
            },
            animation: {
                onComplete: () => {
                    console.log(`${title} carregado com sucesso!`);
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !context.dropped) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                        context.dropped = true;
                    }
                    return delay;
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                },
            },
        },
    };

    if (chartId === 'income-bar-chart') {
        incomeBarChart = new Chart(ctx, config);
    } else if (chartId === 'expense-bar-chart') {
        expenseBarChart = new Chart(ctx, config);
    }
}

// Carregar o mês atual ao carregar a página
document.addEventListener('DOMContentLoaded', () => {
    loadMonth('current');
});

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('uploadCashbookModal');
    console.log(modal); // Deve exibir o elemento do modal no console
    if (!modal) {
        console.error('O modal uploadCashbookModal não foi encontrado no DOM.');
    }
});

let currentStep = 1;

function toggleSteps(direction) {
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const prevButton = document.getElementById('prev-step');
    const nextButton = document.getElementById('next-step');
    const saveButton = document.getElementById('save-button');
    const progressBar = document.getElementById('progress-bar');
    const stepCircles = document.querySelectorAll('.step-circle');

    if (direction === 'next' && currentStep === 1) {
        step1.classList.add('d-none');
        step2.classList.remove('d-none');
        prevButton.disabled = false;
        nextButton.classList.add('d-none');
        saveButton.classList.remove('d-none');
        progressBar.style.width = '100%';
        stepCircles[1].classList.add('active');
        currentStep++;
    } else if (direction === 'prev' && currentStep === 2) {
        step2.classList.add('d-none');
        step1.classList.remove('d-none');
        prevButton.disabled = true;
        nextButton.classList.remove('d-none');
        saveButton.classList.add('d-none');
        progressBar.style.width = '50%';
        stepCircles[1].classList.remove('active');
        currentStep--;
    }
}

function loadEditModal(id) {
    fetch(`/cashbook/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            const form = document.getElementById('editTransactionForm');
            form.action = `/cashbook/${id}`;
            document.getElementById('edit_value').value = data.cashbook.value;
            document.getElementById('edit_description').value = data.cashbook.description;
            document.getElementById('edit_date').value = data.cashbook.date;
            document.getElementById('edit_is_pending').value = data.cashbook.is_pending;
            document.getElementById('edit_category_id').value = data.cashbook.category_id;
            document.getElementById('edit_type_id').value = data.cashbook.type_id;
            document.getElementById('edit_note').value = data.cashbook.note;
            document.getElementById('edit_segment_id').value = data.cashbook.segment_id;
        });
}

function loadDeleteModal(id, description) {
    const form = document.getElementById('deleteTransactionForm');
    form.action = `/cashbook/${id}`;
    document.getElementById('deleteTransactionDescription').textContent = description;
}
</script>

<style>
.list-group-item .d-flex .text-xs {
    margin-right: 10px;
}

.d-flex.align-items-center.justify-content-end {
    margin-left: auto;
    /* Fixa o valor no final */
    font-weight: bold;
    color: var(--bs-success);
}

.d-flex.align-items-center.justify-content-end.text-danger {
    color: var(--bs-danger);
}

/* Alinhamento e espaçamento entre os elementos do item */
.d-flex .btn {
    margin-left: 10px;
}

/* Responsividade */
@media (max-width: 768px) {
    .list-group-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .d-flex.align-items-center.justify-content-end {
        margin-left: 0;
        margin-top: 10px;
    }
}

.progress-container {
    position: relative;
}

.progress {
    height: 5px;
    background-color: #e9ecef;
}

.progress-bar {
    height: 5px;
    background-color: #0d6efd;
}

.step-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
    color: #6c757d;
}

.step-circle.active {
    background-color: #0d6efd;
    color: #fff;
}
</style>
<link rel="stylesheet" href="{{ asset('css/invoice.css') }}">

@endsection