@foreach($sales as $sale)
    @foreach($sale->payments as $payment)
        <div class="modal fade" id="modalDeletePayment{{ $payment->id }}" tabindex="-1"
            aria-labelledby="modalDeletePaymentLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletePaymentLabel">Confirmar Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza de que deseja excluir este pagamento de R$
                            {{ number_format($payment->amount_paid, 2, ',', '.') }}?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('sales.deletePayment', ['saleId' => $sale->id, 'paymentId' => $payment->id]) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach