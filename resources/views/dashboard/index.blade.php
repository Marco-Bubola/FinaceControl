@extends('layouts.user_type.auth')

@section('content')
<div class="row g-4">
    {{-- Cashbook --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="rounded-circle bg-gradient-success d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                        @if(($totalCashbook ?? 0) >= 0)
                            <i class="fas fa-arrow-up text-white fa-lg"></i>
                        @else
                            <i class="fas fa-arrow-down text-white fa-lg"></i>
                        @endif
                    </span>
                </div>
                <div>
                    <p class="mb-1 text-uppercase text-secondary fw-bold small">Saldo total (Cashbook)</p>
                    <h5 class="fw-bolder mb-0 {{ ($totalCashbook ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        R$ {{ number_format($totalCashbook ?? 0, 2, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Produtos --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="rounded-circle bg-gradient-info d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                        <i class="fas fa-boxes text-white fa-lg"></i>
                    </span>
                </div>
                <div>
                    <p class="mb-1 text-uppercase text-secondary fw-bold small">Produtos</p>
                    <div class="fw-bolder mb-0">
                        <span title="Total de produtos" class="me-2">
                            <i class="fas fa-cubes text-info"></i>
                            {{ $totalProdutos ?? 0 }}
                        </span>
                        <span title="Total em estoque">
                            <i class="fas fa-warehouse text-warning"></i>
                            {{ $totalProdutosEstoque ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Clientes --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="rounded-circle bg-gradient-primary d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                        <i class="fas fa-users text-white fa-lg"></i>
                    </span>
                </div>
                <div>
                    <p class="mb-1 text-uppercase text-secondary fw-bold small">Clientes</p>
                    <div class="fw-bolder mb-0">
                        <span title="Total de clientes" class="me-2">
                            <i class="fas fa-user-friends text-primary"></i>
                            {{ $totalClientes ?? 0 }}
                        </span>
                        <span title="Com vendas pendentes">
                            <i class="fas fa-clock text-danger"></i>
                            {{ $clientesComSalesPendentes ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Faturamento --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="rounded-circle bg-gradient-warning d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                        <i class="fas fa-cash-register text-white fa-lg"></i>
                    </span>
                </div>
                <div>
                    <p class="mb-1 text-uppercase text-secondary fw-bold small">Faturamento</p>
                    <div class="fw-bolder mb-0">
                        <span title="Total faturado" class="me-2">
                            <i class="fas fa-coins text-success"></i>
                            R$ {{ number_format($totalFaturamento ?? 0, 2, ',', '.') }}
                        </span>
                        <span title="Valor faltante">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                            R$ {{ number_format($totalFaltante ?? 0, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- NOVO ROW PARA CASHBOOK --}}
<div class="row mt-4">
  {{-- Totais de Receitas e Despesas de todos os meses --}}
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

    {{-- Estilo customizado para ícones circulares --}}
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

    {{-- Gráfico de Barras --}}
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

{{-- ROW: Últimos Produtos --}}
<div class="row mt-4">
    {{-- Últimos Produtos --}}
    <div class="col-md-7 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase text-primary fw-bold letter-spacing-1">
                        <i class="fas fa-box-open me-2"></i>Últimos Produtos Adicionados (Estoque > 0)
                    </h6>
                    <a href="{{ url('products') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-boxes me-1"></i> Ver todos
                    </a>
                </div>
                <div class="row">
                    @forelse($ultimosProdutos as $produto)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                @if(!empty($produto->image))
                                    <img src="{{ asset('storage/products/' . $produto->image) }}" class="product-img"
                                        alt="{{ $produto->name }}">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light" style="height:100px;">
                                        <i class="fas fa-box fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div class="card-body p-2">
                                    <div class="fw-bold small text-truncate" title="{{ $produto->name }}">{{ $produto->name }}</div>
                                    <div class="text-muted small">Estoque: <span class="fw-bold">{{ $produto->stock_quantity }}</span></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted">Nenhum produto com estoque.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    {{-- Gráfico de Barras de Possível Faturamento dos Produtos --}}
    <div class="col-md-5 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <h6 class="mb-4 text-uppercase text-primary fw-bold letter-spacing-1">
                    <i class="fas fa-chart-bar me-2"></i>Possível Faturamento dos Produtos
                </h6>
                <canvas id="produtosBarChart" height="70"></canvas>
            </div>
        </div>
    </div>
    </div>
{{-- FIM ROW PRODUTOS--}}
{{-- ROW: Clientes com Vendas Pendentes --}}
<div class="row mt-4">
    <div class="col-md-12 mb-4">
        <div class="card shadow border-0 h-100 bg-gradient-light">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="text-uppercase text-primary fw-bold d-flex align-items-center mb-0" style="letter-spacing: 1.5px;">
                        <i class="fas fa-user-clock me-3 fa-xl"></i>
                        Clientes com Vendas Pendentes
                    </h4>
                    <a href="{{ url('clients') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-users me-1"></i> Ver todos
                    </a>
                </div>
                <div class="row">
                    @forelse($clientesPendentes as $cliente)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 border-0 shadow-lg rounded-4 position-relative cliente-card-hover bg-white">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-user-circle fa-2x text-primary me-3"></i>
                                        <span class="fw-bold fs-5 text-truncate" title="{{ $cliente->name }}">{{ $cliente->name }}</span>
                                    </div>
                                    <div class="text-muted mb-3 fs-6 d-flex align-items-center justify-content-between">
                                        <span>
                                            <i class="fas fa-shopping-cart me-2"></i>
                                            <span class="fw-semibold">Vendas Pendentes:</span>
                                        </span>
                                        @if(count($cliente->sales) > 1)
                                            <span class="ms-auto show-more-toggle" data-cliente="{{ $cliente->id }}" style="cursor:pointer;user-select:none;">
                                                <span class="badge bg-secondary me-1">{{ count($cliente->sales) }}</span>
                                                <i class="fas fa-chevron-down" id="toggle-icon-{{ $cliente->id }}"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <ul class="list-unstyled mb-0" id="vendas-list-{{ $cliente->id }}">
                                        @foreach($cliente->sales as $idx => $sale)
                                            <li
                                                class="d-flex align-items-center gap-2 mb-3 py-2 px-2 rounded-3 venda-linha venda-hover
                                                    @if($idx > 0) d-none @endif"
                                                data-cliente="{{ $cliente->id }}"
                                                data-sale="{{ $sale->id }}"
                                                style="cursor:pointer;"
                                                onclick="if(event.target.tagName !== 'BUTTON'){ window.location='{{ route('sales.show', $sale->id) }}'; }"
                                            >
                                                <span class="badge bg-primary fs-6 px-2 py-1">
                                                    <i class="fas fa-receipt me-1"></i> #{{ $sale->id }}
                                                </span>
                                                <span class="text-dark fw-semibold fs-6">
                                                    <i class="fas fa-money-bill-wave me-1 text-success"></i>
                                                    {{ number_format($sale->total_price, 2, ',', '.') }}
                                                </span>
                                                <span class="badge bg-danger fs-6 px-2 py-1 ms-auto">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                     {{ number_format($sale->valor_restante, 2, ',', '.') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted">
                            <i class="fas fa-smile-beam fa-2x mb-2"></i><br>
                            Nenhum cliente com vendas pendentes.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cliente-card-hover {
        transition: transform 0.18s, box-shadow 0.18s;
        border: 2px solid transparent;
    }
    .cliente-card-hover:hover {
        transform: translateY(-6px) scale(1.04);
        box-shadow: 0 10px 32px rgba(0,0,0,0.13);
        border-color: #0d6efd;
        background: linear-gradient(120deg, #f8fafc 80%, #e3f0ff 100%);
    }
    .venda-linha {
        background: #f5f8fa;
        border: 1px solid #e3e8ee;
        font-size: 1.1rem;
        align-items: center;
        transition: background 0.15s, box-shadow 0.15s;
    }
    .venda-hover:hover {
        background: #e3f0ff;
        box-shadow: 0 2px 8px rgba(13,110,253,0.08);
        cursor: pointer;
    }
    .venda-linha .badge {
        font-size: 1rem;
        border-radius: 0.5rem;
    }
    .fs-5 { font-size: 1.25rem !important; }
    .fs-6 { font-size: 1.1rem !important; }
    @media (max-width: 991px) {
        .col-lg-3 { flex: 0 0 50%; max-width: 50%; }
    }
    @media (max-width: 767px) {
        .col-md-4, .col-lg-3 { flex: 0 0 100%; max-width: 100%; }
    }
</style>
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
        });
    }

    // Inicializa com os dados do blade
    renderChart({!! json_encode($dadosReceita) !!}, {!! json_encode($dadosDespesa) !!});

    document.getElementById('anoSelect').addEventListener('change', function() {
        const ano = this.value;
        fetch("{{ route('dashboard.cashbookChartData') }}?ano=" + ano)
            .then(res => res.json())
            .then(function(data) {
                renderChart(data.dadosReceita, data.dadosDespesa);
                // Atualiza receitas, despesas, saldo e nome do mês do último mês com movimentação
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

    // Gráfico de Pizza de Receitas e Despesas dos Produtos
    const totalReceitasProdutos = {{ $totalReceitasProdutos ?? 0 }};
    const totalDespesasProdutos = {{ $totalDespesasProdutos ?? 0 }};
    const totalSaldoProdutos = {{ $totalSaldoProdutos ?? 0 }};

    if (document.getElementById('produtosBarChart')) {
        const ctxProdutos = document.getElementById('produtosBarChart').getContext('2d');
        new Chart(ctxProdutos, {
            type: 'doughnut',
            data: {
                labels: ['Receitas (Preço de Venda)', 'Despesas (Preço de Compra)'],
                datasets: [
                    {
                        data: [totalReceitasProdutos, totalDespesasProdutos],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.7)',    // Receitas - verde
                            'rgba(220, 53, 69, 0.7)'     // Despesas - vermelho
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(220, 53, 69, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                }
            },
            plugins: [{
                id: 'centerText',
                afterDraw: chart => {
                    const {ctx, chartArea} = chart;
                    if (!chartArea) return; // chartArea pode não estar disponível em resize
                    ctx.save();
                    // Texto superior: "Possível Lucro"
                    ctx.font = 'bold 1.7em sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillStyle = '#6c757d';
                    ctx.fillText('Possível Lucro', chartArea.left + chartArea.width / 2, chartArea.top + chartArea.height / 2 - 18);

                    // Ícone de moeda (usando emoji para compatibilidade)
                    ctx.font = '1.5em sans-serif';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('💰', chartArea.left + chartArea.width / 2, chartArea.top + chartArea.height / 2);

                    // Valor do saldo
                    ctx.font = 'bold 1.7em sans-serif';
                    ctx.textBaseline = 'top';
                    const saldo = totalSaldoProdutos;
                    const text = 'R$ ' + saldo.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                    ctx.fillStyle = saldo >= 0 ? '#28a745' : '#dc3545';
                    ctx.fillText(text, chartArea.left + chartArea.width / 2, chartArea.top + chartArea.height / 2 + 18);
                    ctx.restore();
                }
            }]
        }); // <-- FECHA new Chart
    } // <-- FECHA if (document.getElementById('produtosBarChart'))

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.show-more-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                const clienteId = this.getAttribute('data-cliente');
                const vendasList = document.getElementById('vendas-list-' + clienteId);
                const icon = document.getElementById('toggle-icon-' + clienteId);
                if (vendasList) {
                    const hidden = vendasList.querySelector('li.d-none');
                    if (hidden) {
                        // Mostrar todas
                        vendasList.querySelectorAll('li.d-none').forEach(function(li) {
                            li.classList.remove('d-none');
                        });
                        if (icon) {
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-up');
                        }
                    } else {
                        // Esconder todas menos a primeira
                        vendasList.querySelectorAll('li').forEach(function(li, idx) {
                            if (idx > 0) li.classList.add('d-none');
                        });
                        if (icon) {
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        }
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection
