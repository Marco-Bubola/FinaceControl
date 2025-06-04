@php
    $categoriesGroup = $eventsGroupedByMonthAndCategory['current'] ?? [];
@endphp

@if(count($categoriesGroup))
    <div class="invoice-month-section my-4 p-2" id="invoice-month-section-current">
      
        <div class="row g-3">
            @foreach ($categoriesGroup as $categoryId => $categoryInvoices)
                @php
                    $category = $categories->firstWhere('id_category', $categoryId);
                    $categoryTotal = collect($categoryInvoices)->sum('value');
                @endphp
                <div class="col-12">
                    <div class="card category-card shadow-sm mb-2" data-category="current-{{ $categoryId }}">
                        <div class="card-header d-flex align-items-center justify-content-between category-card-header" style="cursor:pointer; background: {{ $category->hexcolor_category ?? '#f0f0f0' }}10;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="icon-circle" style="background: {{ $category->hexcolor_category ?? '#ccc' }};">
                                    <i class="{{ $category->icone ?? 'bi bi-tag' }}"></i>
                                </span>
                                <span class="fw-bold">{{ $category->name ?? 'Sem Categoria' }}</span>
                            </div>
                            <div class="fw-bold text-success">
                                R$ {{ number_format($categoryTotal, 2) }}
                                <i class="fas fa-chevron-down ms-2 toggle-icon"></i>
                            </div>
                        </div>
                        <div class="card-body invoices-list d-none">
                            <div class="row g-2">
                                @foreach ($categoryInvoices as $invoice)
                                    <div class="col-md-4">
                                        <div class="modern-transaction-card shadow-sm border-1 h-100 d-flex flex-column">
                                            <div class="modern-card-header d-flex align-items-center justify-content-between flex-wrap" style="background: {{ optional($invoice->category)->hexcolor_category ?? '#f0f0f0' }}10;">
                                                <div class="d-flex align-items-center gap-2 flex-shrink-1">
                                                    <div class="icon-circle flex-shrink-0" style="background: {{ optional($invoice->category)->hexcolor_category ?? '#ccc' }};">
                                                        <i class="{{ optional($invoice->category)->icone ?? 'bi bi-tag' }}"></i>
                                                    </div>
                                                    <div class="modern-card-category text-truncate" style="max-width: 110px;">
                                                        <span class="fw-bold" >
                                                            {{ optional($invoice->category)->name ?? 'Sem Categoria' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="modern-card-actions-header d-flex gap-1 flex-shrink-0 mt-1 mt-md-0">
                                                    <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                        data-bs-target="#editInvoiceModal{{ $invoice->id_invoice ?? $invoice->id }}" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteInvoiceModal{{ $invoice->id_invoice ?? $invoice->id }}" title="Excluir">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" data-bs-toggle="modal"
                                                        data-bs-target="#copyInvoiceModal{{ $invoice->id_invoice ?? $invoice->id }}" title="Duplicar">
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
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="no-transactions-card my-4 d-flex flex-column align-items-center justify-content-center">
        <div class="no-transactions-icon mb-2">
            <i class="bi bi-emoji-frown"></i>
        </div>
        <div class="no-transactions-text text-secondary fw-semibold">
            Nenhuma transação encontrada neste mês.
        </div>
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.category-card-header').forEach(function(header) {
        header.addEventListener('click', function() {
            const card = header.closest('.category-card');
            const body = card.querySelector('.invoices-list');
            const icon = header.querySelector('.toggle-icon');
            body.classList.toggle('d-none');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        });
    });
});
</script>
@endpush
