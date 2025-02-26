@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <!-- Botão para abrir o Modal de Adicionar Venda -->
    <div class="d-flex justify-content-end">
        <button class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalAddSale">Adicionar Venda</button>
    </div>

    <!-- Exibir todas as vendas -->
    <div class="row mt-2">
        @foreach($sales as $sale)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <!-- Exibir o nome do cliente, telefone, editar, excluir e adicionar produto -->
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <!-- Nome do Cliente e Telefone -->
                            <p><strong>Nome:</strong> {{ ucwords($sale->client->name) }} |
                                <strong>Telefone:</strong> {{ $sale->client->phone }}
                            </p>
                        </div>

                        <div class="col-md-2 text-center">
                            <!-- Status da venda -->
                            <p><strong>Status:</strong>
                                <span class="badge
            @if($sale->status == 'active') badge-success
            @elseif($sale->status == 'inactive') badge-secondary
            @else badge-danger @endif
            text-dark">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </p>
                        </div>

                        <div class="col-md-3 text-end">
                            <!-- Botões de Editar, Excluir e Adicionar Produto -->
                            <div class="d-flex justify-content-end align-items-center">
                                <!-- Botão Editar -->
                                <button class="btn btn-warning btn-sm me-2 p-1" data-bs-toggle="modal" data-bs-target="#modalEditSale{{ $sale->id }}">
                                    <!-- Ícone de edição (pincel) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    </svg>
                                </button>

                                <!-- Botão Excluir -->
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm me-1 p-1" onclick="return confirm('Tem certeza que deseja excluir esta venda?')">
                                        <!-- Ícone de lixeira -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                        </svg>
                                    </button>
                                </form>

                                <!-- Botão Adicionar Produto -->
                                <button class="btn btn-primary btn-sm p-1" data-bs-toggle="modal" data-bs-target="#modalAddProductToSale{{ $sale->id }}">
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
                    <div class="row">
                        @foreach($sale->saleItems as $item)
                        <div class="col-md-3">
                            <div class="card">
                                <img src="{{ asset('storage/products/'.$item->product->image) }}" class="card-img-top" alt="{{ $item->product->name }}" style="height: 100px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title text-center">{{ $item->product->name }}</h6>
                                    <p class="card-text text-center">R$ {{ number_format($item->product->price, 2, ',', '.') }}</p>
                                    <p class="card-text text-center">R$ {{ number_format($item->product->price_sale, 2, ',', '.') }}</p>

                                    <p class="card-text text-center">Qtd: {{ $item->quantity }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Exibir o valor total da venda -->
                    <div class="row mt-3">
                        <div class="col-md-12 text-end">
                            <h5><strong>Total: R$ {{ number_format($sale->total_price, 2, ',', '.') }}</strong></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

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
                <form action="{{ route('sales.addProduct', $sale->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Selecione o Produto -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="product_id">Produto</label>
                            <select name="product_id" id="product_id" class="form-control" required>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} - R$ {{ number_format($product->price, 2, ',', '.') }} (Estoque: {{ $product->stock_quantity }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="quantity">Quantidade</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Barra de pesquisa -->
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="searchProduct" placeholder="Pesquise produtos por nome ou código..." />
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-info" id="filterSelectedProducts">Mostrar Selecionados</button>
                        </div>
                    </div>

                    <!-- Produtos Disponíveis -->
                    <div class="row mt-3" id="productList">
                        @foreach($products as $product)
                        <div class="col-md-2 mb-3">
                            <div class="card product-item" data-product-id="{{ $product->id }}">
                                <img src="{{ asset('storage/products/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 100px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title text-center">{{ $product->name }}</h6>
                                    <p class="card-text text-center">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                    <p class="card-text text-center">Estoque: {{ $product->stock_quantity }}</p>
                                    <input type="checkbox" class="form-check-input product-checkbox" data-product-id="{{ $product->id }}" />
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

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
</script>

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
                    <div class="row">
                        @foreach($sale->saleItems as $item)
                        <div class="col-md-2 mb-3">
                            <div class="card">
                                <img src="{{ asset('storage/products/'.$item->product->image) }}" class="card-img-top" alt="{{ $item->product->name }}" style="height: 100px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title text-center">{{ $item->product->name }}</h6>
                                    <p class="card-text text-center">R$ {{ number_format($item->product->price, 2, ',', '.') }}</p>
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
                    <div class="row mb-3">
                        <!-- Selecione o cliente -->
                        <div class="col-md-6">
                            <label for="client_id">Cliente</label>
                            <div class="d-flex">
                                <input type="text" class="form-control" id="clientSearch" placeholder="Pesquise por nome ou email do cliente..." onkeyup="searchClients()">
                                <select name="client_id" id="client_id" class="form-control mt-2 ms-2" required>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->phone }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- Filtro de Produtos -->
                        <div class="col-md-12">
                            <label for="productSearch">Produtos</label>
                            <div class="d-flex">
                                <input type="text" class="form-control" id="productSearch" placeholder="Pesquise por nome ou código do produto..." onkeyup="searchProducts()">
                                <input type="checkbox" id="showSelected" class="ms-2 mt-2" /> Mostrar apenas selecionados
                            </div>
                        </div>
                    </div>

                    <div class="row" id="products-container">
                        @foreach($products as $product)
                        <div class="col-md-2 mb-3 product-card" data-product-id="{{ $product->id }}">
                            <div class="card" style="cursor: pointer;">
                                <img src="{{ asset('storage/products/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ $product->name }}</h5>
                                    <p class="card-text text-center">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                    <p class="card-text text-center">Estoque: {{ $product->stock_quantity }}</p>
                                    <input type="checkbox" name="products[{{ $product->id }}][product_id]" value="{{ $product->id }}" class="product-checkbox" data-product-id="{{ $product->id }}" disabled>
                                    <input type="number" name="products[{{ $product->id }}][quantity]" class="form-control product-quantity" min="1" value="1" disabled>
                                    <input type="hidden" name="products[{{ $product->id }}][price]" value="{{ $product->price }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Adicionar Venda</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Filtro para pesquisa de produtos
    function searchProducts() {
        let filter = document.getElementById('productSearch').value.toLowerCase();
        let products = document.querySelectorAll('.product-card');
        products.forEach(function(product) {
            let name = product.querySelector('.card-title').textContent.toLowerCase();
            let code = product.querySelector('.card-text').textContent.toLowerCase();
            if (name.includes(filter) || code.includes(filter)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    }

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

    // Lógica para selecionar produtos ao clicar na div do produto
    document.querySelectorAll('.product-card').forEach(function(productCard) {
        productCard.addEventListener('click', function() {
            let checkbox = productCard.querySelector('.product-checkbox');
            let quantityInput = productCard.querySelector('.product-quantity');
            if (checkbox.disabled) {
                checkbox.disabled = false;
                quantityInput.disabled = false;
                checkbox.checked = true; // Marca a checkbox
            } else {
                checkbox.disabled = true;
                quantityInput.disabled = true;
                checkbox.checked = false; // Desmarca a checkbox
            }
        });
    });

    // Mostrar apenas os produtos selecionados
    document.getElementById('showSelected').addEventListener('change', function() {
        let selectedProducts = document.querySelectorAll('.product-checkbox:checked');
        document.querySelectorAll('.product-card').forEach(function(productCard) {
            if (Array.from(selectedProducts).some(function(checkbox) {
                    return checkbox.closest('.product-card') === productCard;
                })) {
                productCard.style.display = ''; // Exibe os selecionados
            } else {
                productCard.style.display = 'none'; // Esconde os não selecionados
            }
        });
    });
</script>



@endsection
