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

    <!-- Estilos para o card de produto moderno -->
    <style>
        .product-card-modern {
            border: none;
            border-radius: 1.2em;
            box-shadow: 0 2px 12px rgba(13,110,253,0.07);
            overflow: visible;
            position: relative;
            background: #fff;
            transition: box-shadow 0.18s, transform 0.18s;
            min-height: 390px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-card-modern:hover {
            box-shadow: 0 8px 32px rgba(13,110,253,0.13);
            transform: translateY(-2px) scale(1.01);
        }
        .product-card-modern .product-img-area {
            position: relative;
            background: #f8f9fa;
            border-top-left-radius: 1.2em;
            border-top-right-radius: 1.2em;
            overflow: visible;
            min-height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-card-modern .product-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-top-left-radius: 1.2em;
            border-top-right-radius: 1.2em;
            display: block;
        }
        .product-card-modern .badge-product-code {
            position: absolute;
            top: 0.7em;
            left: 0.7em;
            background: #343a40e6;
            color: #fff;
            font-size: 0.98em;
            font-weight: 600;
            padding: 0.32em 0.95em;
            border-radius: 1.2em;
            z-index: 2;
            box-shadow: 0 2px 8px rgba(52,58,64,0.13);
            letter-spacing: 0.03em;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 0.3em;
        }
        .product-card-modern .badge-quantity {
            position: absolute;
            bottom: 0.7em;
            right: 0.7em;
            background: #0dcaf0e6;
            color: #0d6efd;
            font-size: 1em;
            font-weight: 700;
            padding: 0.32em 1.1em;
            border-radius: 1.2em;
            z-index: 2;
            box-shadow: 0 2px 8px rgba(13,202,240,0.13);
            display: flex;
            align-items: center;
            gap: 0.3em;
        }
        .product-card-modern .category-icon-wrapper {
            position: absolute;
            left: 50%;
            bottom: -28px;
            transform: translateX(-50%);
            z-index: 3;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 2px 12px rgba(13,110,253,0.10);
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #f8f9fa;
        }
        .product-card-modern .category-icon {
            font-size: 2.1em;
            color: inherit;
        }
        .product-card-modern .card-body {
            padding: 2.2em 1em 1.1em 1em;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: stretch;
            min-height: 180px;
            position: relative;
        }
        .product-card-modern .product-title {
            font-size: 1.13em;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 0.15em;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            text-align: center;
        }
        .product-card-modern .product-description {
            font-size: 0.98em;
            color: #6c757d;
            margin-bottom: 0.6em;
            display: flex;
            align-items: center;
            gap: 0.4em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            justify-content: center;
        }
        .product-card-modern .price-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.2em;
            margin-bottom: 0.7em;
        }
        .product-card-modern .badge-price {
            background: #f1f3f5;
            color: #495057;
            font-size: 1.04em;
            font-weight: 600;
            border-radius: 1.2em;
            padding: 0.32em 1.1em;
            display: flex;
            align-items: center;
            gap: 0.3em;
            box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        }
        .product-card-modern .badge-price-sale {
            background: #eafbee;
            color: #198754;
            font-size: 1.09em;
            font-weight: 700;
            border-radius: 1.2em;
            padding: 0.32em 1.1em;
            display: flex;
            align-items: center;
            gap: 0.3em;
            box-shadow: 0 1px 4px rgba(25,135,84,0.07);
        }
        .product-card-modern .btn-action-group {
            position: absolute;
            top: 0.7em;
            right: 0.7em;
            z-index: 4;
            display: flex;
            flex-direction: column;
            gap: 0.4em;
        }
        .product-card-modern .btn-action-group .btn {
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            font-size: 1.1em;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            border: none;
        }
        .product-card-modern .btn-action-group .btn-primary {
            background: #0d6efd;
            color: #fff;
        }
        .product-card-modern .btn-action-group .btn-danger {
            background: #dc3545;
            color: #fff;
        }
        .product-card-modern .out-of-stock {
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(220, 53, 69, 0.9);
            color: #fff;
            font-size: 0.98em;
            font-weight: 600;
            padding: 0.32em 0.95em;
            border-top-left-radius: 1.2em;
            border-bottom-right-radius: 1.2em;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 0.3em;
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
            .product-card-modern {
                min-height: 340px;
            }
        }
        @media (max-width: 575px) {
            .col-md-2 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .product-card-modern {
                min-height: 300px;
            }
        }
    </style>

    <!-- Tabela de Produtos -->
    <div id="productsContainer" class="row mt-4">
        {{-- Bloco estilizado para nenhum produto encontrado --}}
        @if($products->isEmpty())
            <div class="col-12">
                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                    <div class="animated-icon mb-4">
                        <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                            <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3" fill="#f8fafc"/>
                            <rect x="35" y="55" width="60" height="40" rx="12" fill="#e9f2ff" stroke="#6ea8fe" stroke-width="3"/>
                            <rect x="50" y="40" width="30" height="25" rx="7" fill="#f8fafc" stroke="#6ea8fe" stroke-width="3"/>
                            <path d="M45 95c0-10 10-18 20-18s20 8 20 18" stroke="#6ea8fe" stroke-width="3" stroke-linecap="round"/>
                            <circle cx="65" cy="75" r="6" fill="#6ea8fe" opacity="0.15"/>
                            <rect x="60" y="65" width="10" height="10" rx="3" fill="#6ea8fe" opacity="0.25"/>
                        </svg>
                    </div>
                    <h2 class="fw-bold mb-3 text-primary" style="font-size:2.5rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                        Nenhum Produto Encontrado
                    </h2>
                    <p class="mb-4 text-secondary text-center" style="max-width: 480px; font-size:1.25rem; font-weight:500; line-height:1.6;">
                        <span style="color:#0d6efd; font-weight:700;">Ops!</span> Sua prateleira está vazia.<br>
                        <span style="color:#6ea8fe;">Cadastre seu primeiro produto</span> e comece a vender agora mesmo!
                    </p>
                
                </div>
            </div>
            <style>
            .animated-icon svg {
                animation: floatIcon 2.5s ease-in-out infinite;
                filter: drop-shadow(0 4px 16px #e3eafc);
            }
            @keyframes floatIcon {
                0%, 100% { transform: translateY(0);}
                50% { transform: translateY(-14px);}
            }
            .stylish-btn, .btn-xl {
                background: linear-gradient(90deg, #6ea8fe 0%, #0d6efd 100%);
                color: #fff;
                border: none;
                border-radius: 2.5em;
                transition: background 0.2s, transform 0.15s;
                box-shadow: 0 4px 24px rgba(13,110,253,0.12);
                font-size: 1.25rem;
                padding: 0.9em 2.5em;
            }
            .stylish-btn:hover, .stylish-btn:focus {
                background: linear-gradient(90deg, #0d6efd 0%, #6ea8fe 100%);
                color: #fff;
                transform: translateY(-2px) scale(1.05);
                box-shadow: 0 8px 32px rgba(13,110,253,0.18);
            }
            </style>
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
    <script>
    function setupDynamicSearch() {
        const searchInput = document.getElementById('search-input');
        if (!searchInput) return;

        let timeout = null;

        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = this.value;
                const selectionStart = this.selectionStart;
                const selectionEnd = this.selectionEnd;

                const url = `{{ route('products.index') }}?search=${encodeURIComponent(query)}`;

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
                            document.querySelector('.container-fluid.py-4').innerHTML =
                                newContent.innerHTML;
                            setupDynamicSearch();
                            // Recupera o novo input e restaura foco/cursor
                            const newSearchInput = document.getElementById('search-input');
                            if (newSearchInput) {
                                newSearchInput.focus();
                                newSearchInput.setSelectionRange(selectionStart,
                                    selectionEnd);
                            }
                        }
                    })
                    .catch(error => console.error('Erro ao buscar dados:', error));
            }, 50); // Debounce mais rápido
        });
    }
    document.addEventListener('DOMContentLoaded', setupDynamicSearch);

 

    </script>
    @include('products.delet')
    @include('products.upload')
    @include('products.create')
    @include('products.edit')
    @endsection
