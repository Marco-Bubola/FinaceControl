
<!-- Modal de Editar Cliente Moderno -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .modal-edit-client .modal-header {
        background: linear-gradient(90deg, #0d6efd 60%, #6ea8fe 100%);
        color: #fff;
        border-bottom: none;
        border-radius: 0.7rem 0.7rem 0 0;
    }
    .modal-edit-client .modal-title {
        font-weight: 700;
        font-size: 1.7rem;
        letter-spacing: 0.02em;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }
    .modal-edit-client .modal-content {
        border-radius: 0.7rem;
        box-shadow: 0 8px 32px rgba(13,110,253,0.10);
        border: none;
    }
    .modal-edit-client label,
    .modal-edit-client .form-section-title {
        font-size: 1.13em;
    }
    .modal-edit-client .form-group label {
        font-weight: 600;
        color: #0d6efd;
        display: flex;
        align-items: center;
        gap: 0.4em;
    }
    .modal-edit-client .form-control {
        border-radius: 0.5em;
        border: 1.5px solid #e3eafc;
        box-shadow: 0 1px 4px rgba(13,110,253,0.04);
        transition: border 0.2s;
        font-size: 1.08em;
        padding: 0.65em 1em;
    }
    .modal-edit-client .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.15rem rgba(13,110,253,0.13);
    }
    .modal-edit-client .input-icon {
        position: absolute;
        left: 0.9em;
        top: 50%;
        transform: translateY(-50%);
        color: #6ea8fe;
        font-size: 1.1em;
        pointer-events: none;
    }
    .modal-edit-client .input-group-custom {
        position: relative;
    }
    .modal-edit-client .input-group-custom .form-control {
        padding-left: 2.2em;
    }
    .modal-edit-client .btn-primary {
        border-radius: 2em;
        font-weight: 600;
        padding: 0.5em 1.5em;
        font-size: 1.08em;
        box-shadow: 0 2px 8px rgba(13,110,253,0.10);
        transition: background 0.18s, box-shadow 0.18s;
        min-width: 170px;
    }
    .modal-edit-client .btn-primary:hover {
        background: #0b5ed7;
        box-shadow: 0 4px 16px rgba(13,110,253,0.16);
    }
    .modal-edit-client .btn-cancel {
        border-radius: 2em;
        font-weight: 600;
        padding: 0.5em 1.5em;
        font-size: 1.08em;
        background: #f8f9fa;
        color: #6c757d;
        border: 1.5px solid #dee2e6;
        margin-right: 0.7em;
        transition: background 0.18s, color 0.18s, border 0.18s;
        min-width: 140px;
    }
    .modal-edit-client .btn-cancel:hover {
        background: #e9ecef;
        color: #495057;
        border-color: #adb5bd;
    }
    .modal-edit-client .required-star {
        color: #dc3545;
        font-size: 1.1em;
        margin-left: 0.1em;
    }
    .modal-edit-client .modal-body {
        background: #f8fafc;
        border-radius: 0 0 0.7rem 0.7rem;
    }
    .modal-edit-client .form-section-title {
        font-size: 1.18em;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.7em;
        margin-top: 1.2em;
        display: flex;
        align-items: center;
        gap: 0.4em;
    }
    .modal-edit-client #addressFields {
        background: #f1f7ff;
        border-radius: 0.5em;
        padding: 1em 0.7em 0.5em 0.7em;
        margin-bottom: 1em;
        box-shadow: 0 1px 6px rgba(13,110,253,0.06);
        border: 1px solid #e3eafc;
        animation: fadeIn 0.4s;
    }
    .modal-edit-client .modal-footer {
        border-top: none;
        background: transparent;
        justify-content: center;
        padding-top: 0;
        padding-bottom: 1.5em;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px);}
        to { opacity: 1; transform: translateY(0);}
    }
</style>

@foreach($clients as $client)
    <div class="modal fade modal-edit-client" id="modalEditClient{{ $client->id }}" tabindex="-1" aria-labelledby="modalEditClientLabel{{ $client->id }}"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditClientLabel{{ $client->id }}">
                        <i class="bi bi-pencil-square"></i>
                        Editar Cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('clients.update', $client->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <div class="form-section-title">
                                        <i class="bi bi-person-badge"></i> Dados do Cliente
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group input-group-custom">
                                                <label for="name{{ $client->id }}">
                                                    <i class="bi bi-person-fill"></i>
                                                    Nome do Cliente
                                                    <span class="required-star">*</span>
                                                </label>
                                                <input type="text" name="name" id="name{{ $client->id }}" class="form-control"
                                                    placeholder="Digite o nome do cliente" value="{{ $client->name }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group input-group-custom">
                                                <label for="email{{ $client->id }}">
                                                    <i class="bi bi-envelope-at-fill"></i>
                                                    Email
                                                    <span class="text-muted">(Opcional)</span>
                                                </label>
                                                <input type="email" name="email" id="email{{ $client->id }}" class="form-control"
                                                    placeholder="Digite o email" value="{{ $client->email }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group input-group-custom">
                                                <label for="phone{{ $client->id }}">
                                                    <i class="bi bi-telephone-fill"></i>
                                                    Telefone
                                                    <span class="text-muted">(Opcional)</span>
                                                </label>
                                                <input type="text" name="phone" id="phone{{ $client->id }}" class="form-control"
                                                    placeholder="(XX) XXXXX-XXXX" value="{{ $client->phone }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group input-group-custom">
                                                <label for="cep{{ $client->id }}">
                                                    <i class="bi bi-geo-alt-fill"></i>
                                                    CEP
                                                    <span class="text-muted">(Opcional)</span>
                                                </label>
                                                <input type="text" name="cep" id="cep{{ $client->id }}" class="form-control"
                                                    placeholder="Digite o CEP" maxlength="9" onblur="searchAddressByCep()" value="{{ $client->cep }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="addressFields" style="display: block;">
                                        <div class="form-section-title">
                                            <i class="bi bi-geo-fill"></i> Endereço Completo
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group input-group-custom">
                                                    <label for="address{{ $client->id }}">
                                                        <i class="bi bi-house-door-fill"></i>
                                                        Endereço
                                                        <span class="text-muted">(Opcional)</span>
                                                    </label>
                                                    <input type="text" name="address" id="address{{ $client->id }}" class="form-control"
                                                        placeholder="Digite o endereço" value="{{ $client->address }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group input-group-custom">
                                                    <label for="city{{ $client->id }}">
                                                        <i class="bi bi-building"></i>
                                                        Cidade
                                                        <span class="text-muted">(Opcional)</span>
                                                    </label>
                                                    <input type="text" name="city" id="city{{ $client->id }}" class="form-control"
                                                        placeholder="Digite a cidade" value="{{ $client->city }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group input-group-custom">
                                                    <label for="state{{ $client->id }}">
                                                        <i class="bi bi-flag-fill"></i>
                                                        Estado
                                                        <span class="text-muted">(Opcional)</span>
                                                    </label>
                                                    <input type="text" name="state" id="state{{ $client->id }}" class="form-control"
                                                        placeholder="Digite o estado" value="{{ $client->state }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group input-group-custom">
                                                    <label for="district{{ $client->id }}">
                                                        <i class="bi bi-signpost-split-fill"></i>
                                                        Bairro
                                                        <span class="text-muted">(Opcional)</span>
                                                    </label>
                                                    <input type="text" name="district" id="district{{ $client->id }}" class="form-control"
                                                        placeholder="Digite o bairro" value="{{ $client->district }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center mt-3">
                                            <div class="modal-footer d-flex justify-content-center">
                                                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle"></i> Cancelar
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-save"></i>
                                                    Salvar Alterações
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Coluna da Direita (opcional para dados adicionais) -->
                                <!-- <div class="col-md-4"></div> -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
