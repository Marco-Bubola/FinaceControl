
@if(isset($sales))
    @foreach($sales as $sale)
        @foreach($sale->payments as $payment)
            <div class="modal fade" id="modalDeletePayment{{ $payment->id }}" tabindex="-1"
                aria-labelledby="modalDeletePaymentLabel{{ $payment->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-light border-0 rounded-top-4">
                            <div class="d-flex align-items-center w-100">
                                <i class="bi bi-cash-coin text-danger fs-1 me-3"></i>
                                <div>
                                    <h5 class="modal-title mb-1 fw-semibold text-danger" id="modalDeletePaymentLabel{{ $payment->id }}">
                                        Excluir pagamento?
                                    </h5>
                                    <small class="text-muted">Você está prestes a remover um pagamento desta venda. Tudo certo?</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p class="mb-2 fs-5">
                                Tem certeza de que deseja excluir o pagamento de
                                <strong class="text-danger">R$ {{ number_format($payment->amount_paid, 2, ',', '.') }}</strong>
                                do cliente <strong class="text-primary">{{ $sale->client->name ?? '-' }}</strong>?
                            </p>
                            <p class="mb-4">
                                Forma de pagamento:
                                <strong class="text-info">{{ $payment->payment_method }}</strong>
                                <br>
                                Data: <strong>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</strong>
                            </p>
                            <div class="alert alert-info py-2 px-3 mb-0 small rounded-pill" role="alert">
                                Não se preocupe, você pode adicionar outro pagamento a qualquer momento!
                            </div>
                        </div>
                        <div class="modal-footer border-0 justify-content-center bg-light rounded-bottom-4">
                            <form action="{{ route('sales.deletePayment', ['saleId' => $sale->id, 'paymentId' => $payment->id]) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">
                                    <i class="bi bi-trash me-1"></i> Excluir pagamento
                                </button>
                                <input type="hidden" name="from" value="index">
                            </form>
                            <button type="button" class="btn btn-primary px-4 rounded-pill ms-2" data-bs-dismiss="modal">
                                Manter pagamento
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@elseif(isset($sale) && isset($payment))
  @foreach($sale->payments as $payment)
    <div class="modal fade" id="modalDeletePayment{{ $payment->id }}" tabindex="-1"
        aria-labelledby="modalDeletePaymentLabel{{ $payment->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-light border-0 rounded-top-4">
                    <div class="d-flex align-items-center w-100">
                        <i class="bi bi-cash-coin text-danger fs-1 me-3"></i>
                        <div>
                            <h5 class="modal-title mb-1 fw-semibold text-danger" id="modalDeletePaymentLabel{{ $payment->id }}">
                                Excluir pagamento?
                            </h5>
                            <small class="text-muted">Você está prestes a remover um pagamento desta venda. Tudo certo?</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <input type="hidden" name="from" value="show">
                <div class="modal-body text-center">
                    <p class="mb-2 fs-5">
                        Tem certeza de que deseja excluir o pagamento de
                        <strong class="text-danger">R$ {{ number_format($payment->amount_paid, 2, ',', '.') }}</strong>
                        do cliente <strong class="text-primary">{{ $sale->client->name ?? '-' }}</strong>?
                    </p>
                    <p class="mb-4">
                        Forma de pagamento:
                        <strong class="text-info">{{ $payment->payment_method }}</strong>
                        <br>
                        Data: <strong>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</strong>
                    </p>
                    <div class="alert alert-info py-2 px-3 mb-0 small rounded-pill" role="alert">
                        Não se preocupe, você pode adicionar outro pagamento a qualquer momento!
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center bg-light rounded-bottom-4">
                    <form action="{{ route('sales.deletePayment', ['saleId' => $sale->id, 'paymentId' => $payment->id]) }}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">
                            <i class="bi bi-trash me-1"></i> Excluir pagamento
                        </button>
                        <input type="hidden" name="from" value="show">
                    </form>
                    <button type="button" class="btn btn-primary px-4 rounded-pill ms-2" data-bs-dismiss="modal">
                        Manter pagamento
                    </button>
                </div>
            </div>
        </div>
    </div>
       @endforeach
@endif
