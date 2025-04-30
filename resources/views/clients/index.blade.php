@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        @include('message.alert')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Filtros e Pesquisa -->
            <div class="row w-100">

                <!-- Coluna de Filtro (Meio) -->
                <div class="col-md-3 mb-3">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle w-100" type="button" id="clientFilterMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Filtrar Clientes
                        </button>
                        <ul class="dropdown-menu w-100 p-4" aria-labelledby="clientFilterMenuButton">
                            <!-- Filtro por Data -->
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('clients.index', ['filter' => 'created_at', 'per_page' => request('per_page')]) }}">
                                    Últimos Adicionados
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('clients.index', ['filter' => 'updated_at', 'per_page' => request('per_page')]) }}">
                                    Últimos Atualizados
                                </a>
                            </li>

                            <!-- Filtro por Nome -->
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('clients.index', ['filter' => 'name_asc', 'per_page' => request('per_page')]) }}">
                                    Nome A-Z
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('clients.index', ['filter' => 'name_desc', 'per_page' => request('per_page')]) }}">
                                    Nome Z-A
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <!-- Filtro por Itens por Página -->
                            <li>
                                <form id="pagination-form" action="{{ route('clients.index') }}" method="GET"
                                    class="d-flex align-items-center w-100">

                                    <select name="per_page" id="per_page" class="form-control w-90"
                                        onchange="this.form.submit()">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>Itens por página:
                                            10</option>
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>Itens por página:
                                            20</option>
                                        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>Itens por página:
                                            30</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>Itens por página:
                                            50</option>
                                    </select>
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- Coluna de Pesquisa (Esquerda) -->
                <div class="col-md-3 mb-3">
                    <form action="{{ route('clients.index') }}" method="GET" class="d-flex align-items-center w-100">
                        <div class="input-group w-100">
                            <input type="text" name="search" id="search-input" class="form-control"
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
                <!-- Coluna de Adicionar Cliente (Direita) -->
                <div class="col-md-3 mb-3 text-end">
                    <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal"
                        data-bs-target="#modalAddClient">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            class="bi bi-plus-square" viewBox="0 0 16 16">
                            <path
                                d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                            <path
                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                        </svg>
                        Cliente</a>
                </div>


            </div>
        </div>

        <!-- Tabela de clientes -->
        <div class="row mt-4" id="client-list">
            @foreach($clients as $client)
                <div class="col-md-3 mb-4">
                    <div class="card h-100"
                        style="min-height: 450px; position: relative; border-radius: 12px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                        <img src="{{ asset('storage/products/product-placeholder.png') }}" class="card-img-top"
                            alt="Imagem do Cliente" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        <!-- Botões sobre a imagem -->
                        <div style="position: absolute; top: 10px; right: 10px; display: flex; gap: 5px;">
                            <button class="btn btn-primary " data-bs-toggle="modal"
                                data-bs-target="#modalEditClient{{ $client->id }}" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-danger " data-bs-toggle="modal"
                                data-bs-target="#modalDeleteClient{{ $client->id }}" title="Excluir">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="btn btn-secondary " data-bs-toggle="modal"
                                data-bs-target="#modalFullHistory{{ $client->id }}" title="Histórico Completo">
                                <i class="bi bi-clock-history"></i>
                            </button>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center" style="font-size: 1.1rem; font-weight: 600; color: #333;">
                                {{ ucwords($client->name) }}</h5>
                            <p><strong>Email:</strong> {{ $client->email ?? 'N/A' }}</p>
                            <p><strong>Telefone:</strong> {{ $client->phone ?? 'N/A' }}</p>
                            <p><strong>Endereço:</strong> {{ $client->address ?? 'N/A' }}</p>

                            <!-- Histórico de vendas pendentes -->
                            <div class="mt-3">
                                <h6 class="text-primary" style="font-weight: 600;">Vendas Pendentes</h6>
                                <p><strong>Total Pendentes:</strong>
                                    <span class="badge bg-warning text-dark">
                                        {{ $client->sales->where('status', '!=', 'Paga')->count() }} Vendas
                                    </span>
                                </p>

                                <table class="table table-sm table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->sales->where('status', '!=', 'Paga') as $sale)
                                            <tr>
                                                <td>R$ {{ number_format($sale->total_price, 2, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge bg-danger"
                                                        style="font-size: 0.9rem;">{{ $sale->status }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info "
                                                        title="Ver Detalhes">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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

    <style>
        /* Personalizando a tabela */
        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Botões sobre a imagem */
        .card-body button {
            border-radius: 6px;
            padding: 8px 12px;
        }

        /* Badges de status */
        .badge {
            font-size: 0.9rem;
        }

        /* Estilizando o nome do cliente */
        .card-body h5 {
            font-size: 1.1rem;
            color: #333;
            font-weight: 600;
        }
    </style>
    <!-- Inclusão dos modais -->
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

        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.getElementById('phone');

            // Máscara para o telefone, formatando enquanto o usuário digita
            phoneInput.addEventListener('input', function (e) {
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
        document.querySelector('#add-client-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('{{ route('clients.store') }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(response => response.json())
                .then(data => {
                    // Atualizar lista de clientes dinamicamente
                });
        });

        // Editar cliente dinamicamente
        document.querySelectorAll('.edit-client').forEach(button => {
            button.addEventListener('click', function () {
                const clientId = this.dataset.id;
                // Abrir modal e carregar dados do cliente
            });
        });

        // Excluir cliente dinamicamente
        document.querySelectorAll('.delete-client').forEach(button => {
            button.addEventListener('click', function () {
                const clientId = this.dataset.id;
                fetch(`/clients/${clientId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(response => response.json())
                    .then(data => {
                        // Remover cliente da lista
                    });
            });
        });


    </script>
@endsection
