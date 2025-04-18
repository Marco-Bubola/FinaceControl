@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        @include('message.alert')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Filtros e Pesquisa -->
            <div class="row w-100">

                <!-- Coluna de Filtro (Meio) -->
                <div class="col-md-4 mb-3">
                    <form action="{{ route('clients.index') }}" method="GET" class="d-flex align-items-center w-100">
                        <select name="filter" class="form-control w-100" onchange="this.form.submit()">
                            <option value="">Filtrar</option>
                            <option value="created_at" {{ request('filter') == 'created_at' ? 'selected' : '' }}>Últimos
                                Adicionados</option>
                            <option value="updated_at" {{ request('filter') == 'updated_at' ? 'selected' : '' }}>Últimos
                                Atualizados</option>
                            <option value="name_asc" {{ request('filter') == 'name_asc' ? 'selected' : '' }}>Nome A-Z</option>
                            <option value="name_desc" {{ request('filter') == 'name_desc' ? 'selected' : '' }}>Nome Z-A
                            </option>
                        </select>
                    </form>
                </div>

                <!-- Coluna de Pesquisa (Esquerda) -->
                <div class="col-md-4 mb-3">
                    <form action="{{ route('clients.index') }}" method="GET" class="d-flex align-items-center w-100">
                        <div class="input-group w-100">
                            <input type="text" name="search" class="form-control w-65  h-25 "
                                placeholder="Pesquisar por nome" value="{{ request('search') }}">
                            <button class="btn btn-primary h-20" type="submit">Pesquisar</button>
                        </div>
                    </form>
                </div>

                <!-- Coluna de Adicionar Cliente (Direita) -->
                <div class="col-md-4 mb-3 text-end">
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

                <!-- Adicionar seleção de quantidade por página -->
                <div class="col-md-4 mb-3">
                    <form id="pagination-form" action="{{ route('clients.index') }}" method="GET"
                        class="d-flex align-items-center w-100">
                        <label for="per_page" class="me-2">Itens por página:</label>
                        <select name="per_page" id="per_page" class="form-control w-50" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabela de clientes -->
        <div class="row mt-4" id="client-list">
            @foreach($clients as $client)
                <div class="col-md-3 mb-4">
                    <div class="card h-100" style="min-height: 400px;">
                        <img src="{{ asset('storage/products/product-placeholder.png') }}" class="card-img-top" alt="Imagem do Cliente">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">{{ ucwords($client->name) }}</h5>
                            <p><strong>Email:</strong> {{ $client->email ?? 'N/A' }}</p>
                            <p><strong>Telefone:</strong> {{ $client->phone ?? 'N/A' }}</p>
                            <p><strong>Endereço:</strong> {{ $client->address ?? 'N/A' }}</p>

                            <!-- Histórico de vendas pendentes -->
                            <div class="mt-3">
                                <h6>Vendas Pendentes</h6>
                                <p><strong>Total Pendentes:</strong>
                                    {{ $client->sales->where('status', '!=', 'Paga')->count() }}
                                </p>
                                <table class="table table-bordered">
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
                                                    <span class="badge bg-danger">{{ $sale->status }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                                                        Ver Detalhes
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Botões de ação -->
                            <div class="mt-auto">
                                <button class="btn btn-primary btn-sm w-100 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#modalEditClient{{ $client->id }}">Editar</button>
                                <button class="btn btn-danger btn-sm w-100 mb-2" data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteClient{{ $client->id }}">Excluir</button>
                                <button class="btn btn-secondary btn-sm w-100" data-bs-toggle="modal"
                                    data-bs-target="#modalFullHistory{{ $client->id }}">Histórico Completo</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de Histórico Completo -->
                <div class="modal fade" id="modalFullHistory{{ $client->id }}" tabindex="-1" aria-labelledby="modalFullHistoryLabel{{ $client->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalFullHistoryLabel{{ $client->id }}">Histórico Completo de Vendas - {{ $client->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Data</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->sales as $sale)
                                            <tr>
                                                <td>{{ $sale->id }}</td>
                                                <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                                                <td>R$ {{ number_format($sale->total_price, 2, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $sale->status == 'Paga' ? 'success' : 'danger' }}">
                                                        {{ $sale->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                                                        Ver Detalhes
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="d-flex justify-content-center mt-4">
            {{ $clients->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

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

        // Pesquisa dinâmica
        document.querySelector('input[name="search"]').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const clients = document.querySelectorAll('#client-list .card');
            clients.forEach(client => {
                const name = client.querySelector('.card-title').textContent.toLowerCase();
                client.style.display = name.includes(searchTerm) ? 'block' : 'none';
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
