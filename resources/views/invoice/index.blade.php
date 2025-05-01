@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4 custom-invoice-container">
        @include('message.alert')
        <div class="row">

            <div class="row col-md-12  min-vh-85">
                <!-- Cartão com Informações à Esquerda -->
                <div class="col-md-6 custom-card">
                    <div class="card bg-gradient-primary shadow-xl custom-card">
                        <div class="overflow-hidden position-relative border-radius-xl custom-card-background">
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
                                            {{ \Carbon\Carbon::parse($bank->start_date)->format('d/m') }} -
                                            {{ \Carbon\Carbon::parse($bank->end_date)->format('d/m') }}
                                        </h6>
                                    </div>
                                    <img class="w-25 border-radius-lg shadow-sm" src="../assets/img/logos/mastercard.png"
                                        alt="logo">
                                </div>
                                <div class="row mt-3">
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
                                        <a href="#" id="previous-month"
                                            class="btn btn-outline-secondary btn-sm btn-change-month"
                                            data-month="{{ $previousMonth }}">
                                            <i class="fas fa-chevron-left me-1"></i> Mês Anterior
                                        </a>
                                        <a href="#" id="next-month"
                                            class="btn btn-outline-secondary btn-sm btn-change-month"
                                            data-month="{{ $nextMonth }}">
                                            Próximo Mês <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                                <h6 id="current-month-title"
                                    class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                                    {{ $currentMonthName }} ({{ $currentStartDate->format('d/m/Y') }} -
                                    {{ $currentEndDate->format('d/m/Y') }})
                                </h6>
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
                </div>


                <!-- Gráfico à Direita -->
                <div class="col-md-6 custom-chart">
                    <div class="card bg-transparent shadow-xl custom-chart-card">
                        <div class="overflow-hidden position-relative border-radius-xl custom-card-background">
                            <span class="mask bg-gradient-dark"></span>
                            <div class="card-body position-relative z-index-1 p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <i class="fas fa-chart-pie text-white p-2 me-3" style="font-size: 24px;"></i>
                                    <h5 class="text-white mb-0">Gráfico por Categorias</h5>
                                </div>
                                <!-- Canvas do Gráfico -->
                                <div class="d-flex justify-content-center align-items-center" id="chart-container">
                                    <!-- Exibe mensagem se não houver dados -->
                                    <span class="text-white" id="no-data-message" style="display: none;">Sem dados</span>
                                    <canvas id="updateCategoryChart" class="bg-white p-3 rounded shadow-sm"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Transações -->
            <div class="col-md-12 custom-transactions pt-4 p-3">
                <div class="card h-100 mb-4 custom-transactions-card ">

                    <div class="card-body ">

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

                const ctx = document.getElementById('updateCategoryChart');
                if (!ctx) {
                    console.error('Elemento canvas para o gráfico não encontrado.');
                    return;
                }

                // Se não houver dados, mostra a mensagem "Sem dados" e cria o gráfico com valor zero
                if (!categories || categories.length === 0) {


                    // Criar gráfico com valor zero e cor vermelha
                    categories = [{
                        label: 'Nenhuma Categoria',
                        value: 0
                    }]; // Categoria fictícia com valor zero
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
                        type: 'pie',
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

            // Atualiza o gráfico e os dados do mês
            $('.btn-change-month').on('click', function (e) {
                e.preventDefault();
                const month = $(this).data('month');
                updateMonthData(month);
            });
        });
    </script>

    @include('invoice.create')
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
@endsection
