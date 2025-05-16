@if(isset($sales))
@foreach($sales as $sale)
    @foreach($sale->payments as $payment)
        <div class="modal fade" id="editPaymentModal{{ $payment->id }}" tabindex="-1"
            aria-labelledby="editPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPaymentModalLabel{{ $payment->id }}">Editar Pagamento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('sales.updatePayment', ['saleId' => $sale->id, 'paymentId' => $payment->id]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Adicionando o campo oculto 'from' -->
                        <input type="hidden" name="from" value="index">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="paymentAmount" class="form-label">Valor do Pagamento</label>
                                <input type="number" step="0.01" class="form-control" name="amount_paid"
                                    value="{{ $payment->amount_paid }}" required min="0">
                            </div>
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Forma de Pagamento</label>
                                <select class="form-control" name="payment_method" required>
                                    <option value="Dinheiro" {{ $payment->payment_method == 'Dinheiro' ? 'selected' : '' }}>
                                        Dinheiro</option>
                                    <option value="Cartão de Crédito" {{ $payment->payment_method == 'Cartão de Crédito' ? 'selected' : '' }}>Cartão de
                                        Crédito</option>
                                    <option value="Cartão de Débito" {{ $payment->payment_method == 'Cartão de Débito' ? 'selected' : '' }}>Cartão de Débito
                                    </option>
                                    <option value="PIX" {{ $payment->payment_method == 'PIX' ? 'selected' : '' }}>PIX</option>
                                    <option value="Boleto" {{ $payment->payment_method == 'Boleto' ? 'selected' : '' }}>Boleto
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="paymentDate" class="form-label">Data do Pagamento</label>
                                <input type="date" class="form-control" name="payment_date"
                                    value="{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Confirmar Alteração</button>
                        </div>
                        <input type="hidden" name="from" value="index">
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endforeach
@endif

@if(isset($sale) && isset($payment))
@foreach($sale->payments as $payment)
    <div class="modal fade" id="editPaymentModal{{ $payment->id }}" tabindex="-1"
        aria-labelledby="editPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentModalLabel{{ $payment->id }}">Editar Pagamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sales.updatePayment', ['saleId' => $sale->id, 'paymentId' => $payment->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="from" value="index">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Valor do Pagamento</label>
                            <input type="number" step="0.01" class="form-control" name="amount_paid"
                                value="{{ $payment->amount_paid }}" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Forma de Pagamento</label>
                            <select class="form-control" name="payment_method" required>
                                <option value="Dinheiro" {{ $payment->payment_method == 'Dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                                <option value="Cartão de Crédito" {{ $payment->payment_method == 'Cartão de Crédito' ? 'selected' : '' }}>Cartão de Crédito</option>
                                <option value="Cartão de Débito" {{ $payment->payment_method == 'Cartão de Débito' ? 'selected' : '' }}>Cartão de Débito</option>
                                <option value="PIX" {{ $payment->payment_method == 'PIX' ? 'selected' : '' }}>PIX</option>
                                <option value="Boleto" {{ $payment->payment_method == 'Boleto' ? 'selected' : '' }}>Boleto</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data do Pagamento</label>
                            <input type="date" class="form-control" name="payment_date"
                                value="{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Confirmar Alteração</button>
                    </div>
                    <input type="hidden" name="from" value="show">
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif
