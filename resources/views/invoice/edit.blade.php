<!-- Modal de Edição -->
<div class="modal fade" id="editInvoiceModal{{ $invoice->id_invoice }}" tabindex="-1"
                aria-labelledby="editInvoiceModalLabel{{ $invoice->id_invoice }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editInvoiceModalLabel{{ $invoice->id_invoice }}">Editar Transação</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('invoices.update', $invoice->id_invoice) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="description{{ $invoice->id_invoice }}" class="form-label">Descrição</label>
                                        <input type="text" class="form-control" id="description{{ $invoice->id_invoice }}"
                                            name="description" value="{{ $invoice->description }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="value{{ $invoice->id_invoice }}" class="form-label">Valor</label>
                                        <input type="number" class="form-control" id="value{{ $invoice->id_invoice }}"
                                            name="value" value="{{ $invoice->value }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="installments{{ $invoice->id_invoice }}" class="form-label">Parcelas</label>
                                        <input type="text" class="form-control" id="installments{{ $invoice->id_invoice }}"
                                            name="installments" value="{{ $invoice->installments }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id{{ $invoice->id_invoice }}" class="form-label">Categoria</label>
                                        <select class="form-control" id="category_id{{ $invoice->id_invoice }}"
                                            name="category_id" required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id_category }}" {{ $invoice->category_id == $category->id_category ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="invoice_date{{ $invoice->id_invoice }}" class="form-label">Data da
                                            Transferência</label>
                                        <input type="date" class="form-control" id="invoice_date{{ $invoice->id_invoice }}"
                                            name="invoice_date"
                                            value="{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>