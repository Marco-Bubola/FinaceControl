
@foreach($sales as $sale)
        <!-- Modal para adicionar pagamento -->
        <div class="modal fade" id="paymentModal{{ $sale->id }}" tabindex="-1" aria-labelledby="paymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Adicionar Pagamento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('sales.addPayment', $sale->id) }}" method="POST">
                        @csrf
                        <!-- Adicionando o campo oculto 'from' -->
                        <input type="hidden" name="from" value="index">
                        <div class="modal-body">
                            <div id="paymentFields">
                                <div class="payment-item mb-3">
                                    <label for="paymentAmount" class="form-label">Valor do Pagamento</label>
                                    <input type="number" step="0.01" class="form-control" name="amount_paid[]" required min="0">
                                </div>
                                <div class="payment-item mb-3">
                                    <label for="paymentMethod" class="form-label">Forma de Pagamento</label>
                                    <select class="form-control" name="payment_method[]" required>
                                        <option value="Dinheiro">Dinheiro</option>
                                        <option value="Cartão de Crédito">Cartão de Crédito</option>
                                        <option value="Cartão de Débito">Cartão de Débito</option>
                                        <option value="PIX">PIX</option>
                                        <option value="Boleto">Boleto</option>
                                    </select>
                                </div>
                                <div class="payment-item mb-3">
                                    <label for="paymentDate" class="form-label">Data do Pagamento</label>
                                    <input type="date" class="form-control" name="payment_date[]" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-info" id="addPaymentField">Adicionar Pagamento</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Confirmar Pagamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
