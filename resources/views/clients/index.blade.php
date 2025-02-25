@extends('layouts.user_type.auth')

@section('content')
<div class="container mt-4">
    <!-- Botão para abrir o Modal de Adicionar Cliente -->
    <div class="d-flex justify-content-end">
        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalAddClient">Adicionar Cliente</a>
    </div>

    <!-- Tabela de clientes -->
    <div class="row mt-4">
        @foreach($clients as $client)
        <div class="col-md-3">
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
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm p-1" title="Excluir">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                            </svg>
                        </button>
                    </form>
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
                            <div class="col-md-8 text-center">
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
@endsection
