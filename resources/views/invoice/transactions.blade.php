@forelse ($eventsGroupedByMonth as $month => $monthlyInvoices)
<h6 class="text-uppercase text-body text-xs font-weight-bolder mt-4 custom-month-title">{{ $month }}</h6>
<ul class="list-group custom-transaction-list">
    @foreach ($monthlyInvoices as $invoice)
    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg custom-transaction-item">
        <div class="d-flex align-items-center">
            <button
                class="btn btn-icon-only btn-rounded {{ $invoice->value < 0 ? 'btn-outline-danger' : 'btn-outline-success' }} mb-0 me-3 btn-sm d-flex align-items-center justify-content-center custom-transaction-icon">
                <i class="{{ $invoice->value < 0 ? 'fas fa-arrow-down' : 'fas fa-arrow-up' }}"></i>
            </button>
            <div class="d-flex flex-column custom-transaction-details">
                <h6 class="mb-1 text-dark text-sm custom-transaction-description">{{ $invoice->description }}</h6>
                <span class="text-xs custom-transaction-category">Categoria: {{ $invoice->category->name }}</span>
                <span class="text-xs custom-transaction-installments">Parcelas: {{ $invoice->installments }}</span>
                <span class="text-xs custom-transaction-date">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editInvoiceModal{{ $invoice->id_invoice }}">
                <i class="fas fa-edit"></i> <!-- Ícone de edição -->
            </button>
            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteInvoiceModal{{ $invoice->id_invoice }}">
                <i class="fas fa-trash"></i> <!-- Ícone de exclusão -->
            </button>
            <div
            class="d-flex align-items-center {{ $invoice->value < 0 ? 'text-danger text-gradient' : 'text-success text-gradient' }} text-sm font-weight-bold custom-transaction-value">
            {{ $invoice->value < 0 ? '-' : '+' }} R$ {{ number_format(abs($invoice->value), 2) }}
            </div>
        </div>
    </li>

    <!-- Modal de Edição -->
    <div class="modal fade" id="editInvoiceModal{{ $invoice->id_invoice }}" tabindex="-1" aria-labelledby="editInvoiceModalLabel{{ $invoice->id_invoice }}" aria-hidden="true">
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
                                <input type="text" class="form-control" id="description{{ $invoice->id_invoice }}" name="description" value="{{ $invoice->description }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="value{{ $invoice->id_invoice }}" class="form-label">Valor</label>
                                <input type="number" class="form-control" id="value{{ $invoice->id_invoice }}" name="value" value="{{ $invoice->value }}" step="0.01" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="installments{{ $invoice->id_invoice }}" class="form-label">Parcelas</label>
                                <input type="number" class="form-control" id="installments{{ $invoice->id_invoice }}" name="installments" value="{{ $invoice->installments }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id{{ $invoice->id_invoice }}" class="form-label">Categoria</label>
                                <select class="form-control" id="category_id{{ $invoice->id_invoice }}" name="category_id" required>
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
                                <label for="invoice_date{{ $invoice->id_invoice }}" class="form-label">Data da Transferência</label>
                                <input type="date" class="form-control" id="invoice_date{{ $invoice->id_invoice }}" name="invoice_date" value="{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}" required>
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

    <!-- Modal de Exclusão -->
    <div class="modal fade" id="deleteInvoiceModal{{ $invoice->id_invoice }}" tabindex="-1" aria-labelledby="deleteInvoiceModalLabel{{ $invoice->id_invoice }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteInvoiceModalLabel{{ $invoice->id_invoice }}">Excluir Transação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir a transação <strong>{{ $invoice->description }}</strong> no valor de <strong>R$ {{ number_format($invoice->value, 2) }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('invoices.destroy', $invoice->id_invoice) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</ul>
@empty
<p class="text-muted custom-no-transactions">Nenhuma transferência encontrada neste mês.</p>
@endforelse
