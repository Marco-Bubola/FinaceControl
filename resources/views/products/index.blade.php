@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        @include('message.alert')

        <!-- Filtro e Pesquisa -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="row w-100">

                <!-- Filtros e Itens por Página -->
                <div class="col-md-3 mb-3">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle w-100" type="button" id="filterMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Filtrar
                        </button>
                        <ul class="dropdown-menu w-100 p-4" aria-labelledby="filterMenuButton">
                            <!-- Filtro por data -->
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('products.index', ['filter' => 'created_at', 'per_page' => request('per_page')]) }}">
                                    Últimos Adicionados
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('products.index', ['filter' => 'updated_at', 'per_page' => request('per_page')]) }}">
                                    Últimos Atualizados
                                </a>
                            </li>

                            <!-- Filtro por nome -->
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('products.index', ['filter' => 'name_asc', 'per_page' => request('per_page')]) }}">
                                    Nome A-Z
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('products.index', ['filter' => 'name_desc', 'per_page' => request('per_page')]) }}">
                                    Nome Z-A
                                </a>
                            </li>

                            <!-- Filtro por preço -->
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('products.index', ['filter' => 'price_asc', 'per_page' => request('per_page')]) }}">
                                    Preço A-Z
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('products.index', ['filter' => 'price_desc', 'per_page' => request('per_page')]) }}">
                                    Preço Z-A
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <!-- Filtro por Categorias -->
                            <li>
                                <div class="dropdown-item">
                                    <label for="categoryFilter" class="form-check-label">Filtrar por Categoria</label>
                                    <div id="categoryFilter" class="category-filter">
                                        @foreach ($categories as $category)
                                            @if ($category->type === 'product')
                                                <!-- Exibe apenas as categorias de tipo "product" -->
                                                <div class="category-option">
                                                    <input type="checkbox" class="form-check-input category-checkbox"
                                                        id="category-{{ $category->id_category }}"
                                                        value="{{ $category->id_category }}">
                                                    <label class="form-check-label" for="category-{{ $category->id_category }}">
                                                        {{ $category->name }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </li>

                            <!-- Filtro por Itens por Página -->
                            <li>
                                <div class="dropdown-item">
                                    <label for="perPageSelect" class="form-check-label">Itens por Página</label>
                                    <select name="per_page" id="perPageSelect" class="form-control w-100">
                                        <option value="12" {{ request('per_page') == '12' ? 'selected' : '' }}>12 itens
                                        </option>
                                        <option value="24" {{ request('per_page') == '24' ? 'selected' : '' }}>24 itens
                                        </option>
                                        <option value="48" {{ request('per_page') == '48' ? 'selected' : '' }}>48 itens
                                        </option>
                                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 itens
                                        </option>
                                    </select>
                                </div>
                            </li>

                            <!-- Botão de Aplicar -->
                            <li>
                                <div class="dropdown-item">
                                    <button id="applyFilterBtn" class="btn btn-primary w-100">Aplicar Filtros</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Pesquisa Dinâmica -->
                <div class="col-md-3 mb-3">
                    <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center w-100">
                        <div class="input-group w-100">
                            <input type="text" name="search" id="search-input" class="form-control"
                                placeholder="Pesquisar por nome ou código" value="{{ request('search') }}">
                            <input type="hidden" name="filter" value="{{ request('filter') }}"> <!-- Preserva o filtro -->
                            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                            <!-- Preserva o per_page -->
                        </div>
                    </form>
                </div>
                <!-- Botões de Adicionar Produto e Upload -->
                <div class="col-md-6 mb-3 text-end">
                    <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal"
                        data-bs-target="#modalAddProduct">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            class="bi bi-plus-square" viewBox="0 0 16 16">
                            <path
                                d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                            <path
                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                        </svg>
                        Produto</a>
                    <!-- index.blade.php ou onde o botão de upload está presente -->
                    <a href="#" class="btn bg-gradient-secondary btn-sm mb-0 ms-2" data-bs-toggle="modal"
                        data-bs-target="#modalUploadProduct">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-file-earmark-arrow-up" viewBox="0 0 16 16">
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
                    <div class="card position-relative h-100">
                        @if($product->stock_quantity == 0)
                            <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1" style="z-index: 10;">
                                Fora de Estoque
                            </div>
                        @endif
                        <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top h-100"
                            alt="{{ $product->name }}">

                        <div class="position-absolute top-0 end-0 p-2">
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm p-1" data-bs-toggle="modal"
                                data-bs-target="#modalEditProduct{{ $product->id }}" title="Editar">
                                <!-- Ícone de editar -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                </svg>
                            </a>
                            <!-- Botão de Exclusão -->
                            <button type="button" class="btn btn-danger btn-sm p-1" data-bs-toggle="modal"
                                data-bs-target="#modalDeleteProduct{{ $product->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <!-- O nome do produto com tooltip -->
                            <h5 class="card-title text-center" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $product->name }}" style="font-size: 1.2rem; font-weight: 600; color: #333;">
                                {{ ucwords($product->name) }}
                            </h5>

                            <!-- Descrição com truncamento -->
                            <p class="card-text text-center text-truncate" style="max-width: 250px; font-size: 0.9rem;">
                                {{ ucwords($product->description) }}
                            </p>

                            <!-- Informações detalhadas do produto -->
                            <div class="row">
                                <div class="col-6 text-center">
                                    <!-- Preço Original e Preço com Desconto -->
                                    <p><strong>Preço:</strong> <br> <span class="text-muted">R$
                                            {{ number_format($product->price, 2, ',', '.') }}</span></p>
                                    <p><strong>Venda:</strong><br> <span class="text-success">R$
                                            {{ number_format($product->price_sale, 2, ',', '.') }}</span></p>
                                    <p><strong>Qtd:</strong><br> <span
                                            class="badge bg-info">{{ $product->stock_quantity }}</span></p>
                                </div>

                                <div class="col-6 text-center">
                                    <!-- Categoria e Código do Produto -->
                                    <p><strong>Categoria:</strong> <br>
                                        <button
                                            class="btn btn-icon-only btn-rounded mb-0 btn-sm d-flex align-items-center justify-content-center mx-auto"
                                            style="background-color: {{ $product->category->hexcolor_category ?? '#000' }}; color: #fff; width: 50px; height: 50px;">
                                            <i class="{{ $product->category->icone ?? 'bi bi-tag' }}"
                                                style="font-size: 1.5rem;"></i>
                                        </button>
                                    </p>
                                    <p><strong>Código:</strong><br> <span
                                            class="badge bg-secondary">{{ $product->product_code }}</span></p>
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
                        document.addEventListener('DOMContentLoaded', function () {
                            // Botão de Aplicar Filtros
                            document.getElementById('applyFilterBtn').addEventListener('click', function () {
                                var selectedCategories = [];

                                // Obter todas as categorias selecionadas
                                document.querySelectorAll('.category-checkbox:checked').forEach(function (checkbox) {
                                    selectedCategories.push(checkbox.value);
                                });

                                // Criar a URL com os filtros aplicados
                                var url = new URL(window.location.href);
                                url.searchParams.set('category_id', selectedCategories.join(','));

                                // Atualizar a URL para aplicar os filtros
                                window.location.href = url.href;
                            });

                            // Filtragem de Itens por Página
                            document.getElementById('perPageSelect').addEventListener('change', function () {
                                var perPage = this.value;
                                var url = new URL(window.location.href);
                                url.searchParams.set('per_page', perPage);
                                window.location.href = url.href;
                            });

                            // Animação ao selecionar/desmarcar categorias
                            document.querySelectorAll('.category-checkbox').forEach(function (checkbox) {
                                checkbox.addEventListener('change', function () {
                                    this.closest('.category-option').classList.toggle('selected', this.checked);
                                });
                            });
                        });


                    </script>
        <script>
            function setupDynamicSearch() {
                const searchInput = document.getElementById('search-input');
                if (!searchInput) return;

                let timeout = null;

                searchInput.addEventListener('input', function () {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        const query = this.value;
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
                                    document.querySelector('.container-fluid.py-4').innerHTML = newContent.innerHTML;
                                    setupDynamicSearch(); // Reaplicar o evento após atualização do DOM
                                }
                            })
                            .catch(error => console.error('Erro ao buscar dados:', error));
                    }, 100); // Adiciona um atraso para evitar requisições excessivas
                });
            }

            document.addEventListener('DOMContentLoaded', setupDynamicSearch);

            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('searchInput');
                const productsContainer = document.getElementById('productsContainer');

                // Função para lidar com erros de resposta
                function handleErrorResponse(response) {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                }

                // Pesquisa dinâmica
                searchInput.addEventListener('input', function () {
                    const query = searchInput.value;
                    fetch(`{{ route('products.index') }}?search=${query}&ajax=1`)
                        .then(handleErrorResponse)
                        .then(data => {
                            if (data.html) {
                                productsContainer.innerHTML = data.html; // Insere apenas o HTML da tabela
                            } else {
                                console.error('Erro: Resposta inválida do servidor.');
                            }
                        })
                        .catch(error => console.error('Erro ao carregar os produtos:', error));
                });

                // Filtros e itens por página
                document.getElementById('filterSelect').addEventListener('change', function () {
                    const filter = this.value;
                    fetch(`{{ route('products.index') }}?filter=${filter}&ajax=1`)
                        .then(handleErrorResponse)
                        .then(data => {
                            if (data.html) {
                                productsContainer.innerHTML = data.html; // Insere apenas o HTML da tabela
                            }
                        });
                });

                document.getElementById('perPageSelect').addEventListener('change', function () {
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
                    link.addEventListener('click', function (event) {
                        event.preventDefault();
                        const url = this.href + '&ajax=1';

                        fetch(url)
                            .then(handleErrorResponse)
                            .then(data => {
                                if (data.html) {
                                    productsContainer.innerHTML = data.html; // Insere apenas o HTML da tabela
                                }
                            })
                            .catch(error => console.error('Erro ao carregar a página:', error));
                    });
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

            document.addEventListener('DOMContentLoaded', function () {
                const filterSelect = document.getElementById('filterSelect');
                const perPageSelect = document.getElementById('perPageSelect');

                // Evento para o filtro
                if (filterSelect) {
                    filterSelect.addEventListener('change', function () {
                        const filter = this.value;
                        const url = new URL(window.location.href);
                        url.searchParams.set('filter', filter); // Atualiza o parâmetro 'filter'
                        window.location.href = url.toString(); // Redireciona para a URL atualizada
                    });
                }

                // Evento para itens por página
                if (perPageSelect) {
                    perPageSelect.addEventListener('change', function () {
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

        <style>
            .pagination .page-item {
                margin: 0 5px;
                /* Adiciona espaçamento horizontal entre os botões */
            }

            .pagination .page-item .page-link {
                min-width: 60px;
                /* Garante que o texto fique dentro do círculo */
                text-align: center;
                /* Garante que o texto fique centralizado */
                border-radius: 50%;
                /* Forma circular */
                padding: 6px 3px;
                /* Ajuste no padding para um visual mais equilibrado */
            }

            .pagination .page-item.active .page-link {
                background-color: #007bff;
                border-color: #007bff;
                color: white;
            }

            .pagination .page-item.disabled .page-link {
                background-color: #e9ecef;
                color: #6c757d;
            }

            /* Garantir que os cards tenham uma altura fixa e o conteúdo se ajuste adequadamente */
            .card {
                height: auto;
                /* Defina uma altura fixa para os cards */
            }

            /* Para a imagem, defina um tamanho fixo e ajuste o conteúdo */
            .card-img-top {
                width: 100%;
                height: 200px;
                /* Altura fixa para as imagens */
                object-fit: cover;
                /* Faz com que a imagem se ajuste ao tamanho sem distorcer */
            }

            /* Truncar o nome e a descrição com "..." e permitir que o nome completo seja mostrado em um tooltip */
            .card-title {
                white-space: nowrap;
                /* Não quebra a linha */
                overflow: hidden;
                /* Esconde o texto que ultrapassa */
                text-overflow: ellipsis;
                /* Adiciona "..." no final */
                max-width: 250px;
                /* Define o tamanho máximo para o nome */
                text-align: center;
                position: relative;
            }

            /* Tooltip para o nome completo do produto */
            .card-title[data-bs-toggle="tooltip"]:hover::after {
                content: attr(data-bs-original-title);
                position: absolute;
                top: 100%;
                left: 0;
                padding: 5px;
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                font-size: 12px;
                border-radius: 3px;
                width: 100%;
                z-index: 10;
            }

            /* Garantir que o conteúdo da descrição também seja truncado corretamente */
            .card-text {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                max-width: 250px;
                /* Ajuste de largura */
            }

            /* Para os campos de preço e quantidade, garantir que eles não ultrapassem o tamanho do card */
            .card-body .row .col-6 {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                max-width: 100%;
                /* Assegura que a largura seja ajustada */
            }

            /* Definir altura fixa para os inputs de preço, venda e quantidade */
            .card-body input.form-control {
                height: 38px;
                /* Altura consistente para os inputs */
                width: 100%;
            }

            /* Definir largura fixa para os botões e garantir que eles ocupem toda a largura do card */
            .card-body button {
                width: 100%;
                padding: 10px;
                font-size: 1em;
            }

            /* Manter uma proporção de layout consistente dentro do card */
            .card-body {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                height: 100%;
                /* Assegura que o card se expanda para preencher o espaço */
            }

            /* Ajuste de altura das colunas para garantir que elas não se sobreponham */
            .col-md-3 {
                height: 100%;
            }

            /* Ajuste do card para que a altura total seja compatível */
            .card-body .row {
                margin-bottom: 10px;
            }


            .category-filter {
                max-height: 250px;
                overflow-y: auto;
                padding: 10px 0;
                margin-bottom: 20px;
                transition: all 0.3s ease;
            }

            .category-option {
                display: flex;
                align-items: center;
                padding: 8px 0;
                transition: background-color 0.2s ease;
            }

            .category-option:hover {
                background-color: #f7f7f7;
                cursor: pointer;
            }

            .category-option input[type="checkbox"] {
                margin-right: 10px;
                transform: scale(1.2);
            }

            #applyFilterBtn {
                font-size: 16px;
                font-weight: bold;
                padding: 10px;
                background-color: #007bff;
                border-color: #007bff;
                transition: background-color 0.3s, transform 0.3s;
            }

            #applyFilterBtn:hover {
                background-color: #0056b3;
                border-color: #0056b3;
                transform: scale(1.05);
            }

            #perPageSelect {
                margin-top: 10px;
                padding: 5px;
                font-size: 14px;
                border-radius: 5px;
                border: 1px solid #ccc;
                transition: border-color 0.3s;
            }

            #perPageSelect:hover {
                border-color: #007bff;
            }

            .dropdown-item {
                padding: 10px;
            }

            #applyFilterBtn {
                margin-top: 10px;
            }
        </style>
@endsection