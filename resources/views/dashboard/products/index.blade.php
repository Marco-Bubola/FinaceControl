@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid px-0">
    {{-- Linha de cards compactos: botão + KPIs + destaques --}}
    <div class="row g-3 mb-4 align-items-stretch flex-nowrap flex-md-wrap" style="overflow-x:auto;">
        {{-- Card: Acesso Produtos --}}
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-2">
            <a href="{{ url('products') }}" class="w-100 text-decoration-none">
                <div class="card shadow-sm border-0 text-center h-100 py-2 card-hover bg-white" style="min-height: 110px;">
                    <div class="card-body p-2 d-flex flex-column align-items-center" style="gap: 0.15rem;">
                        <span class="icon icon-shape icon-md shadow border-radius-md bg-light text-center mb-1 d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-box2 text-primary" viewBox="0 0 16 16">
                                <path d="M2.95.4a1 1 0 0 1 .8-.4h8.5a1 1 0 0 1 .8.4l2.85 3.8a.5.5 0 0 1 .1.3V15a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4.5a.5.5 0 0 1 .1-.3zM7.5 1H3.75L1.5 4h6zm1 0v3h6l-2.25-3zM15 5H1v10h14z"/>
                            </svg>
                        </span>
                        <span class="fw-bold text-primary small">Produtos</span>
                        <div class="d-flex flex-column align-items-center mt-0 w-100" style="gap: 0.10rem;">
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <i class="fas fa-cubes fa-xs text-primary me-1"></i>
                                <span class="fw-bold text-secondary small">Total:</span>
                                <span class="fw-bold small ms-1">{{ $totalProdutos ?? 0 }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <i class="fas fa-boxes fa-xs text-secondary me-1"></i>
                                <span class="fw-bold text-secondary small">Estoque:</span>
                                <span class="fw-bold small ms-1">{{ $totalProdutosEstoque ?? 0 }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="fw-bold small text-muted">Sem estoque:</span>
                                <span class="fw-bold small text-danger ms-1">{{ $produtosSemEstoque ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Card: Maior Estoque --}}
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-2">
            <div class="card shadow-sm text-center h-100 d-flex flex-column justify-content-between">
                <div class="card-body py-2 px-1 d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center justify-content-center mb-1 gap-2">
                        <span class="fs-5 text-primary"><i class="fas fa-box-open"></i></span>
                        <span class="fw-bold small text-primary">Maior Estoque</span>
                    </div>
                    @if($produtoMaiorEstoque && !empty($produtoMaiorEstoque->image))
                        <img src="{{ asset('storage/products/' . $produtoMaiorEstoque->image) }}" alt="{{ $produtoMaiorEstoque->name }}" class="mb-1" style="width:80px;height:80px;object-fit:cover;border-radius:10px;">
                    @endif
                    <div class="fw-bold small text-truncate">
                        @if($produtoMaiorEstoque)
                            {{ \Illuminate\Support\Str::limit($produtoMaiorEstoque->name, 18) }}
                            <span class="text-info">({{ $produtoMaiorEstoque->stock_quantity }})</span>
                        @else
                            <span class="text-muted">Nenhum</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Mais Vendido --}}
        <div class="col-12 col-sm-6 col-md-3 col-lg-2 mb-2">
            <div class="card shadow-sm text-center h-100 d-flex flex-column justify-content-between">
                <div class="card-body py-2 px-1 d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center justify-content-center mb-1 gap-2">
                        <span class="fs-5 text-success"><i class="fas fa-star"></i></span>
                        <span class="fw-bold small text-success">Mais Vendido</span>
                    </div>
                    @if($produtoMaisVendido && !empty($produtoMaisVendido->image))
                        <img src="{{ asset('storage/products/' . $produtoMaisVendido->image) }}" alt="{{ $produtoMaisVendido->name }}" class="mb-1" style="width:80px;height:80px;object-fit:cover;border-radius:10px;">
                    @endif
                    <div class="fw-bold small text-truncate">
                        {{ $produtoMaisVendido ? \Illuminate\Support\Str::limit($produtoMaisVendido->name, 18) : 'N/A' }}
                    </div>
                    <span class="text-muted small">Qtd: {{ $produtosMaisVendidos->first()->total_vendido ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Card: Maior Receita --}}
        <div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-2">
            <div class="card shadow-sm text-center h-100 d-flex flex-column justify-content-between">
                <div class="card-body py-2 px-1 d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center justify-content-center mb-1 gap-2">
                        <span class="fs-5 text-secondary"><i class="fas fa-coins"></i></span>
                        <span class="fw-bold small text-secondary">Maior Receita</span>
                    </div>
                    @php
                        $prodMaiorReceita = \App\Models\Product::where('product_code', $produtosMaiorReceita->first()->product_code ?? null)->first();
                    @endphp
                    @if($prodMaiorReceita && !empty($prodMaiorReceita->image))
                        <img src="{{ asset('storage/products/' . $prodMaiorReceita->image) }}" alt="{{ $prodMaiorReceita->name }}" class="mb-1" style="width:80px;height:80px;object-fit:cover;border-radius:10px;">
                    @endif
                    <div class="fw-bold small text-truncate">
                        {{ $prodMaiorReceita ? \Illuminate\Support\Str::limit($prodMaiorReceita->name, 18) : '-' }}
                    </div>
                    <span class="text-muted small">R$ {{ number_format($produtosMaiorReceita->first()->receita_total ?? 0, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Card: Maior Lucro --}}
        <div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-2">
            <div class="card shadow-sm text-center h-100 d-flex flex-column justify-content-between">
                <div class="card-body py-2 px-1 d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center justify-content-center mb-1 gap-2">
                        <span class="fs-5 text-warning"><i class="fas fa-money-bill-wave"></i></span>
                        <span class="fw-bold small text-warning">Maior Lucro</span>
                    </div>
                    @php
                        $prodMaiorLucro = \App\Models\Product::where('product_code', $produtoMaiorLucro->first()->product_code ?? null)->first();
                    @endphp
                    @if($prodMaiorLucro && !empty($prodMaiorLucro->image))
                        <img src="{{ asset('storage/products/' . $prodMaiorLucro->image) }}" alt="{{ $prodMaiorLucro->name }}" class="mb-1" style="width:80px;height:80px;object-fit:cover;border-radius:10px;">
                    @endif
                    <div class="fw-bold small text-truncate">
                        {{ $prodMaiorLucro ? \Illuminate\Support\Str::limit($prodMaiorLucro->name, 18) : '-' }}
                    </div>
                    <span class="text-muted small">R$ {{ number_format($produtoMaiorLucro->first()->lucro_total ?? 0, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráficos principais --}}
    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-header pb-0"><h6>Últimos Produtos Adicionados (Estoque > 0)</h6></div>
                <div class="card-body">
                    <div class="row">
                        @forelse($ultimosProdutos as $produto)
                            <div class="col-3 mb-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    @if(!empty($produto->image))
                                        <img src="{{ asset('storage/products/' . $produto->image) }}" class="product-img" alt="{{ $produto->name }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:70px;">
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
        <div class="col-md-4">
            <div class="card shadow-sm ">
                <div class="card-header pb-0"><h6 class="mb-0">Receitas x Despesas dos Produtos</h6></div>
                <div class="position-relative w-100 d-flex justify-content-center align-items-center flex-grow-1" style="min-height:260px;">
                    <canvas id="produtosChart" style="max-width:100%; max-height:100%; width:100%; height:100%;"></canvas>
                    <div id="produtosChartCenter" class="position-absolute top-50 start-50 translate-middle text-info fw-bold" style="font-size:2rem; pointer-events:none;">
                        <!-- Lucro total será preenchido via JS -->
                    </div>
                </div>
            </div>
              <div class="card shadow-sm">
                <div class="card-header pb-0"><h6 class="mb-0">Vendas por Categoria</h6></div>
                <div class="card-body">
                    <canvas id="pieChart" height="180"></canvas>
                </div>
            </div>
        </div>
       
    </div>

    {{-- Listas e gráficos auxiliares --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header pb-0"><h6 class="mb-0">Vendas por Produto (Top 10)</h6></div>
                <div class="card-body">
                    <canvas id="barChartProdutos" height="180"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header pb-0"><h6>Produtos com Estoque Baixo e Alta Demanda</h6></div>
                <div class="card-body">
                    <canvas id="estoqueBaixoAltaDemandaChart" height="120"></canvas>
                </div>
            </div>
        </div>
      
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico Doughnut de Receitas e Despesas dos Produtos + Lucro centralizado (nova aparência)
    document.addEventListener('DOMContentLoaded', function() {
       
        
        // Gráfico de Barras: Vendas por Produto (Top 10)
        const barChartCanvas = document.getElementById('barChartProdutos');
        if (barChartCanvas) {
            const labels = {!! json_encode($produtosMaisVendidos->map(function($p) {
                return \App\Models\Product::where('product_code', $p->product_code)->value('name') ?? 'Produto removido';
            })) !!};
            const shortLabels = labels.map(name => name ? name.charAt(0) : '-');
            new Chart(barChartCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: shortLabels,
                    datasets: [{
                        label: 'Qtd Vendida',
                        data: {!! json_encode($produtosMaisVendidos->pluck('total_vendido')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    return labels[context[0].dataIndex] || '-';
                                }
                            }
                        }
                    },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Gráfico de Pizza: Vendas por Categoria
        const pieChartCanvas = document.getElementById('pieChart');
        if (pieChartCanvas) {
            const ctx = pieChartCanvas.getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($dadosGraficoPizza->pluck('category_name')) !!},
                    datasets: [{
                        data: {!! json_encode($dadosGraficoPizza->pluck('total_sold')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        }

        // Produtos Chart (lucro possível)
        const vendaTotal = (
            {{ \App\Models\Product::where('user_id', Auth::id())->sum(DB::raw('price_sale * stock_quantity')) }}
        );
        const custoTotal = (
            {{ \App\Models\Product::where('user_id', Auth::id())->sum(DB::raw('price * stock_quantity')) }}
        );
        const lucroPossivel = vendaTotal - custoTotal;

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

        // Produtos com Estoque Baixo e Alta Demanda (Barra)
        const ctxEstoque = document.getElementById('estoqueBaixoAltaDemandaChart');
        if (ctxEstoque) {
            const labels = {!! json_encode($produtosEstoqueBaixoAltaDemanda->map(function($p) {
                return \App\Models\Product::where('product_code', $p->product_code)->value('name') ?? 'Produto removido';
            })) !!};
            new Chart(ctxEstoque.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Qtd Vendida',
                        data: {!! json_encode($produtosEstoqueBaixoAltaDemanda->pluck('total_vendido')) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                afterBody: function(context) {
                                    const idx = context[0].dataIndex;
                                    const estoque = {!! json_encode($produtosEstoqueBaixoAltaDemanda->pluck('stock_quantity')) !!};
                                    return 'Estoque: ' + estoque[idx];
                                }
                            }
                        }
                    },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    });
</script>
@endpush
@endsection