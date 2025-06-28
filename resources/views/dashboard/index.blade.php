@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">
    <div class="row mb-5">
        <div class="col-12 text-center">
            {{-- Modern Gradient Title --}}
            <h1 class="fw-bold display-4 mb-2"
                style="letter-spacing:-1px; background: linear-gradient(45deg, #2937f0, #9f1ae2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Dashboard</h1>
            <p class="text-muted mb-0" style="font-size:1.2rem;">Visão geral das suas finanças, produtos e vendas</p>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        {{-- Card Cashbook --}}
        <div class="col-lg-4 col-md-6">
            <a href="{{ url('dashboard/cashbook') }}" class="card-link-wrapper"
                style="text-decoration:none; color:inherit;">
                <div class="card shadow-lg border-0 h-100 quick-access-card card-clickable position-relative card-cashbook"
                    style="border-radius: 15px; transition: all 0.3s ease;">
                    <div class="card-body d-flex flex-column align-items-center p-4">
                        <div class="w-100 mb-4">
                            {{-- Header --}}
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-gradient-success p-3" style="border-radius: 12px;">
                                        <i class="fas fa-wallet text-white fa-2x"></i>
                                    </div>
                                    <h3 class="fw-bold text-success mb-0 ms-3" style="font-size: 1.8rem;">Cashbook</h3>
                                </div>
                                <div class="bg-success bg-opacity-10 px-3 py-2 rounded-pill">
                                    <span class="text-success fw-bold">Total</span>
                                </div>
                            </div>

                            {{-- Chart Area --}}
                            <div class="position-relative d-flex justify-content-center align-items-center"
                                style="height: 220px;">
                                <canvas id="cashbookChart" style="width: 100%; max-height: 220px;"></canvas>
                                <div id="cashbookChartCenter"
                                    class="position-absolute top-50 start-50 translate-middle text-success fw-bold text-center"
                                    style="font-size: 1.8rem; pointer-events: none;">
                                    R$ {{ number_format($totalCashbook ?? 0, 2, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        {{-- Stats Area --}}
                        <div class="w-100">
                            <div class="row g-3 mb-3">
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded-3 text-center card-stat-item hover-effect">
                                        <i class="fas fa-exchange-alt text-primary mb-2"></i>
                                        <h6 class="mb-1 small text-muted">Movimentações</h6>
                                        <span
                                            class="fw-bold fs-5 text-dark">{{ \App\Models\Cashbook::where('user_id', Auth::id())->count() }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-success bg-opacity-10 rounded-3 card-stat-item hover-effect">
                                        <i class="fas fa-arrow-up text-success mb-2"></i>
                                        <h6 class="mb-1 small text-muted">Receitas</h6>
                                        <span class="fw-bold text-success fs-5">
                                            R$
                                            {{ number_format(\App\Models\Cashbook::where('user_id', Auth::id())->where('type_id', 1)->sum('value'), 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-danger bg-opacity-10 rounded-3 card-stat-item hover-effect">
                                        <i class="fas fa-arrow-down text-danger mb-2"></i>
                                        <h6 class="mb-1 small text-muted">Despesas</h6>
                                        <span class="fw-bold text-danger fs-5">
                                            R$
                                            {{ number_format(\App\Models\Cashbook::where('user_id', Auth::id())->where('type_id', 2)->sum('value'), 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 card-stat-item hover-effect">
                                        <i class="fas fa-calendar-day text-info me-2"></i>
                                        <h6 class="mb-1 small text-muted">Última Mov.:</h6>
                                        @if($ultimaMovimentacaoCashbook)
                                        <span class="fw-bold text-dark">
                                            {{ \Carbon\Carbon::parse($ultimaMovimentacaoCashbook->date)->format('d/m/Y') }}
                                            <span
                                                class="{{ $ultimaMovimentacaoCashbook->type_id == 1 ? 'text-success' : 'text-danger' }}">
                                                R$ {{ number_format($ultimaMovimentacaoCashbook->value, 2, ',', '.') }}
                                            </span>
                                        </span>
                                        @else
                                        <span class="text-muted small">Nenhuma movimentação</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 card-stat-item hover-effect">
                                        <i class="fas fa-calendar-week text-secondary me-2"></i>
                                        <h6 class="mb-1 small text-muted">Média diária:</h6>
                                        <span class="fw-bold text-dark">
                                            R$ {{
                                                \App\Models\Cashbook::where('user_id', Auth::id())
                                                ->selectRaw('SUM(value)/GREATEST(DATEDIFF(MAX(date), MIN(date)),1) as media')
                                                ->value('media') ? number_format(\App\Models\Cashbook::where('user_id', Auth::id())
                                                ->selectRaw('SUM(value)/GREATEST(DATEDIFF(MAX(date), MIN(date)),1) as media')
                                                ->value('media'), 2, ',', '.') : '0,00'
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Card Produtos --}}
        <div class="col-lg-4 col-md-6">
            <a href="{{ url('dashboard/products') }}" class="card-link-wrapper"
                style="text-decoration:none; color:inherit;">
                <div class="card shadow-lg border-0 h-100 quick-access-card card-clickable position-relative card-products"
                    style="border-radius: 15px; transition: all 0.3s ease;">
                    <div class="card-body d-flex flex-column align-items-center p-4">
                        <div class="w-100 mb-4">
                            {{-- Header --}}
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-gradient-info p-3" style="border-radius: 12px;">
                                        <i class="fas fa-box text-white fa-2x"></i>
                                    </div>
                                    <h3 class="fw-bold text-info mb-0 ms-3" style="font-size: 1.8rem;">Produtos</h3>
                                </div>
                                <div class="bg-info bg-opacity-10 px-3 py-2 rounded-pill">
                                    <span class="text-info fw-bold">Estoque</span>
                                </div>
                            </div>

                            {{-- Chart Area (Lucro Previsto) --}}
                            <div class="position-relative d-flex justify-content-center align-items-center"
                                style="height: 220px;">
                                <canvas id="produtosChart" style="width: 100%; max-height: 220px;"></canvas>
                                <div id="produtosChartCenter"
                                    class="position-absolute top-50 start-50 translate-middle text-info fw-bold text-center"
                                    style="font-size: 1.5rem; pointer-events: none;">
                                    {{-- Lucro total será preenchido via JS --}}
                                </div>
                            </div>
                        </div>

                        {{-- Stats Area --}}
                        <div class="w-100">
                            <div class="row g-3 mb-3">
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded-3 card-stat-item hover-effect">
                                        <i class="fas fa-boxes text-info mb-2"></i>
                                        <h6 class="mb-1 small text-muted">Total de produtos</h6>
                                        <span class="fw-bold fs-5 text-dark">{{ $totalProdutos ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-warning bg-opacity-10 rounded-3 card-stat-item hover-effect">
                                        <i class="fas fa-warehouse text-warning mb-2"></i>
                                        <h6 class="mb-1 small text-muted">Em estoque</h6>
                                        <span class="fw-bold fs-5 text-dark">{{ $totalProdutosEstoque ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-secondary bg-opacity-10 rounded-3 card-stat-item hover-effect">
                                        <i class="fas fa-boxes-stacked text-secondary mb-2"></i>
                                        <h6 class="mb-1 small text-muted">Sem estoque</h6>
                                        <span class="fw-bold fs-5 text-danger">
                                            {{ \App\Models\Product::where('user_id', Auth::id())->where('stock_quantity', 0)->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-success bg-opacity-10 rounded-3 card-stat-item hover-effect">
                                        <i class="fas fa-box-open text-primary me-2"></i>
                                        <h6 class="mb-1 small text-muted">Maior estoque</h6>
                                        @if($produtoMaiorEstoque)
                                        <span class="fw-bold text-end">
                                            {{ $produtoMaiorEstoque->name }}
                                            <span
                                                class="text-info d-block">({{ $produtoMaiorEstoque->stock_quantity }})</span>
                                        </span>
                                        @else
                                        <span class="text-muted">Nenhum produto</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-danger bg-opacity-10 rounded-3 card-stat-item hover-effect">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        <h6 class="mb-1 small text-muted">Mais vendido</h6>
                                        <span class="fw-bold text-success text-end">
                                            {{ $produtoMaisVendido ? $produtoMaisVendido->name : 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Card Vendas --}}
        <div class="col-lg-4 col-md-6">
            <a href="{{ url('dashboard/sales') }}" class="card-link-wrapper"
                style="text-decoration:none; color:inherit;">
                <div class="card shadow-lg border-0 h-100 quick-access-card card-clickable position-relative card-sales"
                    style="border-radius: 15px; transition: all 0.3s ease;">
                    <div class="card-body d-flex flex-column align-items-center p-4">
                        <div class="w-100 mb-4">
                            {{-- Header --}}
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-gradient-warning p-3" style="border-radius: 12px;">
                                        <i class="fas fa-shopping-cart text-white fa-2x"></i>
                                    </div>
                                    <h3 class="fw-bold text-warning mb-0 ms-3" style="font-size: 1.8rem;">Vendas</h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 px-3 py-2 rounded-pill">
                                    <span class="text-warning fw-bold">Faturamento</span>
                                </div>
                            </div>



                            {{-- Chart Area --}}
                            <div class="position-relative d-flex justify-content-center align-items-center"
                                style="height: 220px;">
                                <canvas id="vendasChart" style="width: 100%; max-height: 220px;"></canvas>
                                <div id="vendasChartCenter"
                                    class="position-absolute top-50 start-50 translate-middle text-warning fw-bold"
                                    style="font-size: 1.8rem; pointer-events: none;">
                                    R$ {{ number_format($totalFaturamento ?? 0, 2, ',', '.') }}
                                </div>
                            </div>

                        </div>


                        {{-- Stats Area --}}
                        <div class="w-100">
                            <div class="row g-3">
                                <!-- Card 1: Valor faltante -->
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-file-invoice-dollar text-danger me-2"></i>Valor
                                                faltante</span>
                                            <span class="fw-bold text-danger">
                                                R$ {{ number_format($totalFaltante ?? 0, 2, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 2: Qtd. vendas -->
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-list-ol text-primary me-2"></i>Qtd. vendas</span>
                                            <span class="fw-bold text-dark">
                                                {{ \App\Models\Sale::where('user_id', Auth::id())->count() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card 4: Clientes -->
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-user-friends text-primary me-2"></i>Clientes</span>
                                            <span class="fw-bold text-dark">{{ $totalClientes ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 5: Pendentes -->
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-user-check text-secondary me-2"></i>Pendentes</span>
                                            <span title="Com vendas pendentes">
                                                <i class="fas fa-clock text-danger"></i>
                                                {{ $clientesComSalesPendentes ?? 0 }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 3: Última venda -->
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-calendar-day text-info me-2"></i>Última venda</span>
                                            @if($ultimaVenda)
                                            <span>
                                                {{ \Carbon\Carbon::parse($ultimaVenda->created_at)->format('d/m') }}
                                                <span class="text-success">
                                                    R$ {{ number_format($ultimaVenda->total_price, 2, ',', '.') }}
                                                </span>
                                            </span>
                                            @else
                                            <span class="text-muted">Nenhuma venda</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
:root {
    --primary-bg: #1a2238;
    --secondary-bg: #28304a;
    --accent: #e94560;
    --card-bg: #232b43;
    --text-main: #f5f6fa;
    --text-muted: #a0a4b8;
    --border-radius: 18px;
    --shadow: 0 4px 32px rgba(20, 30, 60, 0.18);
    --spacing: 1.5rem;
    --font-main: 'Segoe UI', 'Roboto', Arial, sans-serif;
}

body {
    background: var(--primary-bg);
    color: var(--text-main);
    font-family: var(--font-main);
    margin: 0;
    padding: 0;
}

.container-fluid {
    padding: var(--spacing);
}

.card {
    background: var(--card-bg);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    border: none;
    color: var(--text-main);
    margin-bottom: var(--spacing);
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 8px 40px rgba(233, 69, 96, 0.18);
}

.card-body {
    padding: 2rem 1.5rem 1.5rem 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: space-between;

}

.card-body .mb-3 {
    width: 100%;
}

.cashbook-stats-row {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: stretch;
    width: 100%;
    gap: 1.2rem;
}

.cashbook-stat-col {
    flex: 1 1 0;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    padding: 0.7rem 0.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 0;
}

.cashbook-extra-row {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 1.2rem;
}

.cashbook-extra-col {
    flex: 1 1 0;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 10px;
    padding: 0.5rem 0.7rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-width: 0;
    font-size: 0.98em;
}

@media (max-width: 991.98px) {
    .cashbook-stats-row {
        flex-direction: column;
        gap: 0.7rem;
    }

    .cashbook-stat-col {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 0.7rem 1rem;
    }

    .cashbook-stat-col span {
        margin: 0 0.3rem;
    }

    .cashbook-extra-row {
        flex-direction: column;
        gap: 0.7rem;
    }

    .cashbook-extra-col {
        width: 100%;
        align-items: flex-start;
        padding: 0.7rem 1rem;
    }
}

.produtos-header-flex,
.vendas-header-flex {
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 100%;
    justify-content: space-between;
    margin-bottom: 1.2rem;
}

.produtos-header-left,
.vendas-header-left {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 0.7rem;
}

.produtos-header-center,
.vendas-header-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex: 1 1 0;
    gap: 0.1rem;
}

.produtos-header-right,
.vendas-header-right {
    width: 48px;
    min-width: 48px;
}

.cashbook-header-flex {
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 100%;
    justify-content: space-between;
    margin-bottom: 1.2rem;
}

.cashbook-header-left {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 0.7rem;
}

.cashbook-header-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex: 1 1 0;
    gap: 0.1rem;
}

.cashbook-header-right {
    width: 48px;
    min-width: 48px;
    /* Espaço para balancear o centro */
}

.card-body .d-flex.align-items-center.mb-2 {
    justify-content: flex-start;
    gap: 0.7rem;
}

.card-body h3 {
    margin-bottom: 0;
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--accent);
}

.card-link-wrapper {
    text-decoration: none;
    color: inherit;
    display: block;
    border-radius: var(--border-radius);
    transition: box-shadow 0.2s;
}

.card-link-wrapper:focus-visible {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
}

.icon-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--secondary-bg);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    margin-bottom: 0.2rem;
    margin-right: 0.7rem;
}

.icon-circle i {
    font-size: 2rem;
    color: var(--accent);
}

.display-6,
.fw-bold.display-6 {
    font-size: 2.1rem;
    font-weight: 700;
    color: var(--accent);
    margin: 0.5rem 0;
}

h1.display-5 {
    color: var(--accent);
    font-weight: 800;
    letter-spacing: -1.5px;
}

h3 {
    color: var(--text-main);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.2rem;
}

.text-muted {
    color: var(--text-muted) !important;
}

.list-group-item {
    background: transparent;
    border: none;
    padding: .7rem 0;
    color: var(--text-main);
}

.list-group-item .fw-bold {
    color: var(--accent);
}

@media (max-width: 991.98px) {
    .card-body {
        min-height: 300px;
        padding: 1.2rem 0.7rem 1rem 0.7rem;
    }

    .icon-circle {
        width: 36px;
        height: 36px;
        font-size: 1.2rem;
    }

    .display-6,
    .fw-bold.display-6 {
        font-size: 1.3rem;
    }
}

@media (max-width: 575.98px) {
    .card-body {
        min-height: 180px;
        padding: 1rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-body:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .icon-circle {
        width: 32px;
        height: 32px;
        font-size: 1rem;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--accent), rgba(var(--accent-rgb), 0.8));
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .icon-circle:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
}
</style>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cashbook Chart
    new Chart(document.getElementById('cashbookChart'), {
        type: 'doughnut',
        data: {
            labels: ['Receitas', 'Despesas'],
            datasets: [{
                data: [{
                        {
                            \
                            App\ Models\ Cashbook::where('user_id', Auth::id()) - > where(
                                'type_id', 1) - > sum('value')
                        }
                    },
                    {
                        {
                            \
                            App\ Models\ Cashbook::where('user_id', Auth::id()) - > where(
                                'type_id', 2) - > sum('value')
                        }
                    }
                ],
                backgroundColor: ['#28a745', '#dc3545'],
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true
                }
            },
            cutout: '75%'
        }
    });

    // Produtos Chart (lucro possível)
    const vendaTotal = ({
        {
            \
            App\ Models\ Product::where('user_id', Auth::id()) - > sum(DB::raw(
                'price_sale * stock_quantity'))
        }
    });
    const custoTotal = ({
        {
            \
            App\ Models\ Product::where('user_id', Auth::id()) - > sum(DB::raw(
                'price * stock_quantity'))
        }
    });
    const lucroPossivel = vendaTotal - custoTotal;

    document.getElementById('produtosChartCenter').innerHTML =
        '<div style="font-size:1.1rem;color:#6c757d;">Lucro Previsto</div>' +
        '<div style="font-size:1.3rem;color:#28a745;">R$ ' + lucroPossivel.toLocaleString('pt-BR', {
            minimumFractionDigits: 2
        }) + '</div>';

    new Chart(document.getElementById('produtosChart'), {
        type: 'doughnut',
        data: {
            labels: [' ', 'Custo Estoque', 'Venda Total'],
            datasets: [{
                data: [, custoTotal, vendaTotal],
                backgroundColor: ['#6c757d', '#17a2b8'],
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false,
                    position: 'bottom'
                },
                tooltip: {
                    enabled: true
                }
            },
            cutout: '75%'
        }
    });

    // Vendas Chart (faturamento x faltante)
    const valorRecebido = ({
        {
            $totalFaturamento ?? 0
        }
    } - {
        {
            $totalFaltante ?? 0
        }
    });
    document.getElementById('vendasChartCenter').innerHTML =
        '<div style="font-size:1.1rem;color:#6c757d;">Recebido</div>' +
        '<div style="font-size:1.3rem;color:#28a745;">R$ ' + valorRecebido.toLocaleString('pt-BR', {
            minimumFractionDigits: 2
        }) + '</div>';

    new Chart(document.getElementById('vendasChart'), {
        type: 'doughnut',
        data: {
            labels: ['Faturado', 'Faltante'],
            datasets: [{
                data: [{
                        {
                            $totalFaturamento ?? 0
                        }
                    },
                    {
                        {
                            $totalFaltante ?? 0
                        }
                    }
                ],
                backgroundColor: ['#ffc107', '#dc3545'],
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false,
                    position: 'bottom'
                },
                tooltip: {
                    enabled: true
                }
            },
            cutout: '75%'
        }
    });
});
</script>
@endpush

@endsection