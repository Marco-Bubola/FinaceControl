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
                    <form action="{{ route('clients.store') }}" method="POST" id="clientForm">
                        @csrf
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
                                                placeholder="Digite o nome do cliente" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="col-form-label text-center">Email <span
                                                    class="text-muted">(Opcional)</span></label>
                                            <input type="email" name="email" id="email" class="form-control"
                                                placeholder="Digite o email"
                                                pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
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
                                                placeholder="(XX) XXXXX-XXXX">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cep" class="col-form-label text-center">CEP <span
                                                    class="text-muted">(Opcional)</span></label>
                                            <input type="text" name="cep" id="cep" class="form-control"
                                                placeholder="Digite o CEP" maxlength="9" onblur="searchAddressByCep()">
                                        </div>
                                    </div>
                                </div>

                                <!-- Linha 3: Endereço Completo (Aparece somente após digitar o CEP) -->
                                <div id="addressFields" style="display: none;">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address" class="col-form-label text-center">Endereço <span
                                                        class="text-muted">(Opcional)</span></label>
                                                <input type="text" name="address" id="address" class="form-control"
                                                    placeholder="Digite o endereço">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="city" class="col-form-label text-center">Cidade <span
                                                        class="text-muted">(Opcional)</span></label>
                                                <input type="text" name="city" id="city" class="form-control"
                                                    placeholder="Digite a cidade">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="state" class="col-form-label text-center">Estado <span
                                                        class="text-muted">(Opcional)</span></label>
                                                <input type="text" name="state" id="state" class="form-control"
                                                    placeholder="Digite o estado">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="district" class="col-form-label text-center">Bairro <span
                                                        class="text-muted">(Opcional)</span></label>
                                                <input type="text" name="district" id="district" class="form-control"
                                                    placeholder="Digite o bairro">
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
                                <button type="submit" class="btn btn-primary">Adicionar Cliente</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
