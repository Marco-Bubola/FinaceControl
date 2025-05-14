@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    @include('message.alert')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- Filtros e Pesquisa -->
        <div class="row w-100">


            {{-- Troque id="customDropdown" por class="dropdown-filtros" em todos os dropdowns de filtro --}}
            <div class="col-md-3 mb-3">
                <form action="{{ route('sales.index') }}" method="GET" class="w-100" id="filtersForm">
                    <div class="dropdown w-100 dropdown-filtros" data-bs-auto-close="outside">
                        <button
                            class="btn btn-gradient-primary w-100 dropdown-toggle rounded-pill shadow d-flex justify-content-between align-items-center px-4 py-2"
                            type="button" id="dropdownFilter" data-bs-toggle="dropdown" aria-expanded="false"
                            style="font-weight:600;">
                            <span>
                                <i class="bi bi-funnel-fill me-2"></i> Filtros
                            </span>
                            @if(request('filter') || request('per_page') || request('status') || request('date_start')
                            || request('date_end') || request('client_id') || request('min_value') ||
                            request('max_value') || request('payment_type'))
                            <span class="badge bg-light text-primary ms-2">Ativo</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-animate w-100 rounded-4 p-3 border-0 shadow-lg"
                            aria-labelledby="dropdownFilter" style="min-width: 320px;">
                            <!-- ... (restante dos filtros permanece igual) ... -->
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
                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-list-ol text-success me-2"></i>
                                        <h6 class="mb-0 text-success" style="font-size:1rem;">Qtd. Itens</h6>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php $perPages = [9, 25, 50, 100]; @endphp
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
                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-flag-fill text-warning me-2"></i>
                                        <h6 class="mb-0 text-warning" style="font-size:1rem;">Status</h6>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php
                                        $statuses = [
                                        '' => 'Todos',
                                        'pago' => 'Pago',
                                        'pendente' => 'Pendente',
                                        'cancelado' => 'Cancelado',
                                        ];
                                        @endphp
                                        @foreach($statuses as $key => $label)
                                        <div class="form-check form-check-inline form-check-custom">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="status_{{ $key ?: 'all' }}" value="{{ $key }}"
                                                {{ request('status', '') === $key ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="status_{{ $key ?: 'all' }}"
                                                data-bs-toggle="tooltip" title="{{ $label }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-range text-info me-2"></i>
                                        <h6 class="mb-0 text-info" style="font-size:1rem;">Período</h6>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" class="form-control form-control-sm" name="date_start"
                                                value="{{ request('date_start') }}" placeholder="De">
                                        </div>
                                        <div class="col-6">
                                            <input type="date" class="form-control form-control-sm" name="date_end"
                                                value="{{ request('date_end') }}" placeholder="Até">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Filtro por Valor -->
                            <li>
                                <div class="filter-section mb-3" tabindex="0">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-cash-coin text-success me-2"></i>
                                        <h6 class="mb-0 text-success" style="font-size:1rem;">Valor</h6>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" step="0.01" class="form-control form-control-sm"
                                                name="min_value" value="{{ request('min_value') }}" placeholder="Mín.">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" step="0.01" class="form-control form-control-sm"
                                                name="max_value" value="{{ request('max_value') }}" placeholder="Máx.">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Botões -->
                            <li>
                                <div class="d-flex gap-2 mt-2">
                                    <button type="submit" class="btn btn-gradient-success rounded-pill px-3 flex-fill">
                                        <i class="bi bi-check2-circle"></i> Aplicar
                                    </button>
                                    @if(request('filter') || request('per_page') || request('status') ||
                                    request('date_start') || request('date_end') || request('client_id') ||
                                    request('min_value') || request('max_value') || request('payment_type'))
                                    <a href="{{ route('sales.index') }}"
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

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Para cada dropdown de filtro na página
                document.querySelectorAll('.dropdown-filtros').forEach(function(dropdownEl) {
                    // Impede o fechamento ao clicar em qualquer parte interna do dropdown, exceto nos botões
                    dropdownEl.querySelector('.dropdown-menu').addEventListener('mousedown', function(
                    e) {
                        if (
                            e.target.closest('button[type="submit"]') ||
                            e.target.closest('a.btn-outline-secondary')
                        ) {
                            // Permite o clique normal
                        } else {
                            e.stopPropagation();
                        }
                    });

                    // Função para fechar o dropdown deste filtro
                    function closeDropdown() {
                        var dropdownToggle = dropdownEl.querySelector('.dropdown-toggle');
                        var dropdown = bootstrap.Dropdown.getOrCreateInstance(dropdownToggle);
                        dropdown.hide();
                    }

                    // Ao clicar em "Aplicar"
                    var submitBtn = dropdownEl.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.addEventListener('click', function() {
                            setTimeout(closeDropdown, 100); // Fecha após submit
                        });
                    }

                    // Ao clicar em "Limpar"
                    var clearBtn = dropdownEl.querySelector('a.btn-outline-secondary');
                    if (clearBtn) {
                        clearBtn.addEventListener('click', function() {
                            closeDropdown();
                        });
                    }
                });

                // Ativa tooltips do Bootstrap (caso use tooltips nos filtros)
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl)
                });
            });
            </script>

            <style>
            .btn-gradient-primary {
                background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
                border: none;
                color: #fff;
                font-weight: 600;
                transition: background 0.2s, box-shadow 0.2s;
            }

            .btn-gradient-primary:hover,
            .btn-gradient-success:hover {
                background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
                color: #fff;
                box-shadow: 0 2px 12px rgba(67, 233, 123, 0.15);
            }

            .btn-gradient-success {
                background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
                border: none;
                color: #fff;
                font-weight: 600;
                transition: background 0.2s, box-shadow 0.2s;
            }
            

            .filter-section {
                background: #f8f9fa;
                border-radius: 0.75rem;
                padding: 0.75rem 1rem;
                box-shadow: 0 1px 4px rgba(56, 249, 215, 0.04);
                margin-bottom: 0.5rem;
                transition: box-shadow 0.2s;
            }

            .filter-section:focus-within,
            .filter-section:focus {
                box-shadow: 0 0 0 2px #38f9d7;
                outline: none;
            }

            .form-check-custom .form-check-input:checked {
                background-color: #38f9d7;
                border-color: #38f9d7;
                box-shadow: 0 0 0 0.15rem rgba(56, 249, 215, .25);
            }

            .form-check-custom .form-check-input {
                cursor: pointer;
                border-radius: 50%;
                width: 1.1em;
                height: 1.1em;
                margin-top: 0.15em;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .form-check-custom .form-check-label {
                cursor: pointer;
                font-weight: 500;
                margin-left: 0.3em;
                color: #333;
                transition: color 0.2s;
            }

            .form-check-custom .form-check-input:focus {
                box-shadow: 0 0 0 0.15rem rgba(56, 249, 215, .25);
            }

            .form-check-inline {
                margin-right: 0.5rem;
            }

            .dropdown-menu .form-check-custom:hover {
                background: #e9f7f7;
                border-radius: 0.5rem;
            }

            .dropdown-menu {
                border: none;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            }

            .dropdown-header {
                font-size: 1rem;
                font-weight: 600;
                letter-spacing: 0.02em;
            }

            .btn-outline-secondary {
                border: 1.5px solid #adb5bd;
                color: #495057;
                background: #fff;
                font-weight: 600;
                transition: background 0.2s, color 0.2s;
            }

            .btn-outline-secondary:hover {
                background: #f8f9fa;
                color: #0d6efd;
            }

            @media (max-width: 600px) {
                .dropdown-menu {
                    min-width: 95vw !important;
                }

                .filter-section {
                    padding: 0.5rem 0.5rem;
                }
            }
            </style>


           
<div class="col-md-4 mb-3">
    <form action="{{ route('sales.index') }}" method="GET" class="d-flex align-items-center w-100">
        <div class="input-group search-bar-sales w-100">
            <span class="input-group-text search-bar-sales-icon" id="search-addon">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" name="search" id="search-input" class="form-control search-bar-sales-input"
                placeholder="Pesquisar por cliente" value="{{ request('search') }}" aria-label="Pesquisar por cliente" aria-describedby="search-addon">
        </div>
    </form>
</div>

<style>
/* Estilo exclusivo para o campo de pesquisa de vendas */
.search-bar-sales {
    border-radius: 2rem;
    box-shadow: 0 2px 8px rgba(56, 249, 215, 0.08);
    background: #fff;
    transition: box-shadow 0.2s;
}
.search-bar-sales:focus-within {
    box-shadow: 0 0 0 3px #38f9d7;
}
.search-bar-sales-icon {
    background: transparent;
    border: none;
    color: #38f9d7;
    font-size: 1.3rem;
    border-radius: 2rem 0 0 2rem;
    padding-left: 1rem;
}
.search-bar-sales-input {
    border: none;
    border-radius: 0 2rem 2rem 0;
    background: transparent;
    font-size: 1.1rem;
    padding-left: 0.5rem;
    box-shadow: none;
    transition: background 0.2s;
}
.search-bar-sales-input:focus {
    background: #f8f9fa;
    outline: none;
    box-shadow: none;
}
@media (max-width: 600px) {
    .search-bar-sales-input {
        font-size: 1rem;
    }
    .search-bar-sales-icon {
        font-size: 1.1rem;
        padding-left: 0.5rem;
    }
}
</style>

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
                        setupDynamicSearch();
                        // Recupera o novo input e restaura foco/cursor
                        const newSearchInput = document.getElementById('search-input');
                        if (newSearchInput) {
                            newSearchInput.focus();
                            newSearchInput.setSelectionRange(selectionStart, selectionEnd);
                        }
                    }
                })
                .catch(error => console.error('Erro ao buscar dados:', error));
        }, 100); // Debounce mais rápido
    });
}
document.addEventListener('DOMContentLoaded', setupDynamicSearch);


</script>

            <div class="col-md-5 mb-3 d-flex justify-content-end align-items-center">
                <a href="#" class="btn bg-gradient-primary mb-0 d-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#modalAddSale">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        class="bi bi-plus-square me-1" viewBox="0 0 16 16">
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
    <div id="sales-list">
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
    <style>
        /* CSS restrito ao dropdown de ações */
        .dropdown-acoes-unico .dropdown {
            min-width: 180px;
        }
        .dropdown-acoes-unico .btn-light.dropdown-toggle {
             background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
                border: none;
                color: #fff;
                font-weight: 600;
                transition: background 0.2s, box-shadow 0.2s;
        }
        .dropdown-acoes-unico .btn-light.dropdown-toggle:focus,
        .dropdown-acoes-unico .btn-light.dropdown-toggle:hover {
           background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
                color: #fff;
                box-shadow: 0 2px 12px rgba(67, 233, 123, 0.15);
        }
        .dropdown-acoes-unico .dropdown-menu {
            padding: 0.25rem 0;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            font-size: 1rem;
        }
        .dropdown-acoes-unico .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background 0.15s;
            font-weight: 400;
        }
        .dropdown-acoes-unico .dropdown-item:focus,
        .dropdown-acoes-unico .dropdown-item:hover {
            background: #f1f3f4;
            color: #222;
        }
        .dropdown-acoes-unico .dropdown-item a {
            text-decoration: none;
            color: inherit;
        }
        @media (max-width: 576px) {
            .dropdown-acoes-unico .dropdown,
            .dropdown-acoes-unico .dropdown-menu,
            .dropdown-acoes-unico .btn-light.dropdown-toggle {
                width: 100%;
                min-width: unset;
            }
        }
    </style>
    <div class="dropdown-acoes-unico">
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle w-100"
                type="button" id="dropdownMenuButton{{ $sale->id }}" data-bs-toggle="dropdown"
                aria-expanded="false">
                Ações
                <i class="bi bi-three-dots-vertical ms-2"></i>
            </button>
            <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton{{ $sale->id }}">
                <li>
                    <button class="dropdown-item" data-bs-toggle="modal"
                        data-bs-target="#paymentHistoryModal{{ $sale->id }}"
                        title="Histórico de pagamento">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        Histórico de Pagamento
                    </button>
                </li>
                <li>
                    <a href="{{ route('sales.export', $sale->id) }}" class="dropdown-item"
                        title="Exportar PDF">
                        <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
                        Exportar PDF
                    </a>
                </li>
                <li>
                    <button class="dropdown-item" data-bs-toggle="modal"
                        data-bs-target="#paymentModal{{ $sale->id }}" title="Adicionar Pagamento">
                        <i class="bi bi-plus-square me-2 text-success"></i>
                        Adicionar Pagamento
                    </button>
                </li>
                <li>
                    <a href="{{ route('sales.show', $sale->id) }}" class="dropdown-item"
                        title="Ver Detalhes">
                        <i class="bi bi-eye me-2 text-info"></i>
                        Ver Detalhes
                    </a>
                </li>
                <li>
                    <button class="dropdown-item" data-bs-toggle="modal"
                        data-bs-target="#modalEditSale{{ $sale->id }}" title="Editar Venda">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>
                        Editar Venda
                    </button>
                </li>
                <li>
                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item"
                            data-bs-toggle="modal" data-bs-target="#modalDeleteSale{{ $sale->id }}"
                            title="Excluir Venda">
                            <i class="bi bi-trash3 me-2 text-danger"></i>
                            Excluir Venda
                        </button>
                    </form>
                </li>
                <li>
                    <button class="dropdown-item" data-bs-toggle="modal"
                        data-bs-target="#modalAddProductToSale{{ $sale->id }}"
                        title="Adicionar Produto à Venda">
                        <i class="bi bi-plus-square me-2 text-success"></i>
                        Adicionar Produto
                    </button>
                </li>
            </ul>
        </div>
    </div>
</div>

                    </div>
                </div>
                
<div class="card-body sale-products-section">
    <!-- Produtos da venda -->
    <div class="row" id="sale-products-{{ $sale->id }}">
        @foreach($sale->saleItems as $index => $item)
        <div class="col-md-3 sale-product {{ $index >= 4 ? 'd-none extra-product' : '' }}">
            <div class="card sale-product-card h-100 shadow-sm border-0">
                <div class="sale-product-img-wrapper">
                    <img src="{{ asset('storage/products/' . $item->product->image) }}" class="card-img-top sale-product-img"
                        alt="{{ $item->product->name }}">
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <!-- Nome do produto com truncamento e tooltip -->
                    <h6 class="card-title text-center sale-product-title"
                        title="{{ $item->product->name }}">
                        {{ $item->product->name }}
                    </h6>
                    <!-- Preço -->
                    <p class="card-text text-center sale-product-price">
                        <span class="badge bg-light text-dark fw-normal">Preço</span>
                        <span class="fw-bold">R$ {{ number_format($item->product->price, 2, ',', '.') }}</span>
                    </p>
                    <!-- Preço de venda -->
                    <p class="card-text text-center sale-product-saleprice">
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-normal">Venda</span>
                        <span class="fw-bold ">R$ {{ number_format($item->price_sale, 2, ',', '.') }}</span>
                    </p>
                    <!-- Quantidade -->
                    <p class="card-text text-center sale-product-qty">
                        <span class="badge bg-success bg-opacity-10 text-success fw-normal">Qtd</span>
                        <span class="fw-bold">{{ $item->quantity }}</span>
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($sale->saleItems->count() > 4)
    <div class="text-center mt-3">
        <button class="btn btn-outline-primary sale-products-expand"
            id="expandProducts-{{ $sale->id }}">+{{ $sale->saleItems->count() - 4 }} mais</button>
        <button class="btn btn-outline-secondary sale-products-collapse d-none" id="collapseProducts-{{ $sale->id }}">Mostrar menos</button>
    </div>
    @endif

    <div class="row mt-4">
        <div class="col-md-12">
            <!-- Card único para informações adicionais -->
            <div class="card sale-totals-card p-4 shadow-lg rounded-4 border-0">
                <div class="row d-flex justify-content-between align-items-center">
                    <!-- Coluna com Total -->
                    <div class="col-md-4 d-flex flex-column align-items-start">
                        <h6 class="sale-total-label mb-2">
                            <i class="bi bi-cash-coin me-1 text-primary"></i>
                            <span>Total:</span>
                        </h6>
                        <span class="sale-total-value">
                            R$ {{ number_format($sale->total_price, 2, ',', '.') }}
                        </span>
                    </div>
                    <!-- Coluna com Total Pago -->
                    <div class="col-md-4 d-flex flex-column align-items-center">
                        <h6 class="sale-total-label mb-2">
                            <i class="bi bi-wallet2 me-1 text-success"></i>
                            <span>Total Pago:</span>
                        </h6>
                        <span class="sale-total-paid">
                            R$ {{ number_format($sale->total_paid, 2, ',', '.') }}
                        </span>
                    </div>
                    <!-- Coluna com Saldo Restante -->
                    <div class="col-md-4 d-flex flex-column align-items-end">
                        <h6 class="sale-total-label mb-2">
                            <i class="bi bi-exclamation-circle me-1 text-danger"></i>
                            <span>Saldo Restante:</span>
                        </h6>
                        <span class="badge sale-total-badge">
                            R$ {{ number_format($sale->total_price - $sale->total_paid, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('sales.paymentHistory')
</div>

<style>
/* Escopo exclusivo para a seção de produtos da venda */
.sale-products-section {
    background: #fafdff;
    border-radius: 1.5rem;
    padding-bottom: 2rem;
}
.sale-products-section .sale-product-card {
    border-radius: 1.2rem;
    transition: box-shadow 0.18s, transform 0.18s;
    background: linear-gradient(135deg, #f8f9fa 80%, #e6fff7 100%);
    min-height: 370px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.sale-products-section .sale-product-card:hover {
    box-shadow: 0 6px 24px rgba(56,249,215,0.13);
    transform: translateY(-4px) scale(1.03);
}
.sale-products-section .sale-product-img-wrapper {
    background: #fff;
    border-radius: 1.2rem 1.2rem 0 0;
    overflow: hidden;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.sale-products-section .sale-product-img {
    max-height: 170px;
    width: auto;
    object-fit: contain;
    transition: transform 0.2s;
}
.sale-products-section .sale-product-card:hover .sale-product-img {
    transform: scale(1.07) rotate(-2deg);
}
.sale-products-section .sale-product-title {
    font-weight: 600;
    font-size: 1.05rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 0.5rem;
    color: #222;
}
.sale-products-section .sale-product-price,
.sale-products-section .sale-product-saleprice,
.sale-products-section .sale-product-qty {
    margin-bottom: 0.3rem;
    font-size: 1rem;
}
.sale-products-section .sale-product-saleprice {
    color: #007bff;
}
.sale-products-section .sale-product-qty {
    color: #38b000;
}
.sale-products-section .badge {
    font-size: 0.85rem;
    border-radius: 0.7rem;
    padding: 0.3em 0.7em;
    font-weight: 500;
    letter-spacing: 0.01em;
}
.sale-products-section .sale-products-expand,
.sale-products-section .sale-products-collapse {
    border-radius: 2rem;
    font-weight: 500;
    min-width: 120px;
    transition: background 0.18s, color 0.18s;
}
.sale-products-section .sale-products-expand:hover,
.sale-products-section .sale-products-collapse:hover {
    background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
    color: #fff;
}
.sale-products-section .sale-totals-card {
    background: linear-gradient(120deg, #f8f9fa 80%, #e0fff7 100%);
    border-radius: 1.2rem;
    margin-top: 1.5rem;
}
.sale-products-section .sale-total-label {
    font-weight: 600;
    font-size: 1.1rem;
    color: #38b000;
    display: flex;
    align-items: center;
}
.sale-products-section .sale-total-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #222;
}
.sale-products-section .sale-total-paid {
    font-size: 1.15rem;
    font-weight: 600;
    color: #007bff;
}
.sale-products-section .sale-total-badge {
    background: linear-gradient(90deg, #ff5858 0%, #f09819 100%);
    color: #fff;
    font-size: 1.1rem;
    font-weight: 700;
    padding: 0.5em 1.1em;
    border-radius: 1.2rem;
    box-shadow: 0 2px 8px rgba(255,88,88,0.08);
}
@media (max-width: 900px) {
    .sale-products-section .col-md-3 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}
@media (max-width: 600px) {
    .sale-products-section .col-md-3 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    .sale-products-section .sale-product-card {
        min-height: 320px;
    }
}
</style>

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
</div>

@include('sales.createPayament')
@include('sales.editPayament')
@include('sales.deletPayament')
@include('sales.edit')
@include('sales.create')
@include('sales.delet')
@include('sales.addProduct')

@endsection
