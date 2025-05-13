<!-- Modal de Histórico de Pagamentos -->
<div class="modal fade" id="paymentHistoryModal{{ $sale->id }}" tabindex="-1" aria-labelledby="paymentHistoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content animate__animated animate__fadeInDown">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="paymentHistoryModalLabel">
                    <i class="fas fa-history me-2"></i>Histórico de Pagamentos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @forelse($sale->payments as $payment)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0 h-100 animate__animated animate__fadeInUp">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title text-success mb-0">
                                        <i class="fas fa-coins me-2"></i>
                                        R$ {{ number_format($payment->amount_paid, 2, ',', '.') }}
                                    </h5>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-warning   p-1" data-bs-toggle="modal"
                                            data-bs-target="#editPaymentModal{{ $payment->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            </svg>
                                        </button>

                                        <!-- Botão de Excluir Pagamento -->
                                        <button type="button" class="btn btn-danger   p-1" data-bs-toggle="modal"
                                            data-bs-target="#modalDeletePayment{{ $payment->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                <path
                                                    d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <p class="card-text mb-1">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                    {{ $payment->created_at->format('d/m/Y H:i') }}
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-credit-card me-2 text-warning"></i>
                                    {{ $payment->payment_method }}
                                </p>
                            </div>

                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted">
                        <i class="fas fa-exclamation-circle me-2"></i> Nenhum pagamento registrado.
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>