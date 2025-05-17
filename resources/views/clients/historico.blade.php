
<!-- Modal de Histórico Completo Moderno -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .modal-history-client .modal-header {
        background: linear-gradient(90deg, #0d6efd 60%, #6ea8fe 100%);
        color: #fff;
        border-bottom: none;
        border-radius: 0.7rem 0.7rem 0 0;
    }
    .modal-history-client .modal-title {
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: 0.02em;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }
    .modal-history-client .modal-content {
        border-radius: 0.7rem;
        box-shadow: 0 8px 32px rgba(13,110,253,0.10);
        border: none;
    }
    .modal-history-client .modal-body {
        background: #f8fafc;
        border-radius: 0 0 0.7rem 0.7rem;
        padding: 2em 1.2em 1.2em 1.2em;
    }
    .modal-history-client .sale-card {
        background: #fff;
        border-radius: 0.7em;
        box-shadow: 0 2px 12px rgba(13,110,253,0.07);
        padding: 1.1em 1.2em;
        margin-bottom: 1.1em;
        display: flex;
        align-items: center;
        gap: 1.2em;
        transition: box-shadow 0.18s;
        border-left: 6px solid #e3eafc;
        position: relative;
    }
    .modal-history-client .sale-card:last-child {
        margin-bottom: 0;
    }
    .modal-history-client .sale-icon {
        font-size: 2.1em;
        color: #0d6efd;
        background: #e7f1ff;
        border-radius: 50%;
        padding: 0.35em;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.7em;
        min-height: 2.7em;
    }
    .modal-history-client .sale-info {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        gap: 0.15em;
    }
    .modal-history-client .sale-id-date {
        font-size: 1.08em;
        font-weight: 600;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 0.7em;
    }
    .modal-history-client .sale-total {
        font-size: 1.13em;
        font-weight: 700;
        color: #198754;
        margin-top: 0.1em;
    }
    .modal-history-client .sale-status {
        display: flex;
        align-items: center;
        gap: 0.4em;
        font-size: 1.08em;
        font-weight: 600;
        margin-top: 0.1em;
    }
    .modal-history-client .status-paid {
        color: #198754;
        background: #eafbee;
        border-radius: 1.2em;
        padding: 0.25em 1em;
        display: flex;
        align-items: center;
        gap: 0.3em;
    }
    .modal-history-client .status-pending {
        color: #dc3545;
        background: #fdeaea;
        border-radius: 1.2em;
        padding: 0.25em 1em;
        display: flex;
        align-items: center;
        gap: 0.3em;
    }
    .modal-history-client .sale-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5em;
        align-items: flex-end;
        min-width: 120px;
    }
    .modal-history-client .btn-details {
        border-radius: 2em;
        font-weight: 600;
        padding: 0.4em 1.2em;
        font-size: 1em;
        background: #0d6efd;
        color: #fff;
        border: none;
        transition: background 0.18s, box-shadow 0.18s;
        box-shadow: 0 2px 8px rgba(13,110,253,0.10);
        display: flex;
        align-items: center;
        gap: 0.4em;
    }
    .modal-history-client .btn-details:hover {
        background: #0b5ed7;
        color: #fff;
    }
    .modal-history-client .modal-footer {
        border-top: none;
        background: transparent;
        justify-content: center;
        padding-top: 0;
        padding-bottom: 1.5em;
    }
    @media (max-width: 600px) {
        .modal-history-client .sale-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.7em;
            padding: 1em 0.7em;
        }
        .modal-history-client .sale-actions {
            width: 100%;
            align-items: stretch;
        }
    }
</style>

<div class="modal fade modal-history-client" id="modalFullHistory{{ $client->id }}" tabindex="-1"
    aria-labelledby="modalFullHistoryLabel{{ $client->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFullHistoryLabel{{ $client->id }}">
                    <i class="bi bi-clock-history"></i>
                    Histórico Completo de Vendas - {{ $client->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @forelse($client->sales as $sale)
                    <div class="sale-card">
                        <div class="sale-icon">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div class="sale-info">
                            <div class="sale-id-date">
                                <span><i class="bi bi-hash"></i> #{{ $sale->id }}</span>
                                <span><i class="bi bi-calendar-event"></i> {{ $sale->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="sale-total">
                                <i class="bi bi-currency-dollar"></i>
                                R$ {{ number_format($sale->total_price, 2, ',', '.') }}
                            </div>
                            <div class="sale-status">
                                @if($sale->status == 'Paga' || $sale->status == 'pago')
                                    <span class="status-paid">
                                        <i class="bi bi-check-circle-fill"></i> Pago
                                    </span>
                                @else
                                    <span class="status-pending">
                                        <i class="bi bi-exclamation-circle-fill"></i> Pendente
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="sale-actions">
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-details">
                                <i class="bi bi-eye"></i> Ver Detalhes
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-emoji-frown" style="font-size:2em;"></i><br>
                        Nenhuma venda encontrada para este cliente.
                    </div>
                @endforelse
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>
