@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <!-- Exibir erros de validação -->
    @if ($errors->any())
    <div id="error-message" class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="closeAlert('error-message')"></button>
        <div id="error-timer" class="alert-timer">30s</div>
    </div>
    @endif

    <!-- Exibir sucesso -->
    @if (session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="closeAlert('success-message')"></button>
        <div id="success-timer" class="alert-timer">30s</div>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- Filtros e Pesquisa -->
        <div class="row w-100">
            <!-- Coluna de Filtro (Meio) -->
            <div class="col-md-2 mb-3">
                <form action="{{ route('sales.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <select name="filter" class="form-control w-100" onchange="this.form.submit()">
                        <option value="">Filtrar</option>
                        <option value="created_at" {{ request('filter') == 'created_at' ? 'selected' : '' }}>Últimos Adicionados</option>
                        <option value="updated_at" {{ request('filter') == 'updated_at' ? 'selected' : '' }}>Últimos Atualizados</option>
                        <option value="name_asc" {{ request('filter') == 'name_asc' ? 'selected' : '' }}>Nome A-Z</option>
                        <option value="name_desc" {{ request('filter') == 'name_desc' ? 'selected' : '' }}>Nome Z-A</option>
                        <option value="price_asc" {{ request('filter') == 'price_asc' ? 'selected' : '' }}>Preço A-Z</option>
                        <option value="price_desc" {{ request('filter') == 'price_desc' ? 'selected' : '' }}>Preço Z-A</option>
                    </select>
                </form>
            </div>
            <div class="col-md-2 mb-3">
                <form action="{{ route('sales.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <select name="per_page" class="form-control w-100" onchange="this.form.submit()">
                        <option value="9" {{ request('per_page', 9) == '9' ? 'selected' : '' }}>9 itens</option>
                        <option value="25" {{ request('per_page', 9) == '25' ? 'selected' : '' }}>25 itens</option>
                        <option value="50" {{ request('per_page', 9) == '50' ? 'selected' : '' }}>50 itens</option>
                        <option value="100" {{ request('per_page', 9) == '100' ? 'selected' : '' }}>100 itens</option>
                    </select>
                </form>
            </div>
            <!-- Coluna de Pesquisa (Esquerda) -->
            <div class="col-md-4 mb-3">
                <form action="{{ route('sales.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <div class="input-group w-100">
                        <input type="text" name="search" class="form-control w-65 h-25" placeholder="Pesquisar por cliente" value="{{ request('search') }}">
                        <button class="btn btn-primary h-20" type="submit">Pesquisar</button>
                    </div>
                </form>
            </div>

            <!-- Coluna de Adicionar Venda (Direita) -->
            <div class="col-md-4 mb-3 text-end">
                <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalAddSale">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                    </svg>
                    Adicionar Venda
                </a>
            </div>
        </div>
    </div>

    <!-- Exibir todas as vendas -->
    @if(isset($sales) && $sales->isNotEmpty())
    <div class="row mt-4">
        @foreach($sales as $sale)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm rounded-lg h-100">
                <div class="card-header bg-primary text-white">
                    <!-- Exibir o nome do cliente, telefone, editar, excluir e adicionar produto -->
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <!-- Link para redirecionar apenas ao clicar no nome do cliente e telefone -->
                            <a href="{{ route('sales.show', $sale->id) }}" class="text-decoration-none text-dark">
                                <p class="mb-0"><strong>Nome:</strong> {{ ucwords($sale->client->name) }} |
                                    <strong>Telefone:</strong> {{ $sale->client->phone }}
                                </p>
                            </a>
                        </div>

                        <div class="col-md-3 text-center">
                            <!-- Status da venda -->
                            <p class="mb-0"><strong>Status:</strong>
                                <span class="badge
                @if($sale->status == 'active') badge-success
                @elseif($sale->status == 'inactive') badge-secondary
                @else badge-danger @endif
                text-dark">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </p>
                        </div>

                        <div class="col-md-2 text-end">
                            <!-- Botões de Editar, Excluir e Adicionar Produto -->
                            <div class="d-flex justify-content-end align-items-center">
                                <!-- Botão Editar -->
                                <button class="btn btn-warning btn-sm me-2 p-2" data-bs-toggle="modal" data-bs-target="#modalEditSale{{ $sale->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    </svg>
                                </button>
                                <!-- Botão Excluir -->
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm me-1 p-2" data-bs-toggle="modal" data-bs-target="#modalDeleteSale{{ $sale->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                        </svg>
                                    </button>
                                </form>
                                <!-- Botão Adicionar Produto -->
                                <button class="btn btn-primary btn-sm p-2" data-bs-toggle="modal" data-bs-target="#modalAddProductToSale{{ $sale->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    <!-- Produtos da venda -->
                    <div class="row" id="sale-products-{{ $sale->id }}">
                        @foreach($sale->saleItems as $index => $item)
                        <div class="col-md-3 sale-product {{ $index >= 4 ? 'd-none extra-product' : '' }}">
                            <div class="card d-flex flex-column h-100">
                                <img src="{{ asset('storage/products/'.$item->product->image) }}" class="card-img-top" alt="{{ $item->product->name }}" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <!-- Nome do produto com truncamento e tooltip -->
                                    <h6 class="card-title text-center" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->product->name }}">
                                        {{ $item->product->name }}
                                    </h6>
                                    <!-- Preço -->
                                    <p class="card-text text-center" style="font-weight: bold; color: #333;">
                                        <strong>Preço:</strong> R$ {{ number_format($item->product->price, 2, ',', '.') }}
                                    </p>
                                    <!-- Preço de venda -->
                                    <p class="card-text text-center" style="font-weight: bold; color: #007bff;">
                                        <strong>Preço Venda:</strong> R$ {{ number_format($item->price_sale, 2, ',', '.') }}
                                    </p>
                                    <!-- Quantidade -->
                                    <p class="card-text text-center" style="color: #555;">
                                        <strong>Qtd:</strong> {{ $item->quantity }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Botões de Expandir/Colapsar -->
                    @if($sale->saleItems->count() > 4)
                    <div class="text-center mt-3">
                        <button class="btn btn-primary" id="expandProducts-{{ $sale->id }}">+{{ $sale->saleItems->count() - 4 }} mais</button>
                        <button class="btn btn-secondary d-none" id="collapseProducts-{{ $sale->id }}">Mostrar menos</button>
                    </div>
                    @endif

                    <!-- Informações adicionais da venda -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <!-- Card único para informações adicionais -->
                            <div class="card p-4 shadow-sm rounded-lg border-0" style="background-color: #ffffff;">
                                <div class="row">
                                    <!-- Coluna da esquerda com total e saldo restante -->
                                    <div class="col-md-6">
                                        <h5 class="text-primary mb-3" style="font-weight: 600;">
                                            <strong>Total: </strong> R$ {{ number_format($sale->total_price, 2, ',', '.') }}
                                        </h5>
                                        <h6 class="text-muted mb-3" style="font-weight: 500;">
                                            <strong>Total Pago:</strong> R$ {{ number_format($sale->total_paid, 2, ',', '.') }}
                                            <!-- Botão de Ver Detalhes -->
                                            <button class="btn btn-outline-primary btn-sm ms-2 rounded-pill px-3 py-1" data-bs-toggle="modal" data-bs-target="#paymentHistoryModal{{ $sale->id }}">
                                                Ver Detalhes
                                            </button>
                                        </h6>
                                        <h6 class="text-muted mb-3" style="font-weight: 500;">
                                            <strong>Saldo Restante:</strong>
                                            <span style="color: #dc3545;">R$ {{ number_format($sale->total_price - $sale->total_paid, 2, ',', '.') }}</span>
                                        </h6>
                                    </div>

                                    <!-- Coluna da direita com forma de pagamento e desconto -->
                                    <div class="col-md-6 text-end">
                                        <h6 class="text-muted mb-3" style="font-weight: 500;">
                                            <strong>Forma de Pagamento:</strong>
                                            @if($sale->payments->isNotEmpty())
                                            {{ $sale->payments->first()->payment_method }} <!-- Pegando a primeira forma de pagamento da venda -->
                                            @else
                                            Não especificado
                                            @endif
                                        </h6>
                                        <h6 class="text-muted mb-3" style="font-weight: 500;">
                                            <strong>Desconto:</strong> R$ {{ number_format($sale->discount, 2, ',', '.') }}
                                        </h6>
                                        <!-- Botão de Adicionar Pagamento -->
                                        <button class="btn btn-success rounded-pill px-4 py-2 mt-3" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $sale->id }}">
                                            Adicionar Pagamento
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal de Histórico de Pagamentos -->
                    <div class="modal fade" id="paymentHistoryModal{{ $sale->id }}" tabindex="-1" aria-labelledby="paymentHistoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="paymentHistoryModalLabel">Histórico de Pagamentos</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Valor Pago</th>
                                                <th>Forma de Pagamento</th>
                                                <th>Ações</th> <!-- Coluna de Ações -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sale->payments as $payment)
                                            <tr>
                                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                                <td>R$ {{ number_format($payment->amount_paid, 2, ',', '.') }}</td>
                                                <td>{{ $payment->payment_method }}</td>
                                                <td>
                                                    <!-- Botões para Editar e Excluir -->
                                                    <button class="btn btn-warning btn-sm me-2 p-1" data-bs-toggle="modal" data-bs-target="#editPaymentModal{{ $payment->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Botão de Excluir Pagamento -->
                                                    <button type="button" class="btn btn-danger btn-sm me-1 p-1" data-bs-toggle="modal" data-bs-target="#modalDeletePayment{{ $payment->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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
    <div class="alert alert-warning text-center">
        Nenhuma venda encontrada.
    </div>
    @endif


    <div class="d-flex justify-content-center">
        <ul class="pagination">
            {{-- Link para a primeira página --}}
            @if ($sales->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Primeira</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $sales->url(1) }}&per_page={{ request('per_page', 9) }}">Primeira</a>
            </li>
            @endif

            {{-- Link para a página anterior --}}
            @if ($sales->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Anterior</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $sales->previousPageUrl() }}&per_page={{ request('per_page', 9) }}">Anterior</a>
            </li>
            @endif

            {{-- Páginas individuais --}}
            @foreach ($sales->getUrlRange(1, $sales->lastPage()) as $page => $url)
            <li class="page-item {{ $page == $sales->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $url }}&per_page={{ request('per_page', 9) }}">{{ $page }}</a>
            </li>
            @endforeach

            {{-- Link para a próxima página --}}
            @if ($sales->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $sales->nextPageUrl() }}&per_page={{ request('per_page', 9) }}">Próxima</a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link">Próxima</span>
            </li>
            @endif

            {{-- Link para a última página --}}
            @if ($sales->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $sales->url($sales->lastPage()) }}&per_page={{ request('per_page', 9) }}">Última</a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link">Última</span>
            </li>
            @endif
        </ul>
    </div>
</div>

@foreach($sales as $sale)
<!-- Modal para adicionar pagamento -->
<div class="modal fade" id="paymentModal{{ $sale->id }}" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Adicionar Pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sales.addPayment', $sale->id) }}" method="POST">
                @csrf
                <!-- Adicionando o campo oculto 'from' -->
                <input type="hidden" name="from" value="index">
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
@endforeach


@foreach($sales as $sale)
<!-- Modal para Editar Pagamento -->
@foreach($sale->payments as $payment)
<div class="modal fade" id="editPaymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="editPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPaymentModalLabel{{ $payment->id }}">Editar Pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sales.updatePayment', ['saleId' => $sale->id, 'paymentId' => $payment->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Adicionando o campo oculto 'from' -->
                <input type="hidden" name="from" value="index">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="paymentAmount" class="form-label">Valor do Pagamento</label>
                        <input type="number" step="0.01" class="form-control" name="amount_paid" value="{{ $payment->amount_paid }}" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Forma de Pagamento</label>
                        <select class="form-control" name="payment_method" required>
                            <option value="Dinheiro" {{ $payment->payment_method == 'Dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                            <option value="Cartão de Crédito" {{ $payment->payment_method == 'Cartão de Crédito' ? 'selected' : '' }}>Cartão de Crédito</option>
                            <option value="Cartão de Débito" {{ $payment->payment_method == 'Cartão de Débito' ? 'selected' : '' }}>Cartão de Débito</option>
                            <option value="PIX" {{ $payment->payment_method == 'PIX' ? 'selected' : '' }}>PIX</option>
                            <option value="Boleto" {{ $payment->payment_method == 'Boleto' ? 'selected' : '' }}>Boleto</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="paymentDate" class="form-label">Data do Pagamento</label>
                        <input type="date" class="form-control" name="payment_date" value="{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}" required>
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
@endforeach

@foreach($sale->payments as $payment)
<div class="modal fade" id="modalDeletePayment{{ $payment->id }}" tabindex="-1" aria-labelledby="modalDeletePaymentLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeletePaymentLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja excluir este pagamento de R$ {{ number_format($payment->amount_paid, 2, ',', '.') }}?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('sales.deletePayment', ['saleId' => $sale->id, 'paymentId' => $payment->id]) }}" method="POST">
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
@endforeach
<!-- Modal de Confirmar Exclusão para Vendas -->
@foreach($sales as $sale)
<div class="modal fade" id="modalDeleteSale{{ $sale->id }}" tabindex="-1" aria-labelledby="modalDeleteSaleLabel{{ $sale->id }}" aria-hidden="true">
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
@endforeach

@foreach($sales as $sale)
<!-- Modal de Adicionar Produto à Venda -->
<div class="modal fade" id="modalAddProductToSale{{ $sale->id }}" tabindex="-1" aria-labelledby="modalAddProductToSaleLabel{{ $sale->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddProductToSaleLabel{{ $sale->id }}">Adicionar Produto à Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulário para Adicionar Produto à Venda -->
                <form action="{{ route('sales.addProduct', $sale->id) }}" method="POST" id="saleForm{{ $sale->id }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Adicionando o campo oculto 'from' -->
                    <input type="hidden" name="from" value="index">
                    <!-- Barra de Pesquisa -->
                    <div class="d-flex mb-3">
                        <input type="text" class="form-control" id="productSearch{{ $sale->id }}" placeholder="Pesquise por nome ou código do produto..." />
                        <div class="form-check form-switch ms-2 mt-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="showSelectedBtn{{ $sale->id }}" />
                            <label class="form-check-label" for="showSelectedBtn{{ $sale->id }}">Mostrar apenas selecionados</label>
                        </div>
                    </div>

                    <!-- Produtos Disponíveis -->
                    <div class="row mb-4" id="productList{{ $sale->id }}">
                        @foreach($products as $product)
                        @if($product->stock_quantity > 0)
                        <div class="col-md-3 mb-4 product-card" data-product-id="{{ $product->id }}" style="opacity: 0.5;">
                            <div class="card product-item" style="cursor: pointer;">
                                <!-- Checkbox sobre a imagem -->
                                <div class="form-check form-switch" style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                    <input class="form-check-input product-checkbox" type="checkbox" role="switch" id="flexSwitchCheckDefault{{ $product->id }}" data-product-id="{{ $product->id }}" />
                                </div>

                                <img src="{{ asset('storage/products/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ $product->name }}</h5>
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Código</th>
                                            <td>{{ $product->product_code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Estoque</th>
                                            <td><span class="product-stock">{{ $product->stock_quantity }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Preço Original</th>
                                            <td>
                                                <input type="number" class="form-control product-price-original"
                                                    name="products[{{ $product->id }}][price_original]"
                                                    value="{{ old('products.'.$product->id.'.price_original', $product->price) }}"
                                                    min="0" step="any" disabled />
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Preço de Venda</th>
                                            <td>
                                                <input type="number" class="form-control product-price-sale"
                                                    name="products[{{ $product->id }}][price_sale]"
                                                    value="{{ old('products.'.$product->id.'.price_sale', $product->price_sale) }}"
                                                    min="0" step="any" disabled />
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Qtd</th>
                                            <td>
                                                <input type="number" class="form-control product-quantity" name="products[{{ $product->id }}][quantity]" min="1" value="1" disabled />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let form = document.getElementById('saleForm{{ $sale->id }}');
        let productList = document.getElementById('productList{{ $sale->id }}');
        // Função para a pesquisa de produtos
        document.getElementById('productSearch{{ $sale->id }}').addEventListener('input', function() {
            let filter = this.value.toLowerCase().replace(/\./g, ''); // Remove os pontos do input

            let products = document.querySelectorAll('#productList{{ $sale->id }} .product-card');
            products.forEach(function(product) {
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
        document.getElementById('showSelectedBtn{{ $sale->id }}').addEventListener('click', function() {
            let productCards = document.querySelectorAll('#productList{{ $sale->id }} .product-card');
            let selectedProducts = document.querySelectorAll('#productList{{ $sale->id }} .product-checkbox:checked');

            let isShowingSelectedOnly = this.dataset.selected === 'true';

            if (isShowingSelectedOnly) {
                productCards.forEach(function(productCard) {
                    productCard.style.display = ''; // Exibe todos os produtos
                });
                this.dataset.selected = 'false'; // Atualiza o estado para mostrar todos
                this.textContent = 'Mostrar apenas selecionados'; // Atualiza o texto do botão
            } else {
                productCards.forEach(function(productCard) {
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

        document.querySelectorAll('.product-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
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
        form.addEventListener("submit", function(event) {
            let selectedProducts = form.querySelectorAll('input[name^="products"]');

            if (selectedProducts.length === 0) {
                event.preventDefault();
                alert("Selecione pelo menos um produto.");
            }
        });
    });
</script>
@endforeach

@foreach($sales as $sale)
<!-- Modal de Editar Venda -->
<div class="modal fade" id="modalEditSale{{ $sale->id }}" tabindex="-1" aria-labelledby="modalEditSaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditSaleLabel">Editar Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sales.update', $sale->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Selecione o Cliente -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="client_id">Cliente</label>
                            <select name="client_id" id="client_id" class="form-control" required>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $sale->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Produtos da venda -->
                    <div class="row" style="max-height: 400px; overflow-y: auto;"> <!-- Barra de rolagem aqui -->
                        @foreach($sale->saleItems as $item)
                        <div class="col-md-3 mb-3">
                            <div class="card d-flex flex-column h-100">
                                <img src="{{ asset('storage/products/'.$item->product->image) }}" class="card-img-top" alt="{{ $item->product->name }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <!-- Nome do produto com truncamento -->
                                    <h6 class="card-title text-center" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->product->name }}">
                                        {{ $item->product->name }}
                                    </h6>
                                    <p class="card-text text-center" id="price-display-{{ $item->product->id }}">R$ {{ number_format($item->price_sale, 2, ',', '.') }}</p>

                                    <!-- Campo de edição do preço de venda -->
                                    <input type="number" name="products[{{ $item->product->id }}][price_sale]" value="{{ number_format($item->price_sale, 2, '.', '') }}" min="0" class="form-control mb-2 text-center" step="0.01" required>
                                    <!-- Campo de quantidade -->
                                    <p class="card-text text-center">Qtd: {{ $item->quantity }}</p>
                                    <input type="number" name="products[{{ $item->product->id }}][quantity]" min="1" class="form-control" value="{{ $item->quantity }}">
                                    <input type="hidden" name="products[{{ $item->product->id }}][product_id]" value="{{ $item->product->id }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Botão para Atualizar Venda -->
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Atualizar Venda</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal de Adicionar Venda -->
<div class="modal fade" id="modalAddSale" tabindex="-1" aria-labelledby="modalAddSaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddSaleLabel">Adicionar Nova Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sales.store') }}" method="POST" enctype="multipart/form-data" id="saleForm">
                    @csrf
                    <!-- Barra de Progresso com Bootstrap -->
                    <div class="progress ">
                        <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="3"></div>
                    </div>

                    <!-- Nomes das Etapas -->
                    <div class="d-flex justify-content-between text-center mb-4">
                        <span id="step-1-name" class="text-center">1. Cliente</span>
                        <span id="step-2-name" class="text-center">2. Produtos</span>
                        <span id="step-3-name" class="text-center">3. Resumo</span>
                    </div>

                    <!-- Conteúdo das Etapas -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Etapa 1: Cliente -->
                        <div class="tab-pane fade show active" id="step-1" role="tabpanel" aria-labelledby="step-1-tab">
                            <div class="row mb-4">
                                <div class="col-md-6">

                                    <input type="text" class="form-control" id="clientSearch" placeholder="Pesquise por nome ou telefone do cliente..." onkeyup="searchClients()" />
                                    <select name="client_id" id="client_id" class="form-control mt-2" required onchange="displayClientInfo()">
                                        <option value="" selected disabled>Selecione o cliente</option>
                                        @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->phone }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6" id="client-info" style="display:none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title text-center" id="client-name"></h5>
                                            <p class="card-text text-center" id="client-email"></p>
                                            <div class="row">
                                                <div class="col-6">
                                                    <p><strong>Telefone:</strong> <span id="client-phone"></span></p>
                                                    <p><strong>Endereço:</strong> <span id="client-address"></span></p>
                                                </div>
                                                <div class="col-6">
                                                    <p><strong>Data de Cadastro:</strong> <span id="client-registration_date"></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Etapa 2: Produtos -->
                        <div class="tab-pane fade" id="step-2" role="tabpanel" aria-labelledby="step-2-tab">
                            <div class="row mb-4">
                                <div class="col-md-12">

                                    <div class="d-flex mb-3">
                                        <input type="text" class="form-control" id="productSearch" placeholder="Pesquise por nome ou código do produto..." onkeyup="searchProducts()" />
                                        <div class="form-check form-switch ms-2 mt-2">
                                            <input class="form-check-input" type="checkbox" role="switch" id="showSelectedBtn" />
                                            <label class="form-check-label" for="showSelectedBtn">Mostrar apenas selecionados</label>
                                        </div>
                                    </div>
                                </div>

                                @foreach($products as $product)
                                @if($product->stock_quantity > 0) <!-- Apenas produtos com estoque maior que 0 -->
                                <div class="col-md-3 mb-4 product-card" data-product-id="{{ $product->id }}" style="opacity: 0.5;">
                                    <div class="card product-item" style="cursor: pointer;">
                                        <!-- Checkbox sobre a imagem -->
                                        <div class="form-check form-switch" style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                            <input class="form-check-input product-checkbox" type="checkbox" role="switch" id="flexSwitchCheckDefault{{ $product->id }}" data-product-id="{{ $product->id }}" />
                                        </div>

                                        <img src="{{ asset('storage/products/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title text-center">{{ $product->name }}</h5>
                                            <table class="table table-bordered table-sm">
                                                <tr>
                                                    <th>Código</th>
                                                    <td>{{ $product->product_code }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estoque</th>
                                                    <td><span class="product-stock">{{ $product->stock_quantity }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Preço</th>
                                                    <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Preço Venda</th>
                                                    <td>
                                                        <input type="number" class="form-control price-sale-input" name="products[{{ $product->id }}][price_sale]" value="{{ $product->price_sale }}" min="0" step="0.01" disabled />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Qtd</th>
                                                    <td>
                                                        <input type="number" name="products[{{ $product->id }}][quantity]" class="form-control product-quantity" min="1" value="1" disabled />
                                                    </td>
                                                </tr>
                                            </table>

                                            <input type="hidden" name="products[{{ $product->id }}][price]" value="{{ $product->price }}">
                                            <input type="hidden" name="products[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Etapa 3: Resumo -->
                        <div class="tab-pane fade" id="step-3" role="tabpanel" aria-labelledby="step-3-tab">

                            <!-- Resumo do Cliente -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h6 class="card-title text-center">Cliente</h6>
                                            <p><strong>Nome:</strong> <span id="summary-client-name">N/A</span></p>
                                            <p><strong>Telefone:</strong> <span id="summary-client-phone">N/A</span></p>
                                            <p><strong>Endereço:</strong> <span id="summary-client-address">N/A</span></p>
                                            <p><strong>Data de Cadastro:</strong> <span id="summary-client-registration_date">N/A</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumo dos Produtos Selecionados -->
                            <h6 class="text-center mb-3">Produtos Selecionados</h6>
                            <div class="row" id="selected-products-summary">
                                <!-- Aqui os produtos selecionados serão inseridos dinamicamente -->
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary" id="prevBtn" onclick="moveStep(-1)">Voltar</button>

                            <!-- Botão "Próximo" para todas as etapas exceto a última -->
                            <button type="button" class="btn btn-primary" id="nextBtn" onclick="moveStep(1)" style="display: inline;">Próximo</button>

                            <!-- Botão "Finalizar Venda" só aparece na última etapa -->
                            <button type="submit" class="btn btn-primary" id="finishBtn" style="display: none;">Finalizar Venda</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    // Controlador de navegação entre as etapas
    let currentStep = 1;
    const totalSteps = 3; // Temos 3 etapas

    function showStep(step) {
        // Esconde todas as etapas e mostra a etapa selecionada
        const steps = document.querySelectorAll('.tab-pane');
        steps.forEach((stepElement, index) => {
            if (index + 1 === step) {
                stepElement.classList.add('show', 'active');
            } else {
                stepElement.classList.remove('show', 'active');
            }
        });

        // Atualiza a barra de progresso
        const progressBar = document.getElementById('progress-bar');
        const progressPercentage = (step / totalSteps) * 100;
        progressBar.style.width = `${progressPercentage}%`;
        progressBar.setAttribute('aria-valuenow', step);

        // Atualiza os passos da barra
        const progressSteps = document.querySelectorAll('.progress-step');
        progressSteps.forEach((stepElement, index) => {
            if (index + 1 <= step) {
                stepElement.classList.add('active');
            } else {
                stepElement.classList.remove('active');
            }
        });

        // Exibe ou esconde o botão "Próximo" e "Finalizar"
        if (step === totalSteps) {
            document.getElementById('nextBtn').style.display = 'none';
            document.getElementById('finishBtn').style.display = 'inline-block';
        } else {
            document.getElementById('nextBtn').style.display = 'inline-block';
            document.getElementById('finishBtn').style.display = 'none';
        }

        // Mostra ou esconde o botão "Voltar"
        if (step === 1) {
            document.getElementById('prevBtn').style.display = 'none';
        } else {
            document.getElementById('prevBtn').style.display = 'inline-block';
        }
    }

    // Função para mudar de etapa
    function moveStep(stepChange) {
        const newStep = currentStep + stepChange;
        if (newStep >= 1 && newStep <= totalSteps) {
            currentStep = newStep;
            showStep(currentStep);
        }
    }

    // Inicializa a barra de progresso na primeira etapa
    showStep(currentStep);


    function displayClientInfo() {
        var clientId = document.getElementById('client_id').value; // Pega o ID do cliente selecionado

        // Verifica se o ID do cliente foi selecionado
        if (clientId) {
            // Fazendo uma requisição AJAX para buscar os dados do cliente
            fetch(`/client/${clientId}/data`)
                .then(response => response.json())
                .then(data => {
                    // Preenchendo os campos com os dados do cliente
                    document.getElementById('client-name').textContent = data.name || 'N/A';
                    document.getElementById('client-email').textContent = data.email || 'N/A';
                    document.getElementById('client-phone').textContent = data.phone || 'N/A';
                    document.getElementById('client-address').textContent = data.address || 'N/A';
                    document.getElementById('client-registration_date').textContent = data.created_at || 'N/A';

                    // Exibe a seção de informações do cliente
                    document.getElementById('client-info').style.display = 'block';
                })
                .catch(error => {
                    console.error('Erro ao buscar os dados do cliente:', error);
                });
        } else {
            // Se nenhum cliente for selecionado, esconde a área de informações
            document.getElementById('client-info').style.display = 'none';
        }
    }
    // Atualiza o resumo do cliente e dos produtos selecionados
    function updateSummary() {
        const clientId = document.getElementById('client_id').value;
        const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
        const selectedProductsSummary = document.getElementById('selected-products-summary');

        // Limpa o conteúdo anterior do resumo
        selectedProductsSummary.innerHTML = '';

        // Verifica se o cliente foi selecionado
        if (clientId) {
            // Busca os dados do cliente diretamente
            const clientName = document.getElementById('client-name').textContent;
            const clientPhone = document.getElementById('client-phone').textContent;
            const clientAddress = document.getElementById('client-address').textContent;
            const clientRegistrationDate = document.getElementById('client-registration_date').textContent;

            // Preenche o resumo com os dados do cliente
            document.getElementById('summary-client-name').textContent = clientName || 'N/A';
            document.getElementById('summary-client-phone').textContent = clientPhone || 'N/A';
            document.getElementById('summary-client-address').textContent = clientAddress || 'N/A';
            document.getElementById('summary-client-registration_date').textContent = clientRegistrationDate || 'N/A';
        } else {
            // Caso o cliente não seja selecionado, esconde a área de resumo do cliente
            document.getElementById('summary-client-name').textContent = 'N/A';
            document.getElementById('summary-client-phone').textContent = 'N/A';
            document.getElementById('summary-client-address').textContent = 'N/A';
            document.getElementById('summary-client-registration_date').textContent = 'N/A';
        }

        // Preenche o resumo dos produtos selecionados
        selectedProducts.forEach(function(checkbox) {
            const productCard = checkbox.closest('.product-card');
            const productName = productCard.querySelector('.card-title').textContent;
            const productQuantity = productCard.querySelector('.product-quantity').value;
            const productPriceSale = productCard.querySelector('.price-sale-input').value;

            // Cria e adiciona cada produto ao resumo de forma estilosa
            const summaryItem = document.createElement('div');
            summaryItem.classList.add('col-md-2', 'mb-4'); // Usando classes do Bootstrap para responsividade
            summaryItem.innerHTML = `
            <div class="card">
                <img src="${productCard.querySelector('.card-img-top').src}" class="card-img-top" alt="${productName}" style="height: 150px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">${productName}</h5>
                    <p><strong>Qtd:</strong> ${productQuantity}</p>
                    <p><strong>Preço:</strong> R$ ${parseFloat(productPriceSale).toFixed(2).replace('.', ',')}</p>
                </div>
            </div>
        `;
            selectedProductsSummary.appendChild(summaryItem);
        });
    }
    document.getElementById('productSearch').addEventListener('input', function() {
        let filter = this.value.toLowerCase().replace(/\./g, ''); // Remove os pontos do input

        let products = document.querySelectorAll('.product-card');

        products.forEach(function(product) {
            let productName = product.querySelector('.card-title').textContent.toLowerCase();
            let productCode = product.querySelector('table tr td').textContent.toLowerCase().replace(/\./g, ''); // Remove os pontos

            if (productName.includes(filter) || productCode.includes(filter)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    });

    // Filtro para pesquisa de clientes
    function searchClients() {
        let filter = document.getElementById('clientSearch').value.toLowerCase();
        let clients = document.querySelectorAll('#client_id option');
        clients.forEach(function(client) {
            let name = client.textContent.toLowerCase();
            if (name.includes(filter)) {
                client.style.display = '';
            } else {
                client.style.display = 'none';
            }
        });
    }


    // Função para lidar com o estado de seleção do produto
    document.querySelectorAll('.product-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var productCard = this.closest('.product-card'); // Obtém o card do produto mais próximo
            var quantityInput = productCard.querySelector('.product-quantity'); // Obtém o campo de quantidade
            var priceSaleInput = productCard.querySelector('.price-sale-input'); // Obtém o campo de preço de venda

            if (this.checked) {
                // Quando o checkbox é marcado
                productCard.style.opacity = 1; // Torna o produto totalmente visível
                quantityInput.disabled = false; // Habilita o campo de quantidade
                priceSaleInput.disabled = false; // Habilita o campo de preço de venda
                productCard.classList.add('selected'); // Adiciona a classe 'selected' para aplicar estilos adicionais
            } else {
                // Quando o checkbox é desmarcado
                productCard.style.opacity = 0.5; // Torna o produto meio cinza (transparente)
                quantityInput.disabled = true; // Desabilita o campo de quantidade
                priceSaleInput.disabled = true; // Desabilita o campo de preço de venda
                productCard.classList.remove('selected'); // Remove a classe 'selected' quando desmarcado
            }
        });
    });

    // Mostrar apenas os produtos selecionados
    document.getElementById('showSelectedBtn').addEventListener('click', function() {
        let productCards = document.querySelectorAll('.product-card');
        let selectedProducts = document.querySelectorAll('.product-checkbox:checked');

        // Verifica se estamos mostrando apenas os selecionados ou todos os produtos
        let isShowingSelectedOnly = this.dataset.selected === 'true';

        // Se estamos mostrando apenas os selecionados, alteramos para mostrar todos
        if (isShowingSelectedOnly) {
            productCards.forEach(function(productCard) {
                productCard.style.display = ''; // Exibe todos os produtos
            });
            this.dataset.selected = 'false'; // Atualiza o estado para mostrar todos
            this.textContent = 'Mostrar apenas selecionados'; // Atualiza o texto do botão
        } else {
            // Caso contrário, mostramos apenas os produtos selecionados
            productCards.forEach(function(productCard) {
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

    document.getElementById('saleForm').addEventListener('submit', function(event) {
        // Seleciona os produtos que estão marcados
        let selectedProducts = document.querySelectorAll('.product-checkbox:checked');

        // Se nenhum produto foi selecionado, impede o envio
        if (selectedProducts.length === 0) {
            event.preventDefault();
            alert('Por favor, selecione ao menos um produto.');
        } else {
            // Cria uma lista com os dados dos produtos selecionados
            let selectedProductData = [];

            selectedProducts.forEach(function(checkbox) {
                let productCard = checkbox.closest('.product-card');
                let quantityInput = productCard.querySelector('.product-quantity');
                let priceSaleInput = productCard.querySelector('.price-sale-input');

                // Verifica se a quantidade e o preço de venda estão definidos e são válidos
                if (quantityInput && priceSaleInput && quantityInput.value > 0 && priceSaleInput.value > 0) {
                    selectedProductData.push({
                        product_id: checkbox.dataset.productId, // Use dataset para acessar o ID do produto
                        quantity: quantityInput.value,
                        price_sale: priceSaleInput.value
                    });
                }
            });

            // Verifica se temos produtos válidos para enviar
            if (selectedProductData.length === 0) {
                event.preventDefault();
                alert('Por favor, preencha corretamente a quantidade e preço de venda dos produtos selecionados.');
                return;
            }

            // Envia os dados dos produtos selecionados como JSON
            let form = document.getElementById('saleForm');
            let productsInput = form.querySelector('input[name="products"]');

            if (!productsInput) {
                // Cria o campo hidden se ele não existir
                productsInput = document.createElement('input');
                productsInput.type = 'hidden';
                productsInput.name = 'products'; // Este será o nome que o Laravel espera
                form.appendChild(productsInput);
            }

            // Atualiza o campo hidden com os dados dos produtos selecionados
            productsInput.value = JSON.stringify(selectedProductData);
        }
    });
    // Atualiza o resumo sempre que a navegação for realizada (próximo passo)
    document.getElementById('nextBtn').addEventListener('click', updateSummary);

    // Inicializa a exibição da primeira etapa
    document.addEventListener('DOMContentLoaded', function() {
        showStep(currentStep);
    });

    // Adiciona novos campos de pagamento
    document.getElementById('addPaymentField').addEventListener('click', function() {
        const paymentFields = document.getElementById('paymentFields');
        const newPaymentField = document.createElement('div');
        newPaymentField.classList.add('payment-item', 'mb-3');

        newPaymentField.innerHTML = `
            <label for="paymentAmount" class="form-label">Valor do Pagamento</label>
            <input type="number" step="0.01" class="form-control" name="amount_paid[]" required min="0">
            <label for="paymentMethod" class="form-label">Forma de Pagamento</label>
            <select class="form-control" name="payment_method[]" required>
                <option value="Dinheiro">Dinheiro</option>
                <option value="Cartão de Crédito">Cartão de Crédito</option>
                <option value="Cartão de Débito">Cartão de Débito</option>
                <option value="PIX">PIX</option>
                <option value="Boleto">Boleto</option>
            </select>
            <label for="paymentDate" class="form-label">Data do Pagamento</label>
            <input type="date" class="form-control" name="payment_date[]" required>
        `;

        paymentFields.appendChild(newPaymentField);
    });
    document.addEventListener("DOMContentLoaded", function() {
        // Encontrar todos os botões de expandir para cada venda
        document.querySelectorAll("[id^='expandProducts-']").forEach(function(expandBtn) {
            const saleId = expandBtn.id.split('-')[1]; // Extrai o ID da venda do botão

            let collapseBtn = document.getElementById(`collapseProducts-${saleId}`);

            // Expansão dos produtos
            expandBtn.addEventListener("click", function() {
                // Mostrar os produtos extras dessa venda
                document.querySelectorAll(`#sale-products-${saleId} .extra-product`).forEach(el => el.classList.remove("d-none"));
                expandBtn.classList.add("d-none"); // Esconder o botão de expandir
                collapseBtn.classList.remove("d-none"); // Mostrar o botão de colapsar
            });

            // Colapsar os produtos
            collapseBtn.addEventListener("click", function() {
                // Esconder os produtos extras dessa venda
                document.querySelectorAll(`#sale-products-${saleId} .extra-product`).forEach(el => el.classList.add("d-none"));
                collapseBtn.classList.add("d-none"); // Esconder o botão de colapsar
                expandBtn.classList.remove("d-none"); // Mostrar o botão de expandir
            });
        });
    });


    // Função para buscar produtos durante a digitação
    document.getElementById('searchProduct').addEventListener('keyup', function() {
        var filter = this.value.toLowerCase();
        var products = document.querySelectorAll('.product-item');

        products.forEach(function(product) {
            var name = product.querySelector('.card-title').textContent.toLowerCase();
            var code = product.querySelector('.card-text').textContent.toLowerCase();

            if (name.includes(filter) || code.includes(filter)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    });

    // Botão para filtrar e mostrar apenas os produtos selecionados
    document.getElementById('filterSelectedProducts').addEventListener('click', function() {
        var products = document.querySelectorAll('.product-item');
        products.forEach(function(product) {
            var checkbox = product.querySelector('.product-checkbox');
            if (checkbox.checked) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Função para iniciar o timer e ocultar a mensagem após 30 segundos
        function startTimer(messageId, timerId) {
            let timerValue = 30;
            const timerElement = document.getElementById(timerId);
            const messageElement = document.getElementById(messageId);

            // Atualiza o temporizador a cada segundo
            const interval = setInterval(function() {
                if (timerValue > 0) {
                    timerElement.innerHTML = `${timerValue--}s`;
                } else {
                    clearInterval(interval);
                    // Fecha a mensagem após 30 segundos e recarrega a página
                    messageElement.classList.remove('show');
                    messageElement.classList.add('fade');
                    location.reload(); // Recarregar a página após 30 segundos
                }
            }, 1000); // Atualiza a cada segundo
        }

        // Iniciar o timer para a mensagem de erro (se existir)
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            startTimer('error-message', 'error-timer');
        }

        // Iniciar o timer para a mensagem de sucesso (se existir)
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            startTimer('success-message', 'success-timer');
        }

        // Configuração para mostrar que a página voltou ao estado original
        const closeButton = document.querySelectorAll('.btn-close');
        closeButton.forEach(button => {
            button.addEventListener('click', function() {
                // Resetando o timer de 30 segundos e voltando a página ao estado original
                document.getElementById('error-message')?.classList.remove('show');
                document.getElementById('success-message')?.classList.remove('show');
            });
        });
    });

    // Função para fechar o alerta ao clicar no "X"
    function closeAlert(messageId) {
        document.getElementById(messageId).classList.remove('show');
        document.getElementById(messageId).classList.add('fade');
    }
</script>

<style>
    /* Contêiner da Barra de Progresso */
    .progress-bar-container {
        width: 100%;
        margin-bottom: 30px;
        display: flex;
        justify-content: center;

    }

    span {
        flex: 1;
    }

    /* Barra de Progresso */
    .progress-bar {
        width: 80%;
        background-color: #28a745;
        /* Cor de fundo da barra */
        height: 10px;
        border-radius: 5px;
        position: relative;
    }

    /* Estilo dos Passos da Barra de Progresso */
    .progress-step {
        background-color: #ddd;
        color: #555;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        border-radius: 20px;
        transition: background-color 0.3s ease, transform 0.2s ease;
        position: relative;
        z-index: 1;
    }

    /* Quando a etapa está ativa */
    .progress-step.active {
        background-color: #28a745;
        color: white;
    }

    /* Linha entre os botões */
    .progress-step::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 100%;
        width: 100%;
        height: 3px;
        background-color: #ddd;
        transform: translateY(-50%);
        z-index: -1;
    }

    /* Linha verde para etapas ativas */
    .progress-step.active::after {
        background-color: #28a745;
    }

    /* Remover linha do último botão */
    .progress-step:last-child::after {
        display: none;
    }

    /* Efeito de hover para interação */
    .progress-step:hover {
        transform: translateY(-2px);
        box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
    }



    .alert-timer {
        position: absolute;
        top: 10px;
        right: 40px;
        background-color: #ff9800;
        color: white;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-top: 5px;
    }

    .alert-dismissible .btn-close {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        color: #fff;
        background: transparent;
        border: none;
    }
</style>
@endsection