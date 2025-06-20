<style>
#addTransactionModal .modal-content {
    background: linear-gradient(135deg, #232526 0%, #414345 100%);
    border-radius: 18px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    border: none;
    color: #fff;
    backdrop-filter: blur(6px);
}
#addTransactionModal .modal-header {
    background: rgba(34, 193, 195, 0.18);
    border-top-left-radius: 18px;
    border-top-right-radius: 18px;
    border-bottom: none;
    padding-bottom: 0.5rem;
}
#addTransactionModal .modal-title {
    font-weight: 700;
    font-size: 1.5rem;
    color: #22c1c3;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
#addTransactionModal .btn-close {
    filter: invert(1);
    opacity: 0.7;
    transition: opacity 0.2s;
}
#addTransactionModal .btn-close:hover {
    opacity: 1;
}
#addTransactionModal .modal-body {
    padding: 2rem 2.5rem;
}
#addTransactionModal .form-label {
    font-weight: 600;
    color: #22c1c3;
    margin-bottom: 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
#addTransactionModal .form-control {
    background: rgba(255,255,255,0.08);
    border: 1.5px solid #22c1c3;
    border-radius: 10px;
    color: #fff;
    padding-left: 2.2rem;
    font-size: 1rem;
    transition: border 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(34,193,195,0.07);
}
#addTransactionModal .form-control:focus {
    border-color: #fd6e6a;
    box-shadow: 0 0 0 2px #fd6e6a44;
    background: rgba(255,255,255,0.13);
    color: #fff;
}
#addTransactionModal .input-icon {
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
#addTransactionModal .input-group {
    position: relative;
}
#addTransactionModal .modal-footer {
    border-top: none;
    background: transparent;
    padding-bottom: 1.5rem;
    padding-top: 0.5rem;
}
#addTransactionModal .btn {
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
#addTransactionModal .btn-primary {
    background: linear-gradient(90deg, #22c1c3 0%, #fd6e6a 100%);
    border: none;
    color: #fff;
}
#addTransactionModal .btn-primary:hover {
    background: linear-gradient(90deg, #fd6e6a 0%, #22c1c3 100%);
    color: #fff;
    box-shadow: 0 4px 16px #fd6e6a44;
}
#addTransactionModal .btn-success {
    background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
    border: none;
    color: #232526;
}
#addTransactionModal .btn-success:hover {
    background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
    color: #232526;
    box-shadow: 0 4px 16px #43e97b44;
}
#addTransactionModal .btn-secondary {
    background: #232526;
    border: 1.5px solid #22c1c3;
    color: #22c1c3;
}
#addTransactionModal .btn-secondary:hover {
    background: #414345;
    color: #fd6e6a;
    border-color: #fd6e6a;
}
#addTransactionModal .progress {
    height: 8px;
    border-radius: 8px;
    background: rgba(255,255,255,0.08);
    overflow: visible;
}
#addTransactionModal .progress-bar {
    background: linear-gradient(90deg, #22c1c3 0%, #fd6e6a 100%);
    border-radius: 8px;
    transition: width 0.4s cubic-bezier(.4,2,.3,1);
}
#addTransactionModal .progress-container {
    filter: drop-shadow(0 2px 8px #22c1c344);
}
#addTransactionModal .step-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #232526;
    border: 2px solid #22c1c3;
    color: #22c1c3;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.1rem;
    transition: background 0.2s, color 0.2s, border 0.2s;
}
#addTransactionModal .step-circle.active {
    background: linear-gradient(90deg, #22c1c3 0%, #fd6e6a 100%);
    color: #fff;
    border: 2px solid #fd6e6a;
}
#addTransactionModal select.form-control {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="none" stroke="%2322c1c3" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>');
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1.2em;
    padding-right: 2.5rem;
}
#addTransactionModal input[type="file"].form-control {
    padding-left: 0.75rem;
}

/* Customização Choices.js para combinar com o modal */
#addTransactionModal .choices {
    margin-bottom: 0;
}
#addTransactionModal .choices__inner {
    background: rgba(255,255,255,0.08);
    border: 1.5px solid #22c1c3;
    border-radius: 10px;
    color: #fff;
    min-height: 44px;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(34,193,195,0.07);
    padding-left: 2.2rem;
    transition: border 0.2s, box-shadow 0.2s;
}
#addTransactionModal .choices__inner:focus-within {
    border-color: #fd6e6a;
    box-shadow: 0 0 0 2px #fd6e6a44;
    background: rgba(255,255,255,0.13);
    color: #fff;
}
.choices__list--dropdown,
.choices__list[aria-expanded] {
    background: #000 !important;
    color: #000 !important;
}
#addTransactionModal .choices__item--selectable.is-highlighted {
    background: linear-gradient(90deg, #22c1c3 0%, #fd6e6a 100%);
    color: #fff;
}
#addTransactionModal .choices__item--selectable {
    color: #000 !important;
}
#addTransactionModal .choices__placeholder {
    color: #aaa;
    opacity: 0.7;
}
#addTransactionModal .choices[data-type*=select-one]::after {
    border-color: #22c1c3 transparent transparent;
}
#addTransactionModal .ts-wrapper {
    background: rgba(255,255,255,0.08);
    border: 1.5px solid #22c1c3;
    border-radius: 10px;
    color: #fff;
    min-height: 44px;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(34,193,195,0.07);
    padding-left: 2.2rem;
    transition: border 0.2s, box-shadow 0.2s;
}
#addTransactionModal .ts-control {
    background: transparent;
    color: #fff;
    border: none;
}
#addTransactionModal .ts-dropdown {
    background: #000 !important;
    color: #000 !important;
    border-radius: 10px;
    border: 1.5px solid #22c1c3;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
}
#addTransactionModal .ts-dropdown .active {
    background: linear-gradient(90deg, #22c1c3 0%, #fd6e6a 100%) !important;
    color: #fff !important;
}
#addTransactionModal .ts-dropdown .option {
    color: #fff !important;
}
#addTransactionModal .ts-dropdown .option.selected {
    color: #fff !important;
}
#addTransactionModal .ts-placeholder {
    color: #aaa !important;
    opacity: 0.7;
}
</style>

<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('cashbook.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionModalLabel">
                        <svg width="28" height="28" fill="none" style="vertical-align:middle;">
                            <rect x="2" y="7" width="24" height="14" rx="5" fill="#22c1c3"/>
                            <rect x="8" y="13" width="6" height="3" rx="1.5" fill="#fff"/>
                            <rect x="18" y="13" width="4" height="3" rx="1.5" fill="#fff"/>
                        </svg>
                        Adicionar Transação
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Progress Bar -->
                    <div class="progress-container mb-4">
                        <div class="progress">
                            <div id="progress-bar" class="progress-bar" style="width: 50%;"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="step-circle active">1</div>
                            <div class="step-circle">2</div>
                        </div>
                        <div class="d-flex justify-content-between text-center mt-1">
                            <small>Informações Básicas</small>
                            <small>Detalhes Adicionais</small>
                        </div>
                    </div>

                    <!-- Step 1 -->
                    <div id="step-1">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><path d="M12 1v22M5 6h14M5 18h14" stroke="#22c1c3" stroke-width="2" fill="none"/></svg>
                                    Valor
                                </label>
                                <div class="input-group">
                                    <svg class="input-icon" viewBox="0 0 24 24"><path d="M12 1v22M5 6h14M5 18h14" stroke="#22c1c3" stroke-width="2" fill="none"/></svg>
                                    <input type="number" step="0.01" class="form-control" id="value" name="value" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke="#22c1c3" stroke-width="2" fill="none"/><path d="M8 12h8M8 16h4" stroke="#22c1c3" stroke-width="2"/></svg>
                                    Descrição
                                </label>
                                <div class="input-group">
                                    <svg class="input-icon" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke="#22c1c3" stroke-width="2" fill="none"/><path d="M8 12h8M8 16h4" stroke="#22c1c3" stroke-width="2"/></svg>
                                    <input type="text" class="form-control" id="description" name="description">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="5" stroke="#fd6e6a" stroke-width="2" fill="none"/><path d="M8 2v4M16 2v4M3 10h18" stroke="#fd6e6a" stroke-width="2"/></svg>
                                    Data
                                </label>
                                <div class="input-group">
                                    <svg class="input-icon" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="5" stroke="#fd6e6a" stroke-width="2" fill="none"/><path d="M8 2v4M16 2v4M3 10h18" stroke="#fd6e6a" stroke-width="2"/></svg>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="is_pending" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#22c1c3" stroke-width="2" fill="none"/><path d="M8 12l2 2 4-4" stroke="#22c1c3" stroke-width="2" fill="none"/></svg>
                                    Pendente
                                </label>
                                <div class="input-group">
                                    <svg class="input-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#22c1c3" stroke-width="2" fill="none"/><path d="M8 12l2 2 4-4" stroke="#22c1c3" stroke-width="2" fill="none"/></svg>
                                    <select class="form-control" id="is_pending" name="is_pending" required>
                                        <option value="0">Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.27 5.82 22 7 14.14l-5-4.87 6.91-1.01z" stroke="#22c1c3" stroke-width="2" fill="none"/></svg>
                                    Categoria
                                </label>
                                <div class="input-group">
                                    <svg class="input-icon" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.27 5.82 22 7 14.14l-5-4.87 6.91-1.01z" stroke="#22c1c3" stroke-width="2" fill="none"/></svg>
                                    <select class="form-control tom-select" id="category_id" name="category_id" required>
                                        <option value="">Selecione...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="type_id" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke="#22c1c3" stroke-width="2" fill="none"/><path d="M8 12h8" stroke="#22c1c3" stroke-width="2"/></svg>
                                    Tipo
                                </label>
                                <div class="input-group">
                                    <svg class="input-icon" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke="#22c1c3" stroke-width="2" fill="none"/><path d="M8 12h8" stroke="#22c1c3" stroke-width="2"/></svg>
                                    <select class="form-control tom-select" id="type_id" name="type_id" required>
                                        <option value="">Selecione...</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id_type }}">{{ $type->desc_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="client_id" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke="#22c1c3" stroke-width="2" fill="none"/><path d="M4 20c0-4 8-4 8-4s8 0 8 4" stroke="#22c1c3" stroke-width="2" fill="none"/></svg>
                                    Cliente
                                </label>
                                <div class="input-group">
                                    <svg class="input-icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke="#22c1c3" stroke-width="2" fill="none"/><path d="M4 20c0-4 8-4 8-4s8 0 8 4" stroke="#22c1c3" stroke-width="2" fill="none"/></svg>
                                    <select class="form-control tom-select" id="client_id" name="client_id">
                                        <option value="">Nenhum cliente</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cofrinho_id" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.27 5.82 22 7 14.14l-5-4.87 6.91-1.01z" stroke="#f7971e" stroke-width="2" fill="none"/></svg>
                                    Cofrinho
                                </label>
                                <div class="input-group">
                                    <svg class="input-icon" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.27 5.82 22 7 14.14l-5-4.87 6.91-1.01z" stroke="#f7971e" stroke-width="2" fill="none"/></svg>
                                    <select class="form-control tom-select" id="cofrinho_id" name="cofrinho_id">
                                        <option value="">Nenhum</option>
                                        @foreach($cofrinhos as $cofrinho)
                                            <option value="{{ $cofrinho->id }}">{{ $cofrinho->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div id="step-2" class="d-none">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="note" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke="#fd6e6a" stroke-width="2" fill="none"/><path d="M8 12h8M8 16h4" stroke="#fd6e6a" stroke-width="2"/></svg>
                                    Nota
                                </label>
                                <div class="input-group">
                                    <svg class="input-icon" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke="#fd6e6a" stroke-width="2" fill="none"/><path d="M8 12h8M8 16h4" stroke="#fd6e6a" stroke-width="2"/></svg>
                                    <textarea class="form-control" id="note" name="note"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="segment_id" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke="#fd6e6a" stroke-width="2" fill="none"/><path d="M12 8v8M8 12h8" stroke="#fd6e6a" stroke-width="2"/></svg>
                                    Segmento
                                </label>
                                <div class="input-group">
                                    <svg class="input-icon" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke="#fd6e6a" stroke-width="2" fill="none"/><path d="M12 8v8M8 12h8" stroke="#fd6e6a" stroke-width="2"/></svg>
                                    <select class="form-control tom-select" id="segment_id" name="segment_id">
                                        <option value="">Nenhum</option>
                                        @foreach($segments as $segment)
                                            <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="attachment" class="form-label">
                                    <svg class="input-icon" viewBox="0 0 24 24"><path d="M17.657 16.243a6 6 0 1 1-8.485-8.485l7.071-7.071a4 4 0 1 1 5.657 5.657l-7.071 7.071a2 2 0 1 1-2.828-2.828l6.364-6.364" stroke="#22c1c3" stroke-width="2" fill="none"/></svg>
                                    Anexo
                                </label>
                                <input type="file" class="form-control" id="attachment" name="attachment">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="prev-step" onclick="toggleSteps('prev')"
                        disabled>
                        <svg width="20" height="20" fill="none"><path d="M13 17l-5-5 5-5" stroke="#22c1c3" stroke-width="2" stroke-linecap="round"/></svg>
                        Voltar
                    </button>
                    <button type="button" class="btn btn-primary" id="next-step"
                        onclick="toggleSteps('next')">
                        Próximo
                        <svg width="20" height="20" fill="none"><path d="M7 7l5 5-5 5" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <button type="submit" class="btn btn-success d-none" id="save-button">
                        <svg width="20" height="20" fill="none"><path d="M5 13l4 4L19 7" stroke="#232526" stroke-width="2" stroke-linecap="round"/></svg>
                        Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa Tom Select para todos os selects do modal de criação
    document.querySelectorAll('#addTransactionModal .tom-select').forEach(function(select) {
        new TomSelect(select, {
            create: false,
            sortField: {field: "text", direction: "asc"},
            placeholder: select.querySelector('option[value=""]') ? select.querySelector('option[value=""]').textContent : 'Selecione...'
        });
    });
});
</script>
