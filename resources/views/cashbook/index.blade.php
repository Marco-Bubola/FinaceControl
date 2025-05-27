@extends('layouts.user_type.auth')

@section('content')

    @include('message.alert')

    <div class="row" style=" border: none;">
        <div class="col-md-12">
            <div class="card h-100 mb-4" style="background-color: transparent; border: none;">
                <!-- HEADER EM UMA LINHA SÓ, ESTILIZADO E RESPONSIVO -->
                <div class="card-header py-4" style="background-color: transparent; border-bottom: none;">
                    <div class="row align-items-center g-3">
                        <div class="col-12">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 flex-lg-nowrap">
                                <!-- Ícone e Título -->
                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <i class="fas fa-wallet text-primary" style="font-size: 2.2rem;"></i>
                                    <h2 class="mb-0 fw-bold" style="letter-spacing: 0.01em; font-size: 1.7rem;">Suas Transações</h2>
                                </div>
                                <!-- Navegação de Mês com Cards Estilizados -->
                                <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                                    <!-- Card Mês Anterior -->
                                    <div id="prev-month-card" class="month-nav-card" onclick="loadMonth('previous')">
                                        <span class="month-nav-icon bg-white">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                        <div class="month-nav-content">
                                            <span class="month-nav-title" id="prev-month-name">...</span>
                                            <span class="month-nav-label">Saldo</span>
                                            <span class="month-nav-balance" id="prev-month-balance">...</span>
                                        </div>
                                    </div>
                                    <!-- Card Mês Atual -->
                                    <div class="month-nav-card active">
                                        <span class="month-nav-icon" style="background: #bae6fd;">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <div class="month-nav-content">
                                            <span class="month-nav-title" id="month-name">{{ $monthName ?? '' }}</span>
                                            <span class="month-nav-label">Saldo</span>
                                            <span class="month-nav-balance" id="month-balance">
                                                {{ $totals['balance'] >= 0 ? '+' : '-' }} R$ {{ number_format(abs($totals['balance']), 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Card Próximo Mês -->
                                    <div id="next-month-card" class="month-nav-card" onclick="loadMonth('next')">
                                       
                                        <div class="month-nav-content">
                                            <span class="month-nav-title" id="next-month-name">...</span>
                                            <span class="month-nav-label">Saldo</span>
                                            <span class="month-nav-balance" id="next-month-balance">...</span>
                                        </div>
                                         <span class="month-nav-icon bg-white">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- Botões de Ação -->
                                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                    <button class="btn btn-outline-success d-flex align-items-center px-4 py-2"
                                        data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                                        <i class="fas fa-plus me-2"></i> Adicionar
                                    </button>
                                    <button class="btn btn-outline-info d-flex align-items-center px-4 py-2"
                                        data-bs-toggle="modal" data-bs-target="#uploadCashbookModal">
                                        <i class="fas fa-upload me-2"></i> Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4 p-4">


                    <div class="row">
                        <!-- Coluna da Esquerda - Transações -->
                        <div class="col-lg-8" id="transactions-container">
                            @if($transactions->isEmpty())
                            <div class="col-12">
                                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                    <div class="animated-icon mb-4">
                                        <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                                            <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3"
                                                fill="#f8fafc" />
                                            <!-- Ícone de carteira triste -->
                                            <rect x="40" y="60" width="50" height="30" rx="10" fill="#e9f2ff"
                                                stroke="#6ea8fe" stroke-width="3" />
                                            <rect x="55" y="40" width="20" height="25" rx="6" fill="#f8fafc"
                                                stroke="#6ea8fe" stroke-width="3" />
                                            <rect x="50" y="95" width="30" height="8" rx="4" fill="#6ea8fe"
                                                opacity="0.18" />
                                            <ellipse cx="60" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25" />
                                            <ellipse cx="70" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25" />
                                            <!-- Boca triste -->
                                            <path d="M60 85 Q65 80 70 85" stroke="#6ea8fe" stroke-width="2"
                                                fill="none" />
                                        </svg>
                                    </div>
                                    <h2 class="fw-bold mb-3 text-primary"
                                        style="font-size:2.2rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                                        Nenhuma Transação Encontrada
                                    </h2>
                                    <p class="mb-4 text-secondary text-center"
                                        style="max-width: 480px; font-size:1.15rem; font-weight:500; line-height:1.6;">
                                        <span style="color:#0d6efd; font-weight:700;">Ops!</span> Não encontramos
                                        transações para o mês selecionado.<br>
                                        <span style="color:#6ea8fe;">Adicione uma nova transação</span> para começar a
                                        registrar seu fluxo financeiro!
                                    </p>
                                </div>
                            </div>
                            @else
                            <!-- Conteúdo do mês será carregado dinamicamente -->
                            @endif
                        </div>

                        <!-- Coluna da Direita - Gráfico -->
                        <div class="col-lg-4" id="chart-container">
                            <div class="row mb-4 justify-content-center">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between gap-3 flex-wrap">
                                        <!-- Card Receitas -->
                                        <div class="card shadow-sm border-0 flex-fill"
                                            style="min-width: 120px; background: linear-gradient(135deg, #e6f9ec 60%, #f8fafc 100%);">
                                            <div class="card-body text-center p-3">
                                                <div class="mb-2">
                                                    <i class="fas fa-arrow-up text-success" style="font-size: 1.6rem;"></i>
                                                </div>
                                                <h6 class="text-sm fw-bold mb-1 text-success">Receitas</h6>
                                                <div class="fs-5 fw-bold text-success mb-0" id="income-value">
                                                    + R$ {{ number_format(abs($totals['income']), 2) }}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Card Despesas -->
                                        <div class="card shadow-sm border-0 flex-fill"
                                            style="min-width: 120px; background: linear-gradient(135deg, #ffeaea 60%, #f8fafc 100%);">
                                            <div class="card-body text-center p-3">
                                                <div class="mb-2">
                                                    <i class="fas fa-arrow-down text-danger" style="font-size: 1.6rem;"></i>
                                                </div>
                                                <h6 class="text-sm fw-bold mb-1 text-danger">Despesas</h6>
                                                <div class="fs-5 fw-bold text-danger mb-0" id="expense-value">
                                                    - R$ {{ number_format(abs($totals['expense']), 2) }}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Card Saldo -->
                                        <div class="card shadow-sm border-0 flex-fill"
                                            style="min-width: 120px; background: linear-gradient(135deg, #eaf6ff 60%, #f8fafc 100%);">
                                            <div class="card-body text-center p-3">
                                                <div class="mb-2">
                                                    <i class="fas fa-wallet text-{{ $totals['balance'] >= 0 ? 'success' : 'danger' }}"
                                                        style="font-size: 1.6rem;"></i>
                                                </div>
                                                <h6 class="text-sm fw-bold mb-1 text-{{ $totals['balance'] >= 0 ? 'success' : 'danger' }}">
                                                    Saldo
                                                </h6>
                                                <div class="fs-5 fw-bold text-{{ $totals['balance'] >= 0 ? 'success' : 'danger' }} mb-0" id="balance-value">
                                                    {{ $totals['balance'] >= 0 ? '+' : '-' }} R$ {{ number_format(abs($totals['balance']), 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Gráfico de pizza será inserido aqui -->
                            <div class="bg-white rounded shadow-sm p-3 mb-4">
                                <canvas id="transaction-pie-chart" width="400" height="400"></canvas>
                            </div>
                            <div class="mt-4 bg-white rounded shadow-sm p-3">
                                <h6 class="text-center mb-3">Receitas por Categoria</h6>
                                <canvas id="income-bar-chart" width="400" height="400"></canvas>
                            </div>
                            <div class="mt-4 bg-white rounded shadow-sm p-3">
                                <h6 class="text-center mb-3">Despesas por Categoria</h6>
                                <canvas id="expense-bar-chart" width="400" height="400"></canvas>
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
    const incomeElement = document.getElementById('income-value');
    const expenseElement = document.getElementById('expense-value');
    const balanceElement = document.getElementById('balance-value');
    const prevMonthName = document.getElementById('prev-month-name');
    const prevMonthBalance = document.getElementById('prev-month-balance');
    const nextMonthName = document.getElementById('next-month-name');
    const nextMonthBalance = document.getElementById('next-month-balance');

    fetch(`/cashbook/month/${direction}?currentMonth=${currentMonth}`)
        .then(response => response.json())
        .then(data => {
            // Atualizar o mês atual e os valores exibidos
            updateMonthData(data, monthNameElement, incomeElement, expenseElement, balanceElement);

            // Atualizar os cards dos meses anterior e próximo
            if (data.prevMonth) {
                prevMonthName.textContent = data.prevMonth.name;
                prevMonthBalance.textContent = `${data.prevMonth.balance >= 0 ? '+' : '-'} R$ ${Math.abs(data.prevMonth.balance).toFixed(2)}`;
                prevMonthBalance.className = `fw-bold text-${data.prevMonth.balance >= 0 ? 'success' : 'danger'}`;
            } else {
                prevMonthName.textContent = '---';
                prevMonthBalance.textContent = '---';
                prevMonthBalance.className = 'fw-bold text-secondary';
            }
            if (data.nextMonth) {
                nextMonthName.textContent = data.nextMonth.name;
                nextMonthBalance.textContent = `${data.nextMonth.balance >= 0 ? '+' : '-'} R$ ${Math.abs(data.nextMonth.balance).toFixed(2)}`;
                nextMonthBalance.className = `fw-bold text-${data.nextMonth.balance >= 0 ? 'success' : 'danger'}`;
            } else {
                nextMonthName.textContent = '---';
                nextMonthBalance.textContent = '---';
                nextMonthBalance.className = 'fw-bold text-secondary';
            }

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
    balanceElement.className = `fs-5 fw-bold text-${data.totals.balance >= 0 ? 'success' : 'danger'} mb-0`;

    // Atualizar saldo do card central de navegação de mês
    const monthBalanceElement = document.getElementById('month-balance');
    if (monthBalanceElement) {
        monthBalanceElement.textContent = `${data.totals.balance >= 0 ? '+' : '-'} R$ ${Math.abs(data.totals.balance).toFixed(2)}`;
        monthBalanceElement.className = `fw-bold text-${data.totals.balance >= 0 ? 'success' : 'danger'}`;
    }

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
    const maxVisible = 24;

    if (allTransactions.length > 0) {
        // Divida as transações em visíveis e ocultas
        const visibleTransactions = allTransactions.slice(0, maxVisible);
        const hiddenTransactions = allTransactions.slice(maxVisible);

        let html = `
            <div class="row" id="visible-transactions">
                ${visibleTransactions.map(transaction => renderTransactionCard(transaction)).join('')}
            </div>
        `;

        if (hiddenTransactions.length > 0) {
            html += `
                <div class="row d-none" id="hidden-transactions">
                    ${hiddenTransactions.map(transaction => renderTransactionCard(transaction)).join('')}
                </div>
                <div class="text-center my-3">
                    <button id="show-more-btn" class="btn btn-outline-primary">
                        Mostrar mais
                    </button>
                </div>
            `;
        }

        container.innerHTML = html;

        // Lógica do botão "Mostrar mais/menos"
        if (hiddenTransactions.length > 0) {
            const showMoreBtn = document.getElementById('show-more-btn');
            const hiddenRow = document.getElementById('hidden-transactions');
            let expanded = false;
            showMoreBtn.addEventListener('click', function () {
                expanded = !expanded;
                if (expanded) {
                    hiddenRow.classList.remove('d-none');
                    showMoreBtn.textContent = 'Mostrar menos';
                } else {
                    hiddenRow.classList.add('d-none');
                    showMoreBtn.textContent = 'Mostrar mais';
                    // Scroll para o botão ao recolher
                    showMoreBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }  } else {
        container.innerHTML = `
            <div class="col-12">
                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                    <div class="animated-icon mb-4">
                        <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                            <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3" fill="#f8fafc"/>
                            <!-- Ícone de carteira triste -->
                            <rect x="40" y="60" width="50" height="30" rx="10" fill="#e9f2ff" stroke="#6ea8fe" stroke-width="3"/>
                            <rect x="55" y="40" width="20" height="25" rx="6" fill="#f8fafc" stroke="#6ea8fe" stroke-width="3"/>
                            <rect x="50" y="95" width="30" height="8" rx="4" fill="#6ea8fe" opacity="0.18"/>
                            <ellipse cx="60" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25"/>
                            <ellipse cx="70" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25"/>
                            <!-- Boca triste -->
                            <path d="M60 85 Q65 80 70 85" stroke="#6ea8fe" stroke-width="2" fill="none"/>
                        </svg>
                    </div>
                    <h2 class="fw-bold mb-3 text-primary" style="font-size:2.2rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                        Nenhuma Transação Encontrada
                    </h2>
                    <p class="mb-4 text-secondary text-center" style="max-width: 480px; font-size:1.15rem; font-weight:500; line-height:1.6;">
                        <span style="color:#0d6efd; font-weight:700;">Ops!</span> Não encontramos transações para o mês selecionado.<br>
                        <span style="color:#6ea8fe;">Adicione uma nova transação</span> para começar a registrar seu fluxo financeiro!
                    </p>
                </div>
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
        <div class="col-md-4 mb-2">
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
                                        onclick="loadEditModal(${transaction.id}); event.stopPropagation();">
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

/* Estilo para os cards de navegação de mês - NOVO */
.month-nav-card {
    min-width: 220px;
    max-width: 260px;
    height: 90px;
    border-radius: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    transition: box-shadow 0.2s, transform 0.15s;
    cursor: pointer;
    padding: 0.5rem 1.2rem;
    background: #fff;
    border: none;
}
.month-nav-card.active {
    background: linear-gradient(90deg, #eaf6ff 60%, #f8fafc 100%);
    border: 2px solid #0ea5e9;
    box-shadow: 0 4px 18px rgba(14,165,233,0.10);
    cursor: default;
}
.month-nav-card:hover:not(.active) {
    box-shadow: 0 4px 18px rgba(14,165,233,0.13);
    transform: translateY(-2px) scale(1.03);
}
.month-nav-icon {
    font-size: 2.2rem;
    margin-right: 1.1rem;
    color: #0ea5e9;
    flex-shrink: 0;
    background: #e0f2fe;
    border-radius: 50%;
    padding: 0.4rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
}
.month-nav-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    flex: 1;
}
.month-nav-title {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.1rem;
}
.month-nav-label {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 0.1rem;
}
.month-nav-balance {
    font-weight: 700;
    font-size: 1.05rem;
    color: #0ea5e9;
}
/* Responsividade extra para o header em linha única */
@media (max-width: 1200px) {
    .month-nav-card {
        min-width: 180px;
        max-width: 220px;
        height: 80px;
        font-size: 0.95rem;
    }
    .month-nav-icon {
        font-size: 1.7rem;
        width: 38px;
        height: 38px;
        margin-right: 0.7rem;
    }
    .month-nav-title, .month-nav-label, .month-nav-balance {
        font-size: 0.95rem;
    }
}
@media (max-width: 992px) {
    .d-flex.flex-wrap.align-items-center.justify-content-between.gap-3.flex-lg-nowrap.flex-wrap {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 1.2rem !important;
    }
    .month-nav-card {
        min-width: 100%;
        max-width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
<link rel="stylesheet" href="{{ asset('css/invoice.css') }}">

@endsection
