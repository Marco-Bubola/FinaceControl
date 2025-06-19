@extends('layouts.user_type.auth')

@section('content')
<link rel="stylesheet" href="{{ asset('css/sales.css') }}">
<div class="container-fluid py-4">
    @include('message.alert')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- Filtros e Pesquisa -->
        <div class="row w-100">
            {{-- Troque id="customDropdown" por class="dropdown-filtros" em todos os dropdowns de filtro --}}
            <div class="col-md-3 mb-3">
                <form action="{{ route('sales.index') }}" method="GET" class="w-100" id="filtersForm">
                    <div class="dropdown w-100 dropdown-filtros" data-bs-auto-close="outside">
                        <button
                            class="btn btn-gradient-primary w-100 dropdown-toggle rounded-pill shadow d-flex justify-content-between align-items-center px-4 py-2"
                            type="button" id="dropdownFilter" data-bs-toggle="dropdown" aria-expanded="false"
                            style="font-weight:600;">
                            <span>
                                <i class="bi bi-funnel-fill me-2"></i> Filtros
                            </span>
                            @if(request('filter') || request('per_page') || request('status') || request('date_start')
                            || request('date_end') || request('client_id') || request('min_value') ||
                            request('max_value') || request('payment_type'))
                            <span class="badge bg-light text-primary ms-2">Ativo</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-animate w-100 rounded-4 p-3 border-0 shadow-lg"
                            aria-labelledby="dropdownFilter" style="min-width: 320px;">
                            <!-- ... (restante dos filtros permanece igual) ... -->
                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-sort-alpha-down text-primary me-2"></i>
                                        <h6 class="mb-0 text-primary" style="font-size:1rem;">Ordenar</h6>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php
                                        $filters = [
               'created_at' => 'Últimos Adicionados',
                                        'updated_at' => 'Últimos Atualizados',
                                        'name_asc' => 'Nome A-Z',
                                        'name_desc' => 'Nome Z-A',
                                        'price_asc' => 'Preço A-Z',
                                        'price_desc' => 'Preço Z-A',
                                        ];
                                        @endphp
                                        @foreach($filters as $key => $label)
                                        <div class="form-check form-check-inline form-check-custom">
                                            <input class="form-check-input" type="radio" name="filter"
                                                id="filter_{{ $key }}" value="{{ $key }}"
                                                {{ request('filter') == $key ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="filter_{{ $key }}"
                                                data-bs-toggle="tooltip" title="{{ $label }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                            
    <li>
        <div class="filter-section mb-3" tabindex="0">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-list-ol text-success me-2"></i>
                <h6 class="mb-0 text-success" style="font-size:1rem;">Qtd. Itens</h6>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @php $perPages = [10, 24, 50, 100, -1]; @endphp
                @foreach($perPages as $num)
                    <div class="form-check form-check-inline form-check-custom">
                        <input class="form-check-input" type="radio" name="per_page"
                            id="per_page_{{ $num == -1 ? 'all' : $num }}" value="{{ $num }}"
                            {{ request('per_page') == $num || (is_null(request('per_page')) && $num == 10) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="per_page_{{ $num == -1 ? 'all' : $num }}"
                            data-bs-toggle="tooltip" title="{{ $num == -1 ? 'Todos os itens' : $num . ' itens' }}">
                            {{ $num == -1 ? 'Todos' : $num }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </li>

                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-flag-fill text-warning me-2"></i>
                                        <h6 class="mb-0 text-warning" style="font-size:1rem;">Status</h6>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php
                                        $statuses = [
                                        '' => 'Todos',
                                        'pago' => 'Pago',
                                        'pendente' => 'Pendente',
                                        'cancelado' => 'Cancelado',
                                        ];
                                        @endphp
                                        @foreach($statuses as $key => $label)
                                        <div class="form-check form-check-inline form-check-custom">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="status_{{ $key ?: 'all' }}" value="{{ $key }}"
                                                {{ request('status', '') === $key ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="status_{{ $key ?: 'all' }}"
                                                data-bs-toggle="tooltip" title="{{ $label }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-range text-info me-2"></i>
                                        <h6 class="mb-0 text-info" style="font-size:1rem;">Período</h6>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" class="form-control form-control-sm" name="date_start"
                                                value="{{ request('date_start') }}" placeholder="De">
                                        </div>
                                        <div class="col-6">
                                            <input type="date" class="form-control form-control-sm" name="date_end"
                                                value="{{ request('date_end') }}" placeholder="Até">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Filtro por Valor -->
                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-cash-coin text-success me-2"></i>
                                        <h6 class="mb-0 text-success" style="font-size:1rem;">Valor</h6>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" step="0.01" class="form-control form-control-sm"
                                                name="min_value" value="{{ request('min_value') }}" placeholder="Mín.">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" step="0.01" class="form-control form-control-sm"
                                                name="max_value" value="{{ request('max_value') }}" placeholder="Máx.">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Botões -->
                            <li>
                                <div class="d-flex gap-2 mt-2">
                                    <button type="submit" class="btn btn-gradient-success rounded-pill px-3 flex-fill">
                                        <i class="bi bi-check2-circle"></i> Aplicar
                                    </button>
                                    @if(request('filter') || request('per_page') || request('status') ||
                                    request('date_start') || request('date_end') || request('client_id') ||
                                    request('min_value') || request('max_value') || request('payment_type'))
                                    <a href="{{ route('sales.index') }}"
                                        class="btn btn-outline-secondary rounded-pill px-3 flex-fill">
                                        <i class="bi bi-x-circle"></i> Limpar
                                    </a>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="col-md-4 mb-3">
                <form action="{{ route('sales.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <div class="input-group search-bar-sales w-100">
                        <span class="input-group-text search-bar-sales-icon" id="search-addon">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" id="search-input" class="form-control search-bar-sales-input"
                            placeholder="Pesquisar por cliente" value="{{ request('search') }}"
                            aria-label="Pesquisar por cliente" aria-describedby="search-addon">
                    </div>
                </form>
            </div>
            <div class="col-md-5 mb-3 d-flex justify-content-end align-items-center">
                <a href="#" class="btn bg-gradient-primary mb-0 d-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#modalAddSale">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        class="bi bi-plus-square me-1" viewBox="0 0 16 16">
                        <path
                            d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                        <path
                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                    </svg>
                    Adicionar Venda
                </a>
            </div>
        </div>
    </div>
    <!-- Exibir todas as vendas -->
    <div id="sales-list">
        @if(isset($sales) && $sales->isNotEmpty())
        <div class="row teste mt-4">
            @foreach($sales as $sale)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm rounded-lg h-100">
                    <div class="card-header bg-primary text-white">
                        <!-- Exibir o nome do cliente, telefone, editar, excluir e adicionar produto -->
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <!-- Link para redirecionar apenas ao clicar no nome do cliente e telefone -->
                                <a href="{{ route('sales.show', $sale->id) }}" class="text-decoration-none text-dark"
                                    style="display: flex; align-items: center; gap: 10px;">
                                    <!-- Ícones e texto formatado -->
                                    <i class="bi bi-person-circle" style="font-size: 1.2rem; color: #007bff;"></i>
                                    <p class="mb-0" style="font-size: 1rem; font-weight: 500; color: #333;">
                                        <strong>Nome:</strong> {{ ucwords($sale->client->name) }}
                                    </p>
                                    <i class="bi bi-telephone" style="font-size: 1.2rem; color: #28a745;"></i>
                                    <p class="mb-0" style="font-size: 1rem; font-weight: 500; color: #333;">
                                        <strong>Telefone:</strong> {{ $sale->client->phone }}
                                    </p>
                                </a>
                            </div>
                            <div class="col-md-5 text-end">

                                <div class="dropdown-acoes-unico">
                                    <div class="dropdown">
                                        <button class="btn btn-light dropdown-toggle w-100" type="button"
                                            id="dropdownMenuButton{{ $sale->id }}" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            Ações
                                            <i class="bi bi-three-dots-vertical ms-2"></i>
                                        </button>
                                        <ul class="dropdown-menu w-100"
                                            aria-labelledby="dropdownMenuButton{{ $sale->id }}">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#paymentHistoryModal{{ $sale->id }}"
                                                    title="Histórico de pagamento">
                                                    <i class="bi bi-clock-history me-2 text-primary"></i>
                                                    Histórico de Pagamento
                                                </button>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item export-pdf-btn"
                                                    data-export-url="{{ route('sales.export', $sale->id) }}"
                                                    title="Exportar PDF">
                                                    <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
                                                    Exportar PDF
                                                </a>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#paymentModal{{ $sale->id }}"
                                                    title="Adicionar Pagamento">
                                                    <i class="bi bi-plus-square me-2 text-success"></i>
                                                    Adicionar Pagamento
                                                </button>
                                            </li>
                                            <li>
                                                <a href="{{ route('sales.show', $sale->id) }}" class="dropdown-item"
                                                    title="Ver Detalhes">
                                                    <i class="bi bi-eye me-2 text-info"></i>
                                                    Ver Detalhes
                                                </a>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditSale{{ $sale->id }}" title="Editar Venda">
                                                    <i class="bi bi-pencil-square me-2 text-warning"></i>
                                                    Editar Venda
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#modalDeleteSale{{ $sale->id }}"
                                                        title="Excluir Venda">
                                                        <i class="bi bi-trash3 me-2 text-danger"></i>
                                                        Excluir Venda
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#modalAddProductToSale{{ $sale->id }}"
                                                    title="Adicionar Produto à Venda">
                                                    <i class="bi bi-plus-square me-2 text-success"></i>
                                                    Adicionar Produto
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#modalParcelasVenda{{ $sale->id }}">
                                                    <i class="bi bi-list-ol me-2 text-primary"></i>
                                                    Parcelas
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body sale-products-section">
                        <div class="row" id="sale-products-{{ $sale->id }}">
                            @foreach($sale->saleItems as $index => $item)
                            <div class="col-md-3 mb-3 sale-product {{ $index >= 4 ? 'd-none extra-product' : '' }} d-flex">
                                <div class="card custom-card shadow-lg rounded-lg flex-fill position-relative">
                                    <!-- Badge do código do produto (modelo moderno) -->
                                    <span class="product-code-badge" title="Código do Produto">
                                        <i class="bi bi-upc-scan"></i> {{ $item->product->product_code ?? 'N/A' }}
                                    </span>
                                    <img src="{{ asset('storage/products/' . $item->product->image) }}"
                                        class="card-img-top" alt="{{ $item->product->name }}">

                                    <div class="card-body d-flex flex-column justify-content-between custom-card-body">
                                        <!-- Nome do produto (abreviado, sem espaço extra) -->
                                        <span class="product-title text-truncate" title="{{ $item->product->name }}">
                                            {{ ucwords($item->product->name) }}
                                        </span>
                                        <!-- Quantidade e preço logo abaixo do nome -->
                                        <div class="product-info d-flex justify-content-center align-items-center gap-2 mb-1">
                                            <span class="product-badge quantity" title="Quantidade">
                                                <i class="bi bi-stack"></i> {{ $item->quantity }}
                                            </span>
                                            <span class="product-badge price" title="Preço Unitário">
                                                <i class="bi bi-currency-dollar"></i> {{ number_format($item->price_sale, 2, ',', '.') }}
                                            </span>
                                        </div>
                                   
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($sale->saleItems->count() > 4)
                        <div class="text-center ">
                            <button class="btn btn-outline-primary sale-products-expand"
                                id="expandProducts-{{ $sale->id }}">+{{ $sale->saleItems->count() - 4 }} mais</button>
                            <button class="btn btn-outline-secondary sale-products-collapse d-none"
                                id="collapseProducts-{{ $sale->id }}">Mostrar menos</button>
                        </div>
                        @endif
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="card sale-totals-card p-4 shadow-lg rounded-4 border-0">
                                    <div class="row d-flex justify-content-between align-items-center">
                                        <div class="col-md-4 d-flex flex-column align-items-start">
                                            <h6 class="sale-total-label mb-2">
                                                <i class="bi bi-cash-coin me-1 text-primary"></i>
                                                <span>Total:</span>
                                            </h6>
                                            <span class="sale-total-value">
                                                R$ {{ number_format($sale->total_price, 2, ',', '.') }}
                                            </span>
                                        </div>
                                        <!-- Coluna com Total Pago -->
                                        <div class="col-md-4 d-flex flex-column align-items-center">
                                            <h6 class="sale-total-label mb-2">
                                                <i class="bi bi-wallet2 me-1 text-success"></i>
                                                <span>Total Pago:</span>
                                            </h6>
                                            <span class="sale-total-paid">
                                                R$ {{ number_format($sale->total_paid, 2, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="col-md-4 d-flex flex-column align-items-end">
                                            <h6 class="sale-total-label mb-2">
                                                <i
                                                    class="bi {{ $sale->status === 'pago' ? 'bi-check-circle' : 'bi-exclamation-circle' }} me-1 text-{{ $sale->status === 'pago' ? 'success' : 'danger' }}"></i>
                                                <span>{{ $sale->status === 'pago' ? 'Status:' : 'Saldo Restante:' }}</span>
                                            </h6>
                                            <span class="badge sale-total-badge">
                                                @if($sale->status === 'pago')
                                                Pago
                                                @else
                                                R$
                                                {{ number_format($sale->total_price - $sale->total_paid, 2, ',', '.') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('sales.paymentHistory')
                        <!-- Modal de Parcelas -->
                        <div class="modal fade" id="modalParcelasVenda{{ $sale->id }}" tabindex="-1" aria-labelledby="modalParcelasVendaLabel{{ $sale->id }}" aria-hidden="true">
                          <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow-lg">
                              <div class="modal-header bg-primary text-white rounded-top-4">
                                <h5 class="modal-title" id="modalParcelasVendaLabel{{ $sale->id }}">
                                  <i class="bi bi-list-ol me-2"></i>Parcelas da Venda
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                              </div>
                              <div class="modal-body">
                                <div class="table-responsive">
                                  <table class="table table-bordered align-middle text-center">
                                    <thead class="table-light">
                                      <tr>
                                        <th>#</th>
                                        <th>Valor</th>
                                        <th>Vencimento</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($sale->parcelasVenda as $parcela)
                                      <tr>
                                        <td>{{ $parcela->numero_parcela }}</td>
                                        <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($parcela->data_vencimento)->format('d/m/Y') }}</td>
                                        <td>
                                          @if($parcela->status == 'pendente')
                                            <span class="badge bg-warning text-dark">Pendente</span>
                                          @elseif($parcela->status == 'paga')
                                            <span class="badge bg-success">Paga</span>
                                          @elseif($parcela->status == 'vencida')
                                            <span class="badge bg-danger">Vencida</span>
                                          @endif
                                        </td>
                                        <td>
                                          @if($parcela->status == 'pendente')
                                          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalPagarParcelaIndex{{ $parcela->id }}">
                                            <i class="bi bi-cash-coin"></i> Registrar Pagamento
                                          </button>
                                          <!-- Modal Registrar Pagamento -->
                                          <div class="modal fade" id="modalPagarParcelaIndex{{ $parcela->id }}" tabindex="-1" aria-labelledby="modalPagarParcelaIndexLabel{{ $parcela->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                              <div class="modal-content rounded-4 shadow-lg">
                                                <div class="modal-header bg-success text-white rounded-top-4">
                                                  <h5 class="modal-title" id="modalPagarParcelaIndexLabel{{ $parcela->id }}">
                                                    <i class="bi bi-cash-coin me-2"></i>Registrar Pagamento da Parcela #{{ $parcela->numero_parcela }}
                                                  </h5>
                                                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                </div>
                                                <form action="{{ route('parcelas.pagar', $parcela->id) }}" method="POST" class="needs-validation" novalidate>
                                                  @csrf
                                                  <div class="modal-body">
                                                    <div class="mb-3">
                                                      <label for="valor_pago_index_{{ $parcela->id }}" class="form-label">Valor Pago</label>
                                                      <input type="number" step="0.01" class="form-control" id="valor_pago_index_{{ $parcela->id }}" name="valor_pago" value="{{ $parcela->valor }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                      <label for="payment_date_index_{{ $parcela->id }}" class="form-label">Data do Pagamento</label>
                                                      <input type="date" class="form-control" id="payment_date_index_{{ $parcela->id }}" name="payment_date" value="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">Confirmar Pagamento</button>
                                                  </div>
                                                </form>
                                              </div>
                                            </div>
                                          </div>
                                          @else
                                          <span class="text-muted">-</span>
                                          @endif
                                        </td>
                                      </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="col-12">
            <div class="d-flex flex-column align-items-center justify-content-center py-5">
                <div class="animated-icon mb-4">
                    <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                        <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3" fill="#f8fafc"/>
                        <rect x="35" y="70" width="60" height="30" rx="12" fill="#e9f2ff" stroke="#6ea8fe" stroke-width="3"/>
                        <rect x="50" y="40" width="30" height="35" rx="7" fill="#f8fafc" stroke="#6ea8fe" stroke-width="3"/>
                        <path d="M50 70 Q65 90 80 70" stroke="#6ea8fe" stroke-width="3" fill="none"/>
                        <rect x="60" y="55" width="10" height="10" rx="3" fill="#6ea8fe" opacity="0.25"/>
                        <circle cx="65" cy="60" r="6" fill="#6ea8fe" opacity="0.15"/>
                        <polyline points="55,50 65,60 75,50" fill="none" stroke="#0d6efd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h2 class="fw-bold mb-3 text-primary" style="font-size:2.5rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                    Nenhuma Venda Encontrada
                </h2>
                <p class="mb-4 text-secondary text-center" style="max-width: 480px; font-size:1.25rem; font-weight:500; line-height:1.6;">
                    <span style="color:#0d6efd; font-weight:700;">Ops!</span> Você ainda não registrou nenhuma venda.<br>
                    <span style="color:#6ea8fe;">Registre sua primeira venda</span> e acompanhe o crescimento do seu negócio!
                </p>
              
            </div>
        </div>
      
        @endif
       <!-- Paginação -->
        @if(!empty($isPaginated) && $isPaginated)
        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination">
                    <!-- Botão para a primeira página -->
                    @if ($sales->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;&laquo;</span></li>
                    @else
                    <li class="page-item"><a class="page-link" href="{{ $sales->url(1) }}">&laquo;&laquo;</a></li>
                    @endif

                    <!-- Botão para a página anterior -->
                    @if ($sales->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                    @else
                    <li class="page-item"><a class="page-link" href="{{ $sales->previousPageUrl() }}">&laquo;</a></li>
                    @endif

                    <!-- Página anterior -->
                    @if ($sales->currentPage() > 1)
                    <li class="page-item"><a class="page-link"
                            href="{{ $sales->url($sales->currentPage() - 1) }}">{{ $sales->currentPage() - 1 }}</a></li>
                    @endif

                    <!-- Página atual -->
                    <li class="page-item active"><span class="page-link">{{ $sales->currentPage() }}</span></li>

                    <!-- Próxima página -->
                    @if ($sales->currentPage() < $sales->lastPage())
                        <li class="page-item"><a class="page-link"
                                href="{{ $sales->url($sales->currentPage() + 1) }}">{{ $sales->currentPage() + 1 }}</a>
                        </li>
                        @endif

                        <!-- Botão para a próxima página -->
                        @if ($sales->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $sales->nextPageUrl() }}">&raquo;</a></li>
                        @else
                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif

                        <!-- Botão para a última página -->
                        @if ($sales->hasMorePages())
                        <li class="page-item"><a class="page-link"
                                href="{{ $sales->url($sales->lastPage()) }}">&raquo;&raquo;</a></li>
                        @else
                        <li class="page-item disabled"><span class="page-link">&raquo;&raquo;</span></li>
                        @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

@include('sales.createPayament')
@include('sales.editPayament')
@include('sales.deletPayament')
@include('sales.edit')
@include('sales.create')
@include('sales.delet')
@include('sales.addProduct')

<script>
window.SALES_INDEX_URL = "{{ route('sales.index') }}";
</script>
<script src="{{ asset('js/sales.js') }}"></script>
@endsection
