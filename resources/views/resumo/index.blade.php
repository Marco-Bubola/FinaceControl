@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">

    {{-- Cabeçalho do Cliente --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1">Resumo Financeiro de {{ $cliente->name }}</h5>
                        <p class="text-muted mb-0">Email: {{ $cliente->email ?? 'N/A' }}</p>
                        <p class="text-muted mb-0">Telefone: {{ $cliente->phone ?? 'N/A' }}</p>
                    </div>
                    <img src="{{ asset('assets/img/logos/user-icon.png') }}" class="rounded shadow" alt="Cliente"
                        style="max-width: 60px;">
                </div>
            </div>
        </div>
    </div>

    {{-- Cartões de Resumo --}}
    <div class="row g-3 mb-4">
        @php
        $cards = [
        ['label' => 'Total de Faturas', 'value' => $totalFaturas, 'colors' => 'danger','color' => 'text-danger', 'icon'
        => 'fas fa-file-invoice'],
        ['label' => 'Total Recebido', 'value' => $totalRecebido, 'colors' => 'success','color' => 'text-success', 'icon'
        => 'fas fa-arrow-down'],
        ['label' => 'Total Enviado', 'value' => $totalEnviado,'colors' => 'warning', 'color' => 'text-warning', 'icon'
        => 'fas fa-arrow-up'],
        ['label' => 'Saldo Atual', 'value' => $saldoAtual,'colors' => 'info', 'color' => 'text-info', 'icon' => 'fas
        fa-wallet'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-sm-6 col-md-3">
            <div class="card bg-dark text-white shadow-sm mb-4 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-{{ $card['colors'] }} rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 50px; height: 50px;">
                        <i class="{{ $card['icon'] }} text-white"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-sm">{{ $card['label'] }}</p>
                        <h6 class="{{ $card['color'] }} font-weight-bold">
                            R$ {{ number_format($card['value'], 2, ',', '.') }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        {{-- Coluna Esquerda --}}
        <div class="col-lg-6">

            {{-- Faturas --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Faturas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($faturas as $fatura)
                        <div class="col-md-6">
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="border: 3px solid {{ $fatura->category->hexcolor_category }};
                                background-color: {{ $fatura->category->hexcolor_category }}20;
                                width: 50px; height: 50px;">
                                        <i class="{{ $fatura->category->icone }}"
                                            style="font-size: 1.5rem; color: {{ $fatura->category->hexcolor_category }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark text-truncate">{{ $fatura->description }}</h6>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($fatura->invoice_date)->format('d/m/Y') }}</small>
                                    </div>
                                    <span class="badge bg-primary fs-6">R$
                                        {{ number_format($fatura->value, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted">Nenhuma fatura registrada.</div>
                        @endforelse
                    </div>
                </div>
            </div>


            {{-- Transferências Enviadas --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0 text-danger"><i class="fas fa-arrow-down me-1"></i> Transferências Enviadas</h6>
                </div>
                <div class="card-body">
                    @php
                    $enviadas = collect($transferencias)->where('tipo', 'Enviado');
                    @endphp

                    <div class="row">
                        @forelse ($enviadas as $transferencia)
                        @php $bgColor = '#dc3545'; @endphp
                        <div class="col-md-6">
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body d-flex align-items-center gap-3">


                                    <button
                                        class="btn btn-icon-only btn-rounded d-flex align-items-center justify-content-center"
                                        style="border: 3px solid {{ $transferencia->category->hexcolor_category ?? '#ccc' }};
                                background-color: {{ $transferencia->category->hexcolor_category ?? '#ccc' }}20;
                                width: 50px; height: 50px;"
                                        title="{{ $transferencia->category->name ?? 'Categoria não definida' }}"
                                        data-bs-toggle="tooltip">
                                        <i class="{{ $transferencia->category->icone ?? 'fas fa-question' }}"
                                            style="font-size: 1.3rem;"></i>
                                    </button>

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark">{{ $transferencia->description }}</h6>
                                        <small class="text-muted">Data:
                                            {{ \Carbon\Carbon::parse($transferencia->transfer_date)->format('d/m/Y') }}</small><br>
                                        <small class="badge bg-primary fs-6">R$
                                            {{ number_format($transferencia->value, 2, ',', '.') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted">Nenhuma transferência enviada.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Transferências Recebidas --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0 text-success"><i class="fas fa-arrow-up me-1"></i> Transferências Recebidas</h6>
                </div>
                <div class="card-body">
                    @php
                    $recebidas = collect($transferencias)->where('tipo', 'Recebido');
                    @endphp

                    <div class="row">
                        @forelse ($recebidas as $transferencia)
                        @php $bgColor = '#198754'; @endphp
                        <div class="col-md-6">
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body d-flex align-items-center gap-3">

                                    <button
                                        class="btn btn-icon-only btn-rounded d-flex align-items-center justify-content-center"
                                        style="border: 3px solid {{ $transferencia->category->hexcolor_category ?? '#ccc' }};
                                    background-color: {{ $transferencia->category->hexcolor_category ?? '#ccc' }}20;
                                    width: 50px; height: 50px;"
                                        title="{{ $transferencia->category->name ?? 'Categoria não definida' }}"
                                        data-bs-toggle="tooltip">
                                        <i class="{{ $transferencia->category->icone ?? 'fas fa-question' }}"
                                            style="font-size: 1.3rem;"></i>
                                    </button>

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark">{{ $transferencia->description }}</h6>
                                        <small class="text-muted">Data:
                                            {{ \Carbon\Carbon::parse($transferencia->transfer_date)->format('d/m/Y') }}</small><br>
                                        <small class="badge bg-primary fs-6">R$
                                            {{ number_format($transferencia->value, 2, ',', '.') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted">Nenhuma transferência recebida.</div>
                        @endforelse
                    </div>
                </div>
            </div>


        </div>

        <!-- Coluna da direita: Gráfico -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Gráfico de Receitas e Despesas</h6>
                </div>
                <div class="card-body">
                    <canvas id="transaction-pie-chart" style="max-height: 500px;"></canvas>
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Gráfico de Categorias</h6>
                </div>
                <div class="card-body">
                    <canvas id="updateCategoryChart" style="max-height: 500px;"></canvas>
                    <div id="no-data-message" class="text-center text-muted" style="display: none;">Sem dados</div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const categories = @json($categories); // Dados das categorias
        const totalInvoices = {{ $totalFaturas ?? 0 }}; // Total de faturas
        // Total de faturas
        updateCategoryChart(categories, totalInvoices);
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

    document.addEventListener('DOMContentLoaded', function() {
        const totals = @json($totals); // Dados de receitas, despesas e saldo
        graficoFinanceiro(totals);
    });

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
    </script>