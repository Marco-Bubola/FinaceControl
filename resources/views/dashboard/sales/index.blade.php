@extends('layouts.user_type.auth')

@section('content')


{{-- SEÇÃO: Painel Resumido --}}
<div class="container-fluid px-1">
    <div class="row justify-content-center mb-1 g-2 flex-nowrap" style="overflow-x:auto;">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-end" style="min-width:270px;">
            <a href="{{ url('sales') }}" class="w-100 text-decoration-none">
                <div class="card shadow-sm border-0 text-center h-100 py-2 card-hover bg-white" style="min-height: 110px;">
                    <div class="card-body p-2 d-flex flex-column align-items-center" style="gap: 0.25rem;">
                        <span class="icon icon-shape icon-md shadow border-radius-md bg-light text-center mb-1 d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-cart-fill text-warning" viewBox="0 0 16 16">
                                <path d="M4 0a1 1 0 0 0-1 1v1h12V1a1 1 0 0 0-1-1H4zM0 4a1 1 0 0 1 1-1h1l1 6h8l1-6h1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 7v1h2V6H3v5zm3 0v1h2V7H6v4zm3 0v1h2V8H9v3zm3 0v1h2V9h-2v2z" />
                            </svg>
                        </span>
                        <span class="fw-bold text-warning small">Vendas</span>
                        <div class="d-flex flex-column align-items-center mt-0 w-100" style="gap: 0.15rem;">
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <i class="fas fa-list-ol fa-xs text-primary me-1"></i>
                                <span class="fw-bold text-secondary small">Total:</span>
                                <span class="fw-bold small ms-1">{{ \App\Models\Sale::where('user_id', Auth::id())->count() }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <i class="fas fa-calendar-day fa-xs text-info me-1"></i>
                                <span class="fw-bold text-secondary small">Últ. venda:</span>
                                @if($ultimaVenda)
                                    <span class="fw-bold small ms-1">{{ \Carbon\Carbon::parse($ultimaVenda->created_at)->format('d/m/Y H:i') }}</span>
                                    <span class="text-success small ms-1">
                                        R$ {{ number_format($ultimaVenda->total_price, 2, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted small ms-1">Nenhuma</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <i class="fas fa-coins fa-xs text-warning me-1"></i>
                                <span class="fw-bold text-secondary small">Faturado:</span>
                                <span class="fw-bold small text-success ms-1">
                                    R$ {{ number_format($totalFaturamento ?? 0, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-file-invoice-dollar fa-xs text-danger me-1"></i>
                                <span class="fw-bold text-secondary small">Faltante:</span>
                                <span class="fw-bold small text-danger ms-1">
                                    R$ {{ number_format($totalFaltante ?? 0, 2, ',', '.') }}
                                </span>
                            </div>
                            {{-- INÍCIO: Informações de Vendas Mês/Ano movidas para cá --}}
                            <div class="row text-center g-0 w-100 mt-1" style="font-size: 0.95em;">
                                <div class="col-6 mb-0">
                                    <div class="fw-bold text-muted small">Mês Atual</div>
                                    <div class="fw-bold text-success fs-8">R$ {{ number_format($vendasMesAtual, 2, ',', '.') }}</div>
                                </div>
                                <div class="col-6 mb-0">
                                    <div class="fw-bold text-muted small">Mês Anterior</div>
                                    <div class="fw-bold text-primary fs-8">R$ {{ number_format($vendasMesAnterior, 2, ',', '.') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold text-muted small">Ano Atual</div>
                                    <div class="fw-bold text-success fs-8">R$ {{ number_format($vendasAnoAtual, 2, ',', '.') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold text-muted small">Ano Anterior</div>
                                    <div class="fw-bold text-primary fs-8">R$ {{ number_format($vendasAnoAnterior, 2, ',', '.') }}</div>
                                </div>
                            </div>
                            {{-- FIM: Informações de Vendas Mês/Ano --}}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center align-items-center" style="min-width:270px;">
            <div class="card shadow-sm border-0 text-center h-100 py-2 card-hover bg-white d-flex align-items-center justify-content-center" style="min-height: 140px;">
                <div class="position-relative w-100 d-flex justify-content-center align-items-center flex-grow-1" style="height:130px; min-height:130px; max-width:260px;">
                    <canvas id="vendasChart" style="width:220px; height:120px; max-width:220px; max-height:120px; display:block; margin:auto;"></canvas>
                    <div id="vendasChartCenter" class="position-absolute top-50 start-50 translate-middle text-warning fw-bold" style="font-size:2rem; pointer-events:none;">
                        R$ {{ number_format($totalFaturamento ?? 0, 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-start" style="min-width:270px;">
            <a href="{{ url('clients') }}" class="w-100 text-decoration-none">
                <div class="card shadow-sm border-0 text-center h-100 py-2 card-hover bg-white" style="min-height: 110px;">
                    <div class="card-body p-2 d-flex flex-column align-items-center" style="gap: 0.25rem;">
                        <span class="icon icon-shape icon-md shadow border-radius-md bg-light text-center mb-1 d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-workspace text-primary" viewBox="0 0 16 16">
                                <path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                <path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.4 5.4 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2z" />
                            </svg>
                        </span>
                        <span class="fw-bold text-primary small">Clientes</span>
                        <div class="d-flex flex-column align-items-center mt-0 w-100" style="gap: 0.15rem;">
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <i class="fas fa-user-friends fa-xs text-primary me-1"></i>
                                <span class="fw-bold text-secondary small">Total:</span>
                                <span class="fw-bold small ms-1">{{ $totalClientes ?? 0 }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <i class="fas fa-user-check fa-xs text-secondary me-1"></i>
                                <span class="fw-bold text-secondary small">Pendentes:</span>
                                <span class="fw-bold small text-danger ms-1">
                                    <i class="fas fa-clock"></i> {{ $clientesComSalesPendentes ?? 0 }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <span class="fw-bold small text-muted">Inativos (6m):</span>
                                <span class="fw-bold small ms-1">{{ $clientesInativos->count() }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="fw-bold small text-muted">Recorrentes:</span>
                                <span class="fw-bold small ms-1">{{ $clientesRecorrentes->count() }}</span>
                                <span class="fw-bold small text-muted ms-2">Ticket Médio:</span>
                                <span class="fw-bold small ms-1">R$ {{ number_format($ticketMedioRecorrente, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- SEÇÃO: Gráficos Principais --}}
<div class="container-fluid px-1">
    <div class="row g-2 mb-1 flex-nowrap" style="overflow-x:auto;">
        <div class="col-12 col-sm-6 col-md-4" style="min-width:320px;">
            <div class="card shadow-sm border-0 h-100" style="min-height: 170px;">
                <div class="card-header pb-2 pt-2 bg-light border-bottom-0"><h6 class="mb-0 fs-7">Evolução Vendas (12m)</h6></div>
                <div class="card-body p-2">
                    <canvas id="evolucaoVendasChart" height="90"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4" style="min-width:320px;">
            <div class="card shadow-sm border-0 h-100" style="min-height: 170px;">
                <div class="card-header pb-2 pt-2 bg-light border-bottom-0"><h6 class="mb-0 fs-7">Top 10 Clientes</h6></div>
                <div class="card-body p-2">
                    <canvas id="vendasPorClienteChart" height="90"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4" style="min-width:320px;">
            <div class="card shadow-sm border-0 h-100" style="min-height: 170px;">
                <div class="card-header pb-2 pt-2 bg-light border-bottom-0"><h6 class="mb-0 fs-7">Vendas por Status</h6></div>
                <div class="card-body p-2">
                    <canvas id="vendasPorStatusChart" height="90"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- SEÇÃO: Comparativos e Clientes Recorrentes --}}
<div class="container-fluid px-1">
    <div class="row g-2 mb-1 align-items-stretch flex-nowrap" style="overflow-x:auto;">
        <div class="col-12 col-sm-6 col-md-4" style="min-width:320px;">
            <div class="card shadow-sm border-0 h-100" style="min-height: 140px;">
                <div class="card-header pb-2 pt-2 d-flex align-items-center justify-content-between bg-gradient-light" style="border-radius: .5rem .5rem 0 0;">
                    <h6 class="mb-0 fs-7 text-warning d-flex align-items-center">
                        <i class="fas fa-chart-bar me-2"></i>
                        Vendas Mensais
                    </h6>
                    <select id="anoVendasMensais" class="form-select form-select-sm w-auto ms-2" style="min-width: 70px; font-size: 0.85rem; padding: 0.1rem 0.5rem;">
                        @php
                            $anoAtual = date('Y');
                            $primeiroAno = $vendasPorMesEvolucao->min(function($v) { return intval(substr($v->periodo, 0, 4)); }) ?? $anoAtual;
                            $ultimoAno = $vendasPorMesEvolucao->max(function($v) { return intval(substr($v->periodo, 0, 4)); }) ?? $anoAtual;
                        @endphp
                        @for($ano = $ultimoAno; $ano >= $primeiroAno; $ano--)
                            <option value="{{ $ano }}" @if($ano == $anoAtual) selected @endif>{{ $ano }}</option>
                        @endfor
                    </select>
                </div>
                <div class="card-body p-2">
                    <canvas id="vendasMensaisBarChart" height="90"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4" style="min-width:320px;">
            <div class="card shadow-sm border-0 h-100" style="min-height: 140px;">
                <div class="card-header pb-2 pt-2 d-flex justify-content-center align-items-center bg-gradient-light" style="border-radius: .5rem .5rem 0 0;">
                    <h6 class="mb-0 fs-7 d-flex align-items-center" style="font-size: 1rem;">
                        <i class="fas fa-sync-alt text-info me-2"></i>
                        Clientes Recorrentes
                    </h6>
                </div>
                <div class="card-body p-2 d-flex align-items-center justify-content-center" style="height: 110px;">
                    <canvas id="clientesRecorrentesChart" height="90" style="max-height: 90px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SEÇÃO: Clientes com Vendas Pendentes --}}
<div class="container-fluid px-1">
    <div class="row g-2 mt-1">
        <div class="col-12">
            <div class="card shadow border-0 h-100 bg-gradient-light">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-uppercase text-primary fw-bold d-flex align-items-center mb-0" style="letter-spacing: 1.5px; font-size: 1rem;">
                            <i class="fas fa-user-clock me-2"></i>
                            Clientes com Vendas Pendentes
                        </h6>
                        <a href="{{ url('clients') }}" class="btn btn-outline-primary btn-sm" style="font-size: 0.95rem; padding: 0.1rem 0.6rem;">
                            <i class="fas fa-users me-1"></i> Ver todos
                        </a>
                    </div>
                    <div class="row g-2">
                        @forelse($clientesPendentes as $cliente)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-1">
                                <div class="card h-100 border-0 shadow-sm rounded-4 position-relative cliente-card-hover bg-white">
                                    <div class="card-body p-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-user-circle fa-lg text-primary me-2"></i>
                                            <span class="fw-bold small text-truncate" title="{{ $cliente->name }}">{{ $cliente->name }}</span>
                                        </div>
                                        <div class="text-muted mb-1 fs-7 d-flex align-items-center justify-content-between">
                                            <span>
                                                <i class="fas fa-shopping-cart me-1"></i>
                                                <span class="fw-semibold">Pendentes:</span>
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
                                                    class="d-flex align-items-center gap-2 mb-1 py-1 px-1 rounded-3 venda-linha venda-hover
                                                        @if($idx > 0) d-none @endif"
                                                    data-cliente="{{ $cliente->id }}"
                                                    data-sale="{{ $sale->id }}"
                                                    style="cursor:pointer;"
                                                    onclick="if(event.target.tagName !== 'BUTTON'){ window.location='{{ route('sales.show', $sale->id) }}'; }"
                                                >
                                                    <span class="badge bg-primary fs-7 px-2 py-1">
                                                        <i class="fas fa-receipt me-1"></i> #{{ $sale->id }}
                                                    </span>
                                                    <span class="text-dark fw-semibold fs-7">
                                                        <i class="fas fa-money-bill-wave me-1 text-success"></i>
                                                        {{ number_format($sale->total_price, 2, ',', '.') }}
                                                    </span>
                                                    <span class="badge bg-danger fs-7 px-2 py-1 ms-auto">
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
                                <i class="fas fa-smile-beam fa-lg mb-1"></i><br>
                                Nenhum cliente com vendas pendentes.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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

    // Gráfico de Pizza de vendas por categoria
    document.addEventListener('DOMContentLoaded', function() {
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
    });

    // Evolução das Vendas (Linha)
    document.addEventListener('DOMContentLoaded', function() {
        const ctxEvolucao = document.getElementById('evolucaoVendasChart');
        if (ctxEvolucao) {
            new Chart(ctxEvolucao.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($vendasPorMesEvolucao->pluck('periodo')) !!},
                    datasets: [{
                        label: 'Total Vendido',
                        data: {!! json_encode($vendasPorMesEvolucao->pluck('total')) !!},
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.3
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

    // Vendas por Cliente (Barra)
    document.addEventListener('DOMContentLoaded', function() {
        const ctxCliente = document.getElementById('vendasPorClienteChart');
        if (ctxCliente) {
            new Chart(ctxCliente.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($vendasPorCliente->map(fn($v) => optional($v->client)->name ?? 'Cliente removido')) !!},
                    datasets: [{
                        label: 'Total Vendido',
                        data: {!! json_encode($vendasPorCliente->pluck('total_vendas')) !!},
                        backgroundColor: 'rgba(255, 159, 64, 0.7)'
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

    // Recorrência de Clientes (Barra)
    document.addEventListener('DOMContentLoaded', function() {
        const ctxRecorrente = document.getElementById('clientesRecorrentesChart');
        if (ctxRecorrente) {
            new Chart(ctxRecorrente.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($clientesRecorrentes->map(fn($c) => optional($c->client)->name ?? 'Cliente removido')) !!},
                    datasets: [{
                        label: 'Qtd Compras',
                        data: {!! json_encode($clientesRecorrentes->pluck('qtd_vendas')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.7)'
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


    // Vendas por Status
    document.addEventListener('DOMContentLoaded', function() {
        const ctxStatus = document.getElementById('vendasPorStatusChart');
        if (ctxStatus) {
            new Chart(ctxStatus.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($vendasPorStatus->pluck('status')) !!},
                    datasets: [{
                        label: 'Qtd',
                        data: {!! json_encode($vendasPorStatus->pluck('total')) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.7)'
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

    // Gráfico de barras de vendas mensais (Ano Atual) - MOSTRAR TODOS OS MESES
    document.addEventListener('DOMContentLoaded', function() {
        const ctxMensal = document.getElementById('vendasMensaisBarChart');
        if (ctxMensal) {
            // Garante todos os meses, mesmo sem vendas
            const mesesLabel = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
            const anoAtual = (new Date()).getFullYear();
            let dados = Array(12).fill(0);
            {!! $vendasPorMesEvolucao->toJson() !!}.forEach(function(item) {
                const [y, m] = item.periodo.split('-');
                if (parseInt(y) === anoAtual) {
                    const idx = parseInt(m, 10) - 1;
                    if (idx >= 0 && idx < 12) {
                        dados[idx] = parseFloat(item.total);
                    }
                }
            });
            new Chart(ctxMensal.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: mesesLabel,
                    datasets: [{
                        label: 'Total Vendido',
                        data: dados,
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

    // Gráfico de barras de vendas mensais (Ano selecionável, mostra todos os meses) - VISUAL MAIS COMPACTO
    const vendasPorMesEvolucao = {!! $vendasPorMesEvolucao->toJson() !!};
    const mesesLabel = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
    function getDadosMensaisPorAno(ano) {
        let dados = Array(12).fill(0);
        vendasPorMesEvolucao.forEach(function(item) {
            const [y, m] = item.periodo.split('-');
            if (parseInt(y) === parseInt(ano)) {
                const idx = parseInt(m, 10) - 1;
                if (idx >= 0 && idx < 12) {
                    dados[idx] = parseFloat(item.total);
                }
            }
        });
        return dados;
    }
    function renderVendasMensaisChart(ano) {
        const ctxMensal = document.getElementById('vendasMensaisBarChart').getContext('2d');
        if(window.vendasMensaisBarChartInstance) window.vendasMensaisBarChartInstance.destroy();
        window.vendasMensaisBarChartInstance = new Chart(ctxMensal, {
            type: 'bar',
            data: {
                labels: mesesLabel,
                datasets: [{
                    label: 'Total Vendido',
                    data: getDadosMensaisPorAno(ano),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderRadius: 8,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                            }
                        }
                    }
                },
                layout: { padding: { left: 0, right: 0, top: 0, bottom: 0 } },
                scales: { 
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: { 
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        const selectAno = document.getElementById('anoVendasMensais');
        renderVendasMensaisChart(selectAno.value);
        selectAno.addEventListener('change', function() {
            renderVendasMensaisChart(this.value);
        });
    });

    // Gráfico de Clientes Recorrentes (mantido)
    document.addEventListener('DOMContentLoaded', function() {
        const ctxRecorrente = document.getElementById('clientesRecorrentesChart');
        if (ctxRecorrente) {
            new Chart(ctxRecorrente.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($clientesRecorrentes->map(fn($c) => optional($c->client)->name ?? 'Cliente removido')) !!},
                    datasets: [{
                        label: 'Qtd Compras',
                        data: {!! json_encode($clientesRecorrentes->pluck('qtd_vendas')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.7)'
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

    // Gráfico de Faturamento x Faltante (Doughnut centralizado)
    document.addEventListener('DOMContentLoaded', function() {
        const faturado = Number({{ $totalFaturamento ?? 0 }});
        const faltante = Number({{ $totalFaltante ?? 0 }});
        const valorRecebido = faturado - faltante;
        const vendasChartCenter = document.getElementById('vendasChartCenter');
        if (vendasChartCenter) {
            vendasChartCenter.innerHTML =
                '<div style="font-size:1.1rem;color:#6c757d;">Recebido</div>' +
                '<div style="font-size:1.7rem;color:#28a745;">R$ ' + valorRecebido.toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</div>';
        }
        const vendasChart = document.getElementById('vendasChart');
        if (vendasChart) {
            // Garante que ambos os valores estejam presentes (mesmo que zero)
            let data = [faturado, faltante];
            // Se ambos forem zero, mostra 1 e 0 só para não sumir o gráfico
            if (faturado <= 0 && faltante <= 0) {
                data = [1, 0];
            }
            new Chart(vendasChart, {
                type: 'doughnut',
                data: {
                    labels: ['Faturado', 'Faltante'],
                    datasets: [{
                        data: data,
                        backgroundColor: ['#ffc107', '#dc3545'],
                    }]
                },
                options: {
                    plugins: {
                        legend: { display: false, position: 'bottom' },
                        tooltip: { enabled: true }
                    },
                    cutout: '70%',
                    maintainAspectRatio: false
                }
            });
        }
    });

</script>
@endpush
@endsection
