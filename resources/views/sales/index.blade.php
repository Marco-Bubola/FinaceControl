@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        @include('message.alert')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Filtros e Pesquisa -->
            <div class="row w-100">

                <div class="col-md-3 mb-3">
                    <!-- Dropdown para Filtrar e Selecionar Itens -->
                    <form action="{{ route('sales.index') }}" method="GET" class="d-flex align-items-center w-100">
                        <div class="dropdown w-100" id="customDropdown"> <!-- ID único para o dropdown -->
                            <!-- Botão de Dropdown -->
                            <button class="btn btn-primary w-100 dropdown-toggle rounded-pill shadow-sm" type="button"
                                id="dropdownFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                Filtros
                            </button>

                            <!-- Itens do Dropdown -->
                            <ul class="dropdown-menu w-100 rounded-3 animate__animated animate__fadeIn"
                                aria-labelledby="dropdownFilter">
                                <!-- Filtro de Ordem (radio buttons dentro do dropdown) -->
                                <li>
                                    <h6 class="dropdown-header">Ordenar</h6>
                                    <div class="dropdown-item">
                                        <input type="radio" name="filter" id="created_at" value="created_at"
                                            onchange="this.form.submit()" {{ request('filter') == 'created_at' ? 'checked' : '' }}>
                                        <label for="created_at">Últimos Adicionados</label>
                                    </div>
                                    <div class="dropdown-item">
                                        <input type="radio" name="filter" id="updated_at" value="updated_at"
                                            onchange="this.form.submit()" {{ request('filter') == 'updated_at' ? 'checked' : '' }}>
                                        <label for="updated_at">Últimos Atualizados</label>
                                    </div>
                                    <div class="dropdown-item">
                                        <input type="radio" name="filter" id="name_asc" value="name_asc"
                                            onchange="this.form.submit()" {{ request('filter') == 'name_asc' ? 'checked' : '' }}>
                                        <label for="name_asc">Nome A-Z</label>
                                    </div>
                                    <div class="dropdown-item">
                                        <input type="radio" name="filter" id="name_desc" value="name_desc"
                                            onchange="this.form.submit()" {{ request('filter') == 'name_desc' ? 'checked' : '' }}>
                                        <label for="name_desc">Nome Z-A</label>
                                    </div>
                                    <div class="dropdown-item">
                                        <input type="radio" name="filter" id="price_asc" value="price_asc"
                                            onchange="this.form.submit()" {{ request('filter') == 'price_asc' ? 'checked' : '' }}>
                                        <label for="price_asc">Preço A-Z</label>
                                    </div>
                                    <div class="dropdown-item">
                                        <input type="radio" name="filter" id="price_desc" value="price_desc"
                                            onchange="this.form.submit()" {{ request('filter') == 'price_desc' ? 'checked' : '' }}>
                                        <label for="price_desc">Preço Z-A</label>
                                    </div>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <!-- Filtro de Quantidade de Itens (radio buttons dentro do dropdown) -->
                                <li>
                                    <h6 class="dropdown-header">Quantidade de Itens</h6>
                                    <div class="dropdown-item">
                                        <input type="radio" name="per_page" id="per_page_9" value="9"
                                            onchange="this.form.submit()" {{ request('per_page') == '9' ? 'checked' : '' }}>
                                        <label for="per_page_9">9 itens</label>
                                    </div>
                                    <div class="dropdown-item">
                                        <input type="radio" name="per_page" id="per_page_25" value="25"
                                            onchange="this.form.submit()" {{ request('per_page') == '25' ? 'checked' : '' }}>
                                        <label for="per_page_25">25 itens</label>
                                    </div>
                                    <div class="dropdown-item">
                                        <input type="radio" name="per_page" id="per_page_50" value="50"
                                            onchange="this.form.submit()" {{ request('per_page') == '50' ? 'checked' : '' }}>
                                        <label for="per_page_50">50 itens</label>
                                    </div>
                                    <div class="dropdown-item">
                                        <input type="radio" name="per_page" id="per_page_100" value="100"
                                            onchange="this.form.submit()" {{ request('per_page') == '100' ? 'checked' : '' }}>
                                        <label for="per_page_100">100 itens</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>


                <!-- Efeito de transição e animação -->
                <style>
                    /* Especificando um estilo para esse dropdown específico */
                    #customDropdown .dropdown-toggle::after {
                        display: none;
                        /* Remove a seta padrão do Bootstrap */
                    }

                    /* Estilização personalizada para o botão (apenas para esse dropdown) */
                    #customDropdown .dropdown-toggle {
                        padding: 10px 20px;
                        font-size: 1rem;
                        border: none;
                        color: white;
                        transition: background-color 0.3s ease;
                    }


                    /* Estilo para o dropdown menu */
                    #customDropdown .dropdown-menu {
                        background-color: #ffffff;
                        /* Cor de fundo branca */
                        border-radius: 10px;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                        transition: opacity 0.3s ease-in-out, transform 0.3s ease;
                        padding: 0.5rem 0;
                    }

                    /* Efeito de animação do dropdown */
                    #customDropdown .dropdown-menu.show {
                        opacity: 1;
                        transform: translateY(10px);
                        /* Efeito de deslizamento suave */
                    }

                    /* Estilo para os itens do dropdown */
                    #customDropdown .dropdown-item {
                        padding: 10px 15px;
                        font-size: 1rem;
                        color: #495057;
                        /* Cor de texto padrão */
                        transition: background-color 0.2s ease, transform 0.2s ease;
                    }

                    #customDropdown .dropdown-item:hover {
                        background-color: #d1e7ff;
                        /* Cor de fundo suave no hover */
                        color: #007bff;
                        transform: scale(1.05);
                        /* Aumento de tamanho no hover */
                    }

                    /* Estilo para os inputs de radio dentro do dropdown */
                    #customDropdown .dropdown-item input[type="radio"] {
                        margin-right: 10px;
                        /* Espaço entre o botão de rádio e o texto */
                        vertical-align: middle;
                    }

                    #customDropdown .dropdown-item label {
                        display: inline-block;
                        vertical-align: middle;
                        color: inherit;
                        /* Garantir que a cor seja herdada */
                    }

                    /* Estilo de seleção para os itens selecionados */
                    #customDropdown .dropdown-item input[type="radio"]:checked+label {
                        background-color: #d1e7ff;
                        color: #007bff;
                    }
                </style>


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

                <div class="col-md-5 mb-3 text-end">
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
                                        <!-- Dropdown para os Botões -->
                                        <div class="dropdown d-flex justify-content-end align-items-center gap-2"
                                            id="customDropdown">
                                            <!-- Botão de Dropdown com ícone personalizado -->
                                            <button class="btn btn-primary p-2 w-100 dropdown-toggle rounded-pill shadow-sm"
                                                type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Ações <i class="bi bi-three-dots-vertical fs-4 ms-2"></i>
                                                <!-- Apenas o ícone personalizado -->
                                            </button>

                                            <!-- Itens do Dropdown -->
                                            <ul class="dropdown-menu shadow-lg w-100 rounded-3 animate__animated animate__fadeIn"
                                                aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <button class="dropdown-item rounded-3" data-bs-toggle="modal"
                                                        data-bs-target="#paymentHistoryModal{{ $sale->id }}"
                                                        title="Historico de pagamento">
                                                        <i class="bi bi-clock-history fs-5 ms-2 text-primary"></i> <span
                                                            class="text-primary fs-5">Histórico de Pagamento</span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <a href="{{ route('sales.export', $sale->id) }}" class="dropdown-item rounded-3"
                                                        title="Exportar PDF">
                                                        <i class="bi bi-file-earmark-pdf fs-5 ms-2 text-danger"></i> <span
                                                            class="text-danger fs-5">Exportar PDF</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item rounded-3" data-bs-toggle="modal"
                                                        data-bs-target="#paymentModal{{ $sale->id }}" title="Adicionar Pagamento">
                                                        <i class="bi bi-plus-square fs-5 ms-2 text-success"></i> <span
                                                            class="text-success fs-5">Adicionar Pagamento</span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <a href="{{ route('sales.show', $sale->id) }}" class="dropdown-item rounded-3"
                                                        title="Ver Detalhes">
                                                        <i class="bi bi-eye fs-5 ms-2 text-info"></i> <span
                                                            class="text-info fs-5">Ver Detalhes</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item rounded-3" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditSale{{ $sale->id }}" title="Editar Venda">
                                                        <i class="bi bi-pencil-square fs-5 ms-2 text-warning"></i> <span
                                                            class="text-warning fs-5">Editar Venda</span>
                                                    </button>
                                                </li>
                                                <li>
                                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item text-danger rounded-3"
                                                            data-bs-toggle="modal" data-bs-target="#modalDeleteSale{{ $sale->id }}"
                                                            title="Excluir Venda">
                                                            <i class="bi bi-trash3 fs-5 ms-2 text-danger"></i> <span
                                                                class="text-danger fs-5">Excluir Venda</span>
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item rounded-3" data-bs-toggle="modal"
                                                        data-bs-target="#modalAddProductToSale{{ $sale->id }}"
                                                        title="Adicionar Produto à Venda">
                                                        <i class="bi bi-plus-square fs-5 ms-2 text-success"></i> <span
                                                            class="text-success fs-5">Adicionar Produto</span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Efeito de transição e animação -->
                                    <style>
                                        /* Especificando um estilo para esse dropdown específico */
                                        #customDropdown .dropdown-toggle::after {
                                            display: none;
                                            /* Remove a seta padrão do Bootstrap */
                                        }

                                        /* Estilização personalizada para o botão (apenas para esse dropdown) */
                                        #customDropdown .dropdown-toggle {
                                            padding: 10px 20px;
                                            font-size: 1rem;
                                            border: none;
                                            color: white;
                                            transition: background-color 0.3s ease;
                                        }



                                        /* Estilo para o dropdown menu */
                                        #customDropdown .dropdown-menu {
                                            background-color: #ffffff;
                                            /* Cor de fundo branca */
                                            border-radius: 10px;
                                            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                            transition: opacity 0.3s ease-in-out, transform 0.3s ease;
                                            padding: 0.5rem 0;
                                        }

                                        /* Efeito de animação do dropdown */
                                        #customDropdown .dropdown-menu.show {
                                            opacity: 1;
                                            transform: translateY(10px);
                                            /* Efeito de deslizamento suave */
                                        }

                                        /* Estilo para os itens do dropdown */
                                        #customDropdown .dropdown-item {
                                            padding: 10px 15px;
                                            font-size: 1.1rem;
                                            /* Aumentando o tamanho do texto */
                                            color: #495057;
                                            /* Cor de texto padrão */
                                            transition: background-color 0.2s ease, transform 0.2s ease;
                                        }

                                        #customDropdown .dropdown-item:hover {
                                            background-color: #e2e6ea;
                                            color: #007bff;
                                            transform: scale(1.05);
                                            /* Aumento de tamanho no hover */
                                        }

                                        /* Animação do dropdown ao aparecer */
                                        #customDropdown .animate__animated.animate__fadeIn {
                                            animation: fadeIn 0.3s ease-in;
                                        }
                                    </style>
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
