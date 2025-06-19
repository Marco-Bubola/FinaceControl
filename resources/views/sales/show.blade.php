@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">

 <!-- Área de destaque para o header da venda -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-4">
        <div class="row align-items-center">
            <div class="col-md-8 mb-3 mb-md-0">
                <h1 class="mb-1 fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-receipt-cutoff text-primary fs-2"></i>
                    Detalhes da Venda <span class="text-muted">#{{ $sale->id }}</span>
                </h1>
                <p class="mb-0 text-secondary">Veja abaixo todas as informações e ações disponíveis para esta venda.</p>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-md-end gap-3">
                    <a href="#" class="export-pdf-btn btn btn-outline-secondary d-flex align-items-center gap-2"
                        data-export-url="{{ route('sales.export', $sale->id) }}"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Exportar relatório em PDF">
                        <i class="bi bi-file-earmark-pdf fs-4"></i>
                        <span class="d-none d-md-inline">Exportar PDF</span>
                    </a>
                    <button class="btn btn-outline-danger d-flex align-items-center gap-2"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDeleteSale{{ $sale->id }}"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir esta venda">
                        <i class="bi bi-trash fs-4"></i>
                        <span class="d-none d-md-inline">Excluir</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ative tooltips do Bootstrap se ainda não estiverem ativos -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush

    <div class="row">
        
<!-- Estilos customizados para os blocos de informação -->
<style>
    .info-block {
        background: #f8f9fa;
        border-radius: 0.75rem;
        padding: 1rem 0.75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        text-align: center;
        min-height: 90px;
        margin-bottom: 0.5rem;
        transition: box-shadow 0.2s;
    }
    .info-block:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
    }
    .info-block .bi {
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }
    .status-block .bi {
        font-size: 1.7rem;
    }
    .status-badge {
        font-size: 1rem;
        padding: 0.5em 1.2em;
        border-radius: 1.5em;
        font-weight: 600;
        letter-spacing: 0.03em;
        display: inline-flex;
        align-items: center;
        gap: 0.5em;
    }
    @media (max-width: 767px) {
        .info-block {
            min-height: 70px;
            padding: 0.75rem 0.5rem;
        }
    }
</style>

<!-- Informações da Venda - Layout Moderno -->
<div class="col-md-6 mb-4">
    <div class="card shadow-lg rounded-lg h-100 border-0">
        <div class="card-body">
            <h5 class="card-title mb-4 text-primary fw-bold">
                <i class="bi bi-info-circle-fill me-2"></i>Informações da Venda
            </h5>
            
            <!-- Card com todos os dados do cliente em uma linha -->
            <div class="card mb-4">
                <div class="card-body bg-light">
                    <div class="row text-center">
                        <!-- Cliente -->
                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <i class="bi bi-person-fill text-primary fs-3"></i>
                            <div class="fw-semibold">Cliente</div>
                            <span class="text-muted small">{{ $sale->client->name ?? 'Nenhum cliente cadastrado' }}</span>
                        </div>
                        <!-- Email -->
                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <i class="bi bi-envelope-fill text-primary fs-3"></i>
                            <div class="fw-semibold">Email</div>
                            <span class="text-muted small">{{ $sale->client->email ?? 'Nenhum email cadastrado' }}</span>
                        </div>
                        <!-- Telefone -->
                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <i class="bi bi-telephone-fill text-primary fs-3"></i>
                            <div class="fw-semibold">Telefone</div>
                            <span class="text-muted small">{{ $sale->client->phone ?? 'Nenhum telefone cadastrado' }}</span>
                        </div>
                        <!-- Endereço -->
                        <div class="col-12 col-md-3">
                            <i class="bi bi-house-door-fill text-primary fs-3"></i>
                            <div class="fw-semibold">Endereço</div>
                            <span class="text-muted small">{{ $sale->client->address ?? 'Nenhum endereço cadastrado' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card com resumo do status da venda -->
            <div class="card mb-4">
                <div class="card-body bg-light">
                    <div class="row text-center">
                        <!-- Status -->
                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <div>
                                @if($sale->status == 'pago')
                                    <span class="status-badge bg-success text-white">
                                        <i class="bi bi-check-circle-fill"></i> Pago
                                    </span>
                                @else
                                    <span class="status-badge bg-warning text-dark">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Pendente
                                    </span>
                                @endif
                            </div>
                            <div class="fw-semibold mt-2">Status</div>
                        </div>
                        <!-- Total Pago -->
                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <i class="bi bi-cash-stack text-success fs-3"></i>
                            <div class="fw-semibold">Total Pago</div>
                            <span class="text-success">R$ {{ number_format($sale->amount_paid, 2, ',', '.') }}</span>
                        </div>
                        <!-- Total da Venda -->
                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                            <i class="bi bi-cash-coin text-primary fs-3"></i>
                            <div class="fw-semibold">Total da Venda</div>
                            <span>R$ {{ number_format($sale->total_price, 2, ',', '.') }}</span>
                        </div>
                        <!-- Total Restante -->
                        <div class="col-12 col-md-3">
                            <i class="bi bi-cash-coin text-warning fs-3"></i>
                            <div class="fw-semibold">Total Restante</div>
                            <span class="text-warning">R$ {{ number_format($sale->total_price - $sale->amount_paid, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Estilos customizados para o histórico de pagamentos -->
<style>
    .payment-list-item {
        background: #f8f9fa;
        border-radius: 0.75rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        transition: box-shadow 0.2s, background 0.2s;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        align-items: center;
    }
    .payment-list-item:hover {
        background: #e9ecef;
        box-shadow: 0 4px 16px rgba(0,0,0,0.07);
    }
    .payment-badge {
        font-size: 0.95em;
        padding: 0.35em 0.9em;
        border-radius: 1em;
        font-weight: 500;
        margin-left: 0.5em;
    }
    .payment-date {
        font-weight: 600;
        color: #0d6efd;
        margin-right: 0.7em;
    }
    .payment-amount {
        font-weight: 700;
        color: #198754;
        margin-right: 0.7em;
    }
    .payment-method-badge {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }
    .payment-actions .btn {
        margin-left: 0.3em;
    }
    @media (max-width: 767px) {
        .payment-list-item {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.5em;
        }
        .payment-actions {
            margin-top: 0.5em;
        }
    }
</style>

<!-- Histórico de Pagamentos - Visual Moderno -->
<div class="col-md-6 mb-4">
    <div class="card shadow-lg rounded-lg h-100 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title text-primary fw-bold d-flex align-items-center gap-2 mb-0" style="font-size: 1.4rem;">
                    <i class="bi bi-wallet2 me-2"></i> Histórico de Pagamentos
                </h5>
                <button class="btn btn-success btn-sm d-flex align-items-center gap-1"
                    data-bs-toggle="modal"
                    data-bs-target="#paymentModal{{ $sale->id }}"
                    data-bs-toggle="tooltip"
                    data-bs-placement="left"
                    title="Adicionar novo pagamento">
                    <i class="bi bi-plus-circle fs-5"></i>
                    <span class="d-none d-md-inline">Pagamento</span>
                </button>
            </div>
            <div class="overflow-auto" style="max-height: 300px;">
                <div class="list-group">
                    {{-- Pagamentos avulsos --}}
                    @forelse($sale->payments as $payment)
                        <div class="list-group-item d-flex justify-content-between payment-list-item">
                            <div class="d-flex flex-wrap align-items-center">
                                <span class="payment-date">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}
                                </span>
                                <span class="payment-amount">
                                    <i class="bi bi-cash-coin"></i>
                                    R$ {{ number_format($payment->amount_paid, 2, ',', '.') }}
                                </span>
                                <span class="payment-badge payment-method-badge">
                                    <i class="bi bi-credit-card-2-front"></i>
                                    {{ $payment->payment_method }}
                                </span>
                            </div>
                            <div class="payment-actions d-flex">
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPaymentModal{{ $payment->id }}"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Editar pagamento">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDeletePayment{{ $payment->id }}"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Excluir pagamento">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                    @endforelse

                    {{-- Parcelas --}}
                    @if(isset($parcelas) && $parcelas->count())
                        @foreach($parcelas as $parcela)
                            <div class="list-group-item d-flex justify-content-between payment-list-item">
                                <div class="d-flex flex-wrap align-items-center">
                                    <span class="payment-date">
                                        <i class="bi bi-list-ol"></i>
                                        Parcela #{{ $parcela->numero_parcela }}
                                    </span>
                                    <span class="payment-amount">
                                        <i class="bi bi-cash-coin"></i>
                                        R$ {{ number_format($parcela->valor, 2, ',', '.') }}
                                    </span>
                                    <span class="payment-badge payment-method-badge">
                                        <i class="bi bi-calendar-range"></i>
                                        {{ \Carbon\Carbon::parse($parcela->data_vencimento)->format('d/m/Y') }}
                                    </span>
                                    @if($parcela->status == 'pendente')
                                        <span class="badge bg-warning text-dark ms-2">Pendente</span>
                                    @elseif($parcela->status == 'paga')
                                        <span class="badge bg-success ms-2">Paga</span>
                                    @elseif($parcela->status == 'vencida')
                                        <span class="badge bg-danger ms-2">Vencida</span>
                                    @endif
                                </div>
                                <div class="payment-actions d-flex">
                                    @if($parcela->status == 'pendente')
                                        <button class="btn btn-success btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modalPagarParcela{{ $parcela->id }}">
                                            <i class="bi bi-cash-coin"></i> Registrar Pagamento
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="modalPagarParcela{{ $parcela->id }}" tabindex="-1" aria-labelledby="modalPagarParcelaLabel{{ $parcela->id }}" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 shadow-lg">
                                              <div class="modal-header bg-success text-white rounded-top-4">
                                                <h5 class="modal-title" id="modalPagarParcelaLabel{{ $parcela->id }}">
                                                  <i class="bi bi-cash-coin me-2"></i>Registrar Pagamento da Parcela #{{ $parcela->numero_parcela }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                              </div>
                                              <form action="{{ route('parcelas.pagar', $parcela->id) }}" method="POST" class="needs-validation" novalidate>
                                                @csrf
                                                <div class="modal-body">
                                                  <div class="mb-3">
                                                    <label for="valor_pago_{{ $parcela->id }}" class="form-label">Valor Pago</label>
                                                    <input type="number" step="0.01" class="form-control" id="valor_pago_{{ $parcela->id }}" name="valor_pago" value="{{ $parcela->valor }}" required>
                                                  </div>
                                                  <div class="mb-3">
                                                    <label for="payment_date_{{ $parcela->id }}" class="form-label">Data do Pagamento</label>
                                                    <input type="date" class="form-control" id="payment_date_{{ $parcela->id }}" name="payment_date" value="{{ date('Y-m-d') }}" required>
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
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    {{-- Mensagem de vazio se não houver nenhum pagamento nem parcela --}}
                    @if($sale->payments->isEmpty() && (!$parcelas || !$parcelas->count()))
                        <div class="alert alert-info text-center mb-0">
                            Nenhum pagamento registrado para esta venda.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Estilos customizados para os cards de produto -->
<style>
    .custom-card {
        border: none;
        border-radius: 1.1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        transition: box-shadow 0.2s, transform 0.2s;
        position: relative;
        overflow: hidden;
        min-height: 120px;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .custom-card:hover {
        box-shadow: 0 8px 32px rgba(0,0,0,0.13);
        transform: translateY(-4px) scale(1.02);
    }
    .product-code-badge {
        position: absolute;
        top: 0.7em;
        left: 0.7em;
        z-index: 2;
        background: #343a40e6;
        color: #fff;
        font-size: 0.98em;
        font-weight: 600;
        padding: 0.32em 0.95em;
        border-radius: 1.2em;
        box-shadow: 0 2px 8px rgba(52,58,64,0.13);
        letter-spacing: 0.03em;
        pointer-events: none;
        display: flex;
        align-items: center;
        gap: 0.3em;
    }
    .custom-card .card-img-top {
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 1.1rem;
        border-top-right-radius: 1.1rem;
        background: #f8f9fa;
        position: relative;
    }
    .custom-card-body {
        padding: 1.1rem 0.7rem 0.7rem 0.7rem;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        height: 100%;
    }
    .btn-edit-product, .btn-delete-product {
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        transition: background 0.15s, color 0.15s;
    }
    .btn-edit-product:hover {
        background: #0d6efd !important;
        color: #fff !important;
    }
    .btn-delete-product:hover {
        background: #dc3545 !important;
        color: #fff !important;
    }
    .product-title {
        font-size: 1.13em;
        font-weight: 700;
        color: #0d6efd;
        letter-spacing: 0.01em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        margin-bottom: 0 !important;
        text-align: center;
        display: block;
    }
    .product-description {
        font-size: 0.98em;
        color: #6c757d;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: center;
        display: block;
        margin-top: 0.3em;
    }
    .product-info {
        margin-top: 0.1em;
        margin-bottom: 0.1em;
        text-align: center;
        display: flex;
        justify-content: center;
        gap: 0.5em;
        align-items: center;
    }
    .product-badge {
        display: flex;
        align-items: center;
        gap: 0.3em;
        font-size: 1em;
        font-weight: 600;
        border-radius: 1.2em;
        padding: 0.35em 0.9em;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        background: #f1f3f5;
        color: #495057;
        min-width: 90px;
        justify-content: center;
    }
    .product-badge.quantity {
        background: #e7f1ff;
        color: #0d6efd;
    }
    .product-badge.price {
        background: #eafbee;
        color: #198754;
    }
    .product-badge .bi {
        font-size: 1.1em;
        margin-right: 0.2em;
    }
    @media (max-width: 991px) {
        .col-md-2 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
    }
    @media (max-width: 767px) {
        .col-md-2 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        .custom-card {
            min-height: 340px;
        }
    }
    @media (max-width: 575px) {
        .col-md-2 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .custom-card {
            min-height: 300px;
        }
    }
</style>

<div class="card col-md-12 mb-4 border-0 shadow-lg">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="card-title text-primary fw-bold d-flex align-items-center gap-2 mb-0" style="font-size: 1.35rem;">
                <i class="bi bi-box-seam"></i> Produtos da Venda
            </h3>
            <button class="btn btn-primary rounded-pill d-flex align-items-center gap-1"
                data-bs-toggle="modal"
                data-bs-target="#modalAddProductToSale{{ $sale->id }}"
                data-bs-toggle="tooltip"
                data-bs-placement="left"
                title="Adicionar novo produto à venda">
                <i class="bi bi-plus-square fs-5"></i>
                <span class="d-none d-md-inline">Adicionar Produto</span>
            </button>
        </div>

        <div class="row mt-4">
            @foreach ($sale->saleItems as $item)
            <div class="col-md-2 mb-4 d-flex">
                <div class="card custom-card shadow-lg rounded-lg flex-fill position-relative">
                    <!-- Badge do código do produto -->
                    <span class="product-code-badge" title="Código do Produto">
                        <i class="bi bi-upc-scan"></i> {{ $item->product->product_code ?? 'N/A' }}
                    </span>
                    <img src="{{ asset('storage/products/' . $item->product->image) }}"
                        data-product-id="{{ $item->id }}" class="card-img-top" alt="{{ $item->product->name }}">

                    <!-- Botões de ação -->
                    <div class="position-absolute top-0 end-0 p-2 d-flex flex-column gap-1" style="z-index:3;">
                        <a href="javascript:void(0)"
                            class="btn btn-primary btn-sm p-1 rounded-circle btn-edit-product"
                            data-product-id="{{ $item->id }}"
                            data-product-price="{{ number_format($item->price_sale, 2, '.', '') }}"
                            data-product-quantity="{{ $item->quantity }}" data-bs-toggle="modal"
                            data-bs-target="#modalEditProduct" title="Editar Produto"
                            data-bs-toggle="tooltip" data-bs-placement="left">
                            <i class="bi bi-pencil-square fs-6"></i>
                        </a>
                        <button type="button"
                            class="btn btn-danger btn-sm p-1 rounded-circle btn-delete-product"
                            data-bs-toggle="modal" data-bs-target="#modalDeleteProduct"
                            data-sale-item-id="{{ $item->id }}" data-product-name="{{ $item->product->name }}"
                            data-product-price="{{ number_format($item->product->price, 2, ',', '.') }}"
                            title="Excluir Produto" data-bs-toggle="tooltip" data-bs-placement="left">
                            <i class="bi bi-trash3 fs-6"></i>
                        </button>
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between custom-card-body">
                        <!-- Nome do produto (abreviado, sem espaço extra) -->
                        <span class="product-title text-truncate" title="{{ $item->product->name }}">
                            {{ ucwords($item->product->name) }}
                        </span>
                        <!-- Quantidade e preço logo abaixo do nome -->
                        <div class="product-info">
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
    </div>
</div>

    </div>

    @include('sales.deletproduct')
    @include('sales.createPayament', ['sale' => $sale])
    @include('sales.editPayament', ['sale' => $sale])
    @include('sales.deletPayament', ['sale' => $sale])
    @include('sales.delet', ['sale' => $sale])
    @include('sales.addproduct', ['sale' => $sale])
    @include('sales.editproduct')
</div>
@endsection
