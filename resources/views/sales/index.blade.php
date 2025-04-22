@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        @include('message.alert')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Filtros e Pesquisa -->
            <div class="row w-100">

                <div class="col-md-2 mb-3">
                    <form action="{{ route('sales.index') }}" method="GET" class="d-flex align-items-center w-100">
                        <select name="filter" class="form-control w-100" onchange="this.form.submit()">
                            <option value="">Filtrar</option>
                            <option value="created_at" {{ request('filter') == 'created_at' ? 'selected' : '' }}>Últimos
                                Adicionados</option>
                            <option value="updated_at" {{ request('filter') == 'updated_at' ? 'selected' : '' }}>Últimos
                                Atualizados</option>
                            <option value="name_asc" {{ request('filter') == 'name_asc' ? 'selected' : '' }}>Nome A-Z</option>
                            <option value="name_desc" {{ request('filter') == 'name_desc' ? 'selected' : '' }}>Nome Z-A
                            </option>
                            <option value="price_asc" {{ request('filter') == 'price_asc' ? 'selected' : '' }}>Preço A-Z
                            </option>
                            <option value="price_desc" {{ request('filter') == 'price_desc' ? 'selected' : '' }}>Preço Z-A
                            </option>
                        </select>
                    </form>
                </div>
                <div class="col-md-2 mb-3">
                    <form action="{{ route('sales.index') }}" method="GET" class="d-flex align-items-center w-100">
                        <select name="per_page" class="form-control w-100" onchange="this.form.submit()">
                            <option value="9" {{ request('per_page', 9) == '8' ? 'selected' : '' }}>8 itens</option>
                            <option value="25" {{ request('per_page', 9) == '24' ? 'selected' : '' }}>28 itens</option>
                            <option value="50" {{ request('per_page', 9) == '56' ? 'selected' : '' }}>56 itens</option>
                            <option value="100" {{ request('per_page', 9) == '100' ? 'selected' : '' }}>100 itens</option>
                        </select>
                    </form>
                </div>

                <div class="col-md-4 mb-3">
                    <form action="{{ route('sales.index') }}" method="GET" class="d-flex align-items-center w-100">
                        <div class="input-group w-100">
                            <input type="text" name="search" id="search-input" class="form-control w-65 h-25"
                                placeholder="Pesquisar por cliente" value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
                <script>
                    function setupDynamicSearch() {
                        const searchInput = document.getElementById('search-input');
                        if (!searchInput) return;

                        let timeout = null;

                        searchInput.addEventListener('input', function () {
                            clearTimeout(timeout);
                            timeout = setTimeout(() => {
                                const query = this.value;
                                const url = `{{ route('sales.index') }}?search=${encodeURIComponent(query)}`;

                                fetch(url)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Erro ao buscar dados');
                                        }
                                        return response.text();
                                    })
                                    .then(html => {
                                        const parser = new DOMParser();
                                        const doc = parser.parseFromString(html, 'text/html');
                                        const newContent = doc.querySelector('.container-fluid.py-4');
                                        if (newContent) {
                                            document.querySelector('.container-fluid.py-4').innerHTML = newContent.innerHTML;
                                            setupDynamicSearch(); // Reaplicar o evento após atualização do DOM
                                        }
                                    })
                                    .catch(error => console.error('Erro ao buscar dados:', error));
                            }, 100); // Adiciona um atraso para evitar requisições excessivas
                        });
                    }

                    document.addEventListener('DOMContentLoaded', setupDynamicSearch);
                </script>

                <div class="col-md-4 mb-3 text-end">
                    <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal"
                        data-bs-target="#modalAddSale">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            class="bi bi-plus-square" viewBox="0 0 16 16">
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
                                        <!-- Botões de Editar, Excluir e Adicionar Produto -->
                                        <div class="d-flex justify-content-end align-items-center gap-2">
                                            <button class="btn btn-github p-2 w-100" data-bs-toggle="modal"
                                                data-bs-target="#paymentHistoryModal{{ $sale->id }}" title="historico de pagamento">
                                                <i class="bi bi-clock-history"></i>
                                            </button>
                                            <!-- Botão Exportar -->
                                            <a href="{{ route('sales.export', $sale->id) }}" class="btn btn-secondary p-2 w-100"
                                                title="Exportar PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>

                                            <!-- Botão Adicionar Pagamento -->
                                            <button class="btn btn-success btn-sm p-2 w-100" data-bs-toggle="modal"
                                                data-bs-target="#paymentModal{{ $sale->id }}" title="Adicionar Pagamento">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                    class="bi bi-plus-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                    <path
                                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                                </svg>
                                            </button>

                                            <!-- Botão Ver Detalhes -->
                                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info p-2 w-100"
                                                title="Ver Detalhes">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- Botão Editar -->
                                            <button class="btn btn-warning  p-2 w-100" data-bs-toggle="modal"
                                                data-bs-target="#modalEditSale{{ $sale->id }}" title="Editar Venda">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                </svg>
                                            </button>

                                            <!-- Botão Excluir -->
                                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger p-2 w-100" data-bs-toggle="modal"
                                                    data-bs-target="#modalDeleteSale{{ $sale->id }}" title="Excluir Venda">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                        <path
                                                            d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                    </svg>
                                                </button>
                                            </form>

                                            <!-- Botão Adicionar Produto -->
                                            <button class="btn btn-primary btn-sm p-2 w-100" data-bs-toggle="modal"
                                                data-bs-target="#modalAddProductToSale{{ $sale->id }}"
                                                title="Adicionar Produto à Venda">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                    class="bi bi-plus-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                    <path
                                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
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
                                                <img src="{{ asset('storage/products/' . $item->product->image) }}" class="card-img-top"
                                                    alt="{{ $item->product->name }}" style="height: 200px; object-fit: cover;">
                                                <div class="card-body">
                                                    <!-- Nome do produto com truncamento e tooltip -->
                                                    <h6 class="card-title text-center"
                                                        style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                        title="{{ $item->product->name }}">
                                                        {{ $item->product->name }}
                                                    </h6>
                                                    <!-- Preço -->
                                                    <p class="card-text text-center" style="font-weight: bold; color: #333;">
                                                        <strong>Preço:</strong> R$
                                                        {{ number_format($item->product->price, 2, ',', '.') }}
                                                    </p>
                                                    <!-- Preço de venda -->
                                                    <p class="card-text text-center" style="font-weight: bold; color: #007bff;">
                                                        <strong>Preço Venda:</strong> R$
                                                        {{ number_format($item->price_sale, 2, ',', '.') }}
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
                                        <button class="btn btn-primary"
                                            id="expandProducts-{{ $sale->id }}">+{{ $sale->saleItems->count() - 4 }} mais</button>
                                        <button class="btn btn-secondary d-none" id="collapseProducts-{{ $sale->id }}">Mostrar
                                            menos</button>
                                    </div>
                                @endif
                                <!-- Informações adicionais da venda -->
                                <!-- Informações adicionais da venda -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <!-- Card único para informações adicionais -->
                                        <div class="card p-4 shadow-lg rounded-lg border-0" style="background-color: #f8f9fa;">
                                            <div class="row d-flex justify-content-between align-items-center">
                                                <!-- Coluna com Total -->
                                                <div class="col-md-4 d-flex flex-column align-items-start">
                                                    <h6 class="text-primary text-center mb-3"
                                                        style="font-weight: 600; font-size: 1.2rem;">
                                                        <strong>Total:</strong> <br>
                                                        <span class="text-dark">
                                                            R$ {{ number_format($sale->total_price, 2, ',', '.') }}
                                                        </span>
                                                    </h6>
                                                </div>

                                                <!-- Coluna com Total Pago -->
                                                <div class="col-md-4 d-flex flex-column align-items-center">
                                                    <h6 class="text-muted text-center mb-3"
                                                        style="font-weight: 500; font-size: 1rem;">
                                                        <strong>Total Pago:</strong> <br>
                                                        <span class="text-dark">
                                                            R$ {{ number_format($sale->total_paid, 2, ',', '.') }}
                                                        </span>
                                                    </h6>
                                                </div>

                                                <!-- Coluna com Saldo Restante -->
                                                <div class="col-md-4 d-flex flex-column align-items-end">
                                                    <h6 class="text-muted text-center mb-3"
                                                        style="font-weight: 500; font-size: 1rem;">
                                                        <strong>Saldo Restante:</strong> <br>

                                                        <span class="badge bg-danger text-white" style="font-size: 1rem;">
                                                            R$
                                                            {{ number_format($sale->total_price - $sale->total_paid, 2, ',', '.') }}
                                                        </span>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <!-- Modal de Histórico de Pagamentos -->
                                <div class="modal fade" id="paymentHistoryModal{{ $sale->id }}" tabindex="-1"
                                    aria-labelledby="paymentHistoryModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="paymentHistoryModalLabel">Histórico de Pagamentos</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
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
                                                                    <button class="btn btn-warning btn-sm me-2 p-1"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editPaymentModal{{ $payment->id }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                            fill="currentColor" class="bi bi-pencil-square"
                                                                            viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                                        </svg>
                                                                    </button>

                                                                    <!-- Botão de Excluir Pagamento -->
                                                                    <button type="button" class="btn btn-danger btn-sm me-1 p-1"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modalDeletePayment{{ $payment->id }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                            fill="currentColor" class="bi bi-trash3"
                                                                            viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                                        </svg>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Fechar</button>
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

        <!-- Paginação -->
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
                                href="{{ $sales->url($sales->currentPage() + 1) }}">{{ $sales->currentPage() + 1 }}</a></li>
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
    </div>

    @include('sales.createPayament')
    @include('sales.editPayament')
    @include('sales.deletPayament')
    @include('sales.edit')
    @include('sales.create')
    @include('sales.delet')
    @include('sales.addProduct')

@endsection