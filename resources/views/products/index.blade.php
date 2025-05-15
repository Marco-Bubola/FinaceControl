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
        @foreach($products as $product)
        <div class="col-md-2 mb-4">
            <div
                class="card h-100 position-relative border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column card-hover">
                @if($product->stock_quantity == 0)
                <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 rounded-end z-1">
                    Fora de Estoque
                </div>
                @endif

                <!-- Botões flutuantes -->
                <div class="position-absolute top-0 end-0 p-2">
                    <a href="javascript:void(0)" class="btn btn-primary  p-1" data-bs-toggle="modal"
                        data-bs-target="#modalEditProduct{{ $product->id }}" title="Editar">
                        <!-- Ícone de editar -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        </svg>
                    </a>
                    <!-- Botão de Exclusão -->
                    <button type="button" class="btn btn-danger p-1" data-bs-toggle="modal"
                        data-bs-target="#modalDeleteProduct{{ $product->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-trash3" viewBox="0 0 16 16">
                            <path
                                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                        </svg>
                    </button>
                </div>


                <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top object-fit-cover"
                    alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">

                <!-- Conteúdo -->
                <div class="card-body d-flex flex-column justify-content-between text-center p-2">
                    <div>
                        <h6 class="card-title mb-1 text-truncate" title="{{ $product->name }}"
                            style="font-size: 1rem; font-weight: 600;">
                            {{ ucwords($product->name) }}
                        </h6>
                        <p class="text-muted text-truncate small" title="{{ $product->description }}">
                            {{ ucwords($product->description) }}
                        </p>
                        <div class="my-2">
                            <span class="badge bg-light text-muted d-block mb-1">
                                PREÇO: R$ {{ number_format($product->price, 2, ',', '.') }}
                            </span>
                            <span class=" badge  text-success d-block">
                                VENDA: R$ {{ number_format($product->price_sale, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <div class="d-flex justify-content-between px-2">
                            <div>
                                <small class="text-muted">Qtd</small><br>
                                <span class="badge bg-info">{{ $product->stock_quantity }}</span>
                            </div>
                            <div>
                                <small class="text-muted">Código</small><br>
                                <span class="badge bg-secondary">{{ $product->product_code }}</span>
                            </div>
                            <button class="btn rounded-circle d-flex align-items-center justify-content-center" style="border: 2px solid {{ $product->category->hexcolor_category }};
                                   background-color: {{ $product->category->hexcolor_category }}20;
                                   width: 45px; height: 45px;" title="{{ $product->category->name }}"
                                data-bs-toggle="tooltip">
                                <i class="{{ $product->category->icone }}"
                                    style="font-size: 1.2rem; color: {{ $product->category->hexcolor_category }};"></i>
                            </button>
                        </div>


                    </div>
                </div>
            </div>
        </div>


        @endforeach
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
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    document.addEventListener('DOMContentLoaded', function() {
        // Botão de Aplicar Filtros
        document.getElementById('applyFilterBtn').addEventListener('click', function() {
            var selectedCategories = [];

            // Obter todas as categorias selecionadas
            document.querySelectorAll('.category-checkbox:checked').forEach(function(checkbox) {
                selectedCategories.push(checkbox.value);
            });

            // Criar a URL com os filtros aplicados
            var url = new URL(window.location.href);
            url.searchParams.set('category_id', selectedCategories.join(','));

            // Atualizar a URL para aplicar os filtros
            window.location.href = url.href;
        });

        // Filtragem de Itens por Página
        document.getElementById('perPageSelect').addEventListener('change', function() {
            var perPage = this.value;
            var url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.href;
        });

        // Animação ao selecionar/desmarcar categorias
        document.querySelectorAll('.category-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                this.closest('.category-option').classList.toggle('selected', this.checked);
            });
        });
    });

    // Filtros e itens por página
    document.getElementById('filterSelect').addEventListener('change', function() {
        const filter = this.value;
        fetch(`{{ route('products.index') }}?filter=${filter}&ajax=1`)
            .then(handleErrorResponse)
            .then(data => {
                if (data.html) {
                    productsContainer.innerHTML = data.html; // Insere apenas o HTML da tabela
                }
            });
    });

    document.getElementById('perPageSelect').addEventListener('change', function() {
        const perPage = this.value;
        fetch(`{{ route('products.index') }}?per_page=${perPage}&ajax=1`)
            .then(handleErrorResponse)
            .then(data => {
                if (data.html) {
                    productsContainer.innerHTML = data.html; // Insere apenas o HTML da tabela
                }
            });
    });

    // Paginação dinâmica
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
    link.addEventListener('click', function(event) {
        event.preventDefault();
        const url = this.href + '&ajax=1';

        fetch(url)
            .then(handleErrorResponse)
            .then(data => {
                if (data.html) {
                    productsContainer.innerHTML = data
                        .html; // Insere apenas o HTML da tabela
                }
            })
            .catch(error => console.error('Erro ao carregar a página:', error));
    });
    });


    // Função para excluir produto dinamicamente
    function deleteProduct(productId) {
        if (confirm('Tem certeza que deseja excluir este produto?')) {
            fetch(`{{ url('products') }}/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`product-card-${productId}`).remove();
                    } else {
                        alert('Erro ao excluir o produto.');
                    }
                })
                .catch(error => console.error('Erro:', error));
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const filterSelect = document.getElementById('filterSelect');
        const perPageSelect = document.getElementById('perPageSelect');

        // Evento para o filtro
        if (filterSelect) {
            filterSelect.addEventListener('change', function() {
                const filter = this.value;
                const url = new URL(window.location.href);
                url.searchParams.set('filter', filter); // Atualiza o parâmetro 'filter'
                window.location.href = url.toString(); // Redireciona para a URL atualizada
            });
        }

        // Evento para itens por página
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                const perPage = this.value;
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', perPage); // Atualiza o parâmetro 'per_page'
                window.location.href = url.toString(); // Redireciona para a URL atualizada
            });
        }
    });
    </script>
    @include('products.delet')
    @include('products.upload')
    @include('products.create')
    @include('products.edit')
    @endsection