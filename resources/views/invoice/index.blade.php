@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4 custom-invoice-container">
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
            <!-- Informações do Cartão -->
            <div class="col-md-7 custom-card-info">
                <div class="card bg-gradient-primary shadow-xl custom-card">
                    <div class="overflow-hidden position-relative border-radius-xl"
                        style="background-image: url('../assets/img/curved-images/curved14.jpg');">
                        <span class="mask bg-gradient-dark opacity-8"></span>
                        <div class="card-body position-relative z-index-1 p-4">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-wifi text-white p-2 me-3" style="font-size: 28px;"></i>
                                        <h5 class="text-white mb-0">{{ $bank->description }}</h5>
                                    </div>
                                    <p class="text-white text-sm opacity-8 mb-1">Titular do Cartão</p>
                                    <h6 class="text-white mb-3">{{ $bank->name }}</h6>
                                    <p class="text-white text-sm opacity-8 mb-1">Validade</p>
                                    <h6 class="text-white mb-3">
                                        {{ \Carbon\Carbon::parse($bank->start_date)->format('d/m') }}
                                        -
                                        {{ \Carbon\Carbon::parse($bank->end_date)->format('d/m') }}
                                    </h6>
                                </div>
                                <img class="w-25 border-radius-lg shadow-sm" src="../assets/img/logos/mastercard.png"
                                    alt="logo">
                            </div>
                            <!-- Resumo do Mês -->
                            <div class="mt-4">
                                <p class="text-primary text-sm font-weight-bold mb-2">Resumo do Mês</p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-white text-sm">Preço Total:</span>
                                    <span class="text-success text-sm font-weight-bold" id="total-invoices">R$
                                        {{ number_format($totalInvoices, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-white text-sm">Maior Fatura:</span>
                                    <span class="text-danger text-sm font-weight-bold" id="highest-invoice">
                                        R$ {{ $highestInvoice ? number_format($highestInvoice->value, 2) : '0,00' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-white text-sm">Menor Fatura:</span>
                                    <span class="text-warning text-sm font-weight-bold" id="lowest-invoice">
                                        R$ {{ $lowestInvoice ? number_format($lowestInvoice->value, 2) : '0,00' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white text-sm">Total de Transações:</span>
                                    <span class="text-info text-sm font-weight-bold"
                                        id="total-transactions">{{ $totalTransactions }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-transparent shadow-xl mt-4 custom-chart-card">
                    <div class="overflow-hidden position-relative border-radius-xl"
                        style="background-image: url('../assets/img/curved-images/curved14.jpg');">
                        <span class="mask bg-gradient-dark"></span>
                        <div class="card-body position-relative z-index-1 p-4">
                            <div class="d-flex align-items-center mb-4">
                                <i class="fas fa-chart-pie text-white p-2 me-3" style="font-size: 24px;"></i>
                                <h5 class="text-white mb-0">Gráfico por Categorias</h5>
                            </div>
                            <canvas id="updateCategoryChart" class="bg-white p-3 rounded shadow-sm"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Transações -->
            <div class="col-md-5 custom-transactions">
                <div class="card h-100 mb-4 custom-transactions-card">
                    <div class="card-header pb-0 px-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-0 text-primary">
                                    <i class="fas fa-wallet me-2"></i> Suas Transações
                                </h6>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end align-items-center">
                                <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal"
                                    data-bs-target="#addTransactionModal">
                                    Adicionar
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#uploadModal">
                                    Upload
                                </button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <a href="#" id="previous-month" class="btn btn-outline-secondary btn-sm btn-change-month"
                                    data-month="{{ $previousMonth }}">
                                    <i class="fas fa-chevron-left me-1"></i> Mês Anterior
                                </a>
                                <a href="#" id="next-month" class="btn btn-outline-secondary btn-sm btn-change-month"
                                    data-month="{{ $nextMonth }}">
                                    Próximo Mês <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <h6 id="current-month-title" class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                            {{ $currentMonthName }} ({{ $currentStartDate->format('d/m/Y') }} -
                            {{ $currentEndDate->format('d/m/Y') }})
                        </h6>
                        <div id="transactions-container">
                            <!-- Conteúdo das transações será atualizado dinamicamente -->
                            @include('invoice.transactions', ['eventsGroupedByMonth' => $eventsGroupedByMonth])
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('invoice.uploadInvoice')

    <script>
        $(document).ready(function () {
            // Dados de categorias enviados pelo backend
            var initialCategories = @json($categoriesData);


            // Atualiza o gráfico na carga inicial
            updateCategoryChart(initialCategories);

            // Dados dos eventos passados do backend para o JavaScript
            var eventsData = @json($eventsDetailed); // Usando os detalhes das faturas

            // Inicialização do calendário
            $('#calendar').fullCalendar({
                locale: 'pt-br', // Configuração de idioma para português
                editable: false,
                events: eventsData, // Carrega todos os eventos diretamente
                displayEventTime: false, // Oculta o horário dos eventos
                selectable: true,
                selectHelper: true,

                // Tradução dos dias da semana
                dayNames: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],

                // Configuração para exibir dias fora do mês atual e adicionar mais uma semana
                showNonCurrentDates: true, // Mostra os dias fora do mês atual
                fixedWeekCount: true, // Garante que o calendário sempre tenha 6 semanas

                // Renderização personalizada dos eventos
                eventRender: function (event, element) {
                    // Define se o evento é de dia inteiro
                    event.allDay = event.allDay === 'true';

                    // Adiciona detalhes ao título do evento com estrutura personalizada
                    const detalhesEvento = `
                            <div class="detalhes-evento">
                                <div class="titulo-evento" style="font-size: 16px; font-weight: bold; color: #343a40;">
                                    ${event.title} <!-- Título do evento -->
                                </div>
                                <div class="categoria-evento" style="font-weight: bold; color: #007bff;">
                                    Categoria: ${event.category}
                                </div>
                                <div class="parcelas-evento" style="font-size: 12px; color: #6c757d;">
                                    Parcelas: ${event.installments}
                                </div>
                                <div class="valor-evento" style="font-size: 14px; font-weight: bold; color: ${event.value < 0 ? '#dc3545' : '#28a745'};">
                                    Valor: ${event.value < 0 ? '-' : '+'} R$ ${Math.abs(event.value).toFixed(2)}
                                </div>
                            </div>
                            `;

                    // Substitui o conteúdo do título do evento
                    element.find('.fc-title').html(detalhesEvento);

                    // Adiciona um estilo de borda ao evento
                    element.css({
                        border: '1px solid #007bff',
                        borderRadius: '5px',
                        padding: '5px',
                        backgroundColor: '#f8f9fa'
                    });
                },

                // Configuração para garantir que o calendário respeite o intervalo de meses
                viewRender: function (view) {
                    const start = moment(view.start).format('YYYY-MM-DD');
                    const end = moment(view.end).subtract(1, 'days').format('YYYY-MM-DD');

                    // Atualiza os eventos exibidos com base no intervalo do calendário
                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar('addEventSource', eventsData.filter(event => {
                        const eventDate = moment(event.start).format('YYYY-MM-DD');
                        return eventDate >= start && eventDate <= end;
                    }));
                },

                // Botões e textos traduzidos
                buttonText: {
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia',
                    list: 'Lista'
                },
                allDayText: 'Dia Inteiro',
                noEventsMessage: 'Nenhum evento encontrado',
            });

            // Função para atualizar os dados do mês, incluindo o gráfico de categorias
            function updateMonthData(month) {
                const bankId = "{{ $bank->id_bank }}";

                $.ajax({
                    url: "{{ route('invoices.index') }}",
                    method: "GET",
                    data: {
                        bank_id: bankId,
                        month: month
                    },
                    success: function (response) {
                        // Atualiza as transações
                        $('#transactions-container').html(response.transactionsHtml);

                        // Atualiza o calendário
                        $('#calendar').fullCalendar('removeEvents');
                        $('#calendar').fullCalendar('addEventSource', response.eventsDetailed);

                        // Atualiza os resumos
                        $('#total-invoices').text(`R$ ${response.totalInvoices.toFixed(2)}`);
                        $('#highest-invoice').text(`R$ ${response.highestInvoice}`);
                        $('#lowest-invoice').text(`R$ ${response.lowestInvoice}`);
                        $('#total-transactions').text(response.totalTransactions);

                        // Atualiza o título do mês
                        $('#current-month-title').text(response.currentMonthTitle);

                        // Atualiza os botões de navegação
                        $('#previous-month').data('month', response.previousMonth);
                        $('#next-month').data('month', response.nextMonth);

                        // Atualiza o gráfico de categorias
                        updateCategoryChart(response.categories);
                    },
                    error: function (xhr, status, error) {
                        console.error('Erro na requisição AJAX:', xhr.responseText);
                        alert('Erro ao carregar os dados do mês. Verifique os logs para mais detalhes.');
                    }
                });
            }

            // Função para atualizar o gráfico de categorias
            function updateCategoryChart(categories) {

                // Converte o objeto de categorias em um array, se necessário
                if (categories && typeof categories === 'object' && !Array.isArray(categories)) {
                    categories = Object.values(categories);
                }

                // Verifica se os dados são válidos
                if (!categories || !Array.isArray(categories) || categories.length === 0) {
                    return;
                }

                const ctx = document.getElementById('updateCategoryChart');
                if (!ctx) {
                    console.error('Elemento canvas para o gráfico não encontrado.');
                    return;
                }

                // Se já existir um gráfico, destrua-o antes de criar um novo
                if (window.categoryChart) {
                    window.categoryChart.destroy();
                }

                // Processa os dados para o gráfico
                const categoryData = categories.map(category => category.value);
                const labels = categories.map(category => category.label);


                // Verifica se os dados estão corretos
                if (categoryData.length === 0 || labels.length === 0) {
                    return;
                }

                // Cria o gráfico
                try {
                    window.categoryChart = new Chart(ctx.getContext('2d'), {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: categoryData,
                                backgroundColor: [
                                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                                ],
                                hoverBackgroundColor: [
                                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (tooltipItem) {
                                            const label = tooltipItem.label || '';
                                            const value = tooltipItem.raw || 0;
                                            return `${label}: R$ ${value.toFixed(2)}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Erro ao criar o gráfico:', error);
                }
            }

            // Botões de navegação de mês
            $('.btn-change-month').on('click', function (e) {
                e.preventDefault();
                const month = $(this).data('month');
                updateMonthData(month);
            });
        });


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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Modal para Adicionar Transferência -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionModalLabel">Adicionar Nova Transferência</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('invoices.store') }}">
                        @csrf
                        <!-- Campo oculto para enviar o id_bank -->
                        <input type="hidden" name="id_bank" value="{{ $bank->id_bank }}">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Descrição</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" required>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">Valor</label>
                                <input type="number" class="form-control @error('value') is-invalid @enderror" id="value"
                                    name="value" step="0.01" required>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="installments" class="form-label">Parcelas</label>
                                <input type="number" class="form-control @error('installments') is-invalid @enderror"
                                    id="installments" name="installments" required>
                                @error('installments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Categoria</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="" disabled selected>Selecione uma categoria</option>
                                    @forelse ($categories as $category)
                                        <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                    @empty
                                        <option value="" disabled>Nenhuma categoria disponível</option>
                                    @endforelse
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="invoice_date" class="form-label">Data da Transferência</label>
                                <input type="date" class="form-control @error('invoice_date') is-invalid @enderror"
                                    id="invoice_date" name="invoice_date" required>
                                @error('invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar Transferência</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
    </style>

    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
@endsection
