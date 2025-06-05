let currentMonth = window.currentMonth || document.querySelector('[data-current-month]')?.dataset.currentMonth || "";
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
            updateMonthData(data, monthNameElement, incomeElement, expenseElement, balanceElement);

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

            // CORRIGIDO: Use transactionsByCategory
            updateTransactions(container, data.transactionsByCategory);

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
function updateTransactions(container, transactionsByCategory) {
    // CORRIGIDO: Recebe já agrupado por categoria
    if (!transactionsByCategory || transactionsByCategory.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                    <div class="animated-icon mb-4">
                        <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                            <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3" fill="#f8fafc"/>
                            <rect x="40" y="60" width="50" height="30" rx="10" fill="#e9f2ff" stroke="#6ea8fe" stroke-width="3"/>
                            <rect x="55" y="40" width="20" height="25" rx="6" fill="#f8fafc" stroke="#6ea8fe" stroke-width="3"/>
                            <rect x="50" y="95" width="30" height="8" rx="4" fill="#6ea8fe" opacity="0.18"/>
                            <ellipse cx="60" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25"/>
                            <ellipse cx="70" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25"/>
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
        return;
    }

    // Renderizar accordion de categorias
    let html = `<div class="accordion" id="accordionCategorias">`;
    transactionsByCategory.forEach((cat, idx) => {
        const collapseId = `collapseCat${cat.category_id}`;
        const headingId = `headingCat${cat.category_id}`;
        const totalReceita = cat.total_receita > 0 ? ` <span class="badge bg-success ms-2">+ R$ ${cat.total_receita.toFixed(2)}</span>` : '';
        const totalDespesa = cat.total_despesa > 0 ? ` <span class="badge bg-danger ms-2">- R$ ${cat.total_despesa.toFixed(2)}</span>` : '';
        html += `
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="${headingId}">
                    <button class="accordion-button collapsed d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}" style="background: linear-gradient(90deg, ${cat.category_hexcolor_category}11 0%, #fff 100%);">
                        <i class="${cat.category_icone}" style="color:${cat.category_hexcolor_category}; font-size:1.3rem;"></i>
                        <span class="fw-bold">${cat.category_name}</span>
                        ${totalReceita}
                        ${totalDespesa}
                    </button>
                </h2>
                <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="${headingId}" data-bs-parent="#accordionCategorias">
                    <div class="accordion-body">
                        <div class="row">
                            ${cat.transactions.map(renderTransactionCard).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    html += `</div>`;
    container.innerHTML = html;
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
                                        data-client_id="${transaction.client_id || ''}"
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
    // Pega o valor do blade se não estiver setado
    if (!currentMonth) {
        const el = document.querySelector('[data-current-month]');
        if (el) currentMonth = el.dataset.currentMonth;
    }
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
            // Corrigir valor para number
            document.getElementById('edit_value').value = Number(data.cashbook.value) || '';
            document.getElementById('edit_description').value = data.cashbook.description;

            // Corrigir formato da data para yyyy-MM-dd
            let date = data.cashbook.date;
            if (date) {
                // Aceita tanto Date quanto string
                let d = new Date(date);
                if (!isNaN(d)) {
                    date = d.toISOString().slice(0, 10);
                } else if (typeof date === 'string' && /^\d{4}-\d{2}-\d{2}/.test(date)) {
                    date = date.slice(0, 10);
                }
            }
            document.getElementById('edit_date').value = date || '';

            document.getElementById('edit_is_pending').value = data.cashbook.is_pending;
            document.getElementById('edit_category_id').value = data.cashbook.category_id;
            document.getElementById('edit_type_id').value = data.cashbook.type_id;
            document.getElementById('edit_note').value = data.cashbook.note;
            document.getElementById('edit_segment_id').value = data.cashbook.segment_id;

            // Preencher o select de cliente corretamente (Choices.js)
            const clientSelect = document.getElementById('edit_client_id');
            if (clientSelect) {
                clientSelect.value = data.cashbook.client_id ?? '';
                // Só tente usar Choices se estiver definido
                if (typeof Choices !== 'undefined' && clientSelect.choices) {
                    clientSelect.choices.setChoiceByValue(String(data.cashbook.client_id ?? ''));
                } else if (typeof Choices !== 'undefined' && window.Choices) {
                    if (!clientSelect.choicesInstance) {
                        clientSelect.choicesInstance = [...document.querySelectorAll('.choices-select')]
                            .map(sel => sel.choices)
                            .find(inst => inst && inst.passedElement.element === clientSelect);
                    }
                    if (clientSelect.choicesInstance) {
                        clientSelect.choicesInstance.setChoiceByValue(String(data.cashbook.client_id ?? ''));
                    }
                }
            }
        });
}

function loadDeleteModal(id, description) {
    const form = document.getElementById('deleteTransactionForm');
    form.action = `/cashbook/${id}`;
    document.getElementById('deleteTransactionDescription').textContent = description;
}
