@extends('layouts.user_type.auth')

@section('content')

<div>
@include('message.alert')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Todos os Usuários</h5>
                        </div>
                        <!-- Botão para abrir o Modal de Criação -->
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" type="button" data-bs-toggle="modal" data-bs-target="#modalAddUser">+&nbsp; Novo Usuário</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Foto</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Função</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Data de Criação</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $user->id }}</p>
                                    </td>
                                    <td>
                                        <div>
                                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://www.gravatar.com/avatar/' . md5($user->email) . '?d=identicon' }}" class="avatar avatar-sm me-3">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $user->name }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $user->role->name ?? 'N/A' }}</p>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <!-- Botão para abrir o Modal de Edição -->
                                        <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->id }}">
                                            <i class="fas fa-pencil-alt text-secondary" title="Editar"></i>
                                        </a>

                                        <!-- Botão de Excluir -->
                                        <form action="{{ route('user.delete', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                <i class="fas fa-trash text-danger" title="Excluir"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>

                                <!-- Modal de Edição de Usuário -->
                                <div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalEditUserLabel">Editar Usuário</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Formulário de Edição -->
                                                <form action="{{ route('user.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    <!-- Remova a linha @method('PUT') -->

                                                    <!-- Campos do formulário -->
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nome</label>
                                                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Telefone</label>
                                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="location" class="form-label">Localização</label>
                                                        <input type="text" class="form-control" id="location" name="location" value="{{ $user->location }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="about_me" class="form-label">Sobre mim</label>
                                                        <textarea class="form-control" id="about_me" name="about_me">{{ $user->about_me }}</textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="role_id" class="form-label">Função</label>
                                                        <select class="form-select" id="role_id" name="role_id" required>
                                                            <option value="" disabled>Selecione a função</option>
                                                            @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                                {{ $role->name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Criação de Usuário -->
<div class="modal fade" id="modalAddUser" tabindex="-1" aria-labelledby="modalAddUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddUserLabel">Criar Novo Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulário de Criação -->
                <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Localização</label>
                        <input type="text" class="form-control" id="location" name="location">
                    </div>

                    <div class="mb-3">
                        <label for="about_me" class="form-label">Sobre mim</label>
                        <textarea class="form-control" id="about_me" name="about_me"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Foto de Perfil</label>
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
