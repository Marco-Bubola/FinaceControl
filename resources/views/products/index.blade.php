@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid ">
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
                      
                        <div class="dropdown-menu p-3 shadow-lg border-0 filtro-dropdown-panel"
                            style="min-width: 750px; max-width: 950px; border-radius: 16px; background: #f7faff;">
                            <div class="container-fluid">
                                <div class="row g-2 mb-2 align-items-end">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold text-primary mb-1 small"><i class="bi bi-eye me-1 small"></i>Mostrar:</label>
                                        <div class="btn-group w-100" role="group" aria-label="Mostrar por página">
                                            @php
                                                $per_page_options = [18, 30, 48, 96];
                                                $per_page_sel = isset($_GET['per_page']) ? intval($_GET['per_page']) : 18;
                                            @endphp
                                            @foreach ($per_page_options as $opt)
                                            <input type="radio" class="btn-check" name="per_page" id="per_page_{{ $opt }}" value="{{ $opt }}" autocomplete="off" {{ $per_page_sel==$opt?'checked':'' }}>
                                            <label class="btn btn-outline-primary btn-sm px-3 py-1" for="per_page_{{ $opt }}"><i class="bi bi-list-ol"></i> {{ $opt }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold text-primary mb-1 small"><i class="bi bi-sort-alpha-down me-1 small"></i>Ordenar:</label>
                                        <div class="btn-group w-100" role="group" aria-label="Ordenar">
                                            @php
                                                $ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'recentes';
                                                $ordem_opts = [
                                                    'recentes' => ['icon' => 'bi-clock-history', 'label' => 'Recentes'],
                                                    'antigas' => ['icon' => 'bi-clock', 'label' => 'Antigas'],
                                                    'az' => ['icon' => 'bi-sort-alpha-down', 'label' => 'A-Z'],
                                                    'za' => ['icon' => 'bi-sort-alpha-up', 'label' => 'Z-A']
                                                ];
                                            @endphp
                                            @foreach ($ordem_opts as $key => $info)
                                            <input type="radio" class="btn-check" name="ordem" id="ordem_{{ $key }}" value="{{ $key }}" autocomplete="off" {{ $ordem==$key?'checked':'' }}>
                                            <label class="btn btn-outline-primary btn-sm px-3 py-1" for="ordem_{{ $key }}"><i class="bi {{ $info['icon'] }}"></i> {{ $info['label'] }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <p class="text-primary fw-bold mb-2 small"><i class="bi bi-funnel me-1"></i>Informações Gerais</p>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-primary small"><i class="bi bi-box-seam me-1 small"></i>Tipo:</label>
                                            <div class="btn-group w-100" role="group" aria-label="Tipo de Produto">
                                                @php
                                                    $tipo_sel = isset($_GET['tipo']) ? $_GET['tipo'] : '';
                                                    $tipo_opts = ['' => 'Todos', 'simples' => 'Simples', 'kit' => 'Kit'];
                                                @endphp
                                                @foreach ($tipo_opts as $key => $label)
                                                <input type="radio" class="btn-check" name="tipo" id="tipo_{{ $key ?: 'todos' }}" value="{{ $key }}" autocomplete="off" {{ $tipo_sel===$key?'checked':'' }}>
                                                <label class="btn btn-outline-primary btn-sm px-3 py-1" for="tipo_{{ $key ?: 'todos' }}">{{ $label }}</label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-primary small"><i class="bi bi-tags me-1 small"></i>Categoria:</label>
                                            <select name="category" class="form-select select2-categoria form-select-sm">
                                                <option value="">Todas</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id_category }}" {{ request('category')==$cat->id_category?'selected':'' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-primary small"><i class="bi bi-activity me-1 small"></i>Status:</label>
                                            <div class="btn-group w-100" role="group" aria-label="Status">
                                                @php
                                                    $status_filtro = isset($_GET['status_filtro']) ? $_GET['status_filtro'] : '';
                                                    $status_opts = [
                                                        '' => ['icon' => 'bi-ui-checks', 'label' => 'Todos'],
                                                        'ativo' => ['icon' => 'bi-check-circle-fill text-success', 'label' => 'Ativo'],
                                                        'inativo' => ['icon' => 'bi-pause-circle text-warning', 'label' => 'Inativo'],
                                                        'descontinuado' => ['icon' => 'bi-x-circle-fill text-danger', 'label' => 'Descontinuado'],
                                                    ];
                                                @endphp
                                                @foreach ($status_opts as $key => $info)
                                                <input type="radio" class="btn-check" name="status_filtro"
                                                    id="status_{{ $key ?: 'todos' }}" value="{{ $key }}"
                                                    autocomplete="off" {{ $status_filtro===$key?'checked':'' }}>
                                                <label class="btn btn-outline-primary btn-sm px-3 py-1" for="status_{{ $key ?: 'todos' }}">
                                                    <i class="bi {{ $info['icon'] }}"></i> {{ $info['label'] }}
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-primary small"><i class="bi bi-currency-exchange me-1 small"></i>Preço:</label>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <input type="number" step="0.01" min="0" name="preco_min" class="form-control form-control-sm" placeholder="Mínimo" value="{{ request('preco_min') }}">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" step="0.01" min="0" name="preco_max" class="form-control form-control-sm" placeholder="Máximo" value="{{ request('preco_max') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-primary fw-bold mb-2 small"><i class="bi bi-stack me-1"></i>Estoque e Datas</p>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-primary small"><i class="bi bi-stack me-1 small"></i>Estoque:</label>
                                            <div class="row g-2 align-items-center">
                                                @php
                                                    $estoque_sel = isset($_GET['estoque']) ? $_GET['estoque'] : '';
                                                    $estoque_valor = isset($_GET['estoque_valor']) ? $_GET['estoque_valor'] : '';
                                                @endphp
                                                <div class="col-6">
                                                    <select name="estoque" class="form-select form-select-sm">
                                                        <option value="" {{ $estoque_sel==''?'selected':'' }}>Todos</option>
                                                        <option value="zerado" {{ $estoque_sel=='zerado'?'selected':'' }}>Zerados</option>
                                                        <option value="abaixo" {{ $estoque_sel=='abaixo'?'selected':'' }}>Abaixo de</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" min="1" name="estoque_valor" class="form-control form-control-sm" placeholder="Qtd" value="{{ $estoque_valor }}" @if($estoque_sel!='abaixo') disabled @endif>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-primary small"><i class="bi bi-calendar-range me-1 small"></i>Data de Criação:</label>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <input type="date" name="data_inicio" class="form-control form-control-sm" value="{{ request('data_inicio') }}">
                                                </div>
                                                <div class="col-6">
                                                    <input type="date" name="data_fim" class="form-control form-control-sm" value="{{ request('data_fim') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row">
                                    <div class="col-12 d-flex gap-2">
                                        <button type="submit" class="btn btn-gradient-primary flex-fill fw-bold btn-sm py-2"
                                            style="border-radius: 8px; background: linear-gradient(90deg,#0d6efd 60%,#4f8cff 100%); color: #fff; border: none;">
                                            <i class="bi bi-funnel"></i> Salvar Filtros
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary flex-fill fw-bold btn-sm py-2" onclick="limparFiltrosProdutos()">
                                            <i class="bi bi-x-circle"></i> Limpar Filtros
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <input type="hidden" name="ordem" value="{{ request('ordem') }}">
                        <input type="hidden" name="status_filtro" value="{{ request('status_filtro') }}">
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    </div>
                </form>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center mb-0 gap-3">
                <button type="button" class="btn btn-primary btn-md d-flex align-items-center gap-2 px-4 py-3 shadow rounded-pill fs-7" data-bs-toggle="modal" data-bs-target="#modalCriarKit">
                    <i class="bi bi-boxes fs-3"></i>
                    Novo Kit
                </button>
                <button type="button" class="btn btn-success btn-md d-flex align-items-center gap-2 px-4 py-3 shadow rounded-pill fs-7" data-bs-toggle="modal" data-bs-target="#modalAddProduct">
                    <i class="bi bi-plus-square fs-3"></i>
                    Novo Produto
                </button>
                    <button type="button" class="btn btn-secondary btn-md d-flex align-items-center gap-2 px-4 py-3 shadow rounded-pill fs-7" data-bs-toggle="modal" data-bs-target="#modalUploadProduct">
                    <i class="bi bi-file-earmark-arrow-up fs-3"></i>
                    Upload
                </button>
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
        @if($product->tipo === 'kit')
        <div class="col-md-4 mb-4">
            <div class="card border-3 border-primary shadow-lg h-100 position-relative rounded-4 bg-white">
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-gradient-primary px-3 py-2 fs-6"><i class="bi bi-boxes me-1"></i>KIT</span>
                </div>
                <div class="d-flex flex-column align-items-center p-3">
                    <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('storage/products/product-placeholder.png') }}"
                        alt="{{ $product->name }}" class="img-fluid rounded-3 shadow mb-2"
                        style="max-height: 120px; object-fit: contain; background: #f8fafc;">
                    <h5 class="card-title text-primary fw-bold mt-2 mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-boxes"></i> {{ $product->name }}
                    </h5>
                    <div class="text-muted small mb-2">#{{ $product->product_code }}</div>
                    <div class="mb-1"><span class="fw-semibold text-success"><i class="bi bi-currency-dollar"></i>
                            {{ number_format($product->price, 2, ',', '.') }}</span></div>
                    <div class="mb-2"><span class="fw-semibold text-warning"><i class="bi bi-cash-coin"></i>
                            {{ number_format($product->price_sale, 2, ',', '.') }}</span></div>
                    <div class="mb-2 w-100">
                        <span class="fw-bold text-secondary"><i class="bi bi-list-ul"></i> Componentes:</span>
                        <ul class="list-unstyled mb-0 ps-2 small">
                            @php $max = 3; $count = 0; @endphp
                            @foreach($product->componentes as $comp)
                            @if($count < $max) <li><i class="bi bi-dot"></i> {{ $comp->componente->name }} <span
                                    class="text-muted">(x{{ $comp->quantidade }})</span></li>
                                @endif
                                @php $count++; @endphp
                                @endforeach
                                @if($product->componentes->count() > $max)
                                <li class="text-muted">+{{ $product->componentes->count() - $max }} outros...</li>
                                @endif
                        </ul>
                    </div>
                    <button
                        class="btn btn-outline-primary btn-sm rounded-pill px-3 d-flex align-items-center gap-1 mt-2"
                        data-bs-toggle="modal" data-bs-target="#modalEditKit{{ $product->id }}">
                        <i class="bi bi-pencil-square"></i> Editar Kit
                    </button>
                </div>
            </div>
        </div>
        @include('products.editkit', ['kit' => $product])
        @else
        <div class="col-md-2 mb-4">
            <div class="product-card-modern position-relative d-flex flex-column ">
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
                    @if($product->stock_quantity == 0)
                    <div class="out-of-stock">
                        <i class="bi bi-x-circle"></i> Fora de Estoque
                    </div>
                    @endif
                    <!-- Código do produto -->
                    <span class="badge-product-code" title="Código do Produto">
                        <i class="bi bi-upc-scan"></i> {{ $product->product_code }}
                    </span>
                    <!-- Quantidade -->
                    <span class="badge-quantity" title="Quantidade em Estoque">
                        <i class="bi bi-stack"></i> {{ $product->stock_quantity }}
                    </span>
                    <!-- Ícone da categoria -->
                    <div class="category-icon-wrapper">
                        <i class="{{ $product->category->icone }} category-icon"></i>
                    </div>
                </div>
                <!-- Conteúdo -->
                <div class="card-body">

                    <div class="product-title" title="{{ $product->name }}">
                        {{ ucwords($product->name) }}
                    </div>
                 
                    
                </div>
                <span class="badge-price" title="Preço de Custo">
                    <i class="bi bi-tag"></i>
                    {{ number_format($product->price, 2, ',', '.') }}
                </span>
                <span class="badge-price-sale" title="Preço de Venda">
                    <i class="bi bi-currency-dollar"></i>
                    {{ number_format($product->price_sale, 2, ',', '.') }}
                </span>
            </div>
        </div>
        @include('products.edit', ['product' => $product, 'categories' => $categories])
        @endif
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

// Habilita/desabilita o input de quantidade conforme o checkbox
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.checkbox-produto-kit').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const input = this.closest('tr').querySelector('.input-quantidade-produto-kit');
            input.disabled = !this.checked;
        });
    });
});

// Habilita/desabilita o input de estoque conforme select
    document.addEventListener('DOMContentLoaded', function() {
        const estoqueSelect = document.querySelector('select[name="estoque"]');
        const estoqueValorInput = document.querySelector('input[name="estoque_valor"]');
        if(estoqueSelect && estoqueValorInput) {
            estoqueSelect.addEventListener('change', function() {
                if(this.value === 'abaixo') {
                    estoqueValorInput.disabled = false;
                } else {
                    estoqueValorInput.disabled = true;
                    estoqueValorInput.value = '';
                }
            });
        }
        // Ativar select2 se disponível
        if(window.jQuery && $('.select2-categoria').length) {
            $('.select2-categoria').select2({
                width: '100%',
                placeholder: 'Selecione uma categoria',
                allowClear: true
            });
        }
    });

function limparFiltrosProdutos() {
    const form = document.getElementById('productsFiltersForm');
    // Limpa todos os campos do formulário
    form.reset();
    // Remove os parâmetros da URL (opcional, se quiser recarregar sem filtros)
    window.location = window.PRODUCTS_INDEX_URL;
}
</script>
<script src="{{ asset('js/products.js') }}"></script>
@include('products.delet')
@include('products.upload')
@include('products.create')
@include('products.edit')
@include('products.createkit')
@endsection

<style>
.filtro-dropdown-panel {
    box-shadow: 0 8px 32px 0 rgba(13,110,253,0.10), 0 1.5px 4px 0 rgba(0,0,0,0.04);
    border-radius: 16px;
    background: #f7faff;
    min-width: 750px;
    max-width: 950px;
    padding: 1.5rem 1.5rem 1rem 1.5rem !important;
}
.filtro-dropdown-panel label.form-label {
    font-size: 0.98rem;
    margin-bottom: 0.2rem;
}
.filtro-dropdown-panel p {
    font-size: 1.02rem;
    margin-bottom: 0.7rem;
    letter-spacing: 0.01em;
}
.filtro-dropdown-panel .btn-group .btn {
    font-size: 0.98rem;
    padding: 0.35rem 0.9rem;
}
.filtro-dropdown-panel .form-control, .filtro-dropdown-panel .form-select {
    border-radius: 8px;
    font-size: 0.98rem;
    padding: 0.35rem 0.7rem;
}
.filtro-dropdown-panel .btn {
    border-radius: 8px;
}
@media (max-width: 950px) {
    .filtro-dropdown-panel {
        min-width: 100% !important;
        max-width: 100% !important;
    }
}
@media (max-width: 700px) {
    .filtro-dropdown-panel .row.g-3 > .col-md-6,
    .filtro-dropdown-panel .row.g-2 > .col-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>