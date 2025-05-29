@extends('layouts.user_type.auth')

@section('content')

<div class="row align-items-stretch" style="height: 83vh;">
    {{-- Card Cashbook --}}
    <div class="col-md-4 mb-4 d-flex" style="height: 83vh;">
        <a href="{{ url('dashboard/cashbook') }}" class="card shadow-lg border-0 w-100 text-decoration-none text-dark d-flex flex-column quick-access-card" style="height:83vh; border-radius:1.5rem; transition:box-shadow .2s,transform .2s;">
            <div class="card-body d-flex flex-column justify-content-between align-items-stretch flex-grow-1 p-4" style="overflow:auto; position:relative;">
                <div class="d-flex align-items-center " style="gap: 1rem;">
                    <span class="rounded-circle bg-gradient-success d-flex align-items-center justify-content-center shadow" style="width:60px; height:60px; border:4px solid #fff;">
                        <i class="fas fa-wallet text-white fa-2x"></i>
                    </span>
                    <h3 class="fw-bold mb-0 text-success" style="font-size:2rem;">Cashbook</h3>
                </div>
                <div class="text-start flex-grow-1">
                    <div class="mb-2 fs-5">
                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                        <span class="fw-bold text-secondary">Saldo total:</span>
                        <span class="fw-bold {{ ($totalCashbook ?? 0) >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:1.5rem;">
                            R$ {{ number_format($totalCashbook ?? 0, 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="mb-2 fs-6">
                        <i class="fas fa-exchange-alt text-primary me-2"></i>
                        <span class="fw-bold text-secondary">Movimentações:</span>
                        <span class="fw-bold text-dark">{{ \App\Models\Cashbook::where('user_id', Auth::id())->count() }}</span>
                    </div>
                    <div class="mb-2 fs-6">
                        <i class="fas fa-arrow-up text-success me-2"></i>
                        <span class="fw-bold text-secondary">Receitas:</span>
                        <span class="fw-bold text-success">
                            R$ {{ number_format(\App\Models\Cashbook::where('user_id', Auth::id())->where('type_id', 1)->sum('value'), 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="mb-2 fs-6">
                        <i class="fas fa-arrow-down text-danger me-2"></i>
                        <span class="fw-bold text-secondary">Despesas:</span>
                        <span class="fw-bold text-danger">
                            R$ {{ number_format(\App\Models\Cashbook::where('user_id', Auth::id())->where('type_id', 2)->sum('value'), 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="mb-2 fs-6">
                        <i class="fas fa-calendar-day text-info me-2"></i>
                        <span class="fw-bold text-secondary">Última movimentação:</span>
                        @if($ultimaMovimentacaoCashbook)
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($ultimaMovimentacaoCashbook->date)->format('d/m/Y') }}</span>
                            <span class="{{ $ultimaMovimentacaoCashbook->type_id == 1 ? 'text-success' : 'text-danger' }}">
                                R$ {{ number_format($ultimaMovimentacaoCashbook->value, 2, ',', '.') }}
                            </span>
                        @else
                            <span class="text-muted">Nenhuma movimentação</span>
                        @endif
                    </div>
                    <div class="mb-2 fs-6">
                        <i class="fas fa-calendar-week text-secondary me-2"></i>
                        <span class="fw-bold text-secondary">Média diária:</span>
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
                <div class="position-relative w-100  d-flex justify-content-center align-items-center flex-grow-1">
                    <canvas id="cashbookChart" style="max-width:100%; max-height:100%; width:100%; height:100%;"></canvas>
                    <div id="cashbookChartCenter" class="position-absolute top-50 start-50 translate-middle text-success fw-bold" style="font-size:2rem; pointer-events:none;">
                        R$ {{ number_format($totalCashbook ?? 0, 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </a>
    </div>
    {{-- Card Produtos --}}
    <div class="col-md-4 mb-4 d-flex" style="height: 83vh;">
        <a href="{{ url('dashboard/products') }}" class="card shadow-lg border-0 w-100 text-decoration-none text-dark d-flex flex-column quick-access-card" style="height:83vh; border-radius:1.5rem; transition:box-shadow .2s,transform .2s;">
            <div class="card-body d-flex flex-column justify-content-between align-items-stretch flex-grow-1 p-4" style="overflow:auto; position:relative;">
                <div class="d-flex align-items-center" style="gap: 1rem;">
                    <span class="rounded-circle bg-gradient-info d-flex align-items-center justify-content-center shadow" style="width:60px; height:60px; border:4px solid #fff;">
                        <i class="fas fa-box text-white fa-2x"></i>
                    </span>
                    <h3 class="fw-bold mb-0 text-info" style="font-size:2rem;">Produtos</h3>
                </div>
                <div class="text-start flex-grow-1">
                    <span title="Total de produtos" class="me-2">
                        <i class="fas fa-cubes text-info"></i>
                        {{ $totalProdutos ?? 0 }}
                    </span>
                    <div class="mb-2 fs-5">
                        <i class="fas fa-warehouse text-warning me-2"></i>
                        <span class="fw-bold text-secondary">Total em estoque:</span>
                        <span class="fw-bold text-dark">{{ $totalProdutosEstoque ?? 0 }}</span>
                    </div>
                    <div class="mb-2 fs-5">
                        <i class="fas fa-box-open text-primary me-2"></i>
                        <span class="fw-bold text-secondary">Maior estoque:</span>
                        @if($produtoMaiorEstoque)
                            <span class="fw-bold">{{ $produtoMaiorEstoque->name }}</span>
                            <span class="text-info">({{ $produtoMaiorEstoque->stock_quantity }})</span>
                        @else
                            <span class="text-muted">Nenhum produto cadastrado</span>
                        @endif
                    </div>
                    <div class="mb-2 fs-5">
                        <i class="fas fa-star text-warning me-2"></i>
                        <span class="fw-bold text-secondary">Produto mais vendido:</span>
                        <span class="fw-bold text-success">
                            {{ $produtoMaisVendido ? $produtoMaisVendido->name : 'N/A' }}
                        </span>
                    </div>
               
                    <div class="mb-2 fs-6">
                        <i class="fas fa-boxes-stacked text-secondary me-2"></i>
                        <span class="fw-bold text-secondary">Produtos sem estoque:</span>
                        <span class="fw-bold text-danger">
                            {{ \App\Models\Product::where('user_id', Auth::id())->where('stock_quantity', 0)->count() }}
                        </span>
                    </div>
                </div>
                <div class="position-relative w-100 d-flex justify-content-center align-items-center flex-grow-1">
                    <canvas id="produtosChart" style="max-width:100%; max-height:100%; width:100%; height:100%;"></canvas>
                    <div id="produtosChartCenter" class="position-absolute top-50 start-50 translate-middle text-info fw-bold" style="font-size:2rem; pointer-events:none;">
                        <!-- Lucro total será preenchido via JS -->
                    </div>
                </div>
            </div>
        </a>
    </div>
    {{-- Card Vendas --}}
    <div class="col-md-4 mb-4 d-flex" style="height: 83vh;">
        <a href="{{ url('dashboard/sales') }}" class="card shadow-lg border-0 w-100 text-decoration-none text-dark d-flex flex-column quick-access-card" style="height:83vh; border-radius:1.5rem; transition:box-shadow .2s,transform .2s;">
            <div class="card-body d-flex flex-column justify-content-between align-items-stretch flex-grow-1 p-4" style="overflow:auto; position:relative;">
                <div class="d-flex align-items-center " style="gap: 1rem;">
                    <span class="rounded-circle bg-gradient-warning d-flex align-items-center justify-content-center shadow" style="width:60px; height:60px; border:4px solid #fff;">
                        <i class="fas fa-shopping-cart text-white fa-2x"></i>
                    </span>
                    <h3 class="fw-bold mb-0 text-warning" style="font-size:2rem;">Vendas</h3>
                </div>
                <div class="text-start flex-grow-1">
                    <div class="mb-2 fs-5">
                        <i class="fas fa-coins text-warning me-2"></i>
                        <span class="fw-bold text-secondary">Total faturado:</span>
                        <span class="fw-bold text-success" style="font-size:1.5rem;">
                            R$ {{ number_format($totalFaturamento ?? 0, 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="mb-2 fs-5">
                        <i class="fas fa-file-invoice-dollar text-danger me-2"></i>
                        <span class="fw-bold text-secondary">Valor faltante:</span>
                        <span class="fw-bold text-danger">
                            R$ {{ number_format($totalFaltante ?? 0, 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="mb-2 fs-5">
                        <i class="fas fa-list-ol text-primary me-2"></i>
                        <span class="fw-bold text-secondary">Quantidade de vendas:</span>
                        <span class="fw-bold text-dark">
                            {{ \App\Models\Sale::where('user_id', Auth::id())->count() }}
                        </span>
                    </div>
                    <div class="mb-2 fs-5">
                        <i class="fas fa-calendar-day text-info me-2"></i>
                        <span class="fw-bold text-secondary">Última venda:</span>
                        @if($ultimaVenda)
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($ultimaVenda->created_at)->format('d/m/Y H:i') }}</span>
                            <span class="text-success">
                                R$ {{ number_format($ultimaVenda->total_price, 2, ',', '.') }}
                            </span>
                        @else
                            <span class="text-muted">Nenhuma venda realizada</span>
                        @endif
                    </div>
                    <span title="Total de clientes" class="me-2">
                        <i class="fas fa-user-friends text-primary"></i>
                        {{ $totalClientes ?? 0 }}
                    </span>
                    <div class="mb-2 fs-5">
                        <i class="fas fa-user-check text-secondary me-2"></i>
                        <span class="fw-bold text-secondary">Clientes com vendas pendentes:</span>
                        <span title="Com vendas pendentes">
                            <i class="fas fa-clock text-danger"></i>
                            {{ $clientesComSalesPendentes ?? 0 }}
                        </span>
                    </div>
                </div>
                <div class="position-relative w-100 d-flex justify-content-center align-items-center flex-grow-1">
                    <canvas id="vendasChart" style="max-width:100%; max-height:100%; width:100%; height:100%;"></canvas>
                    <div id="vendasChartCenter" class="position-absolute top-50 start-50 translate-middle text-warning fw-bold" style="font-size:2rem; pointer-events:none;">
                        R$ {{ number_format($totalFaturamento ?? 0, 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
.quick-access-card:hover, .quick-access-card:focus {
    transform: translateY(-4px) scale(1.03);
    box-shadow: 0 8px 24px rgba(0,0,0,0.10);
    text-decoration: none;
    border: 2px solid #0dcaf0;
}
.quick-access-card .rounded-circle {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.quick-access-card .fa-wallet { background: linear-gradient(135deg,#198754 60%,#28a745 100%);}
.quick-access-card .fa-box { background: linear-gradient(135deg,#0dcaf0 60%,#17a2b8 100%);}
.quick-access-card .fa-shopping-cart { background: linear-gradient(135deg,#ffc107 60%,#fd7e14 100%);}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Cashbook Chart (exemplo: receitas vs despesas)
    new Chart(document.getElementById('cashbookChart'), {
        type: 'doughnut',
        data: {
            labels: ['Receitas', 'Despesas'],
            datasets: [{
                data: [
                    {{ \App\Models\Cashbook::where('user_id', Auth::id())->where('type_id', 1)->sum('value') }},
                    {{ \App\Models\Cashbook::where('user_id', Auth::id())->where('type_id', 2)->sum('value') }}
                ],
                backgroundColor: ['#28a745', '#dc3545'],
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            cutout: '75%'
        }
    });

    // Produtos Chart (lucro possível)
    const vendaTotal = (
        {{ \App\Models\Product::where('user_id', Auth::id())->sum(DB::raw('price_sale * stock_quantity')) }}
    );
    const custoTotal = (
        {{ \App\Models\Product::where('user_id', Auth::id())->sum(DB::raw('price * stock_quantity')) }}
    );
    const lucroPossivel = vendaTotal - custoTotal;

    // Centraliza APENAS o lucro previsto no meio do gráfico de produtos
    document.getElementById('produtosChartCenter').innerHTML =
        '<div style="font-size:1.1rem;color:#6c757d;">Lucro Previsto</div>' +
        '<div style="font-size:1.7rem;color:#28a745;">R$ ' + lucroPossivel.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</div>';

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
                legend: { display: false, position: 'bottom' },
                tooltip: { enabled: true }
            },
            cutout: '75%'
        }
    });

    // Vendas Chart (faturamento x faltante)
    // Centraliza o valor recebido no meio do gráfico de vendas
    const valorRecebido = ({{ $totalFaturamento ?? 0 }} - {{ $totalFaltante ?? 0 }});
    document.getElementById('vendasChartCenter').innerHTML =
        '<div style="font-size:1.1rem;color:#6c757d;">Recebido</div>' +
        '<div style="font-size:1.7rem;color:#28a745;">R$ ' + valorRecebido.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</div>';

    new Chart(document.getElementById('vendasChart'), {
        type: 'doughnut',
        data: {
            labels: ['Faturado', 'Faltante'],
            datasets: [{
                data: [
                    {{ $totalFaturamento ?? 0 }},
                    {{ $totalFaltante ?? 0 }}
                ],
                backgroundColor: ['#ffc107', '#dc3545'],
            }]
        },
        options: {
            plugins: {
                legend: { display: false, position: 'bottom' },
                tooltip: { enabled: true }
            },
            cutout: '75%'
        }
    });
});
</script>
@endpush

@endsection
