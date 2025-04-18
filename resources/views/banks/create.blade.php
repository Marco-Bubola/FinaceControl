<!-- Modal para Adicionar Novo Cartão -->
<div class="modal fade custom-modal-edit" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header custom-modal-header">
                        <h5 class="modal-title text-center" id="addCardModalLabel">Adicionar Novo Cartão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <form method="POST" action="{{ route('banks.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label custom-label">Titular do Cartão</label>
                                    <input type="text" class="form-control custom-input" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="description" class="form-label custom-label">Número do Cartão</label>
                                    <input type="text" class="form-control custom-input" id="description"
                                        name="description" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label custom-label">Data de Início</label>
                                    <input type="date" class="form-control custom-input" id="start_date"
                                        name="start_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label custom-label">Data de Término</label>
                                    <input type="date" class="form-control custom-input" id="end_date" name="end_date"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center custom-modal-footer">
                                <button type="button" class="btn btn-secondary custom-btn"
                                    data-bs-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary custom-btn">Salvar Cartão</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
