@extends('layouts.user_type.auth')

@section('content')

<link rel="stylesheet" href="{{ asset('css/clientes.css') }}">

<div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Filtros e Pesquisa -->
    <div class="row w-100">
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
                        <span class="badge bg-light text-primary ms-2">Ativo</span>
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
    @if($clients->count() > 0)
    @foreach($clients as $client)
    <div class="col-md-2 mb-4">
        <div class="card h-100 custom-card position-relative">
            <div class="d-flex justify-content-center align-items-center" style="height:120px;">
                <img src="{{ $client->caminho_foto }}" alt="Avatar do Cliente" style="width:72px; height:72px; border-radius:50%; border:2px solid #6ea8fe; background:#fff;">
            </div>

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
                            <button class="btn btn-link p-0 mt-1" type="button" data-bs-toggle="collapse"
                                data-bs-target="#pending-sales-collapse-{{ $client->id }}" aria-expanded="false"
                                aria-controls="pending-sales-collapse-{{ $client->id }}"
                                id="toggle-pending-sales-btn-{{ $client->id }}">
                                <span class="ver-mais-label"><i class="bi bi-chevron-down"></i> Ver mais
                                    ({{ $pendingCount - 1 }})</span>
                                <span class="ver-menos-label d-none"><i class="bi bi-chevron-up"></i> Ver
                                    menos</span>
                            </button>
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var collapse = document.getElementById(
                                'pending-sales-collapse-{{ $client->id }}');
                            var btn = document.getElementById('toggle-pending-sales-btn-{{ $client->id }}');
                            if (collapse && btn) {
                                collapse.addEventListener('show.bs.collapse', function() {
                                    btn.querySelector('.ver-mais-label').classList.add('d-none');
                                    btn.querySelector('.ver-menos-label').classList.remove(
                                        'd-none');
                                });
                                collapse.addEventListener('hide.bs.collapse', function() {
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
    @else
    {{-- Bloco ainda mais estilizado para nenhum cliente encontrado --}}
    <div class="col-12">
        <div class="d-flex flex-column align-items-center justify-content-center py-5">
            <div class="animated-icon mb-4">
                <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                    <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3" fill="#f8fafc" />
                    <path d="M95 95c0-16.57-13.43-30-30-30s-30 13.43-30 30" stroke="#6ea8fe" stroke-width="3"
                        stroke-linecap="round" />
                    <circle cx="65" cy="55" r="15" stroke="#6ea8fe" stroke-width="3" fill="#e9f2ff" />
                    <path d="M50 55c0-8.28 6.72-15 15-15s15 6.72 15 15" stroke="#6ea8fe" stroke-width="3"
                        stroke-linecap="round" />
                    <line x1="40" y1="92" x2="90" y2="92" stroke="#adb5bd" stroke-width="3" stroke-linecap="round"
                        opacity="0.5" />
                </svg>
            </div>
            <h2 class="fw-bold mb-3 text-primary"
                style="font-size:2.5rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                Nenhum Cliente Encontrado
            </h2>
            <p class="mb-4 text-secondary text-center"
                style="max-width: 480px; font-size:1.25rem; font-weight:500; line-height:1.6;">
                <span style="color:#0d6efd; font-weight:700;">Ops!</span> Parece que você ainda não cadastrou nenhum
                cliente.<br>
                <span style="color:#6ea8fe;">Que tal começar agora mesmo?</span><br>
                Organize sua carteira e aproveite todos os recursos da plataforma!
            </p>

        </div>
    </div>


    @endif
</div>


<!-- Paginação -->
<div class="d-flex justify-content-center mt-4">
    {{ $clients->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>


@include('clients.create')

@foreach($clients as $client)
@include('clients.edit', ['client' => $client])
@include('clients.delet', ['client' => $client])
@endforeach
<script>
window.CLIENTS_STORE_URL = "{{ route('clients.store') }}";
window.CLIENTS_CSRF_TOKEN = "{{ csrf_token() }}";
window.CLIENTS_INDEX_URL = "{{ route('clients.index') }}";
</script>
<script src="{{ asset('js/clients.js') }}"></script>
@endsection