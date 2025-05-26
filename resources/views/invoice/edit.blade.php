<!-- Modal de Edição Moderno -->


<style>
/* Reutilizando estilos do modal de criação */
.modal-modern .modal-dialog {
    max-width: 900px;
    margin: 2rem auto;
}
@media (max-width: 991.98px) {
    .modal-modern .modal-dialog {
        max-width: 98vw;
    }
}
.modal-modern .modal-content {
    border-radius: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 60%, #e0e7ff 100%);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    border: none;
    position: relative;
    overflow: hidden;
}
.modal-modern .modal-header {
    background: linear-gradient(90deg, #2563eb 60%, #38bdf8 100%);
    color: #fff;
    border-top-left-radius: 1.5rem;
    border-top-right-radius: 1.5rem;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
    position: relative;
    padding-top: 2.5rem;
    padding-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}
.modal-modern .modal-title {
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.modal-modern .modal-title .icon-decor {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #38bdf8 60%, #2563eb 100%);
    border-radius: 50%;
    width: 3.5rem;
    height: 3.5rem;
    box-shadow: 0 4px 24px rgba(56,189,248,0.15);
    border: 3px solid #fff;
    margin-right: 0.5rem;
}
.modal-modern .modal-title .icon-decor i {
    font-size: 2rem;
    color: #fff;
}
.modal-modern .modal-body {
    background: transparent;
    padding: 2.5rem 2rem 1.5rem 2rem;
}
.modal-modern .form-label {
    font-weight: 600;
    color: #2563eb;
    letter-spacing: 0.01em;
    font-size: 1.15rem;
}
.modal-modern .input-group-text {
    background: #f1f5f9;
    border: none;
    color: #2563eb;
    font-size: 1.4rem;
}
.modal-modern .form-control,
.modal-modern .form-select,
.tom-select-custom .ts-control {
    border-radius: 0.7rem !important;
    border: 1.5px solid #e0e7ff !important;
    background: #fff !important;
    font-size: 1.15rem !important;
    min-height: 48px;
    color: #222;
}
.tom-select-custom .ts-control {
    background: #fff !important;
    font-size: 1.15rem !important;
    min-height: 48px;
}
.tom-select-custom .ts-dropdown,
.tom-select-custom .ts-dropdown .ts-dropdown-content {
    background: #fff !important;
    font-size: 1.15rem !important;
    color: #222 !important;
    border-radius: 0.7rem !important;
    box-shadow: 0 4px 24px rgba(56,189,248,0.10);
}
.tom-select-custom .ts-dropdown .option {
    font-size: 1.15rem !important;
    padding: 0.7rem 1.2rem !important;
}
.tom-select-custom .ts-dropdown .option.selected,
.tom-select-custom .ts-dropdown .option.active {
    background: #e0e7ff !important;
    color: #2563eb !important;
}
.modal-modern .form-control:focus,
.modal-modern .form-select:focus,
.tom-select-custom .ts-control:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.12);
}
.modal-modern .modal-footer {
    background: transparent;
    border-top: none;
    padding: 1.5rem 2rem 2rem 2rem;
}
.modal-modern .btn-primary {
    background: linear-gradient(90deg, #22d3ee 0%, #2563eb 100%);
    border: none;
    font-weight: 600;
    letter-spacing: 0.02em;
    border-radius: 0.7rem;
    transition: background 0.2s, transform 0.2s;
    font-size: 1.15rem;
    padding: 0.7rem 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.modal-modern .btn-primary i {
    font-size: 1.4rem;
}
.modal-modern .btn-primary:hover {
    background: linear-gradient(90deg, #2563eb 0%, #22d3ee 100%);
    transform: translateY(-2px) scale(1.03);
}
.modal-modern .btn-secondary {
    border-radius: 0.7rem;
    font-weight: 500;
    border-width: 2px;
    transition: background 0.2s, color 0.2s;
    font-size: 1.15rem;
    padding: 0.7rem 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.modal-modern .btn-secondary i {
    font-size: 1.4rem;
}
.modal-modern .btn-secondary:hover {
    background: #e0e7ff;
    color: #2563eb;
}
@media (max-width: 575.98px) {
    .modal-modern .modal-body,
    .modal-modern .modal-footer {
        padding: 1rem;
    }
    .modal-modern .modal-header {
        padding-top: 3.5rem;
        padding-bottom: 1rem;
    }
    .modal-modern .modal-title {
        font-size: 1.2rem;
    }
}
</style>

<div class="modal fade modal-modern" id="editInvoiceModal{{ $invoice->id_invoice }}" tabindex="-1"
    aria-labelledby="editInvoiceModalLabel{{ $invoice->id_invoice }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="editInvoiceModalLabel{{ $invoice->id_invoice }}">
                    <span class="icon-decor"><i class="bi bi-pencil-square"></i></span>
                    Editar Transação
                </h5>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 top-0 m-3"
                    data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('invoices.update', $invoice->id_invoice) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="description{{ $invoice->id_invoice }}" class="form-label">
                                <i class="bi bi-card-text me-1"></i>
                                Descrição
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-pencil"></i></span>
                                <input type="text" class="form-control" id="description{{ $invoice->id_invoice }}"
                                    name="description" value="{{ $invoice->description }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="value{{ $invoice->id_invoice }}" class="form-label">
                                <i class="bi bi-currency-dollar me-1"></i>
                                Valor
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
                                <input type="text" class="form-control @error('value') is-invalid @enderror"
                                    id="value{{ $invoice->id_invoice }}" name="value"
                                    value="{{ $invoice->value }}" required>
                                @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="invoice_date{{ $invoice->id_invoice }}" class="form-label">
                                <i class="bi bi-calendar-event me-1"></i>
                                Data
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="date" class="form-control"
                                    id="invoice_date{{ $invoice->id_invoice }}" name="invoice_date"
                                    value="{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}"
                                    required>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const input = document.getElementById('value{{ $invoice->id_invoice }}');
                            let rawValue = '';
                            input.addEventListener('input', function (e) {
                                rawValue = input.value.replace(/\D/g, '');
                                const numericValue = (parseInt(rawValue || '0', 10) / 100).toFixed(2);
                                input.value = numericValue.replace('.', ',');
                            });
                        });
                    </script>
                    <div class="row g-4 mt-1">
                        <div class="col-md-4">
                            <label for="installments{{ $invoice->id_invoice }}" class="form-label">
                                <i class="bi bi-list-ol me-1"></i>
                                Parcelas
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-123"></i></span>
                                <input type="text" class="form-control"
                                    id="installments{{ $invoice->id_invoice }}" name="installments"
                                    value="{{ $invoice->installments }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="category_id{{ $invoice->id_invoice }}" class="form-label">
                                <i class="bi bi-tags me-1"></i>
                                Categoria
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <select class="form-select tom-select-custom"
                                    id="category_id{{ $invoice->id_invoice }}" name="category_id" required>
                                    <option value="" disabled>Selecione uma categoria</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id_category }}"
                                            {{ $invoice->category_id == $category->id_category ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="client_id{{ $invoice->id_invoice }}" class="form-label">
                                <i class="bi bi-person-badge me-1"></i>
                                Cliente
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <select class="form-select tom-select-custom"
                                    id="client_id{{ $invoice->id_invoice }}" name="client_id" >
                                    <option value="" disabled {{ is_null($invoice->client_id) ? 'selected' : '' }}>Selecione um cliente</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ $invoice->client_id == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            new TomSelect('#category_id{{ $invoice->id_invoice }}', {
                                create: false,
                                placeholder: "Selecione ou digite para buscar...",
                                allowEmptyOption: true,
                                sortField: {
                                    field: "text",
                                    direction: "asc"
                                }
                            });
                            new TomSelect('#client_id{{ $invoice->id_invoice }}', {
                                create: false,
                                placeholder: "Selecione um cliente...",
                                allowEmptyOption: true,
                                sortField: {
                                    field: "text",
                                    direction: "asc"
                                }
                            });
                        });
                    </script>
                    <div class="modal-footer d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save2 me-1"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>