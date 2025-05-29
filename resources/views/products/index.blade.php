@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <link rel="stylesheet" href="{{ asset('css/produtos.css') }}">
    <!-- Filtro e Pesquisa -->
    <div class="d-flex justify-content-between align-items-center">
        <div class="row w-100">
            {{-- Filtros de Produtos no mesmo padrão dos filtros de vendas --}}
            <div class="col-md-3 mb-3">
                <form action="{{ route('products.index') }}" method="GET" class="w-100" id="productsFiltersForm">
                    <div class="dropdown w-100 dropdown-filtros" data-bs-auto-close="outside">
                        <button
                            class="btn btn-gradient-primary w-100 dropdown-toggle rounded-pill shadow d-flex justify-content-between align-items-center px-4 py-2"
                            type="button" id="dropdownProductsFilter" data-bs-toggle="dropdown" aria-expanded="false"
                            style="font-weight:600;">
                            <span>
                                <i class="bi bi-funnel-fill me-2"></i> Filtros
                            </span>
                            @if(request('filter') || request('per_page') || request('category'))
                            <span class="badge bg-light text-primary ms-2">Ativo</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-animate w-100 rounded-4 p-3 border-0 shadow-lg"
                            aria-labelledby="dropdownProductsFilter" style="min-width: 320px;">
                            {{-- Ordenação --}}
                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-sort-alpha-down text-primary me-2"></i>
                                        <h6 class="mb-0 text-primary" style="font-size:1rem;">Ordenar</h6>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php
                                        $filters = [
                                        'created_at' => 'Adicionados',
                                        'updated_at' => 'Atualizados',
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
                            {{-- Categorias --}}
                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-tags-fill text-info me-2"></i>
                                        <h6 class="mb-0 text-info" style="font-size:1rem;">Categorias</h6>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($categories as $category)
                                        @if ($category->type === 'product')
                                        <div class="form-check form-check-inline form-check-custom">
                                            <input class="form-check-input" type="checkbox" name="category[]"
                                                id="category_{{ $category->id_category }}"
                                                value="{{ $category->id_category }}"
                                                {{ is_array(request('category')) && in_array($category->id_category, request('category')) ? 'checked' : '' }}>
                                            <label class="form-check-label small"
                                                for="category_{{ $category->id_category }}" data-bs-toggle="tooltip"
                                                title="{{ $category->name }}">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                            {{-- Itens por página --}}
                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-list-ol text-success me-2"></i>
                                        <h6 class="mb-0 text-success" style="font-size:1rem;">Qtd. Itens</h6>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php $perPages = [18, 30, 48, 96]; @endphp
                                        @foreach($perPages as $num)
                                        <div class="form-check form-check-inline form-check-custom">
                                            <input class="form-check-input" type="radio" name="per_page"
                                                id="per_page_{{ $num }}" value="{{ $num }}"
                                                {{ request('per_page') == $num ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="per_page_{{ $num }}"
                                                data-bs-toggle="tooltip" title="{{ $num }} itens">
                                                {{ $num }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                            {{-- Botões --}}
                            <li>
                                <div class="d-flex gap-2 mt-2">
                                    <button type="submit" class="btn btn-gradient-success rounded-pill px-3 flex-fill">
                                        <i class="bi bi-check2-circle"></i> Aplicar
                                    </button>
                                    @if(request('filter') || request('per_page') || request('category'))
                                    <a href="{{ route('products.index') }}"
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
            <div class="col-md-3 mb-3">
                <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <div class="input-group search-bar-sales w-100">
                        <span class="input-group-text search-bar-sales-icon" id="search-addon">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" id="search-input" class="form-control search-bar-sales-input"
                            placeholder="Pesquisar por nome ou código" value="{{ request('search') }}"
                            aria-label="Pesquisar por nome ou código" aria-describedby="search-addon">
                        <input type="hidden" name="filter" value="{{ request('filter') }}"> <!-- Preserva o filtro -->
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    </div>
                </form>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center mb-0">
                <a href="#" class="btn bg-gradient-primary btn-sm mb-0 d-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#modalAddProduct">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        class="bi bi-plus-square me-1" viewBox="0 0 16 16">
                        <path
                            d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                        <path
                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                    </svg>
                    Produto
                </a>
                <a href="#" class="btn bg-gradient-secondary btn-sm mb-0 ms-2 d-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#modalUploadProduct">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-file-earmark-arrow-up me-1" viewBox="0 0 16 16">
                        <path
                            d="M8.5 11.5a.5.5 0 0 1-1 0V7.707L6.354 8.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 7.707z" />
                        <path
                            d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                    </svg>
                    upload
                </a>
            </div>
        </div>
    </div>
    <!-- Tabela de Produtos -->
    <div id="productsContainer" class="row mt-4">
        {{-- Bloco estilizado para nenhum produto encontrado --}}
        @if($products->isEmpty())
        <div class="col-12">
            <div class="d-flex flex-column align-items-center justify-content-center py-5">
                <div class="animated-icon mb-4">
                    <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                        <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3" fill="#f8fafc" />
                        <rect x="35" y="55" width="60" height="40" rx="12" fill="#e9f2ff" stroke="#6ea8fe"
                            stroke-width="3" />
                        <rect x="50" y="40" width="30" height="25" rx="7" fill="#f8fafc" stroke="#6ea8fe"
                            stroke-width="3" />
                        <path d="M45 95c0-10 10-18 20-18s20 8 20 18" stroke="#6ea8fe" stroke-width="3"
                            stroke-linecap="round" />
                        <circle cx="65" cy="75" r="6" fill="#6ea8fe" opacity="0.15" />
                        <rect x="60" y="65" width="10" height="10" rx="3" fill="#6ea8fe" opacity="0.25" />
                    </svg>
                </div>
                <h2 class="fw-bold mb-3 text-primary"
                    style="font-size:2.5rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                    Nenhum Produto Encontrado
                </h2>
                <p class="mb-4 text-secondary text-center"
                    style="max-width: 480px; font-size:1.25rem; font-weight:500; line-height:1.6;">
                    <span style="color:#0d6efd; font-weight:700;">Ops!</span> Sua prateleira está vazia.<br>
                    <span style="color:#6ea8fe;">Cadastre seu primeiro produto</span> e comece a vender agora mesmo!
                </p>

            </div>
        </div>
        @else
        @foreach($products as $product)
        <div class="col-md-2 mb-4">
            <div class="product-card-modern position-relative d-flex flex-column h-100">
                <!-- Botões flutuantes -->
                <div class="btn-action-group">
                    <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modalEditProduct{{ $product->id }}" title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#modalDeleteProduct{{ $product->id }}" title="Excluir">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

                <!-- Área da imagem com badges -->
                <div class="product-img-area">
                    <img src="{{ asset('storage/products/' . $product->image) }}" class="product-img"
                        alt="{{ $product->name }}">
                    <!-- Código do produto -->
                    <span class="badge-product-code" title="Código do Produto">
                        <i class="bi bi-upc-scan"></i> {{ $product->product_code }}
                    </span>
                    <!-- Quantidade -->
                    <span class="badge-quantity" title="Quantidade em Estoque">
                        <i class="bi bi-stack"></i> {{ $product->stock_quantity }}
                    </span>
                    <!-- Ícone da categoria -->
                    <div class="category-icon-wrapper" style="color: {{ $product->category->hexcolor_category }}; border: 2px solid {{ $product->category->hexcolor_category }};
                                       background-color: {{ $product->category->hexcolor_category }}20;">
                        <i class="{{ $product->category->icone }} category-icon"></i>
                    </div>
                    @if($product->stock_quantity == 0)
                    <div class="out-of-stock">
                        <i class="bi bi-x-circle"></i> Fora de Estoque
                    </div>
                    @endif
                </div>
                <!-- Conteúdo -->
                <div class="card-body">
                    <div class="product-title" title="{{ $product->name }}">
                        <i class="bi bi-box-seam"></i>
                        {{ ucwords($product->name) }}
                    </div>
                    <div class="product-description" title="{{ $product->description }}">
                        <i class="bi bi-card-text"></i>
                        {{ ucwords($product->description) }}
                    </div>
                    <div class="price-area">
                        <span class="badge-price" title="Preço de Custo">
                            <i class="bi bi-tag"></i>
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </span>
                        <span class="badge-price-sale" title="Preço de Venda">
                            <i class="bi bi-currency-dollar"></i>
                            R$ {{ number_format($product->price_sale, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">
                <!-- Botão para a primeira página -->
                @if ($products->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;&laquo;</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $products->url(1) }}">&laquo;&laquo;</a></li>
                @endif

                <!-- Botão para a página anterior -->
                @if ($products->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}">&laquo;</a></li>
                @endif

                <!-- Página anterior -->
                @if ($products->currentPage() > 1)
                <li class="page-item"><a class="page-link"
                        href="{{ $products->url($products->currentPage() - 1) }}">{{ $products->currentPage() - 1 }}</a>
                </li>
                @endif

                <!-- Página atual -->
                <li class="page-item active"><span class="page-link">{{ $products->currentPage() }}</span></li>

                <!-- Próxima página -->
                @if ($products->currentPage() < $products->lastPage())
                    <li class="page-item"><a class="page-link"
                            href="{{ $products->url($products->currentPage() + 1) }}">{{ $products->currentPage() + 1 }}</a>
                    </li>
                    @endif

                    <!-- Botão para a próxima página -->
                    @if ($products->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}">&raquo;</a></li>
                    @else
                    <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                    @endif

                    <!-- Botão para a última página -->
                    @if ($products->hasMorePages())
                    <li class="page-item"><a class="page-link"
                            href="{{ $products->url($products->lastPage()) }}">&raquo;&raquo;</a></li>
                    @else
                    <li class="page-item disabled"><span class="page-link">&raquo;&raquo;</span></li>
                    @endif
            </ul>
        </nav>
    </div>
</div>
<script>
window.PRODUCTS_INDEX_URL = "{{ route('products.index') }}";
</script>
<script src="{{ asset('js/products.js') }}"></script>
@include('products.delet')
@include('products.upload')
@include('products.create')
@include('products.edit')
@endsection