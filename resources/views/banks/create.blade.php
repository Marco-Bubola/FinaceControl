
<!-- Modal para Adicionar Novo Cartão -->
<!-- Inclua o CSS do flatpickr -->

<style>
    /* Estilo para o modal de cartão */
    #addCardModal .modal-content {
        background: linear-gradient(135deg, #232526 0%, #414345 100%);
        border-radius: 18px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        border: none;
        color: #fff;
    }
    #addCardModal .modal-header.custom-modal-header {
        background: rgba(34, 193, 195, 0.2);
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
        border-bottom: none;
        padding-bottom: 0.5rem;
    }
    #addCardModal .modal-title {
        font-weight: 700;
        font-size: 1.6rem;
        color: #22c1c3;
        letter-spacing: 1px;
    }
    #addCardModal .btn-close {
        filter: invert(1);
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    #addCardModal .btn-close:hover {
        opacity: 1;
    }
    #addCardModal .custom-modal-body {
        padding: 2rem 2.5rem;
    }
    #addCardModal .form-label.custom-label {
        font-weight: 600;
        color: #22c1c3;
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    #addCardModal .form-control.custom-input {
        background: rgba(255,255,255,0.08);
        border: 1.5px solid #22c1c3;
        border-radius: 10px;
        color: #fff;
        padding-left: 2.2rem;
        font-size: 1rem;
        transition: border 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(34,193,195,0.07);
    }
    #addCardModal .form-control.custom-input:focus {
        border-color: #fd6e6a;
        box-shadow: 0 0 0 2px #fd6e6a44;
        background: rgba(255,255,255,0.13);
        color: #fff;
    }
    #addCardModal .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.1rem;
        height: 1.1rem;
        fill: #22c1c3;
        opacity: 0.8;
        pointer-events: none;
    }
    #addCardModal .input-group {
        position: relative;
    }
    /* Estilo para o campo de validade */
    #addCardModal .expiry-input-group {
        position: relative;
    }
    #addCardModal .expiry-input-group .input-icon-calendar {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.1rem;
        height: 1.1rem;
        fill: #fd6e6a;
        opacity: 0.8;
        pointer-events: none;
    }
    #addCardModal .form-control.expiry-input {
        background: rgba(255,255,255,0.08);
        border: 1.5px solid #fd6e6a;
        border-radius: 10px;
        color: #fff;
        padding-left: 2.2rem;
        font-size: 1rem;
        transition: border 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(253,110,106,0.07);
        letter-spacing: 2px;
        width: 100%;
    }
    #addCardModal .form-control.expiry-input:focus {
        border-color: #22c1c3;
        box-shadow: 0 0 0 2px #22c1c344;
        background: rgba(255,255,255,0.13);
        color: #fff;
    }
    #addCardModal .modal-footer.custom-modal-footer {
        border-top: none;
        background: transparent;
        padding-bottom: 1.5rem;
        padding-top: 0.5rem;
    }
    #addCardModal .custom-btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.6rem 1.6rem;
        font-size: 1.1rem;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(34,193,195,0.09);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    #addCardModal .btn-primary.custom-btn {
        background: linear-gradient(90deg, #22c1c3 0%, #fd6e6a 100%);
        border: none;
        color: #fff;
    }
    #addCardModal .btn-primary.custom-btn:hover {
        background: linear-gradient(90deg, #fd6e6a 0%, #22c1c3 100%);
        color: #fff;
        box-shadow: 0 4px 16px #fd6e6a44;
    }
    #addCardModal .btn-secondary.custom-btn {
        background: #232526;
        border: 1.5px solid #22c1c3;
        color: #22c1c3;
    }
    #addCardModal .btn-secondary.custom-btn:hover {
        background: #414345;
        color: #fd6e6a;
        border-color: #fd6e6a;
    }
    
    /* Estilos para campos de data com flatpickr */
    #addCardModal .date-input-group {
        position: relative;
    }
    #addCardModal .date-input-group .input-icon-calendar {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.1rem;
        height: 1.1rem;
        fill: #fd6e6a;
        opacity: 0.8;
        pointer-events: none;
    }
    #addCardModal .form-control.date-input {
        background: rgba(255,255,255,0.08);
        border: 1.5px solid #fd6e6a;
        border-radius: 10px;
        color: #fff;
        padding-left: 2.2rem;
        font-size: 1rem;
        transition: border 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(253,110,106,0.07);
        letter-spacing: 1px;
        width: 100%;
    }
    #addCardModal .form-control.date-input:focus {
        border-color: #22c1c3;
        box-shadow: 0 0 0 2px #22c1c344;
        background: rgba(255,255,255,0.13);
        color: #fff;
    }
    #addCardModal .flatpickr-calendar {
        font-family: inherit;
        border-radius: 12px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        border: 1.5px solid #22c1c3;
        background: #232526;
        color: #fff;
    }
    #addCardModal .flatpickr-day.selected, 
    #addCardModal .flatpickr-day.startRange, 
    #addCardModal .flatpickr-day.endRange, 
    #addCardModal .flatpickr-day:hover {
        background: linear-gradient(90deg, #22c1c3 0%, #fd6e6a 100%);
        color: #fff;
    }
    #addCardModal .flatpickr-months .flatpickr-month {
        color: #22c1c3;
    }
    #addCardModal .flatpickr-weekday {
        color: #fd6e6a;
    }
</style>

<div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title text-center" id="addCardModalLabel">
                    <svg width="24" height="24" fill="none" class="me-2" style="vertical-align:middle;"><rect x="2" y="7" width="20" height="10" rx="3" fill="#22c1c3"/><rect x="6" y="11" width="4" height="2" rx="1" fill="#fff"/><rect x="14" y="11" width="4" height="2" rx="1" fill="#fff"/></svg>
                    Adicionar Novo Cartão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body custom-modal-body">
                <form method="POST" action="{{ route('banks.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label custom-label">
                                <svg class="input-icon" viewBox="0 0 24 24"><path d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" /></svg>
                                Titular do Cartão
                            </label>
                            <div class="input-group">
                                <svg class="input-icon" viewBox="0 0 24 24"><path d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" /></svg>
                                <input type="text" class="form-control custom-input" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label custom-label">
                                <svg class="input-icon" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="10" rx="3"/><rect x="6" y="11" width="4" height="2" rx="1"/><rect x="14" y="11" width="4" height="2" rx="1"/></svg>
                                Número do Cartão
                            </label>
                            <div class="input-group">
                                <svg class="input-icon" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="10" rx="3"/><rect x="6" y="11" width="4" height="2" rx="1"/><rect x="14" y="11" width="4" height="2" rx="1"/></svg>
                                <input type="text" maxlength="19" class="form-control custom-input" id="description" name="description" required autocomplete="off" inputmode="numeric" pattern="[0-9\-]*" placeholder="0000-0000-0000-0000">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Campo de data estilizado com flatpickr -->
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label custom-label">
                                <svg class="input-icon" viewBox="0 0 24 24"><path d="M7 11h10v2H7zm5-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                                Data de Início
                            </label>
                            <div class="date-input-group">
                                <svg class="input-icon-calendar" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zm0-13H5V6h14v1z" fill="#fd6e6a"/></svg>
                                <input type="text" class="form-control date-input" id="start_date" name="start_date" required placeholder="AAAA/MM/DD" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label custom-label">
                                <svg class="input-icon" viewBox="0 0 24 24"><path d="M17 12h-5V7h-2v7h7v-2z"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                                Data de Término
                            </label>
                            <div class="date-input-group">
                                <svg class="input-icon-calendar" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zm0-13H5V6h14v1z" fill="#fd6e6a"/></svg>
                                <input type="text" class="form-control date-input" id="end_date" name="end_date" required placeholder="AAAA/MM/DD" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center custom-modal-footer">
                        <button type="button" class="btn btn-secondary custom-btn" data-bs-dismiss="modal">
                            <svg width="20" height="20" fill="none" style="vertical-align:middle;"><path d="M6 6l8 8M6 14L14 6" stroke="#22c1c3" stroke-width="2" stroke-linecap="round"/></svg>
                            Fechar
                        </button>
                        <button type="submit" class="btn btn-primary custom-btn">
                            <svg width="20" height="20" fill="none" style="vertical-align:middle;"><path d="M5 13l4 4L19 7" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
                            Salvar Cartão
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Script para máscaras e flatpickr -->
            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Máscara dinâmica para número do cartão
                const cardInput = document.getElementById('description');
                cardInput.addEventListener('input', function(e) {
                    let value = cardInput.value.replace(/\D/g, '').slice(0,16);
                    let formatted = '';
                    for (let i = 0; i < value.length; i += 4) {
                        if (formatted) formatted += '-';
                        formatted += value.substr(i, 4);
                    }
                    cardInput.value = formatted;
                });

                // Máscara para validade MM/AA
                const expiryInput = document.getElementById('expiry');
                if (expiryInput) {
                    expiryInput.addEventListener('input', function(e) {
                        let value = expiryInput.value.replace(/\D/g, '').slice(0,4);
                        if (value.length > 2) {
                            value = value.slice(0,2) + '/' + value.slice(2);
                        }
                        expiryInput.value = value;
                    });
                }

                // Máscara para CVV (apenas números)
                const cvvInput = document.getElementById('cvv');
                if (cvvInput) {
                    cvvInput.addEventListener('input', function(e) {
                        cvvInput.value = cvvInput.value.replace(/\D/g, '').slice(0,4);
                    });
                }
                
                // Inicialização do flatpickr para os campos de data
                flatpickr("#start_date", {
                    dateFormat: "Y/m/d",
                    locale: "pt",
                    allowInput: true,
                    altInput: false,
                    disableMobile: true
                });
                
                flatpickr("#end_date", {
                    dateFormat: "Y/m/d",
                    locale: "pt",
                    allowInput: true,
                    altInput: false,
                    disableMobile: true
                });
            });
            </script>
        </div>
    </div>
</div>
