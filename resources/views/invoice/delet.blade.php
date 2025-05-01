 <!-- Modal de Exclusão -->
 <div class="modal fade" id="deleteInvoiceModal{{ $invoice->id_invoice }}" tabindex="-1"
                aria-labelledby="deleteInvoiceModalLabel{{ $invoice->id_invoice }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteInvoiceModalLabel{{ $invoice->id_invoice }}">Excluir Transação
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza de que deseja excluir a transação <strong>{{ $invoice->description }}</strong> no
                                valor de <strong>R$ {{ number_format($invoice->value, 2) }}</strong>?</p>
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