
    <!-- Modal para Adicionar Transferência -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionModalLabel">Adicionar Nova Transferência</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('invoices.store') }}">
                        @csrf
                        <!-- Campo oculto para enviar o id_bank -->
                        <input type="hidden" name="id_bank" value="{{ $bank->id_bank }}">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Descrição</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" required>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">Valor</label>
                                <input type="number" class="form-control @error('value') is-invalid @enderror" id="value"
                                    name="value" step="0.01" required>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="installments" class="form-label">Parcelas</label>
                                <input type="number" class="form-control @error('installments') is-invalid @enderror"
                                    id="installments" name="installments" required>
                                @error('installments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Categoria</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="" disabled selected>Selecione uma categoria</option>
                                    @forelse ($categories as $category)
                                        <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                    @empty
                                        <option value="" disabled>Nenhuma categoria disponível</option>
                                    @endforelse
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="invoice_date" class="form-label">Data da Transferência</label>
                                <input type="date" class="form-control @error('invoice_date') is-invalid @enderror"
                                    id="invoice_date" name="invoice_date" required>
                                @error('invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar Transferência</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
