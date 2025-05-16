@if(isset($sales))
    @foreach($sales as $sale)

<div class="modal fade" id="paymentModal{{ $sale->id }}" tabindex="-1" aria-labelledby="paymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content animate__animated animate__fadeInDown">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-money-bill-wave me-2"></i>Adicionar Pagamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('sales.addPayment', $sale->id) }}" method="POST">
                @csrf
                <input type="hidden" name="from" value="index">
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="row" id="paymentFields">
    <div class="col-md-4">
        <div class="payment-item mb-4 border rounded p-3 bg-light">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-coins me-1"></i>Valor do Pagamento</label>
                <input type="text" class="form-control money-input" name="amount_paid[]" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-credit-card me-1"></i>Forma de Pagamento</label>
                <select class="form-control" name="payment_method[]" required>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Cartão de Crédito">Cartão de Crédito</option>
                    <option value="Cartão de Débito">Cartão de Débito</option>
                    <option value="PIX">PIX</option>
                    <option value="Boleto">Boleto</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-calendar-alt me-1"></i>Data do Pagamento</label>
                <input type="text" class="form-control datepicker" name="payment_date[]" required>
            </div>
        </div>
    </div>
</div>

                    <button type="button" class="btn btn-success mt-2" id="addPaymentField">
                        <i class="fas fa-plus"></i> Adicionar Pagamento
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Pagamento</button>
                </div>
                <input type="hidden" name="from" value="index">
            </form>
        </div>
    </div>
</div>
    @endforeach
@elseif(isset($sale))
<div class="modal fade" id="paymentModal{{ $sale->id }}" tabindex="-1" aria-labelledby="paymentModalLabel{{ $sale->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content animate__animated animate__fadeInDown">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-money-bill-wave me-2"></i>Adicionar Pagamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ route('sales.addPayment', $sale->id) }}" method="POST">
                @csrf
                <input type="hidden" name="from" value="index">
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="row" id="paymentFields{{ $sale->id }}">
    <div class="col-md-4">
        <div class="payment-item mb-4 border rounded p-3 bg-light">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-coins me-1"></i>Valor do Pagamento</label>
                <input type="text" class="form-control money-input" name="amount_paid[]" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-credit-card me-1"></i>Forma de Pagamento</label>
                <select class="form-control" name="payment_method[]" required>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Cartão de Crédito">Cartão de Crédito</option>
                    <option value="Cartão de Débito">Cartão de Débito</option>
                    <option value="PIX">PIX</option>
                    <option value="Boleto">Boleto</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-calendar-alt me-1"></i>Data do Pagamento</label>
                <input type="text" class="form-control datepicker" name="payment_date[]" required>
            </div>
        </div>
    </div>
</div>

                    <button type="button" class="btn btn-success mt-2 addPaymentFieldBtn" 
                        data-sale-id="{{ $sale->id }}">
                        <i class="fas fa-plus"></i> Adicionar Pagamento
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Pagamento</button>
                </div>
                <input type="hidden" name="from" value="show">
            </form>
        </div>
    </div>
</div>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function () {
 flatpickr(".datepicker", {
    dateFormat: "Y/m/d"
            });
        });


       const formatMoney = (value) => {
    let numeric = value.replace(/\D/g, '');
    let float = (parseInt(numeric) / 100).toFixed(2);
    return float; // <-- aqui retorna com ponto, ex: "12.50"
};

        document.querySelectorAll('.money-input').forEach(el => {
            el.addEventListener('input', (e) => {
                e.target.value = formatMoney(e.target.value);
            });
        });

        document.querySelectorAll('.addPaymentFieldBtn').forEach(button => {
            button.addEventListener('click', function () {
                const saleId = this.getAttribute('data-sale-id');
                const container = document.getElementById('paymentFields' + saleId);

    const col = document.createElement('div');
    col.classList.add('col-md-4');

    col.innerHTML = `
        <div class="payment-item mb-4 border rounded p-3 bg-light">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-coins me-1"></i>Valor do Pagamento</label>
                <input type="text" class="form-control money-input" name="amount_paid[]" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-credit-card me-1"></i>Forma de Pagamento</label>
                <select class="form-control" name="payment_method[]" required>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Cartão de Crédito">Cartão de Crédito</option>
                    <option value="Cartão de Débito">Cartão de Débito</option>
                    <option value="PIX">PIX</option>
                    <option value="Boleto">Boleto</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-calendar-alt me-1"></i>Data do Pagamento</label>
                <input type="text" class="form-control datepicker" name="payment_date[]" required>
            </div>
        </div>
    `;

    container.appendChild(col);

    // Reaplica máscara de dinheiro
    col.querySelector('.money-input').addEventListener('input', (e) => {
        e.target.value = formatMoney(e.target.value);
    });

    // Reaplica datepicker
    flatpickr(col.querySelector('.datepicker'), {
        dateFormat: "Y/m/d"
    });
});

    });
</script>
