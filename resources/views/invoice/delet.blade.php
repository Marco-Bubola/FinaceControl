<!-- Modal de Exclusão -->
<div class="modal fade" id="deleteInvoiceModal{{ $invoice->id_invoice }}" tabindex="-1"
    aria-labelledby="deleteInvoiceModalLabel{{ $invoice->id_invoice }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-0 rounded-top-4">
                <div class="d-flex align-items-center w-100">
                    <i class="bi bi-receipt text-info fs-1 me-3"></i>
                    <div>
                        <h5 class="modal-title mb-1 fw-semibold text-info" id="deleteInvoiceModalLabel{{ $invoice->id_invoice }}">
                            Excluir transação?
                        </h5>
                        <small class="text-muted">Você está prestes a remover uma transação. Tudo certo?</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2 fs-5">
                    Tem certeza de que deseja excluir a transação
                    <strong class="text-info">{{ $invoice->description }}</strong>?
                </p>
                <p class="mb-4">
                    Valor da transação:
                    <strong class="text-success">R$ {{ number_format($invoice->value, 2, ',', '.') }}</strong>
                </p>
                <div class="alert alert-info py-2 px-3 mb-0 small rounded-pill" role="alert">
                    Não se preocupe, você pode cadastrar uma nova transação a qualquer momento!
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center bg-light rounded-bottom-4">
                <form method="POST" action="{{ route('invoices.destroy', $invoice->id_invoice) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-primary px-4 rounded-pill ms-2" data-bs-dismiss="modal">
                        Manter transação
                    </button>
                    <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">
                        <i class="bi bi-trash me-1"></i> Excluir transação
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
