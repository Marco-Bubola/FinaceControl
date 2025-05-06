@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid" style="padding-top: 0; margin-top: 0;">
    @include('message.alert')
    <div class="row col-md-12">
        <!-- Cartão com Informações à Esquerda -->
        <div class="card" style="background-color: transparent; border: none; margin-top: 0;">
            <div class="overflow-hidden position-relative" style="background-color: transparent; border: none; margin-top: 0;">
                <div class="card-body position-relative" style="margin-top: 0; padding-top: 0;">
                    <!-- Informações do Banco -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-wifi p-2 me-3" style="font-size: 28px;"></i>
                            <h5 class="mb-0 me-4">{{ $bank->description }}</h5>
                            <p class="text-sm opacity-8 mb-0 me-3">Titular do Cartão:</p>
                            <h6 class="mb-0 me-3">{{ $bank->name }}</h6>
                            <p class="text-sm opacity-8 mb-0 me-3">Validade:</p>
                            <h6 class="mb-0 me-3">
                                {{ \Carbon\Carbon::parse($bank->start_date)->format('d/m') }} -
                                {{ \Carbon\Carbon::parse($bank->end_date)->format('d/m') }}
                            </h6>
                        </div>
                        <img class="w-10 border-radius-lg shadow-sm" src="../assets/img/logos/mastercard.png"
                            alt="logo">
                    </div>

                    <!-- Navegação de Mês -->
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-wallet me-2"></i> Suas Transações
                            </h6>
                        </div>
                        <div class="col-md-6 d-flex justify-content-between align-items-center">
                            <a href="#" id="previous-month" class="btn btn-outline-secondary btn-sm btn-change-month"
                                data-month="{{ $previousMonth }}">
                                <i class="fas fa-chevron-left me-1"></i> Mês Anterior
                            </a>
                            <h6 id="current-month-title"
                                class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                                {{ $currentMonthName }} ({{ $currentStartDate->format('d/m/Y') }} -
                                {{ $currentEndDate->format('d/m/Y') }})
                            </h6>
                            <a href="#" id="next-month" class="btn btn-outline-secondary btn-sm btn-change-month"
                                data-month="{{ $nextMonth }}">
                                Próximo Mês <i class="fas fa-chevron-right ms-1"></i>
                            </a>
                        </div>

                        <div class="col-md-3 d-flex justify-content-end align-items-center">
                            <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal"
                                data-bs-target="#addTransactionModal">
                                Adicionar
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#uploadModal">
                                Upload
                            </button>
                        </div>
                        <div class="col-md-3">
                                <div class="card bg-dark text-white shadow-sm">
                                    <div class="card-body">
                                        <p class="text-sm mb-1">Preço Total:</p>
                                        <h6 class="text-success font-weight-bold" id="total-invoices">R$
                                            {{ number_format($totalInvoices, 2) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-dark text-white shadow-sm">
                                    <div class="card-body">
                                        <p class="text-sm mb-1">Maior Fatura:</p>
                                        <h6 class="text-danger font-weight-bold" id="highest-invoice">
                                            R$
                                            {{ $highestInvoice ? number_format($highestInvoice->value, 2) : '0,00' }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-dark text-white shadow-sm">
                                    <div class="card-body">
                                        <p class="text-sm mb-1">Menor Fatura:</p>
                                        <h6 class="text-warning font-weight-bold" id="lowest-invoice">
                                            R$
                                            {{ $lowestInvoice ? number_format($lowestInvoice->value, 2) : '0,00' }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-dark text-white shadow-sm">
                                    <div class="card-body">
                                        <p class="text-sm mb-1">Total de Transações:</p>
                                        <h6 class="text-info font-weight-bold" id="total-transactions">
                                            {{ $totalTransactions }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
    <!-- Transações -->
    <div class="row col-md-12  pt-2">
        <div class="col-md-8">
            <div id="transactions-container">
                <!-- Conteúdo das transações será atualizado dinamicamente -->
                @include('invoice.transactions', ['eventsGroupedByMonth' => $eventsGroupedByMonth])
            </div>
        </div>
        <!-- Gráfico à Direita -->
        <div class="col-md-4">
            <!-- Exibe mensagem se não houver dados -->
            <span id="no-data-message" style="display: none;">Sem dados</span>
            <canvas id="updateCategoryChart"></canvas>
            <canvas id="lineChart" class="mt-4"></canvas> <!-- Gráfico de linha -->
        </div>
    </div>
</div>

@include('invoice.uploadInvoice')

<script>
$(document).ready(function() {
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
            success: function(response) {
                console.log('Dados recebidos do backend:', response);

                // Verifica se os dados diários estão presentes
                if (!response.dailyLabels || !response.dailyValues) {
                    console.error('Dados diários não encontrados no response.');
                    return;
                }

                // Atualiza o gráfico de linha com dados reais
                addLineChart({
                    labels: response.dailyLabels, // Dias do mês
                    values: response.dailyValues // Valores reais das faturas por dia
                });

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

                // Atualiza o gráfico de linha com dados reais
                addLineChart({
                    labels: response.dailyLabels, // Dias do mês
                    values: response.dailyValues // Valores reais das faturas por dia
                });
            },
            error: function(xhr, status, error) {
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
                            position: 'bottom',
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
                }
            });
        } catch (error) {
            console.error('Erro ao criar o gráfico:', error);
        }
    }

    // Atualiza o gráfico e os dados do mês
    $('.btn-change-month').on('click', function(e) {
        e.preventDefault();
        const month = $(this).data('month');
        updateMonthData(month);
    });

    // Função para adicionar o gráfico de linha com dados reais
    function addLineChart(dailyData) {
        const ctx = document.getElementById('lineChart');
        if (!ctx) {
            console.error('Elemento canvas para o gráfico de linha não encontrado.');
            return;
        }

        const labels = dailyData.labels; // Dias do mês
        const dataset = dailyData.values; // Valores reais das faturas por dia

        // Gera cores diferentes para cada dia
        const colors = labels.map((_, index) => `hsl(${(index * 360) / labels.length}, 70%, 50%)`);


        const chartData = {
            labels: labels,
            datasets: [{
                label: 'Faturas por Dia',
                data: dataset,
                borderColor: colors,
                backgroundColor: colors.map(color => color.replace('70%',
                '90%')), // Cores mais claras para o fundo
                borderWidth: 2,
                pointRadius: 5, // Tamanho dos pontos
                pointBackgroundColor: colors, // Cor dos pontos
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

        // Verifica se o gráfico já existe antes de destruí-lo
        if (window.lineChart && typeof window.lineChart.destroy === 'function') {
            window.lineChart.destroy();
        }

        // Cria o novo gráfico
        window.lineChart = new Chart(ctx.getContext('2d'), config);
    }



    // Atualiza o gráfico e os dados do mês ao clicar nos botões de navegação
    $('.btn-change-month').on('click', function(e) {
        e.preventDefault();
        const month = $(this).data('month');
        updateMonthData(month);
    });
});
</script>

@include('invoice.create')

<link rel="stylesheet" href="{{ asset('css/invoice.css') }}">

@endsection