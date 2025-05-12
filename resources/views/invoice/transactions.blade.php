@forelse ($eventsGroupedByMonth as $month => $monthlyInvoices)

    <div class="row">
        <!-- Container flex para os cards -->
        @foreach ($monthlyInvoices as $invoice)
            <div class="col-md-4 mt-4">
                <!-- 3 cards por linha -->
                <div class="card border-0 shadow-sm custom-transaction-card">
                    <div class="card-body d-flex flex-column position-relative">
                        <div class="d-flex align-items-center ">
                            <!-- Botão de Transação com ícone da categoria e tooltip -->
                            <button
                                class="btn btn-icon-only btn-rounded mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"
                                style="border: 3px solid {{ $invoice->category->hexcolor_category }}; color: #fff; width: 50px; height: 50px;"
                                title="{{ $invoice->category->name }}" data-bs-toggle="tooltip" data-bs-placement="top">
                                <i class="{{ $invoice->category->icone }}" style="font-size: 1.5rem;"></i>
                            </button>

                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm custom-transaction-description">{{ $invoice->description }}
                                </h6>
                                <span class="text-xs custom-transaction-installments">Parcelas:
                                    {{ $invoice->installments }}</span>
                                <span
                                    class="text-xs custom-transaction-date">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
                                <div
                                    class="d-flex align-items-center {{ $invoice->value < 0 ? 'text-danger text-gradient' : 'text-success text-gradient' }} text-sm font-weight-bold custom-transaction-value">
                                    {{ $invoice->value < 0 ? '-' : '+' }} R$ {{ number_format(abs($invoice->value), 2) }}
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <button class="btn btn-outline-primary d-flex align-items-center justify-content-center"
                                style="width: 30px; height: 30px;" data-bs-toggle="modal"
                                data-bs-target="#editInvoiceModal{{ $invoice->id_invoice }}">
                                <i class="fas fa-edit"></i> <!-- Ícone de edição -->
                            </button>
                            <button class="btn btn-outline-danger d-flex align-items-center justify-content-center"
                                style="width: 30px; height: 30px;" data-bs-toggle="modal"
                                data-bs-target="#deleteInvoiceModal{{ $invoice->id_invoice }}">
                                <i class="fas fa-trash"></i> <!-- Ícone de exclusão -->
                            </button>
                            <button class="btn btn-outline-success d-flex align-items-center justify-content-center"
                                style="width: 30px; height: 30px;" data-bs-toggle="modal"
                                data-bs-target="#copyInvoiceModal{{ $invoice->id_invoice }}">
                                <i class="fas fa-copy"></i> <!-- Ícone de cópia -->
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            @include('invoice.edit', ['invoice' => $invoice, 'clients' => $clients])
            @include('invoice.delet')
            @include('invoice.copy')


        @endforeach
    </div> <!-- Fim do container flex -->
@empty
    <p class="text-muted custom-no-transactions">Nenhuma transação encontrada neste mês.</p>
@endforelse
