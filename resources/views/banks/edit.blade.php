<!-- Modal para Editar Cartão -->
<div class="modal fade" id="editBankModal{{ $bank->id_bank }}" tabindex="-1"
    aria-labelledby="editBankModalLabel{{ $bank->id_bank }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title text-center" id="editBankModalLabel{{ $bank->id_bank }}">
                    Editar Cartão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body custom-modal-body">
                <form method="POST" action="{{ route('banks.update', $bank->id_bank) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3"> <label for="name{{ $bank->id_bank }}"
                                class="form-label custom-label">Titular do
                                Cartão</label>
                            <input type="text" class="form-control custom-input" id="name{{ $bank->id_bank }}"
                                name="name" value="{{ $bank->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3"> <label for="description{{ $bank->id_bank }}"
                                class="form-label custom-label">Número do Cartão</label>
                            <input type="text" class="form-control custom-input" id="description{{ $bank->id_bank }}"
                                name="description" value="{{ $bank->description }}" required>
                        </div>


                        <div class="col-md-6 mb-3"> <label for="start_date{{ $bank->id_bank }}"
                                class="form-label custom-label">Data de Início</label>
                            <input type="date" class="form-control custom-input" id="start_date{{ $bank->id_bank }}"
                                name="start_date" value="{{ $bank->start_date }}" required>
                        </div>
                        <div class="col-md-6 mb-3"> <label for="end_date{{ $bank->id_bank }}"
                                class="form-label custom-label">Data de Término</label>
                            <input type="date" class="form-control custom-input" id="end_date{{ $bank->id_bank }}"
                                name="end_date" value="{{ $bank->end_date }}" required>
                        </div>
                        <div class="modal-footer justify-content-center custom-modal-footer">
                            <button type="button" class="btn btn-secondary custom-btn"
                                data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary custom-btn">Salvar
                                Alterações</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
