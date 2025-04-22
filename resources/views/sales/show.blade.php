@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        @include('message.alert')
        <div class="row align-items-center mb-4">
            <h1 class="col-md-10 text-center">Detalhes da Venda #{{ $sale->id }}</h1>
            <div class="col-md-2 text-center">
                <a href="{{ route('sales.export', $sale->id) }}" class="btn btn-secondary">
                    <i class="bi bi-file-earmark-pdf"></i> Exportar Relatório PDF
                </a>
            </div>
        </div>

        <div class="row">
           <!-- Informações da Venda (Tabela Centralizada) -->
<div class="col-md-6 mb-4">
    <div class="card shadow-sm rounded-lg h-100">
        <div class="card-body">
            <h5 class="card-title mb-4 text-primary" style="font-size: 1.7rem;">Informações da Venda</h5>
            <!-- Tabela de Informações -->
            <table class="table table-bordered table-striped custom-table">
                <tbody>
                    <tr>
                        <td><strong>Cliente:</strong></td>
                        <td class="text-center">{{ $sale->client->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td class="text-center">{{ $sale->client->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Telefone:</strong></td>
                        <td class="text-center">{{ $sale->client->phone }}</td>
                    </tr>
                    <tr>
                        <td><strong>Endereço:</strong></td>
                        <td class="text-center">{{ $sale->client->address }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td class="text-center">
                            <span class="badge bg-{{ $sale->status == 'Paga' ? 'success' : 'danger' }}">
                                {{ $sale->status }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Total Pago:</strong></td>
                        <td class="text-center">R$ {{ number_format($sale->amount_paid, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total da Venda:</strong></td>
                        <td class="text-center">R$ {{ number_format($sale->total_price, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Restante:</strong></td>
                        <td class="text-center">R$
                            {{ number_format($sale->total_price - $sale->amount_paid, 2, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Histórico de Pagamentos -->
<div class="col-md-6 mb-4">
    <div class="card shadow-sm rounded-lg h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title text-center text-primary" style="font-size: 1.5rem;">Histórico de Pagamentos</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                    data-bs-target="#paymentModal{{ $sale->id }}">
                    <i class="bi bi-wallet2"></i> Pagamento
                </button>
            </div>
            <div class="overflow-auto" style="max-height: 300px;">
                <div class="list-group">
                    @foreach($sale->payments as $payment)
                        <div class="list-group-item d-flex justify-content-between align-items-center mb-2 custom-list-item">
                            <div>
                                <strong>{{ $payment->created_at->format('d/m') }} | R$
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
                                    <button type="button" class="btn btn-danger btn-sm p-1 rounded-circle btn-delete-product"
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
                                        <p class="text-center price"><strong>Preço Unitário:</strong> R$
                                            {{ number_format($item->price_sale, 2, ',', '.') }}
                                        </p>
                                        <p class="text-center price"><strong>Preço Total:</strong> R$
                                            {{ number_format($item->price_sale * $item->quantity, 2, ',', '.') }}
                                        </p>
                                        <p class="text-center"><strong>Código:</strong> {{ $item->product->product_code }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <style>
            /* Estilos gerais */
.card {
    border-radius: 12px;
    overflow: hidden;
    background-color: #f8f9fa;
}

.card-body {
    padding: 20px;
}

.custom-table th, .custom-table td {
    font-size: 1rem;
    text-align: center;
    padding: 10px;
}

.custom-table th {
    background-color: #f1f1f1;
    font-weight: bold;
    color: #333;
}

.custom-table td {
    color: #555;
}

.custom-list-item {
    background-color: #f9f9f9;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 10px;
    transition: background-color 0.3s ease;
}

.custom-list-item:hover {
    background-color: #e9ecef;
}

/* Histórico de Pagamentos */
.list-group-item {
    border: none;
    padding: 12px;
}

.btn-success, .btn-warning, .btn-danger {
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.btn-success:hover, .btn-warning:hover, .btn-danger:hover {
    transform: scale(1.1);
    background-color: #0056b3; /* Uma cor mais vibrante */
}

/* Ajustes no título e no botão */
.card-title {
    font-size: 1.7rem;
    font-weight: bold;
    color: #007bff;
}

.card-title.text-primary {
    font-size: 1.5rem;
}

.card-title.text-center {
    font-size: 1.6rem;
}

/* Estilo de botão */
button.btn {
    border-radius: 8px;
    padding: 8px 16px;
}

/* Responsividade */
@media (max-width: 768px) {
    .custom-table {
        font-size: 0.9rem;
    }

    .card-body {
        padding: 15px;
    }

    .card-title {
        font-size: 1.5rem;
    }

    .custom-list-item {
        font-size: 0.9rem;
    }
}

            .custom-card {
                border-radius: 15px;
                /* Bordas arredondadas */
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                /* Sombra sutil */
            }

            .custom-card:hover {
                transform: translateY(-5px);
                /* Leve elevação no hover */
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
                /* Sombra mais forte no hover */
            }

            .card-title {
                font-size: 1.2rem;
                font-weight: bold;
                text-transform: capitalize;
            }

            .card-text {
                font-size: 0.9rem;
                color: #555;
            }

            .custom-card-body {
                background-color: #f9f9f9;
                /* Cor de fundo mais suave */
                border-radius: 15px;
                padding: 20px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                /* Sombra suave */
                transition: box-shadow 0.3s ease;
            }

            .custom-card-body:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                /* Sombra mais forte no hover */
            }

            .card-title {
                font-size: 1.5rem;
                /* Tamanho de fonte maior */
                font-weight: 600;
                /* Negrito no título */
                color: #333;
                text-transform: capitalize;
                margin-bottom: 10px;
            }

            .product-description {
                font-size: 1rem;
                color: #555;
                margin-bottom: 15px;
                max-width: 250px;
                margin: 0 auto;
            }

            .product-info p {
                font-size: 1rem;
                margin: 5px 0;
                color: #666;
            }

            .product-info .price {
                font-weight: bold;
                color: #2c3e50;
            }

            .product-info p strong {
                color: #34495e;
                /* Cor de destaque para os títulos das informações */
            }

            /* Efeito de hover para os preços */
            .product-info .price:hover {
                color: #e74c3c;
                /* Cor vibrante para o hover */
                cursor: pointer;
                transition: color 0.3s ease;
            }

            /* Para dispositivos menores */
            @media (max-width: 768px) {
                .custom-card-body {
                    padding: 15px;
                }

                .card-title {
                    font-size: 1.2rem;
                    /* Ajuste no título */
                }

                .product-description {
                    max-width: 100%;
                    /* Deixe a descrição se ajustar melhor em telas menores */
                }

                .product-info p {
                    font-size: 0.9rem;
                    /* Fontes menores para telas pequenas */
                }
            }


            .btn-primary,
            .btn-danger {
                transition: background-color 0.3s ease, transform 0.3s ease;
            }

            .btn-sm {
                font-size: 0.8rem;
            }

            .card-img-top {
                border-radius: 15px 15px 0 0;
                object-fit: cover;
                height: 200px;
            }

            .position-absolute {
                top: 10px;
                right: 10px;
            }

            .text-center {
                text-align: center;
            }

            .text-truncate {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .card-body .card-title {
                margin-bottom: 10px;
            }
        </style>
    </div>
    <!-- Modal de confirmação para exclusão do produto -->
    <div class="modal fade" id="modalDeleteProduct" tabindex="-1" aria-labelledby="modalDeleteProductLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteProductLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Espaço para inserir dinamicamente a mensagem -->
                    <p id="modal-delete-message"></p>
                </div>
                <div class="modal-footer">
                    <form id="form-delete-product" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Seleciona todos os botões de exclusão
            var deleteButtons = document.querySelectorAll('.btn-delete-product');
            deleteButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    // Recupera os dados do item da venda
                    var saleItemId = this.getAttribute('data-sale-item-id');
                    var productName = this.getAttribute('data-product-name');
                    var productPrice = this.getAttribute('data-product-price');

                    // Atualiza a mensagem do modal
                    var message = "Tem certeza de que deseja excluir o produto <strong>" +
                        productName + "</strong>?<br>Preço do produto: <strong>R$ " +
                        productPrice + "</strong>";
                    document.getElementById('modal-delete-message').innerHTML = message;

                    // Atualiza a action do formulário usando a rota nomeada
                    var form = document.getElementById('form-delete-product');
                    // Substitui o placeholder ":id" pelo saleItemId
                    form.action = "{{ route('sales.item.destroy', ':id') }}".replace(':id', saleItemId);
                });
            });
        });
    </script>



    <!-- Modal para adicionar pagamento -->
    <div class="modal fade" id="paymentModal{{ $sale->id }}" tabindex="-1" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Adicionar Pagamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sales.addPayment', $sale->id) }}" method="POST">
                    @csrf
                    <!-- Adicionando o campo oculto 'from' -->
                    <input type="hidden" name="from" value="{{ request()->routeIs('sales.show') ? 'show' : 'index' }}">
                    <div class="modal-body">
                        <div id="paymentFields">
                            <div class="payment-item mb-3">
                                <label for="paymentAmount" class="form-label">Valor do Pagamento</label>
                                <input type="number" step="0.01" class="form-control" name="amount_paid[]" required min="0">
                            </div>
                            <div class="payment-item mb-3">
                                <label for="paymentMethod" class="form-label">Forma de Pagamento</label>
                                <select class="form-control" name="payment_method[]" required>
                                    <option value="Dinheiro">Dinheiro</option>
                                    <option value="Cartão de Crédito">Cartão de Crédito</option>
                                    <option value="Cartão de Débito">Cartão de Débito</option>
                                    <option value="PIX">PIX</option>
                                    <option value="Boleto">Boleto</option>
                                </select>
                            </div>
                            <div class="payment-item mb-3">
                                <label for="paymentDate" class="form-label">Data do Pagamento</label>
                                <input type="date" class="form-control" name="payment_date[]" required>
                            </div>
                        </div>
                        <button type="button" class="btn btn-info" id="addPaymentField">Adicionar Pagamento</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Confirmar Pagamento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($sale->payments as $payment)
        <!-- Modal de Editar Pagamento -->
        <div class="modal fade" id="editPaymentModal{{ $payment->id }}" tabindex="-1"
            aria-labelledby="editPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPaymentModalLabel{{ $payment->id }}">Editar Pagamento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('sales.updatePayment', ['saleId' => $sale->id, 'paymentId' => $payment->id]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Adicionando o campo oculto 'from' -->
                        <input type="hidden" name="from" value="{{ request()->routeIs('sales.show') ? 'show' : 'index' }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="paymentAmount" class="form-label">Valor do Pagamento</label>
                                <input type="number" step="0.01" class="form-control" name="amount_paid"
                                    value="{{ $payment->amount_paid }}" required min="0">
                            </div>
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Forma de Pagamento</label>
                                <select class="form-control" name="payment_method" required>
                                    <option value="Dinheiro" {{ $payment->payment_method == 'Dinheiro' ? 'selected' : '' }}>
                                        Dinheiro</option>
                                    <option value="Cartão de Crédito" {{ $payment->payment_method == 'Cartão de Crédito' ? 'selected' : '' }}>Cartão de Crédito</option>
                                    <option value="Cartão de Débito" {{ $payment->payment_method == 'Cartão de Débito' ? 'selected' : '' }}>Cartão de Débito</option>
                                    <option value="PIX" {{ $payment->payment_method == 'PIX' ? 'selected' : '' }}>PIX</option>
                                    <option value="Boleto" {{ $payment->payment_method == 'Boleto' ? 'selected' : '' }}>Boleto
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="paymentDate" class="form-label">Data do Pagamento</label>
                                <input type="date" class="form-control" name="payment_date"
                                    value="{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Confirmar Alteração</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de Exclusão do Pagamento -->
        <div class="modal fade" id="modalDeletePayment{{ $payment->id }}" tabindex="-1"
            aria-labelledby="modalDeletePaymentLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletePaymentLabel">Confirmar Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza de que deseja excluir este pagamento de R$
                            {{ number_format($payment->amount_paid, 2, ',', '.') }}?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('sales.deletePayment', ['saleId' => $sale->id, 'paymentId' => $payment->id]) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modalDeleteSale{{ $sale->id }}" tabindex="-1"
        aria-labelledby="modalDeleteSaleLabel{{ $sale->id }}" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteSaleLabel{{ $sale->id }}">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Exibir nome do cliente e preço total da venda -->
                    <p>Tem certeza de que deseja excluir a venda do cliente <strong>{{ $sale->client->name }}</strong>?</p>
                    <p>Preço total da venda: <strong>R$ {{ number_format($sale->total_price, 2, ',', '.') }}</strong></p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Adicionar Produto à Venda -->
    <div class="modal fade" id="modalAddProductToSale{{ $sale->id }}" tabindex="-1"
        aria-labelledby="modalAddProductToSaleLabel{{ $sale->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content custom-modal">
                <div class="modal-header justify-content-center text-center">
                    <h5 class="modal-title text-center" id="modalAddProductToSaleLabel{{ $sale->id }}">Adicionar Produto à Venda</h5>
                    <button type="button" class="btn-close custom-close-btn" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 100vh; overflow-y: auto;">
                    <!-- Formulário para Adicionar Produto à Venda -->
                    <form action="{{ route('sales.addProduct', $sale->id) }}" method="POST" id="saleForm{{ $sale->id }}"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Adicionando o campo oculto 'from' -->
                        <input type="hidden" name="from" value="{{ request()->routeIs('sales.show') ? 'show' : 'index' }}">

                        <!-- Barra de Pesquisa -->
                        <div class="d-flex mb-3">
                            <input type="text" class="form-control" id="productSearch{{ $sale->id }}"
                                placeholder="Pesquise por nome ou código do produto..." />
                            <div class="form-check form-switch ms-2 mt-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="showSelectedBtn{{ $sale->id }}" />
                                <label class="form-check-label" for="showSelectedBtn{{ $sale->id }}">Mostrar apenas
                                    selecionados</label>
                            </div>
                        </div>

                        <!-- Produtos Disponíveis -->
                        <div class="product-list-container" style="max-height: 65vh; overflow-y: scroll;">
                            <div class="row mb-4" id="productList{{ $sale->id }}">
                                @foreach($products as $product)
                                    @if($product->stock_quantity > 0)
                                        <div class="col-md-2 mb-4 product-card" data-product-id="{{ $product->id }}">
                                            <div class="card product-item" style="cursor: pointer;">
                                                <!-- Checkbox sobre a imagem -->
                                                <div class="form-check form-switch"
                                                    style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                                    <input class="form-check-input product-checkbox" type="checkbox" role="switch"
                                                        id="flexSwitchCheckDefault{{ $product->id }}"
                                                        data-product-id="{{ $product->id }}" />
                                                </div>

                                                <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top"
                                                    alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                                <div class="card-body">
                                                    <h5 class="card-title text-center">{{ $product->name }}</h5>
                                                    <table class="table table-bordered table-sm">
                                                        <tr>
                                                            <th>Código</th>
                                                            <td>{{ $product->product_code }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Estoque</th>
                                                            <td><span class="product-stock">{{ $product->stock_quantity }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Preço Original</th>
                                                            <td>
                                                                <input type="number" class="form-control product-price-original"
                                                                    name="products[{{ $product->id }}][price_original]"
                                                                    value="{{ old('products.' . $product->id . '.price_original', $product->price) }}"
                                                                    min="0" step="any" disabled />
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Preço de Venda</th>
                                                            <td>
                                                                <input type="number" class="form-control product-price-sale"
                                                                    name="products[{{ $product->id }}][price_sale]"
                                                                    value="{{ old('products.' . $product->id . '.price_sale', $product->price_sale) }}"
                                                                    min="0" step="any" disabled />
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Qtd</th>
                                                            <td>
                                                                <input type="number" class="form-control product-quantity"
                                                                    name="products[{{ $product->id }}][quantity]" min="1" value="1"
                                                                    disabled />
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Botão para Adicionar Produtos -->
                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Adicionar Produto à Venda</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos CSS -->
    <style>
    
        .modal-body {
            max-height: calc(100vh - 150px);
            overflow-y: auto;
            padding-right: 20px;
            /* Para a barra de rolagem */
        }

        .product-list-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            overflow-y: scroll;
        }

        .product-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            background-color: #fff;
        }

        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 15px;
            flex-grow: 1;
        }

        .form-check-input {
            margin-top: 12px;
        }

        .modal-dialog {
            max-width: 90%;
            margin: 30px auto;
        }

        /* Estilo do botão de fechar */
        .custom-close-btn {
            background-color: transparent;
            border: none;
            font-size: 1.5rem;
            color: #999;
            cursor: pointer;
            transition: color 0.3s;
        }

        .custom-close-btn:hover {
            color: #333;
        }

        /* Ajustes para melhor responsividade */
        @media (max-width: 768px) {
            .product-item {
                flex: 0 0 48%;
            }
        }
    </style>



    <!-- Modal Único para Edição do Produto da Venda -->
    <div class="modal fade" id="modalEditProduct" tabindex="-1" aria-labelledby="modalEditProductLabel" aria-hidden="true"
        data-sale-id="{{ $sale->id }}">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditProductLabel">Editar Produto da Venda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form id="form-edit-product" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Campo para editar o preço de venda -->
                        <div class="mb-3">
                            <label for="price_edit" class="form-label">Preço de Venda</label>
                            <input type="number" step="0.01" name="price_sale" id="price_edit" class="form-control"
                                required>
                        </div>
                        <!-- Campo para editar a quantidade -->
                        <div class="mb-3">
                            <label for="quantity_edit" class="form-label">Quantidade</label>
                            <input type="number" name="quantity" id="quantity_edit" class="form-control" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Atualizar Produto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Seleciona todos os botões de edição
            var editButtons = document.querySelectorAll('.btn-edit-product');

            editButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    // Obtém os dados do produto a partir dos data attributes
                    var productId = this.getAttribute('data-product-id');
                    var productPrice = this.getAttribute('data-product-price');
                    var productQuantity = this.getAttribute('data-product-quantity');

                    // Recupera o ID da venda do data attribute do modal (caso precise)
                    var saleId = document.getElementById('modalEditProduct').getAttribute('data-sale-id');

                    // Atualiza os campos do modal
                    document.getElementById('price_edit').value = productPrice;
                    document.getElementById('quantity_edit').value = productQuantity;

                    // Atualiza a action do formulário com a rota correta
                    var form = document.getElementById('form-edit-product');
                    form.action = "{{ url('sales') }}/" + saleId + "/item/" + productId;

                    // Se necessário, você pode abrir o modal manualmente:
                    // var modal = new bootstrap.Modal(document.getElementById('modalEditProduct'));
                    // modal.show();
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let form = document.getElementById('saleForm{{ $sale->id }}');
            let productList = document.getElementById('productList{{ $sale->id }}');
            // Função para a pesquisa de produtos
            document.getElementById('productSearch{{ $sale->id }}').addEventListener('input', function () {
                let filter = this.value.toLowerCase().replace(/\./g, ''); // Remove os pontos do input

                let products = document.querySelectorAll('#productList{{ $sale->id }} .product-card');
                products.forEach(function (product) {
                    let productName = product.querySelector('.card-title').textContent.toLowerCase();
                    let productCode = product.querySelector('table tr td').textContent.toLowerCase().replace(/\./g, ''); // Remove os pontos

                    if (productName.includes(filter) || productCode.includes(filter)) {
                        product.style.display = '';
                    } else {
                        product.style.display = 'none';
                    }
                });
            });

            // Mostrar apenas os produtos selecionados
            document.getElementById('showSelectedBtn{{ $sale->id }}').addEventListener('click', function () {
                let productCards = document.querySelectorAll('#productList{{ $sale->id }} .product-card');
                let selectedProducts = document.querySelectorAll('#productList{{ $sale->id }} .product-checkbox:checked');

                let isShowingSelectedOnly = this.dataset.selected === 'true';

                if (isShowingSelectedOnly) {
                    productCards.forEach(function (productCard) {
                        productCard.style.display = ''; // Exibe todos os produtos
                    });
                    this.dataset.selected = 'false'; // Atualiza o estado para mostrar todos
                    this.textContent = 'Mostrar apenas selecionados'; // Atualiza o texto do botão
                } else {
                    productCards.forEach(function (productCard) {
                        let checkbox = productCard.querySelector('.product-checkbox');
                        if (checkbox.checked) {
                            productCard.style.display = ''; // Exibe o produto selecionado
                        } else {
                            productCard.style.display = 'none'; // Esconde os não selecionados
                        }
                    });
                    this.dataset.selected = 'true'; // Atualiza o estado para mostrar apenas selecionados
                    this.textContent = 'Mostrar todos os produtos'; // Atualiza o texto do botão
                }
            });

            document.querySelectorAll('.product-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    var productCard = this.closest('.product-card'); // Obtém o card do produto mais próximo
                    var quantityInput = productCard.querySelector('.product-quantity'); // Obtém o campo de quantidade
                    var priceSaleInput = productCard.querySelector('.product-price-sale'); // Obtém o campo de preço de venda
                    var priceOriginalInput = productCard.querySelector('.product-price-original'); // Obtém o campo de preço original

                    var productId = this.dataset.productId; // Obtém o ID do produto

                    if (this.checked) {
                        // Quando o checkbox é marcado
                        productCard.style.opacity = 1; // Torna o produto totalmente visível
                        quantityInput.disabled = false; // Habilita o campo de quantidade
                        priceSaleInput.disabled = false; // Habilita o campo de preço de venda
                        priceOriginalInput.disabled = false; // Habilita o campo de preço original
                        addProductToForm(productId, quantityInput.value, priceSaleInput.value, priceOriginalInput.value); // Adiciona os dados ao formulário
                        productCard.classList.add('selected'); // Adiciona a classe 'selected' para aplicar estilos adicionais
                    } else {
                        // Quando o checkbox é desmarcado
                        productCard.style.opacity = 0.5; // Torna o produto meio cinza (transparente)
                        quantityInput.disabled = true; // Desabilita o campo de quantidade
                        priceSaleInput.disabled = true; // Desabilita o campo de preço de venda
                        priceOriginalInput.disabled = true; // Desabilita o campo de preço original
                        removeProductFromForm(productId); // Remove os dados do formulário
                        productCard.classList.remove('selected'); // Remove a classe 'selected' quando desmarcado
                    }
                });
            });

            // Função para adicionar o produto ao formulário
            function addProductToForm(productId, quantity, priceSale, priceOriginal) {
                let form = document.getElementById('saleForm{{ $sale->id }}'); // Formulário específico da venda

                // Adicionar o input hidden para a quantidade
                let inputQuantity = document.createElement("input");
                inputQuantity.type = "hidden";
                inputQuantity.name = `products[${productId}][quantity]`;
                inputQuantity.value = quantity;
                form.appendChild(inputQuantity);

                // Adicionar o input hidden para o id do produto
                let inputProductId = document.createElement("input");
                inputProductId.type = "hidden";
                inputProductId.name = `products[${productId}][product_id]`;
                inputProductId.value = productId;
                form.appendChild(inputProductId);

                // Adicionar o input hidden para o preço de venda
                let inputPriceSale = document.createElement("input");
                inputPriceSale.type = "hidden";
                inputPriceSale.name = `products[${productId}][price_sale]`;
                inputPriceSale.value = priceSale;
                form.appendChild(inputPriceSale);

                // Adicionar o input hidden para o preço original
                let inputPriceOriginal = document.createElement("input");
                inputPriceOriginal.type = "hidden";
                inputPriceOriginal.name = `products[${productId}][price]`; // Nome correto para o preço
                inputPriceOriginal.value = priceOriginal;
                form.appendChild(inputPriceOriginal);
            }

            // Função para remover o produto do formulário
            function removeProductFromForm(productId) {
                let form = document.getElementById('saleForm{{ $sale->id }}'); // Formulário específico da venda
                let inputs = form.querySelectorAll(`input[name="products[${productId}]"]`);
                inputs.forEach(input => input.remove());
            }



            // Verifica se há pelo menos um produto selecionado antes de submeter o formulário
            form.addEventListener("submit", function (event) {
                let selectedProducts = form.querySelectorAll('input[name^="products"]');

                if (selectedProducts.length === 0) {
                    event.preventDefault();
                    alert("Selecione pelo menos um produto.");
                }
            });
        });
    </script>



@endsection