@extends('layouts.user_type.auth')

@section('content')
{{-- Totais de Receitas e Despesas de todos os meses --}}
<div class="row mt-4">
    <div class="col-md-4 mb-4">
        <div class="card shadow-lg border-0 h-100 mb-4" style="background: linear-gradient(135deg, #f8fafc 60%, #e0e7ff 100%);">
            <div class="card-body pb-0">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase text-primary fw-bold letter-spacing-1">
                        <i class="fas fa-wallet me-2"></i>Totais Cashbook
                    </h6>
                    <a href="{{ url('cashbook') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-wallet me-1"></i> Ver todos
                    </a>
                </div>
                <div class="row text-center mb-4">
                    <div class="col-4">
                        <div class="icon-circle bg-success mb-2">
                            <i class="fas fa-arrow-up fa-lg text-white"></i>
                        </div>
                        <div class="small text-muted">Receitas</div>
                        <div class="fw-bolder text-success fs-5 mt-1">
                            R$ {{ number_format($totalReceitas ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="icon-circle bg-danger mb-2">
                            <i class="fas fa-arrow-down fa-lg text-white"></i>
                        </div>
                        <div class="small text-muted">Despesas</div>
                        <div class="fw-bolder text-danger fs-5 mt-1">
                            R$ {{ number_format($totalDespesas ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="icon-circle bg-secondary mb-2">
                            <i class="fas fa-balance-scale fa-lg text-white"></i>
                        </div>
                        <div class="small text-muted">Saldo Geral</div>
                        <div class="fw-bolder {{ ($saldoTotal ?? 0) >= 0 ? 'text-success' : 'text-danger' }} fs-5 mt-1">
                            R$ {{ number_format($saldoTotal ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
                <hr class="my-3">
                <h6 class="mb-4 text-uppercase text-primary fw-bold letter-spacing-1">
                    <i class="fas fa-calendar-alt me-2"></i>Último mês com movimentação
                </h6>
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <span class="badge bg-info fs-6 px-3 py-2 shadow-sm">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <span id="nomeUltimoMes">{{ $nomeUltimoMes }}</span>
                        </span>
                    </div>
                    <div class="col-4">
                        <div class="icon-circle bg-success mb-2">
                            <i class="fas fa-arrow-up fa-lg text-white"></i>
                        </div>
                        <div class="small text-muted">Receitas</div>
                        <div id="receitaUltimoMes" class="fw-bolder text-success fs-6 mt-1">
                            R$ {{ number_format($receitaUltimoMes ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="icon-circle bg-danger mb-2">
                            <i class="fas fa-arrow-down fa-lg text-white"></i>
                        </div>
                        <div class="small text-muted">Despesas</div>
                        <div id="despesaUltimoMes" class="fw-bolder text-danger fs-6 mt-1">
                            R$ {{ number_format($despesaUltimoMes ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="icon-circle bg-secondary mb-2">
                            <i class="fas fa-balance-scale fa-lg text-white"></i>
                        </div>
                        <div class="small text-muted">Saldo</div>
                        <div id="saldoUltimoMes" class="fw-bolder {{ ($saldoUltimoMes ?? 0) >= 0 ? 'text-success' : 'text-danger' }} fs-6 mt-1">
                            R$ {{ number_format($saldoUltimoMes ?? 0, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .letter-spacing-1 {
            letter-spacing: 1px;
        }
    </style>

    <div class="col-md-8 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-uppercase text-secondary fw-bold mb-0">Receitas x Despesas por mês</h6>
                    <form method="GET" class="d-inline-block" id="formAno" onsubmit="return false;">
                        <select name="ano" id="anoSelect" class="form-select form-select-sm" style="width:auto;display:inline-block;">
                            @for($y = date('Y'); $y >= (date('Y')-5); $y--)
                                <option value="{{ $y }}" @if($ano == $y) selected @endif>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
                <canvas id="cashbookChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ROW: Bancos e Invoices --}}
<div class="row mt-4">
    <div class="col-md-12 mb-4">
        <div class="card shadow border-0">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Resumo dos Bancos</h6>
                <span class="small text-muted">Total de Bancos: {{ $totalBancos }}</span>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Banco</th>
                            <th>Descrição</th>
                            <th>Qtd. Invoices</th>
                            <th>Total Invoices</th>
                            <th>Média Invoices</th>
                            <th>Maior Invoice</th>
                            <th>Menor Invoice</th>
                            <th>Saldo (Entradas - Saídas)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bancosInfo as $banco)
                            <tr>
                                <td>{{ $banco['nome'] }}</td>
                                <td>{{ $banco['descricao'] }}</td>
                                <td>{{ $banco['qtd_invoices'] }}</td>
                                <td>R$ {{ number_format($banco['total_invoices'], 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($banco['media_invoices'], 2, ',', '.') }}</td>
                                <td>
                                    @if($banco['maior_invoice'])
                                        R$ {{ number_format($banco['maior_invoice']->value, 2, ',', '.') }}
                                        <br>
                                        <small class="text-muted">{{ $banco['maior_invoice']->description }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($banco['menor_invoice'])
                                        R$ {{ number_format($banco['menor_invoice']->value, 2, ',', '.') }}
                                        <br>
                                        <small class="text-muted">{{ $banco['menor_invoice']->description }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="{{ $banco['saldo'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    R$ {{ number_format($banco['saldo'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3">Totais Gerais</td>
                            <td>R$ {{ number_format($totalInvoicesBancos, 2, ',', '.') }}</td>
                            <td colspan="3"></td>
                            <td>R$ {{ number_format($saldoTotalBancos, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ROW: Gráficos e Informações de Bancos e Invoices --}}
<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header pb-0"><h6>Saldo por Banco</h6></div>
            <div class="card-body">
                <canvas id="bancosSaldoChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header pb-0"><h6>Total de Invoices por Banco</h6></div>
            <div class="card-body">
                <canvas id="bancosInvoicesChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header pb-0"><h6>Distribuição de Saídas (Invoices)</h6></div>
            <div class="card-body">
                <canvas id="bancosSaidasChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header pb-0"><h6>Evolução do Saldo Total dos Bancos (12 meses)</h6></div>
            <div class="card-body">
                <canvas id="bancosEvolucaoSaldoChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ROW: Gráfico Diário de Invoices --}}
<div class="row mt-4">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Gráfico Diário de Invoices (Saídas por Dia)</h6>
                <div>
                    <select id="mesInvoicesSelect" class="form-select form-select-sm d-inline-block" style="width:auto;">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @if($mesInvoices == $m) selected @endif>
                                {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                    <select id="anoInvoicesSelect" class="form-select form-select-sm d-inline-block" style="width:auto;">
                        @for($y = date('Y'); $y >= (date('Y')-5); $y--)
                            <option value="{{ $y }}" @if($anoInvoices == $y) selected @endif>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="invoicesDiarioChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let cashbookChart;
    const meses = {!! json_encode(array_values($meses)) !!};

    function renderChart(receitas, despesas) {
        const ctx = document.getElementById('cashbookChart').getContext('2d');
        if (cashbookChart) cashbookChart.destroy();
        cashbookChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Receitas',
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1,
                        data: receitas
                    },
                    {
                        label: 'Despesas',
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1,
                        data: despesas
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                            }
                        }
                    }
                }
            }
        }); // <-- FECHA Chart
    } // <-- FECHA FUNÇÃO

    renderChart({!! json_encode($dadosReceita) !!}, {!! json_encode($dadosDespesa) !!});

    document.getElementById('anoSelect').addEventListener('change', function() {
        const ano = this.value;
        fetch("{{ route('dashboard.cashbookChartData') }}?ano=" + ano)
            .then(res => res.json())
            .then(function(data) {
                renderChart(data.dadosReceita, data.dadosDespesa);
                document.getElementById('receitaUltimoMes').innerHTML =
                    'R$ ' + (data.receitaUltimoMes).toLocaleString('pt-BR', {minimumFractionDigits: 2});
                document.getElementById('despesaUltimoMes').innerHTML =
                    'R$ ' + (data.despesaUltimoMes).toLocaleString('pt-BR', {minimumFractionDigits: 2});
                document.getElementById('saldoUltimoMes').innerHTML =
                    'R$ ' + (data.saldoUltimoMes).toLocaleString('pt-BR', {minimumFractionDigits: 2});
                document.getElementById('saldoUltimoMes').className =
                    'fw-bolder mt-2 ' + (data.saldoUltimoMes >= 0 ? 'text-success' : 'text-danger');
                document.getElementById('nomeUltimoMes').innerText = data.nomeUltimoMes;
            });
    });

    // Gráfico de Barras: Saldo por Banco
    document.addEventListener('DOMContentLoaded', function() {
        const ctxBancosSaldo = document.getElementById('bancosSaldoChart');
        if (ctxBancosSaldo) {
            new Chart(ctxBancosSaldo.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($bancosInfo->pluck('nome')) !!},
                    datasets: [{
                        label: 'Saldo',
                        data: {!! json_encode($bancosInfo->pluck('saldo')) !!},
                        backgroundColor: {!! json_encode($bancosInfo->pluck('saldo')->map(function($v) { return $v >= 0 ? 'rgba(40,167,69,0.7)' : 'rgba(220,53,69,0.7)'; })->values()) !!}
                    }] // <-- FECHA O ARRAY DE DATASETS
                }, // <-- FECHA O OBJETO DATA
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    });

    // Gráfico de Barras: Total de Invoices por Banco
    document.addEventListener('DOMContentLoaded', function() {
        const ctxBancosInvoices = document.getElementById('bancosInvoicesChart');
        if (ctxBancosInvoices) {
            new Chart(ctxBancosInvoices.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($bancosInfo->pluck('nome')) !!},
                    datasets: [{
                        label: 'Total de Invoices',
                        data: {!! json_encode($bancosInfo->pluck('total_invoices')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    });

    // Gráfico de Pizza: Saídas (Invoices)
    document.addEventListener('DOMContentLoaded', function() {
        const ctxBancosSaidas = document.getElementById('bancosSaidasChart');
        if (ctxBancosSaidas) {
            new Chart(ctxBancosSaidas.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode($bancosInfo->pluck('nome')) !!},
                    datasets: [{
                        data: {!! json_encode($bancosInfo->pluck('saidas')) !!},
                        backgroundColor: [
                            'rgba(220, 53, 69, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(153, 102, 255, 0.7)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } }
                }
            });
        }
    });

    // Gráfico de Linha: Evolução do Saldo Total dos Bancos (últimos 12 meses)
    document.addEventListener('DOMContentLoaded', function() {
        const ctxBancosEvolucao = document.getElementById('bancosEvolucaoSaldoChart');
        if (ctxBancosEvolucao) {
            new Chart(ctxBancosEvolucao.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($bancosEvolucaoMeses) !!},
                    datasets: [{
                        label: 'Saldo Acumulado',
                        data: {!! json_encode($bancosEvolucaoSaldos) !!},
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: false } }
                }
            });
        }
    });

    // Gráfico Diário de Invoices (Saídas por Dia - mês/ano dinâmico)
    let invoicesDiarioChart;
    function renderInvoicesDiarioChart(labels, values) {
        const ctx = document.getElementById('invoicesDiarioChart').getContext('2d');
        if (invoicesDiarioChart) invoicesDiarioChart.destroy();
        invoicesDiarioChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Saídas (Invoices)',
                    data: values,
                    backgroundColor: 'rgba(220, 53, 69, 0.7)'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                            }
                        }
                    }
                }
            } // <-- FALTAVA FECHAR ESTE OBJETO options
        }); // <-- FECHA O Chart
    }

    // Inicializa com os dados do blade
    renderInvoicesDiarioChart({!! json_encode($diasInvoices) !!}, {!! json_encode($valoresInvoices) !!});

    // Troca dinâmica de mês/ano
    document.getElementById('mesInvoicesSelect').addEventListener('change', updateInvoicesDiarioChart);
    document.getElementById('anoInvoicesSelect').addEventListener('change', updateInvoicesDiarioChart);

    function updateInvoicesDiarioChart() {
        const mes = document.getElementById('mesInvoicesSelect').value;
        const ano = document.getElementById('anoInvoicesSelect').value;
        fetch("{{ route('dashboard.invoicesDailyChartData') }}?mes=" + mes + "&ano=" + ano)
            .then(res => res.json())
            .then(function(data) {
                renderInvoicesDiarioChart(data.diasInvoices, data.valoresInvoices);
            });
    }
</script>
@endpush
@endsection
