@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <!-- Exibir erros de validação -->
    @if ($errors->any())
    <div id="error-message" class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="closeAlert('error-message')"></button>
        <div id="error-timer" class="alert-timer">30s</div>
    </div>
    @endif

    <!-- Exibir sucesso -->
    @if (session('success'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="closeAlert('success-message')"></button>
        <div id="success-timer" class="alert-timer">30s</div>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- Filtros e Pesquisa -->
        <div class="row w-100">

            <!-- Coluna de Filtro (Meio) -->
            <div class="col-md-4 mb-3">
                <form action="{{ route('clients.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <select name="filter" class="form-control w-100" onchange="this.form.submit()">
                        <option value="">Filtrar</option>
                        <option value="created_at" {{ request('filter') == 'created_at' ? 'selected' : '' }}>Últimos Adicionados</option>
                        <option value="updated_at" {{ request('filter') == 'updated_at' ? 'selected' : '' }}>Últimos Atualizados</option>
                        <option value="name_asc" {{ request('filter') == 'name_asc' ? 'selected' : '' }}>Nome A-Z</option>
                        <option value="name_desc" {{ request('filter') == 'name_desc' ? 'selected' : '' }}>Nome Z-A</option>
                    </select>
                </form>
            </div>

            <!-- Coluna de Pesquisa (Esquerda) -->
            <div class="col-md-4 mb-3">
                <form action="{{ route('clients.index') }}" method="GET" class="d-flex align-items-center w-100">
                    <div class="input-group w-100">
                        <input type="text" name="search" class="form-control w-65  h-25 " placeholder="Pesquisar por nome" value="{{ request('search') }}">
                        <button class="btn btn-primary h-20" type="submit">Pesquisar</button>
                    </div>
                </form>
            </div>

            <!-- Coluna de Adicionar Cliente (Direita) -->
            <div class="col-md-4 mb-3 text-end">
                <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalAddClient">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                    </svg>
                    Cliente</a>
            </div>
        </div>
    </div>



    <!-- Tabela de clientes -->
    <div class="row mt-4">
        @foreach($clients as $client)
        <div class="col-md-2">
            <div class="card position-relative">
                <!-- Informações do Cliente -->
                <div class="card-body">
                    <h5 class="card-title text-center">{{ ucwords($client->name) }}</h5>
                    <p class="card-text text-center">{{ ucwords($client->email ?? 'N/A') }}</p>

                    <div class="row">
                        <div class="col-6">
                            <p><strong>Telefone:</strong> {{ $client->phone ?? 'N/A' }}</p>
                            <p><strong>Endereço:</strong> {{ $client->address ?? 'N/A' }}</p>
                        </div>
                        <div class="col-6">
                            <p><strong>Data de Cadastro:</strong> {{ $client->registration_date }}</p>
                        </div>
                    </div>
                </div>

                <!-- Botões de Editar e Excluir -->
                <div class="position-absolute top-0 end-0 p-2">
                    <!-- Botão de Editar -->
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm p-1" data-bs-toggle="modal" data-bs-target="#modalEditClient{{ $client->id }}" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        </svg>
                    </a>

                    <!-- Botão de Excluir -->
                    <button class="btn btn-danger btn-sm p-1" data-bs-toggle="modal" data-bs-target="#modalDeleteClient{{ $client->id }}" title="Excluir">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal de Adicionar Cliente -->
<div class="modal fade" id="modalAddClient" tabindex="-1" aria-labelledby="modalAddClientLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Modal Ampliado -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddClientLabel">Adicionar Novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <!-- Formulário de Criação de Cliente -->
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Coluna da Esquerda com os Detalhes do Cliente -->
                            <div class="col-md-12 text-center">
                                <!-- Linha 1: Nome do Cliente e Email -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="col-form-label text-center">Nome do Cliente</label>
                                            <input type="text" name="name" id="name" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="col-form-label text-center">Email</label>
                                            <input type="email" name="email" id="email" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- Linha 2: Telefone e Endereço -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="col-form-label text-center">Telefone</label>
                                            <input type="text" name="phone" id="phone" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address" class="col-form-label text-center">Endereço</label>
                                            <textarea name="address" id="address" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Coluna da Direita -->
                            <div class="col-md-4">
                                <!-- Dados adicionais, como data de cadastro, etc. -->
                            </div>
                        </div>

                        <!-- Centralizar o botão de Adicionar Cliente -->
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Adicionar Cliente</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal de Confirmar Exclusão -->
@foreach($clients as $client)
<div class="modal fade" id="modalDeleteClient{{ $client->id }}" tabindex="-1" aria-labelledby="modalDeleteClientLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteClientLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja excluir este cliente?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
@endforeach
<!-- Modal de Editar Cliente -->
@foreach($clients as $client)
<div class="modal fade" id="modalEditClient{{ $client->id }}" tabindex="-1" aria-labelledby="modalEditClientLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditClientLabel">Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <!-- Formulário de Edição de Cliente -->
                    <form action="{{ route('clients.update', $client->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Coluna da Esquerda com os Detalhes do Cliente -->
                            <div class="col-md-8 text-center">
                                <!-- Linha 1: Nome do Cliente e Email -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="col-form-label text-center">Nome do Cliente</label>
                                            <input type="text" name="name" id="name" class="form-control" value="{{ $client->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="col-form-label text-center">Email</label>
                                            <input type="email" name="email" id="email" class="form-control" value="{{ $client->email }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Linha 2: Telefone e Endereço -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="col-form-label text-center">Telefone</label>
                                            <input type="text" name="phone" id="phone" class="form-control" value="{{ $client->phone }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address" class="col-form-label text-center">Endereço</label>
                                            <textarea name="address" id="address" class="form-control">{{ $client->address }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Coluna da Direita -->
                            <div class="col-md-4">
                                <!-- Dados adicionais, como data de cadastro, etc. -->
                            </div>
                        </div>

                        <!-- Centralizar o botão de Atualizar Cliente -->
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Atualizar Cliente</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Função para iniciar o timer e ocultar a mensagem após 30 segundos
        function startTimer(messageId, timerId) {
            let timerValue = 30;
            const timerElement = document.getElementById(timerId);
            const messageElement = document.getElementById(messageId);

            // Atualiza o temporizador a cada segundo
            const interval = setInterval(function() {
                if (timerValue > 0) {
                    timerElement.innerHTML = `${timerValue--}s`;
                } else {
                    clearInterval(interval);
                    // Fecha a mensagem após 30 segundos e recarrega a página
                    messageElement.classList.remove('show');
                    messageElement.classList.add('fade');
                    location.reload(); // Recarregar a página após 30 segundos
                }
            }, 1000); // Atualiza a cada segundo
        }

        // Iniciar o timer para a mensagem de erro (se existir)
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            startTimer('error-message', 'error-timer');
        }

        // Iniciar o timer para a mensagem de sucesso (se existir)
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            startTimer('success-message', 'success-timer');
        }

        // Configuração para mostrar que a página voltou ao estado original
        const closeButton = document.querySelectorAll('.btn-close');
        closeButton.forEach(button => {
            button.addEventListener('click', function() {
                // Resetando o timer de 30 segundos e voltando a página ao estado original
                document.getElementById('error-message')?.classList.remove('show');
                document.getElementById('success-message')?.classList.remove('show');
            });
        });
    });

    // Função para fechar o alerta ao clicar no "X"
    function closeAlert(messageId) {
        document.getElementById(messageId).classList.remove('show');
        document.getElementById(messageId).classList.add('fade');
    }
</script>

<style>
    .alert-timer {
        position: absolute;
        top: 10px;
        right: 40px;
        background-color: #ff9800;
        color: white;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-top: 5px;
    }

    .alert-dismissible .btn-close {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        color: #fff;
        background: transparent;
        border: none;
    }
</style>

@endsection
