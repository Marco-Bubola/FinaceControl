<!-- Modal de Editar Cliente -->
@foreach($clients as $client)
    <div class="modal fade" id="modalEditClient{{ $client->id }}" tabindex="-1" aria-labelledby="modalEditClientLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- Modal Ampliado -->
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
                                <div class="col-md-12 text-center">
                                    <!-- Linha 1: Nome do Cliente e Email -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="col-form-label text-center">Nome do Cliente <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    placeholder="Digite o nome do cliente" value="{{ $client->name }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="col-form-label text-center">Email <span
                                                        class="text-muted">(Opcional)</span></label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    placeholder="Digite o email" value="{{ $client->email }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Linha 2: Telefone e Endereço -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone" class="col-form-label text-center">Telefone <span
                                                        class="text-muted">(Opcional)</span></label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    placeholder="(XX) XXXXX-XXXX" value="{{ $client->phone }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cep" class="col-form-label text-center">CEP <span
                                                        class="text-muted">(Opcional)</span></label>
                                                <input type="text" name="cep" id="cep" class="form-control"
                                                    placeholder="Digite o CEP" maxlength="9" onblur="searchAddressByCep()"
                                                    value="{{ $client->cep }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Linha 3: Endereço Completo -->
                                    <div id="addressFields" style="display: block;">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address" class="col-form-label text-center">Endereço <span
                                                            class="text-muted">(Opcional)</span></label>
                                                    <input type="text" name="address" id="address" class="form-control"
                                                        placeholder="Digite o endereço" value="{{ $client->address }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="city" class="col-form-label text-center">Cidade <span
                                                            class="text-muted">(Opcional)</span></label>
                                                    <input type="text" name="city" id="city" class="form-control"
                                                        placeholder="Digite a cidade" value="{{ $client->city }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="state" class="col-form-label text-center">Estado <span
                                                            class="text-muted">(Opcional)</span></label>
                                                    <input type="text" name="state" id="state" class="form-control"
                                                        placeholder="Digite o estado" value="{{ $client->state }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="district" class="col-form-label text-center">Bairro <span
                                                            class="text-muted">(Opcional)</span></label>
                                                    <input type="text" name="district" id="district" class="form-control"
                                                        placeholder="Digite o bairro" value="{{ $client->district }}">
                                                </div>
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
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<style>/* Personalização do Modal */
.modal-content {
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Bloco de informações */
.info-block {
    background-color: #f7f8fa;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center; /* Centralizar texto */
}

/* Efeito de hover no bloco de informações */
.info-block:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Ícones dentro dos blocos */
.info-block i {
    font-size: 1.5rem;
    margin-right: 10px;
}

/* Títulos dentro dos blocos */
.info-block h6 {
    font-size: 1.2rem;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 5px;
}

/* Spans dentro dos blocos */
.info-block span {
    font-size: 1rem;
    color: #333;
    display: block;
}
</style>
