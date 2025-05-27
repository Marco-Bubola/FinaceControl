@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid px-2 px-md-4 py-3" style="background: #f8fafc; min-height: 100vh;">
    @include('message.alert')

    <!-- HEADER: Banco + Navegação de Mês + Resumo -->
    <div class="mx-auto" >
        <div class="rounded-4 ">
            <!-- Banco e ações -->
            <div class="d-flex flex-wrap align-items-center gap-3 ">
                <span class="d-flex align-items-center justify-content-center rounded-circle" style="background: #e7f1ff; width: 54px; height: 54px;">
                    <i class="fas fa-wifi text-primary" style="font-size: 2rem;"></i>
                </span>
                <div class="flex-grow-1">
                    <div class="fw-bold fs-5 text-dark mb-1 d-flex align-items-center gap-2">
                        {{ $bank->description }}
                        <img class="ms-2" src="../assets/img/logos/mastercard.png" alt="logo" style="height: 32px; filter: drop-shadow(0 2px 6px rgba(13,110,253,0.10));">
                    </div>
                    <div class="text-secondary small d-flex flex-wrap gap-3">
                        <span><i class="bi bi-person-badge me-1"></i><strong>Titular:</strong> {{ $bank->name }}</span>
                        <span><i class="bi bi-calendar-range me-1"></i><strong>Validade:</strong> {{ \Carbon\Carbon::parse($bank->start_date)->format('d/m') }} - {{ \Carbon\Carbon::parse($bank->end_date)->format('d/m') }}</span>
                    </div>
                </div>
                
                <div class="month-cards-group d-flex flex-wrap gap-4 justify-content-center">
                    <!-- Card Mês Anterior -->
                    <div class="month-card card border-0 shadow-sm bg-white text-center" id="card-previous-month">
                        <div class="card-body py-4 px-3">
                            <div class="mb-1 text-muted small fw-semibold">Mês Anterior</div>
                            <h5 class="card-title mb-3 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-chevron-left fa-lg text-primary"></i>
                                <span id="previous-month-name" class="fw-bold">{{ $previousMonthName }}</span>
                            </h5>
                            <a href="#" id="previous-month"
                                class="btn btn-outline-primary btn-change-month rounded-pill px-4 py-2 fw-semibold"
                                data-month="{{ $previousMonth }}">
                                <i class="fas fa-eye me-1"></i>
                                Ver <span id="previous-month-btn-name">{{ $previousMonthName }}</span>
                            </a>
                        </div>
                    </div>
                    <!-- Card Mês Atual -->
                    <div class="month-card card border-0 shadow bg-gradient text-white text-center month-card-current" id="card-current-month">
                        <div class="card-body py-4 px-3">
                            <div class="mb-1 small fw-semibold" style="opacity:0.92;">Mês Atual</div>
                            <h5 class="card-title mb-3 d-flex align-items-center justify-content-center gap-2" id="current-month-title">
                                <i class="bi bi-calendar3 fa-lg"></i>
                                <span id="current-month-name" class="fw-bold">{{ $currentMonthName }}</span>
                            </h5>
                            <div class="card-text small fw-semibold" style="opacity:0.92;">
                                <span id="current-month-range">
                                    {{ $currentStartDate->format('d/m/Y') }} - {{ $currentEndDate->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Card Próximo Mês -->
                    <div class="month-card card border-0 shadow-sm bg-white text-center" id="card-next-month">
                        <div class="card-body py-4 px-3">
                            <div class="mb-1 text-muted small fw-semibold">Próximo Mês</div>
                            <h5 class="card-title mb-3 d-flex align-items-center justify-content-center gap-2">
                                <span id="next-month-name" class="fw-bold">{{ $nextMonthName }}</span>
                                <i class="fas fa-chevron-right fa-lg text-primary"></i>
                            </h5>
                            <a href="#" id="next-month"
                                class="btn btn-outline-primary btn-change-month rounded-pill px-4 py-2 fw-semibold"
                                data-month="{{ $nextMonth }}">
                                <i class="fas fa-eye me-1"></i>
                                Ver <span id="next-month-btn-name">{{ $nextMonthName }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                 <!-- Bloco de Botões de Ação -->
                    <div class="summary-actions d-flex flex-column justify-content-center align-items-center ms-2">
                        <button class="btn btn-primary mb-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addTransactionModal" title="Adicionar transação">
                            <i class="bi bi-plus-circle fs-5"></i>
                        </button>
                        <button class="btn btn-outline-secondary shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal" title="Upload">
                            <i class="bi bi-upload fs-5"></i>
                        </button>
                    </div>
            </div>

           
        </div>
    </div>

    <!-- CONTEÚDO PRINCIPAL: Transações e Gráficos -->
    <div class="row " >
        <div class="col-lg-8">
           
            <div id="transactions-container">
                <!-- Inclui a view de transações e passa a variável $clients -->
                @include('invoice.transactions', ['eventsGroupedByMonth' => $eventsGroupedByMonth, 'clients' => $clients])
            </div>
        </div>
        <!-- Gráfico à Direita -->
        <div class="col-lg-4">
             
            <!-- Área de Cards de Resumo Modernos -->
            <div class="summary-cards-area-modern my-2">
                <div class="d-flex flex-wrap gap-4 justify-content-center align-items-stretch">
                    <!-- Card: Preço Total -->
                    <div class="summary-card-modern flex-grow-1">
                        <div class="summary-icon-modern summary-total-modern mb-2">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div class="summary-label-modern">Preço Total</div>
                        <div class="summary-value-modern text-success" id="total-invoices">
                            R$ {{ number_format($totalInvoices, 2) }}
                        </div>
                    </div>
                    <!-- Card: Maior Fatura -->
                    <div class="summary-card-modern flex-grow-1">
                        <div class="summary-icon-modern summary-high-modern mb-2">
                            <i class="bi bi-arrow-up-circle-fill"></i>
                        </div>
                        <div class="summary-label-modern">Maior Fatura</div>
                        <div class="summary-value-modern text-danger" id="highest-invoice">
                            R$ {{ $highestInvoice ? number_format($highestInvoice->value, 2) : '0,00' }}
                        </div>
                    </div>
                    <!-- Card: Menor Fatura -->
                    <div class="summary-card-modern flex-grow-1">
                        <div class="summary-icon-modern summary-low-modern mb-2">
                            <i class="bi bi-arrow-down-circle-fill"></i>
                        </div>
                        <div class="summary-label-modern">Menor Fatura</div>
                        <div class="summary-value-modern text-warning" id="lowest-invoice">
                            R$ {{ $lowestInvoice ? number_format($lowestInvoice->value, 2) : '0,00' }}
                        </div>
                    </div>
                    <!-- Card: Total de Transações -->
                    <div class="summary-card-modern flex-grow-1">
                        <div class="summary-icon-modern summary-count-modern mb-2">
                            <i class="bi bi-list-ol"></i>
                        </div>
                        <div class="summary-label-modern">Total de Transações</div>
                        <div class="summary-value-modern text-info" id="total-transactions">
                            {{ $totalTransactions }}
                        </div>
                    </div>
                </div>
            </div>
      
            <div >
                <!-- Exibe mensagem se não houver dados -->
                <span id="no-data-message" style="display: none;">Sem dados</span>
                <canvas id="updateCategoryChart"></canvas>
            </div>
            <div >
                <canvas id="lineChart"></canvas> <!-- Gráfico de linha -->
            </div>
        </div>
    </div>
</div>
@include('invoice.uploadInvoice')

<script>
$(document).ready(function() {
    // Dados de categorias enviados pelo backend
    var initialCategories = @json($categoriesData);

    // Atualiza o gráfico na carga inicial
    if (!window.categoryChart) {
         updateCategoryChart(initialCategories, {{ $totalInvoices }});
    }
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

                // Atualiza os títulos e nomes dos meses
                $('#current-month-name').text(response.currentMonthName);
                $('#previous-month-name').text(response.previousMonthName);
                $('#next-month-name').text(response.nextMonthName);
                $('#previous-month-btn-name').text(response.previousMonthName);
                $('#next-month-btn-name').text(response.nextMonthName);
                $('#current-month-range').text(response.currentMonthRange);
  $('#current-month-title').text(response.currentMonthTitle);
                // Atualiza os botões de navegação
                $('#previous-month').data('month', response.previousMonth);
                $('#next-month').data('month', response.nextMonth);
  // Atualiza as transações
            $('#transactions-container').html(response.transactionsHtml);

            // Reativa o script de expansão dos invoices
            initInvoiceExpanders();

            // Atualiza o gráfico de categorias
            updateCategoryChart(response.categories, response.totalInvoices);

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
                        ctx.fillText('Total de Faturas', width / 2, chart.chartArea.top + (
                            chart.chartArea.bottom - chart.chartArea.top) / 2 - 10);

                        // Exibe o valor total no centro
                        ctx.fillStyle = '#4caf50';
                        ctx.fillText(`R$ ${totalInvoices.toFixed(2)}`, width / 2, chart
                            .chartArea.top + (chart.chartArea.bottom - chart.chartArea
                                .top) / 2 + 15);
                        ctx.restore();
                    }
                }]
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



});
</script>

@include('invoice.create')

<link rel="stylesheet" href="{{ asset('css/invoice.css') }}">

@endsection
