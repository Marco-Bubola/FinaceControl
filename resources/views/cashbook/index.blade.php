@extends('layouts.user_type.auth')

@section('content')

    <div class="container-fluid py-2">
          <!-- Exibir erros de validação -->
          @if ($errors->any())
            <div id="error-message" class="alert alert-danger alert-dismissible fade show custom-error-message" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="closeAlert('error-message')"></button>
                <div id="error-timer" class="alert-timer">30s</div>
            </div>
        @endif

        <!-- Exibir sucesso -->
        @if (session('success'))
            <div id="success-message" class="alert alert-success alert-dismissible fade show custom-success-message"
                role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="closeAlert('success-message')"></button>
                <div id="success-timer" class="alert-timer">30s</div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card h-100 mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-3 justify-content-center d-flex">
                                <h6 class="mb-0">Suas Transações:</h6>
                            </div>
                            <div class="col-md-3 justify-content-center d-flex">
                                <h6 class="mb-0"> <span id="month-name">{{ $monthName ?? '' }}</span></h6>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end align-items-center">
                                <button class="btn btn-primary me-2" onclick="loadMonth('previous')">Mês Anterior</button>
                                <button class="btn btn-primary me-2" onclick="loadMonth('next')">Próximo Mês</button>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTransactionModal">Adicionar Transação</button>
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#uploadCashbookModal">Upload de Transações</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4 p-4">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="text-sm">Receitas</h6>
                                        <span class="text-success">+ R$ {{ number_format(abs($totals['income']), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="text-sm">Despesas</h6>
                                        <span class="text-danger">- R$
                                            {{ number_format(abs($totals['expense']), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
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
                        <div id="transactions-container">
                            @if($transactions->isEmpty())
                                <div class="text-center">
                                    <h6 class="text-muted">Nenhuma transação encontrada para o mês selecionado.</h6>
                                </div>
                            @else
                                <!-- Conteúdo do mês será carregado dinamicamente -->
                            @endif
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

        function loadMonth(direction) {
            const container = document.getElementById('transactions-container');
            const monthNameElement = document.getElementById('month-name');
            const incomeElement = document.querySelector('.text-success');
            const expenseElement = document.querySelector('.text-danger');
            const balanceElement = document.querySelector('.text-lg.font-weight-bold.text-success, .text-lg.font-weight-bold.text-danger');

            fetch(`/cashbook/month/${direction}?currentMonth=${currentMonth}`)
                .then(response => response.json())
                .then(data => {
                    currentMonth = data.currentMonth;
                    monthNameElement.textContent = data.monthName; // Atualizar o nome do mês

                    // Atualizar os valores de receitas, despesas e saldo
                    incomeElement.textContent = `+ R$ ${data.totals.income.toFixed(2)}`; // Sem parseFloat para evitar erros de formatação
                    expenseElement.textContent = `- R$ ${data.totals.expense.toFixed(2)}`; // Mesmo para despesas
                    balanceElement.textContent = `${data.totals.balance >= 0 ? '+' : '-'} R$ ${Math.abs(data.totals.balance).toFixed(2)}`; // Saldo, mantendo o valor correto
                    balanceElement.className = `text-lg font-weight-bold text-${data.totals.balance >= 0 ? 'success' : 'danger'}`;

                    // Verificar se há transações para o mês selecionado
                    if (data.transactionsByDay && Object.keys(data.transactionsByDay).length > 0) {
                        container.innerHTML = `
                    ${Object.keys(data.transactionsByDay).map(day => `
                        <div class="mb-4">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">${day}</h6>
                            <ul class="list-group">
                                ${data.transactionsByDay[day].map(transaction => `
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-icon-only btn-rounded btn-outline-${transaction.type_id == 2 ? 'danger' : 'success'} mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                                <i class="fas fa-arrow-${transaction.type_id == 2 ? 'down' : 'up'}"></i>
                                            </button>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">${transaction.description}</h6>
                                                <span class="text-xs">Data: ${transaction.time}</span>
                                                <span class="text-xs">Categoria: ${transaction.category_name || 'N/A'}</span>
                                                <span class="text-xs">Nota: ${transaction.note || 'Sem nota'}</span>
                                                <span class="text-xs">Segmento: ${transaction.segment_name || 'Nenhum'}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center text-${transaction.type_id == 2 ? 'danger' : 'success'} text-gradient text-sm font-weight-bold">
                                            ${transaction.type_id == 2 ? '-' : '+'} $ ${Math.abs(transaction.value).toFixed(2)}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-icon-only btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editTransactionModal" onclick="loadEditModal(${transaction.id})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-icon-only btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteTransactionModal" onclick="loadDeleteModal(${transaction.id}, '${transaction.description}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                    `).join('')}
                `;
                    } else {
                        container.innerHTML = `
                    <div class="text-center">
                        <h6 class="text-muted">Nenhuma transação encontrada para o mês selecionado.</h6>
                    </div>
                `;
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar os dados do mês:', error);
                });
        }

        // Carregar o mês atual ao carregar a página
        document.addEventListener('DOMContentLoaded', () => {
            loadMonth('current');
        });

        document.addEventListener('DOMContentLoaded', function () {
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

        
    document.addEventListener("DOMContentLoaded", function() {
        // Função para iniciar o timer e ocultar a mensagem após 30 segundos
        function startTimer(messageId, timerId) {
            let timerValue = 30;
            const timerElement = document.getElementById(timerId);
            const messageElement = document.getElementById(messageId);

            // Atualiza o temporizador a cada segundo
            const interval = setInterval(function() {
                if (timerValue > 0) {
                    timerElement.innerHTML = `${timerValue--}s`;
                } else {
                    clearInterval(interval);
                    // Fecha a mensagem após 30 segundos e recarrega a página
                    messageElement.classList.remove('show');
                    messageElement.classList.add('fade');
                    location.reload(); // Recarregar a página após 30 segundos
                }
            }, 1000); // Atualiza a cada segundo
        }

        // Iniciar o timer para a mensagem de erro (se existir)
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            startTimer('error-message', 'error-timer');
        }

        // Iniciar o timer para a mensagem de sucesso (se existir)
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            startTimer('success-message', 'success-timer');
        }

        // Configuração para mostrar que a página voltou ao estado original
        const closeButton = document.querySelectorAll('.btn-close');
        closeButton.forEach(button => {
            button.addEventListener('click', function() {
                // Resetando o timer de 30 segundos e voltando a página ao estado original
                document.getElementById('error-message')?.classList.remove('show');
                document.getElementById('success-message')?.classList.remove('show');
            });
        });
    });

    // Função para fechar o alerta ao clicar no "X"
    function closeAlert(messageId) {
        document.getElementById(messageId).classList.remove('show');
        document.getElementById(messageId).classList.add('fade');
    }
    </script>

    <style>
        .alert-timer {
        position: absolute;
        top: 10px;
        right: 40px;
        background-color: #ff9800;
        color: white;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-top: 5px;
    }

    .alert-dismissible .btn-close {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        color: #fff;
        background: transparent;
        border: none;
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

@endsection