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
        <div
            class="d-flex align-items-center {{ $invoice->value < 0 ? 'text-danger text-gradient' : 'text-success text-gradient' }} text-sm font-weight-bold custom-transaction-value">
            {{ $invoice->value < 0 ? '-' : '+' }} R$ {{ number_format(abs($invoice->value), 2) }}
        </div>
    </li>
    @endforeach
</ul>
@empty
<p class="text-muted custom-no-transactions">Nenhuma transferência encontrada neste mês.</p>
@endforelse
