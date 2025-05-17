@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">

<link rel="stylesheet" href="{{ asset('css/clientes.css') }}">

<style>
    /* Estilos para o card do cliente */
    .custom-card {
        border-radius: 1.1em;
        box-shadow: 0 2px 12px rgba(13,110,253,0.07);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    /* Estilo para mensagem de nenhuma venda pendente */
    .no-pending-sales-card {
        background: #f8fafc;
        border-radius: 0.9em;
        box-shadow: 0 2px 12px rgba(13,110,253,0.04);
        padding: 0.9em 1em;
        margin: 0.5em auto;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1.5px solid #e3eafc;
        transition: box-shadow 0.18s;
    }
    .no-pending-sales-card .no-pending-icon {
        font-size: 1.8em;
        color: #adb5bd;
        margin-bottom: 0.2em;
    }
    .no-pending-sales-card .no-pending-text {
        color: #6c757d;
        font-size: 0.9em;
        font-weight: 500;
        letter-spacing: 0.01em;
    }
    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(13,110,253,0.15);
    }
    .custom-card .card-title {
        font-size: 1.35em;
        font-weight: 700;
        color: #0d6efd;
        display: flex;
        align-items: center;
        gap: 0.5em;
        margin-bottom: 0.7em;
        justify-content: center;
    }
    .custom-card .info-line {
        font-size: 0.9em;
        color: #495057;
        margin-bottom: 0.25em;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }
    .custom-card .info-line i {
        color: #6ea8fe;
        font-size: 1.1em;
        min-width: 1.2em;
        text-align: center;
    }
    .custom-card .card-img-top {
        height: 120px;
        object-fit: cover;
        background: linear-gradient(45deg, #e9f2ff, #f8f9fa);
    }
    .icon-btn {
        width: 28px;
        height: 28px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 0.8rem;
    }
    
    /* Estilos para vendas pendentes */
    .pending-sales-area {
        margin-top: 0.5rem;
    }
    .pending-sale-card {
        background: #fff;
        border-radius: 0.7em;
        box-shadow: 0 2px 12px rgba(220,53,69,0.07);
        padding: 0.7em 0.9em;
        margin-bottom: 0.5em;
        display: flex;
        align-items: center;
        gap: 0.8em;
        border-left: 4px solid #dc3545;
        position: relative;
        transition: box-shadow 0.18s;
    }
    .pending-sale-card:hover {
        box-shadow: 0 4px 15px rgba(220,53,69,0.15);
    }
    .pending-sale-card:last-child {
        margin-bottom: 0;
    }
    .pending-sale-icon {
        font-size: 1.4em;
        color: #dc3545;
        background: #fdeaea;
        border-radius: 50%;
        padding: 0.3em;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2em;
        min-height: 2em;
    }
    .pending-sale-info {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        gap: 0.1em;
    }
    .pending-sale-total {
        font-size: 1em;
        font-weight: 700;
        color: #dc3545;
        display: flex;
        align-items: center;
        gap: 0.4em;
    }
    .pending-sale-status {
        color: #fff;
        background: #dc3545;
        border-radius: 1.2em;
        padding: 0.15em 0.7em;
        font-size: 0.75em;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3em;
        margin-top: 0.1em;
    }
    .pending-sale-actions {
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    /* Estilos para o botão Ver mais/Ver menos */
    .btn-link {
        color: #6c757d;
        font-size: 0.75rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-link:hover {
        color: #dc3545;
    }
    .ver-mais-label, .ver-menos-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.3em;
    }
    /* Estilos para o cabeçalho de vendas pendentes */
    .pending-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.1em;
        margin-bottom: 0.5em;
    }
    .pending-title {
        color: #dc3545;
        font-size: 0.9em;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4em;
        letter-spacing: 0.01em;
    }
    .pending-badge {
        background: #fdeaea;
        color: #dc3545;
        font-weight: 700;
        font-size: 0.9em;
        border-radius: 1.2em;
        padding: 0.28em 0.8em;
        display: flex;
        align-items: center;
        gap: 0.3em;
        box-shadow: 0 1px 4px rgba(220,53,69,0.07);
    }
</style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- Filtros e Pesquisa -->
        <div class="row w-100">

           
<!-- Coluna de Filtro (Meio) - Novo Dropdown no padrão dos filtros de vendas -->
<div class="col-md-3">
    <form action="{{ route('clients.index') }}" method="GET" class="w-100" id="clientsFiltersForm">
        <div class="dropdown w-100 dropdown-filtros" data-bs-auto-close="outside">
            <button
                class="btn btn-gradient-primary w-100 dropdown-toggle rounded-pill shadow d-flex justify-content-between align-items-center px-4 py-2"
                type="button" id="dropdownClientsFilter" data-bs-toggle="dropdown" aria-expanded="false"
                style="font-weight:600;">
                <span>
                    <i class="bi bi-funnel-fill me-2"></i> Filtros
                </span>
                @if(request('filter') || request('per_page'))
                <span class="badge bg-light text-primary ms-2" >Ativo</span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-animate w-100 rounded-4 p-3 border-0 shadow-lg"
                aria-labelledby="dropdownClientsFilter" style="min-width: 320px;">
                <!-- Ordenação -->
                <li>
                    <div class="filter-section mb-3" tabindex="0">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-sort-alpha-down text-primary me-2"></i>
                            <h6 class="mb-0 text-primary" style="font-size:1rem;">Ordenar</h6>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $filters = [
                                    'created_at' => 'Últimos Adicionados',
                                    'updated_at' => 'Últimos Atualizados',
                                    'name_asc' => 'Nome A-Z',
                                    'name_desc' => 'Nome Z-A',
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
                <!-- Itens por página -->
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
                <!-- Botões -->
                <li>
                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-gradient-success rounded-pill px-3 flex-fill">
                            <i class="bi bi-check2-circle"></i> Aplicar
                        </button>
                        @if(request('filter') || request('per_page'))
                        <a href="{{ route('clients.index') }}"
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


            <div class="col-md-4 ">
                <form action="{{ route('clients.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <div class="input-group search-bar-sales w-100">
                        <span class="input-group-text search-bar-sales-icon" id="search-addon">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" id="search-input" class="form-control search-bar-sales-input"
                            placeholder="Pesquisar por cliente" value="{{ request('search') }}"
                            aria-label="Pesquisar por cliente" aria-describedby="search-addon">
                    </div>
                </form>
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

                        const url = `{{ route('clients.index') }}?search=${encodeURIComponent(query)}`;

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
                    }, 100); // Debounce mais rápido
                });
            }

            document.addEventListener('DOMContentLoaded', setupDynamicSearch);
            </script>
 <!-- Coluna de Adicionar Cliente (Direita) -->
<div class="col-md-5 text-end">
    <a href="#" class="btn bg-gradient-primary btn-sm mb-0 d-inline-flex align-items-end justify-content-end"
        data-bs-toggle="modal" data-bs-target="#modalAddClient" style="min-width:unset;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
            class="bi bi-plus-square me-1" viewBox="0 0 16 16">
            <path
                d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
            <path
                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
        </svg>
        Cliente
    </a>
</div>


        </div>
    </div>

    <!-- Tabela de clientes -->
    <div class="row mt-4" id="client-list">
        @foreach($clients as $client)


        <div class="col-md-2 mb-4">
            <div class="card h-100 custom-card position-relative">
                <img src="{{ asset('storage/products/product-placeholder.png') }}" class="card-img-top"
                    alt="Imagem do Cliente">

                <!-- Botões sobre a imagem -->
                <div style="position: absolute; top: 8px; right: 8px; display: flex; gap: 4px;">
                    <button class="btn btn-primary icon-btn" data-bs-toggle="modal"
                        data-bs-target="#modalEditClient{{ $client->id }}" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-danger icon-btn" data-bs-toggle="modal"
                        data-bs-target="#modalDeleteClient{{ $client->id }}" title="Excluir">
                        <i class="bi bi-trash"></i>
                    </button>
                    <button class="btn btn-secondary icon-btn" data-bs-toggle="modal"
                        data-bs-target="#modalFullHistory{{ $client->id }}" title="Histórico Completo">
                        <i class="bi bi-clock-history"></i>
                    </button>
                    <a href="{{ route('teste.index', $client->id) }}" class="btn btn-info icon-btn"
                        title="Resumo Financeiro">
                        <i class="bi bi-bar-chart"></i>
                    </a>
                </div>

                <div class="card-body p-2 d-flex flex-column">
                    <h5 class="card-title text-center text-primary">
                        <i class="bi bi-person-circle"></i>
                        {{ ucwords($client->name) }}
                    </h5>
                    <div class="info-line">
                        <i class="bi bi-envelope-at"></i>
                        <strong>Email:</strong> {{ $client->email ?? 'N/A' }}
                    </div>
                    <div class="info-line">
                        <i class="bi bi-telephone"></i>
                        <strong>Fone:</strong> {{ $client->phone ?? 'N/A' }}
                    </div>
                    <div class="info-line">
                        <i class="bi bi-geo-alt"></i>
                        <strong>End.:</strong> {{ $client->address ?? 'N/A' }}
                    </div>

                    <!-- Histórico de vendas pendentes -->
                    <div class="">
                        <div class="pending-header">
                            <span class="pending-title">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                Vendas Pendentes
                            </span>
                            <span class="pending-badge">
                                <i class="bi bi-clock"></i>
                                {{ $client->sales->where('status', 'pendente')->count() }}
                            </span>
                        </div>

                        <div class="pending-sales-area" id="pending-sales-area-{{ $client->id }}">
                            @php
                                $pendingSales = $client->sales->where('status', 'pendente')->values();
                                $pendingCount = $pendingSales->count();
                            @endphp

                            @if($pendingCount === 0)
                                <div class="no-pending-sales-card">
                                    <span class="no-pending-icon">
                                        <i class="bi bi-emoji-neutral"></i>
                                    </span>
                                    <span class="no-pending-text">
                                        Nenhuma venda pendente
                                    </span>
                                </div>
                            @else
                                {{-- Primeira venda pendente --}}
                                <div class="pending-sale-card">
                                    <div class="pending-sale-icon">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                    </div>
                                    <div class="pending-sale-info">
                                        <div class="pending-sale-total">
                                            <i class="bi bi-currency-dollar"></i>
                                            R$ {{ number_format($pendingSales[0]->total_price, 2, ',', '.') }}
                                        </div>
                                        <span class="pending-sale-status">
                                            <i class="bi bi-clock"></i> Pendente
                                        </span>
                                    </div>
                                    <div class="pending-sale-actions">
                                        <a href="{{ route('sales.show', $pendingSales[0]->id) }}"
                                           class="btn btn-outline-info btn-sm icon-btn" title="Ver Detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>

                                {{-- Demais vendas pendentes (escondidas inicialmente) --}}
                                @if($pendingCount > 1)
                                    <div class="collapse mt-1" id="pending-sales-collapse-{{ $client->id }}">
                                        @foreach($pendingSales->slice(1) as $sale)
                                            <div class="pending-sale-card">
                                                <div class="pending-sale-icon">
                                                    <i class="bi bi-exclamation-circle-fill"></i>
                                                </div>
                                                <div class="pending-sale-info">
                                                    <div class="pending-sale-total">
                                                        <i class="bi bi-currency-dollar"></i>
                                                        R$ {{ number_format($sale->total_price, 2, ',', '.') }}
                                                    </div>
                                                    <span class="pending-sale-status">
                                                        <i class="bi bi-clock"></i> Pendente
                                                    </span>
                                                </div>
                                                <div class="pending-sale-actions">
                                                    <a href="{{ route('sales.show', $sale->id) }}"
                                                       class="btn btn-outline-info btn-sm icon-btn" title="Ver Detalhes">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-link p-0 mt-1" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#pending-sales-collapse-{{ $client->id }}"
                                                aria-expanded="false"
                                                aria-controls="pending-sales-collapse-{{ $client->id }}"
                                                id="toggle-pending-sales-btn-{{ $client->id }}">
                                            <span class="ver-mais-label"><i class="bi bi-chevron-down"></i> Ver mais ({{ $pendingCount - 1 }})</span>
                                            <span class="ver-menos-label d-none"><i class="bi bi-chevron-up"></i> Ver menos</span>
                                        </button>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            var collapse = document.getElementById('pending-sales-collapse-{{ $client->id }}');
                                            var btn = document.getElementById('toggle-pending-sales-btn-{{ $client->id }}');
                                            if (collapse && btn) {
                                                collapse.addEventListener('show.bs.collapse', function () {
                                                    btn.querySelector('.ver-mais-label').classList.add('d-none');
                                                    btn.querySelector('.ver-menos-label').classList.remove('d-none');
                                                });
                                                collapse.addEventListener('hide.bs.collapse', function () {
                                                    btn.querySelector('.ver-mais-label').classList.remove('d-none');
                                                    btn.querySelector('.ver-menos-label').classList.add('d-none');
                                                });
                                            }
                                        });
                                    </script>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('clients.historico', ['client' => $client])
        @endforeach
    </div>


    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        {{ $clients->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

@include('clients.create')

@foreach($clients as $client)
@include('clients.edit', ['client' => $client])
@include('clients.delet', ['client' => $client])
@endforeach

<script>
// Função para buscar o endereço pelo CEP utilizando a API dos Correios
function searchAddressByCep() {
    var cep = document.getElementById('cep').value.replace(/\D/g, ''); // Remove caracteres não numéricos
    if (cep.length === 8) { // Se o CEP tiver 8 dígitos
        var url = `https://viacep.com.br/ws/${cep}/json/`; // API dos Correios

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    // Preenche os campos de endereço automaticamente
                    document.getElementById('address').value = data.logradouro;
                    document.getElementById('city').value = data.localidade;
                    document.getElementById('state').value = data.uf;
                    document.getElementById('district').value = data.bairro;

                    // Exibe os campos de endereço após digitar o CEP
                    document.getElementById('addressFields').style.display = 'block';
                } else {
                    alert('CEP não encontrado!');
                }
            })
            .catch(error => {
                console.error('Erro ao buscar o CEP:', error);
            });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');

    // Máscara para o telefone, formatando enquanto o usuário digita
    phoneInput.addEventListener('input', function(e) {
        let phone = e.target.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos

        // Aplica a máscara dinamicamente enquanto o usuário digita
        if (phone.length <= 2) {
            // Coloca os parênteses quando os dois primeiros números forem digitados
            phone = phone.replace(/^(\d{2})$/, '($1)');
        } else if (phone.length <= 7) {
            // Quando tiver 3 a 7 números, coloca o espaço após os dois primeiros e o hífen
            phone = phone.replace(/^(\d{2})(\d{1,5})$/, '($1) $2');
        } else {
            // Quando tiver mais de 7 números, coloca o hífen
            phone = phone.replace(/^(\d{2})(\d{1,5})(\d{1,4})$/, '($1) $2-$3');
        }

        e.target.value = phone; // Aplica a máscara no input
    });
});


// Adicionar cliente dinamicamente
document.querySelector('#add-client-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('{{ route('clients.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => response.json())
        .then(data => {
            // Atualizar lista de clientes dinamicamente
        });
});

// Editar cliente dinamicamente
document.querySelectorAll('.edit-client').forEach(button => {
    button.addEventListener('click', function() {
        const clientId = this.dataset.id;
        // Abrir modal e carregar dados do cliente
    });
});

// Excluir cliente dinamicamente
document.querySelectorAll('.delete-client').forEach(button => {
    button.addEventListener('click', function() {
        const clientId = this.dataset.id;
        fetch(`/clients/${clientId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => response.json())
            .then(data => {
                // Remover cliente da lista
            });
    });
});
</script>
@endsection
