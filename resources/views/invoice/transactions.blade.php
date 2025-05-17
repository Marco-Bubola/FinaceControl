
@forelse ($eventsGroupedByMonth as $month => $monthlyInvoices)
    <div class="invoice-month-section mb-4 p-2" id="invoice-month-section-{{ $month }}">
        <div class="row g-2">
            @foreach ($monthlyInvoices as $idx => $invoice)
                <div class="col-md-4 {{ $idx >= 18 ? 'd-none extra-invoice-'.$month : '' }}">
                    <!-- Card de transação moderno e contido -->
                    <div class="modern-transaction-card shadow-sm border-1 h-100 d-flex flex-column">
                        <div class="modern-card-header d-flex align-items-center justify-content-between flex-wrap" style="background: {{ $invoice->category->hexcolor_category }}10;">
                            <div class="d-flex align-items-center gap-2 flex-shrink-1">
                                <div class="icon-circle flex-shrink-0" style="background: {{ $invoice->category->hexcolor_category }};">
                                    <i class="{{ $invoice->category->icone }}"></i>
                                </div>
                                <div class="modern-card-category text-truncate" style="max-width: 110px;">
                                    <span class="fw-bold" >
                                        {{ $invoice->category->name }}
                                    </span>
                                </div>
                            </div>
                            <div class="modern-card-actions-header d-flex gap-1 flex-shrink-0 mt-1 mt-md-0">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editInvoiceModal{{ $invoice->id_invoice }}" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteInvoiceModal{{ $invoice->id_invoice }}" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-outline-success" data-bs-toggle="modal"
                                    data-bs-target="#copyInvoiceModal{{ $invoice->id_invoice }}" title="Duplicar">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="modern-card-body flex-grow-1 d-flex flex-column justify-content-between">
                            <div>
                                <div class="modern-card-title text-truncate" title="{{ $invoice->description }}">{{ $invoice->description }}</div>
                                <div class="modern-card-details-badges d-flex gap-2 mb-1 flex-wrap">
                                    <span class="badge badge-date d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar2-week"></i>
                                        {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                    </span>
                                    <span class="badge badge-installments d-flex align-items-center gap-1">
                                        <i class="bi bi-layers"></i>
                                        {{ $invoice->installments }}x
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="modern-card-value {{ $invoice->value < 0 ? 'negative' : 'positive' }}">
                                    {{ $invoice->value < 0 ? '-' : '+' }} R$ {{ number_format(abs($invoice->value), 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('invoice.edit', ['invoice' => $invoice, 'clients' => $clients])
                @include('invoice.delet')
                @include('invoice.copy')
            @endforeach
        </div>
        @if(count($monthlyInvoices) > 18)
            <div class="text-center mt-3">
                <button class="btn btn-link p-0 show-more-invoices-btn"
                        type="button"
                        data-month="{{ $month }}"
                        id="show-more-invoices-btn-{{ $month }}">
                    <span class="show-more-label"><i class="fas fa-chevron-down"></i> Mostrar tudo ({{ count($monthlyInvoices) - 18 }})</span>
                    <span class="show-less-label d-none"><i class="fas fa-chevron-up"></i> Mostrar menos</span>
                </button>
            </div>
        @endif
    </div>
@empty
    <div class="no-transactions-card my-4 d-flex flex-column align-items-center justify-content-center">
        <div class="no-transactions-icon mb-2">
            <i class="bi bi-emoji-frown"></i>
        </div>
        <div class="no-transactions-text text-secondary fw-semibold">
            Nenhuma transação encontrada neste mês.
        </div>
    </div>

    <style>

    .no-transactions-card {
        background: #f8fafc;
        border: 1.5px solid #e3e8ef;
        border-radius: 1.2em;
        min-height: 120px;
        padding: 2em 1em 1.5em 1em;
        box-shadow: 0 1px 8px rgba(13,110,253,0.06);
        max-width: 420px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }
    .no-transactions-icon {
        font-size: 2.5em;
        color: #b6d4fe;
        line-height: 1;
    }
    .no-transactions-text {
        font-size: 1.13em;
        letter-spacing: 0.01em;
    }
    </style>
@endforelse
<style>
.invoice-month-section {
    background: #f8fafc;
    border-radius: 1.2em;
    padding: 1.1em 0.7em 0.7em 0.7em;
    margin-bottom: 2.5em;
    transition: box-shadow 0.2s;
    overflow: hidden;
}
.show-more-invoices-btn {
    background: linear-gradient(90deg, #e7f1ff 60%, #f8fafc 100%);
    color: #0d6efd;
    border: none;
    border-radius: 2em;
    padding: 0.7em 1.7em;
    font-weight: 600;
    font-size: 1.07em;
    box-shadow: 0 2px 10px rgba(13,110,253,0.07);
    transition: background 0.18s, color 0.18s, box-shadow 0.18s;
    outline: none;
    margin: 0 auto;
    display: inline-block;
    cursor: pointer;
    text-decoration: none !important;
}
.show-more-invoices-btn:hover, .show-more-invoices-btn:focus {
    background: linear-gradient(90deg, #d0e6ff 60%, #e7f1ff 100%);
    color: #084298;
    box-shadow: 0 4px 18px rgba(13,110,253,0.13);
    text-decoration: none !important;
}
.show-more-invoices-btn i {
    font-size: 1.18em;
    vertical-align: middle;
    transition: transform 0.25s;
}
.show-more-invoices-btn .show-less-label i {
    transform: rotate(180deg);
}


/* Card moderno de transação compacto */
.modern-transaction-card {
    border-radius: 1.2em;
    background: #fff;
    box-shadow: 0 1px 8px rgba(13,110,253,0.08);
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.18s, transform 0.18s;
    position: relative;
    overflow: hidden;
    height: 100%;
}
.modern-transaction-card:hover {
    box-shadow: 0 8px 24px rgba(13,110,253,0.13);
    transform: translateY(-2px) scale(1.02);
}
.modern-card-header {
    display: flex;
    align-items: center;
    gap: 0.7em;
    padding: 0.7em 1em 0.5em 1em;
    border-top-left-radius: 1.2em;
    border-top-right-radius: 1.2em;
    min-height: 0;
    justify-content: space-between;
    flex-wrap: wrap;
}
.icon-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25em;
    color: #fff;
    box-shadow: 0 1px 8px rgba(13,110,253,0.10);
    flex-shrink: 0;
}
.modern-card-category {
    font-size: 1em;
    font-weight: 500;
    letter-spacing: 0.01em;
    max-width: 90px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.modern-card-actions-header .btn {
    border-radius: 50%;
    width: 30px;
    height: 30px;
    padding: 0;
    font-size: 1em;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
    margin-left: 0.08em;
    margin-right: 0.08em;
}
.modern-card-actions-header .btn-outline-primary:hover {
    background: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}
.modern-card-actions-header .btn-outline-danger:hover {
    background: #dc3545;
    color: #fff;
    border-color: #dc3545;
}
.modern-card-actions-header .btn-outline-success:hover {
    background: #198754;
    color: #fff;
    border-color: #198754;
}
.modern-card-body {
    padding: 0.5em 1em 0.7em 1em;
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 0.2em;
    min-width: 0;
}
.modern-card-title {
    font-size: 1em;
    font-weight: 700;
    color: #22223b;
    margin-bottom: 0.1em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.modern-card-details-badges {
    margin-bottom: 0.1em;
    flex-wrap: wrap;
}
.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.2em;
    font-size: 0.93em;
    font-weight: 500;
    border-radius: 0.7em;
    padding: 0.25em 0.7em;
    background: #f1f3f5;
    color: #495057;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
}
.badge-date {
    background: #e7f1ff;
    color: #0d6efd;
}
.badge-installments {
    background: #eafbee;
    color: #198754;
}
.modern-card-value {
    font-size: 1.08em;
    font-weight: 800;
    margin-top: 0.1em;
    letter-spacing: 0.01em;
    padding: 0.15em 0;
    border-radius: 0.5em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.modern-card-value.positive {
    color: #198754;
    background: linear-gradient(90deg, #eafbee 60%, #fff 100%);
}
.modern-card-value.negative {
    color: #dc3545;
    background: linear-gradient(90deg, #fdeaea 60%, #fff 100%);
}

/* Botão mostrar mais/menos */
.show-more-invoices-btn {
    color: #0d6efd;
    font-weight: 600;
    font-size: 1em;
    background: none;
    border: none;
    transition: color 0.15s;
}
.show-more-invoices-btn:hover {
    color: #084298;
    text-decoration: underline;
}

/* Responsividade */
@media (max-width: 991px) {
    .invoice-month-section {
        padding: 0.7em 0.2em 0.5em 0.2em;
    }
    .modern-card-header, .modern-card-body {
        padding-left: 0.5em;
        padding-right: 0.5em;
    }
    .modern-card-category {
        max-width: 60px;
    }
}
@media (max-width: 575px) {
    .modern-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.3em;
        padding: 0.5em 0.5em 0.3em 0.5em;
    }
    .modern-card-actions-header {
        margin-top: 0.3em;
    }
    .modern-card-category {
        max-width: 100px;
    }
}
</style>
