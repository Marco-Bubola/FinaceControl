
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

 
@endforelse
