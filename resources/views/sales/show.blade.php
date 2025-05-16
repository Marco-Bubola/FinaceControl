@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">

    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="text-center mb-0">Detalhes da Venda #{{ $sale->id }}</h1>
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-end gap-2">
                <a href="#" class="export-pdf-btn btn btn-secondary"
                    data-export-url="{{ route('sales.export', $sale->id) }}">
                    <i class="bi bi-file-earmark-pdf"></i> Exportar Relatório PDF
                </a>
                <button class="btn btn-outline-danger" data-bs-toggle="modal"
                    data-bs-target="#modalDeleteSale{{ $sale->id }}">
                    <i class="bi bi-trash"></i> Excluir Venda
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações da Venda - Layout Melhorado -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg rounded-lg h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-primary">Informações da Venda</h5>
                    <!-- Bloco de Informações -->
                    <div class="row">
                        <!-- Cliente -->
                        <div class="col-md-4 mb-3">
                            <div class="info-block">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-fill text-primary me-2"></i>
                                    <h6 class="mb-0">Cliente:</h6>
                                </div>
                                <span>{{ $sale->client->name ?? 'Nenhum cliente cadastrado' }}</span>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="col-md-4 mb-3">
                            <div class="info-block">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-envelope-fill text-primary me-2"></i>
                                    <h6 class="mb-0">Email:</h6>
                                </div>
                                <span>{{ $sale->client->email ?? 'Nenhum email cadastrado' }}</span>
                            </div>
                        </div>
                        <!-- Telefone -->
                        <div class="col-md-4 mb-3">
                            <div class="info-block">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-telephone-fill text-primary me-2"></i>
                                    <h6 class="mb-0">Telefone:</h6>
                                </div>
                                <span>{{ $sale->client->phone ?? 'Nenhum telefone cadastrado' }}</span>
                            </div>
                        </div>
                        <!-- Endereço -->
                        <div class="col-md-4 mb-3">
                            <div class="info-block">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-house-door-fill text-primary me-2"></i>
                                    <h6 class="mb-0">Endereço:</h6>
                                </div>
                                <span>{{ $sale->client->address ?? 'Nenhum endereço cadastrado' }}</span>
                            </div>
                        </div>
                        <!-- Status -->
                        <div class="col-md-4 mb-3">
                            <div class="info-block status-block">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <h6 class="mb-0">Status:</h6>
                                </div><br>
                                <span class="badge bg-{{ $sale->status == 'Paga' ? 'success' : 'danger' }}">
                                    {{ $sale->status }}
                                </span>
                            </div>
                        </div>
                        <!-- Total Pago -->
                        <div class="col-md-4 mb-3">
                            <div class="info-block">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack text-primary me-2"></i>
                                    <h6 class="mb-0">Total Pago:</h6>
                                </div>
                                <span>R$ {{ number_format($sale->amount_paid, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        <!-- Total da Venda -->
                        <div class="col-md-4 mb-3">
                            <div class="info-block">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-coin text-primary me-2"></i>
                                    <h6 class="mb-0">Total da Venda:</h6>
                                </div>
                                <span>R$ {{ number_format($sale->total_price, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        <!-- Total Restante -->
                        <div class="col-md-4 mb-3">
                            <div class="info-block">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-coin text-warning me-2"></i>
                                    <h6 class="mb-0">Total Restante:</h6>
                                </div>
                                <span>R$
                                    {{ number_format($sale->total_price - $sale->amount_paid, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
        /* Personalização do card */
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Bloco de informações */
        .info-block {
            background-color: #f7f8fa;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            /* Centralizar texto */
            max-height: 200px;
            /* Tamanho fixo para os cards */
        }

        /* Efeito de hover no bloco de informações */
        .info-block:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Ícones dentro dos blocos */
        .info-block i {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        /* Spans dentro dos blocos */
        .info-block span {
            font-size: 1rem;
            color: #333;
            display: block;
        }

        /* Bloco de status */
        .status-block {
            display: flex;
            align-items: center;
        }

        .status-block i {
            margin-right: 10px;
        }

        /* Customização da badge de status */
        .badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            text-transform: uppercase;
        }

        /* Badge 'success' para status pago */
        .badge.bg-success {
            background-color: #28a745;
            color: white;
        }

        /* Badge 'danger' para status não pago */
        .badge.bg-danger {
            background-color: #dc3545;
            color: white;
        }
        </style>
        <!-- Histórico de Pagamentos -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm rounded-lg h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title text-center text-primary" style="font-size: 1.5rem;">Histórico de
                            Pagamentos</h5>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#paymentModal{{ $sale->id }}">
                            <i class="bi bi-wallet2"></i> Pagamento
                        </button>
                    </div>
                    <div class="overflow-auto" style="max-height: 300px;">
                        <div class="list-group">
                            @foreach($sale->payments as $payment)
                            <div
                                class="list-group-item d-flex justify-content-between align-items-center mb-2 custom-list-item">
                                <div>
                                    <strong>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m') }} | R$
                                        {{ number_format($payment->amount_paid, 2, ',', '.') }} |
                                        {{ $payment->payment_method }}</strong>
                                </div>
                                <div class="d-flex">
                                    <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal"
                                        data-bs-target="#editPaymentModal{{ $payment->id }}" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalDeletePayment{{ $payment->id }}" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col-md-12 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-center w-75">Produtos da Venda</h3>
                    <button class="btn btn-primary rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#modalAddProductToSale{{ $sale->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-plus-square" viewBox="0 0 16 16">
                            <path
                                d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                            <path
                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                        </svg> Adicionar Produto
                    </button>
                </div>

                <div class="row mt-4">
                    @foreach ($sale->saleItems as $item)
                    <div class="col-md-2 mb-4">
                        <div class="card custom-card shadow-lg rounded-lg">
                            <img src="{{ asset('storage/products/' . $item->product->image) }}"
                                data-product-id="{{ $item->id }}" class="card-img-top" alt="{{ $item->product->name }}">

                            <div class="position-absolute top-0 end-0 p-2">
                                <a href="javascript:void(0)"
                                    class="btn btn-primary btn-sm p-1 rounded-circle btn-edit-product"
                                    data-product-id="{{ $item->id }}"
                                    data-product-price="{{ number_format($item->price_sale, 2, '.', '') }}"
                                    data-product-quantity="{{ $item->quantity }}" data-bs-toggle="modal"
                                    data-bs-target="#modalEditProduct" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    </svg>
                                </a>
                                <button type="button"
                                    class="btn btn-danger btn-sm p-1 rounded-circle btn-delete-product"
                                    data-bs-toggle="modal" data-bs-target="#modalDeleteProduct"
                                    data-sale-item-id="{{ $item->id }}" data-product-name="{{ $item->product->name }}"
                                    data-product-price="{{ number_format($item->product->price, 2, ',', '.') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path
                                            d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                    </svg>
                                </button>
                            </div>

                            <div class="card-body d-flex flex-column justify-content-between custom-card-body">
                                <h5 class="card-title text-center" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ $item->product->name }}">
                                    {{ ucwords($item->product->name) }}
                                </h5>

                                <p class="card-text text-center text-truncate product-description">
                                    {{ $item->product->description }}
                                </p>

                                <div class="product-info">
                                    <p class="text-center"><strong>Quantidade:</strong> {{ $item->quantity }}</p>
                                    <p class="text-center price">
                                        <strong>Preço Unitário:</strong> <br>R$
                                        {{ number_format($item->price_sale, 2, ',', '.') }}
                                    </p>
                                    <p class="text-center price"><strong>Preço Total:

                                        </strong><br> R$
                                        {{ number_format($item->price_sale * $item->quantity, 2, ',', '.') }}
                                    </p>
                                    <p class="text-center">
                                        <strong>Código:</strong><br> {{ $item->product->product_code }}
                                    </p>
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