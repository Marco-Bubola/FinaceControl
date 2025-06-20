<div class="modal fade" id="uploadCashbookModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl stylish-modal-dialog upload-cashbook-modal-dialog">
        <div class="modal-content stylish-modal">
            <div class="modal-header border-0 pb-0 stylish-modal-header position-relative">
                <div class="d-flex align-items-center w-100">
                    <div class="me-3 d-flex align-items-center justify-content-center stylish-header-icon">
                        <i class="fa fa-upload fa-2x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="modal-title fw-bold mb-0" id="uploadModalLabel">Upload e Confirmação de Transações</h4>
                        <div class="modal-subtitle text-muted">Faça upload do extrato e confirme as transações de forma rápida e segura.</div>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-close shadow-none stylish-close-btn position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Fechar" style="z-index: 20;"></button>
            </div>
            <div class="modal-body pt-0 stylish-modal-body">
                <!-- Barra de Progresso com Círculos e Títulos -->
                <div class="mb-4" id="step-indicator">
                    <div class="d-flex justify-content-between align-items-center stylish-step-bar">
                        <div class="text-center flex-grow-1">
                            <div class="circle active shadow" id="circle-step1">
                                <i class="fa fa-cloud-upload"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress stylish-progress step-progress-gradient" style="height: 10px;">
                                <div class="progress-bar step-progress-bar-animated" id="step-progress-bar-inner" style="width: 50%;"></div>
                            </div>
                        </div>
                        <div class="text-center flex-grow-1">
                            <div class="circle shadow" id="circle-step2">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="text-center flex-grow-1">
                            <small><i class="fa fa-upload me-1"></i>Upload de Transações</small>
                        </div>
                        <div class="text-center flex-grow-1">
                            <small><i class="fa fa-list-check me-1"></i>Confirmação de Transações</small>
                        </div>
                    </div>
                </div>
                <div id="uploadSteps">
                    <!-- Step 1: Upload de Arquivo -->
                    <div id="step1" class="step">
                        <h5 class="text-center mb-3 fw-semibold">Envio de Arquivo em PDF ou CSV</h5>
                        <form id="uploadForm" method="POST" action="{{ route('cashbook.upload') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group text-center mb-4">
                                <div id="drop-area" class="drop-area">
                                    <i class="fa fa-cloud-upload fa-3x text-primary mb-2"></i>
                                    <p class="mb-1"><strong>Arraste e solte</strong> ou</p>
                                    <label for="file-upload" class="btn btn-outline-primary stylish-btn px-4 py-2">
                                        <i class="fa fa-file"></i> <span id="file-upload-label">Escolher arquivo PDF ou CSV</span>
                                    </label>
                                    <input type="file" id="file-upload" name="file" accept=".pdf, .csv"
                                        style="display: none;" required />
                                    <div id="file-preview" class="file-preview mt-3 d-none">
                                        <i id="file-icon" class="fa fa-file-alt fa-2x me-2"></i>
                                        <span id="file-name"></span>
                                        <button type="button" class="btn btn-link text-danger btn-sm ms-2 p-0" id="remove-file" title="Remover arquivo"><i class="fa fa-times-circle"></i></button>
                                    </div>
                                    <div id="upload-feedback" class="upload-feedback mt-2 d-none"></div>
                                </div>
                                <small class="form-text text-muted mt-2">
                                    Somente arquivos PDF ou CSV são aceitos.
                                </small>
                            </div>

                            <!-- Barra de Progresso -->
                            <div class="progress stylish-progress" style="height: 25px; display: none;" id="upload-progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 0%; background-color: #3498db;" aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100">0%</div>
                            </div>

                            <div class="mb-3">
                                <label for="upload_cofrinho_id" class="form-label">Cofrinho</label>
                                <select class="form-control" id="upload_cofrinho_id" name="cofrinho_id">
                                    <option value="">Nenhum</option>
                                    @foreach($cofrinhos as $cofrinho)
                                        <option value="{{ $cofrinho->id }}">{{ $cofrinho->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group text-center mt-4 d-flex justify-content-center gap-3">
                                <button type="button" class="btn btn-outline-secondary stylish-btn px-4 py-2 upload-animate" data-bs-dismiss="modal" id="cancelStep1">
                                    <i class="fa fa-times"></i> Cancelar
                                </button>
                                <button type="button" class="btn btn-primary stylish-btn px-4 py-2 upload-animate" id="nextStep1">
                                    <span id="upload-btn-text">Próximo</span> <i class="fa fa-arrow-right"></i>
                                    <span id="upload-loading" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Step 2: Confirmar Transações -->
                    <div id="step2" class="step d-none">
                        <form id="confirmationForm" method="POST" action="{{ route('cashbook.confirm') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="confirm_cofrinho_id" class="form-label">Cofrinho</label>
                                <select class="form-control" id="confirm_cofrinho_id" name="cofrinho_id">
                                    <option value="">Nenhum</option>
                                    @foreach($cofrinhos as $cofrinho)
                                        <option value="{{ $cofrinho->id }}">{{ $cofrinho->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="table-responsive stylish-table-responsive" style="max-height:55vh; min-height:350px; overflow-y:auto;">
                                <table class="table table-striped table-bordered text-center stylish-table mb-0">
                                    <thead class="table-primary">
                                        <tr>
                                            <th><i class="fa fa-trash"></i></th>
                                            <th><i class="fa fa-calendar-alt"></i> Data</th>
                                            <th><i class="fa fa-dollar-sign"></i> Valor</th>
                                            <th><i class="fa fa-align-left"></i> Descrição</th>
                                            <th><i class="fa fa-tags"></i> Categoria</th>
                                            <th><i class="fa fa-exchange-alt"></i> Tipo</th>
                                            <th><i class="fa fa-user"></i> Cliente</th>
                                            <th><i class="fa fa-piggy-bank"></i> Cofrinho</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transactionRows">
                                        <!-- As transações serão carregadas dinamicamente via JavaScript -->
                                        <tr data-index="${index}">
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-row" title="Remover">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-icon icon-calendar"><i class="fa fa-calendar-alt"></i></span>
                                                    <input type="date" name="transactions[${index}][date]" value="${formattedDate}" class="form-control" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-icon icon-money"><i class="fa fa-dollar-sign"></i></span>
                                                    <input type="number" name="transactions[${index}][value]" value="${value}" class="form-control" step="0.01" required>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="transactions[${index}][description]" value="${transaction.description}" class="form-control" required>
                                            </td>
                                            <td>
                                                <select name="transactions[${index}][category_id]" class="form-control" required>
                                                    <option value="" disabled ${transaction.category_id ? '' : 'selected'}>Selecione</option>
                                                    ${data.categories.map(category => {
                                                        const isSelected = category.id == transaction.category_id ? 'selected' : '';
                                                        return `<option value="${category.id}" ${isSelected}>${category.name}</option>`;
                                                    }).join('')}
                                                </select>
                                            </td>
                                            <td>
                                                <select name="transactions[${index}][type_id]" class="form-control" required>
                                                    <option value="1" ${transaction.type_id == 1 ? 'selected' : ''}>Receita</option>
                                                    <option value="2" ${transaction.type_id == 2 ? 'selected' : ''}>Despesa</option>
                                                </select>
                                            </td>
                                        
                                            <td>
                                                <select name="transactions[${index}][client_id]" class="form-control">
                                                    <option value="" ${!transaction.client_id ? 'selected' : ''}>Nenhum cliente</option>
                                                    ${data.clients.map(client => {
                                                        const isSelected = client.id == transaction.client_id ? 'selected' : '';
                                                        return `<option value="${client.id}" ${isSelected}>${client.name}</option>`;
                                                    }).join('')}
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-icon icon-piggy"><i class="fa fa-piggy-bank"></i></span>
                                                    <select name="transactions[${index}][cofrinho_id]" class="form-control stylish-select-icon">
                                                        <option value="">Nenhum</option>
                                                        ${data.cofrinhos.map(cofrinho => {
                                                            const isSelected = cofrinho.id == transaction.cofrinho_id ? 'selected' : '';
                                                            return `<option value="${cofrinho.id}" ${isSelected}>${cofrinho.nome}</option>`;
                                                        }).join('')}
                                                    </select>
                                                    <span class="input-icon input-icon-select icon-piggy"><i class="fa fa-piggy-bank"></i></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Footer fixo para os botões -->
                            <div class="modal-footer stylish-modal-footer d-block w-100 m-0 p-0" id="step2-actions">
                                <div class="footer-btns-wrapper w-100 d-flex justify-content-center gap-3 py-3 m-0">
                                    <button type="button" class="btn btn-outline-secondary stylish-btn px-4 py-2 upload-animate" data-bs-dismiss="modal" id="cancelStep2">
                                        <i class="fa fa-times"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-success stylish-btn px-4 py-2 upload-animate">
                                        <span>Confirmar Transações</span> <i class="fa fa-check-circle fa-bounce"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stylish-modal-dialog {
    max-width: 1100px;
    min-width: 900px;
    margin-top: 32px;
    margin-bottom: 32px;
}
.upload-cashbook-modal-dialog {
    width: 90vw;
    max-width: 98vw;
    min-width: 900px;
}
.stylish-modal {
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(52, 152, 219, 0.15), 0 1.5px 8px rgba(0,0,0,0.07);
    background: #f8fafc;
    border: none;
    max-height: 85vh;
    min-height: 600px;
}
.stylish-modal-body {
    padding-bottom: 0;
}
.stylish-modal-footer {
    position: sticky;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #f8fafc;
    border-top: 1.5px solid #e0c3fc;
    z-index: 20;
    margin-top: 0;
    box-shadow: none;
    border-bottom-left-radius: 18px;
    border-bottom-right-radius: 18px;
    padding: 0;
}
.footer-btns-wrapper {
    width: 100%;
    background: transparent;
    margin: 0;
    padding: 1.2rem 0 1.2rem 0;
}
.stylish-close-btn {
    background: #fff !important;
    color: #3498db !important;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(52,152,219,0.13);
    font-size: 1.2rem;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, color 0.2s;
    z-index: 20;
}
.stylish-close-btn:hover {
    background: #eaf1fb !important;
    color: #217dbb !important;
}
.stylish-modal-header {
    background: linear-gradient(90deg, #ff9966 0%, #6a11cb 40%, #2575fc 80%, #ff5e62 100%);
    border-top-left-radius: 18px;
    border-top-right-radius: 18px;
    box-shadow: 0 2px 12px rgba(52,152,219,0.10);
    padding: 1.2rem 1.5rem 1rem 1.5rem;
    margin-bottom: 0.5rem;
}
.stylish-header-icon {
    width: 54px;
    height: 54px;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(52,152,219,0.13);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3498db;
    font-size: 2rem;
}
.stylish-modal-header .modal-title {
    color: #fff;
    font-size: 1.45rem;
    font-weight: 700;
    letter-spacing: 0.01em;
}
.stylish-modal-header .modal-subtitle {
    font-size: 1.01rem;
    color: #eaf1fb;
    margin-top: 0.1rem;
    font-weight: 400;
}
.stylish-close-btn {
    background: #fff !important;
    color: #3498db !important;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(52,152,219,0.13);
    font-size: 1.2rem;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, color 0.2s;
}
.stylish-close-btn:hover {
    background: #eaf1fb !important;
    color: #217dbb !important;
}
.stylish-progress {
    background: #eaf1fb;
    border-radius: 8px;
}
.stylish-step-bar {
    position: relative;
    margin-bottom: 0.5rem;
}
.step-progress-gradient {
    background: linear-gradient(90deg, #fbc2eb 0%, #a6c1ee 40%, #fcb69f 100%);
    box-shadow: 0 2px 8px rgba(106,17,203,0.10);
}
.step-progress-bar-animated {
    background: linear-gradient(90deg, #ff9966 0%, #6a11cb 40%, #2575fc 80%, #ff5e62 100%);
    animation: progressBarMove 2s linear infinite alternate;
    box-shadow: 0 0 12px 2px #ff996680;
}
@keyframes progressBarMove {
    0% { filter: brightness(1); }
    100% { filter: brightness(1.15); }
}
.circle {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background-color: #ddd;
    color: #3498db;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 2rem;
    border: 3px solid #eaf1fb;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 12px rgba(52,152,219,0.13);
    position: relative;
}
.circle.active {
    background: linear-gradient(135deg, #ff9966 0%, #6a11cb 60%, #2575fc 100%);
    color: #fff;
    border-color: #6a11cb;
    box-shadow: 0 0 0 7px #fbc2eb, 0 0 16px 2px #ff996680;
    animation: circlePulse 1.2s infinite alternate;
}
@keyframes circlePulse {
    0% { box-shadow: 0 0 0 7px #fbc2eb, 0 0 16px 2px #ff996680; }
    100% { box-shadow: 0 0 0 11px #fbc2eb, 0 0 24px 4px #ff996680; }
}
.stylish-btn {
    border-radius: 8px;
    font-weight: 500;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.08);
}
.stylish-btn:hover, .stylish-btn:focus {
    background: #217dbb !important;
    color: #fff !important;
    box-shadow: 0 4px 16px rgba(52, 152, 219, 0.13);
}
.stylish-table-responsive {
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(52, 152, 219, 0.07);
    background: #fff;
    padding: 8px 0;
}
.stylish-table thead th {
    background: linear-gradient(90deg, #3498db 60%, #27ae60 100%) !important;
    color: #fff;
    border-top: none;
    font-weight: 600;
    font-size: 1.08rem;
    letter-spacing: 0.01em;
}
.stylish-table tbody tr {
    transition: background 0.15s;
}
.stylish-table tbody tr:hover {
    background: #eaf1fb !important;
}
.stylish-table td, .stylish-table th {
    vertical-align: middle;
}
.stylish-table input, .stylish-table select {
    border-radius: 8px;
    border: 2px solid #d1e3f8;
    background: #fff;
    font-size: 1.07rem;
    padding-left: 2.4rem;
    position: relative;
    box-shadow: 0 2px 8px rgba(52,152,219,0.07);
    transition: border 0.2s, box-shadow 0.2s;
    min-height: 44px;
}
.stylish-table input::placeholder, .stylish-table select:invalid {
    color: #b7d6f6;
    font-style: italic;
    opacity: 1;
}
.stylish-table select,
.stylish-table select option,
.stylish-table select:focus,
.stylish-table select:active {
    color: #222 !important;
}
.stylish-table select:invalid, .stylish-table select option[disabled] {
    color: #b7d6f6 !important;
}
.stylish-table .input-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.18rem;
    pointer-events: none;
    opacity: 0.95;
    z-index: 2;
}
.stylish-table .input-icon-select {
    right: 13px;
    left: auto;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.18rem;
    pointer-events: none;
    opacity: 0.85;
    z-index: 2;
}
.icon-calendar { color: #ff9966; }
.icon-money { color: #2575fc; }
.icon-desc { color: #ff5e62; }
.icon-tags { color: #6a11cb; }
.icon-type { color: #fcb69f; }
.icon-user { color: #a6c1ee; }
.stylish-table .input-group {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}
.stylish-table .input-group > .form-control,
.stylish-table .input-group > select.form-control {
    width: 100%;
    padding-left: 2.5rem !important;
    padding-right: 2.5rem !important;
}
.stylish-select-icon {
    padding-right: 2.4rem !important;
}
.stylish-table input:focus, .stylish-table select:focus {
    border-color: #27ae60;
    background: #f8fafc;
    box-shadow: 0 0 0 3px #b7d6f6;
    outline: none;
}
.stylish-table .badge-receita {
    background: linear-gradient(90deg, #ff9966 0%, #6a11cb 100%);
    color: #fff;
    font-size: 0.95rem;
    border-radius: 6px;
    padding: 4px 10px;
    font-weight: 500;
}
.stylish-table .badge-despesa {
    background: #e74c3c;
    color: #fff;
    font-size: 0.95rem;
    border-radius: 6px;
    padding: 4px 10px;
    font-weight: 500;
}
.btn-danger.btn-sm {
    border-radius: 6px;
    transition: background 0.2s;
}
.btn-danger.btn-sm:hover {
    background: #e74c3c !important;
}
#step-indicator small {
    font-size: 1.01rem;
    color: #3498db;
    font-weight: 600;
    letter-spacing: 0.01em;
}
::-webkit-scrollbar {
    width: 8px;
    background: #eaf1fb;
    border-radius: 6px;
}
::-webkit-scrollbar-thumb {
    background: #b7d6f6;
    border-radius: 6px;
}
.drop-area {
    border: 2px dashed #3498db;
    border-radius: 12px;
    padding: 32px 16px 24px 16px;
    background: #f4faff;
    transition: border-color 0.2s, background 0.2s;
    cursor: pointer;
    position: relative;
}
.drop-area.dragover {
    border-color: #217dbb;
    background: #eaf1fb;
}
.file-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eaf1fb;
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 1.05rem;
    color: #217dbb;
    box-shadow: 0 1px 4px rgba(52,152,219,0.07);
}
.file-preview i.fa-file-pdf { color: #e74c3c; }
.file-preview i.fa-file-csv { color: #27ae60; }
.upload-feedback {
    font-size: 1rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
}
.upload-feedback.success { color: #27ae60; }
.upload-feedback.error { color: #e74c3c; }
.upload-animate {
    position: relative;
    overflow: hidden;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.upload-animate:active {
    transform: scale(0.97);
}
</style>

<script>
// Resetar modal ao fechar/cancelar
function resetUploadCashbookModal() {
    // Step 1 visível, Step 2 oculto
    document.getElementById('step1').classList.remove('d-none');
    document.getElementById('step2').classList.add('d-none');
    // Resetar barra de progresso dos steps
    document.getElementById('step-progress-bar-inner').style.width = '50%';
    document.getElementById('circle-step1').classList.add('active');
    document.getElementById('circle-step2').classList.remove('active');
    // Resetar formulário de upload
    document.getElementById('uploadForm').reset();
    document.getElementById('file-upload-label').textContent = 'Escolher arquivo PDF ou CSV';
    var filePreview = document.getElementById('file-preview');
    if (filePreview) filePreview.classList.add('d-none');
    var uploadFeedback = document.getElementById('upload-feedback');
    if (uploadFeedback) {
        uploadFeedback.classList.add('d-none');
        uploadFeedback.textContent = '';
        uploadFeedback.classList.remove('success', 'error');
    }
    // Resetar barra de progresso de upload
    var progressBar = document.getElementById('upload-progress-bar');
    if (progressBar) {
        progressBar.style.display = 'none';
        var progressBarInner = progressBar.querySelector('.progress-bar');
        if (progressBarInner) {
            progressBarInner.style.width = '0%';
            progressBarInner.setAttribute('aria-valuenow', 0);
            progressBarInner.textContent = '0%';
        }
    }
    // Limpar transações
    var transactionRows = document.getElementById('transactionRows');
    if (transactionRows) transactionRows.innerHTML = '';
}

// Eventos para resetar ao fechar/cancelar
['uploadCashbookModal', 'cancelStep1', 'cancelStep2'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el) {
        if (id === 'uploadCashbookModal') {
            el.addEventListener('hidden.bs.modal', resetUploadCashbookModal);
        } else {
            el.addEventListener('click', function() {
                var modal = document.getElementById('uploadCashbookModal');
                if (modal) {
                    var modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) modalInstance.hide();
                }
                resetUploadCashbookModal();
            });
        }
    }
});
</script>
<script>
// Validação do modal de upload e confirmação
(function() {
    // Step 1: Upload
    const uploadForm = document.getElementById('uploadForm');
    const fileInput = document.getElementById('file-upload');
    const nextStep1 = document.getElementById('nextStep1');
    const fileUploadLabel = document.getElementById('file-upload-label');
    const uploadFeedback = document.getElementById('upload-feedback');

    nextStep1.addEventListener('click', function(e) {
        let valid = true;
        uploadFeedback.classList.add('d-none');
        uploadFeedback.textContent = '';
        uploadFeedback.classList.remove('error', 'success');
        fileInput.classList.remove('is-invalid');
        fileUploadLabel.classList.remove('text-danger');
        // Validação do arquivo
        if (!fileInput.files.length) {
            valid = false;
            fileInput.classList.add('is-invalid');
            fileUploadLabel.classList.add('text-danger');
            uploadFeedback.classList.remove('d-none');
            uploadFeedback.classList.add('error');
            uploadFeedback.textContent = 'Selecione um arquivo PDF ou CSV.';
        } else {
            const file = fileInput.files[0];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!["pdf", "csv"].includes(ext)) {
                valid = false;
                fileInput.classList.add('is-invalid');
                fileUploadLabel.classList.add('text-danger');
                uploadFeedback.classList.remove('d-none');
                uploadFeedback.classList.add('error');
                uploadFeedback.textContent = 'Arquivo inválido. Apenas PDF ou CSV são aceitos.';
            }
        }
        if (!valid) {
            e.preventDefault();
            return false;
        }
    });

    // Step 2: Confirmação
    const confirmationForm = document.getElementById('confirmationForm');
    if (confirmationForm) {
        confirmationForm.addEventListener('submit', function(e) {
            let valid = true;
            let firstInvalid = null;
            let errorMsg = '';
            // Limpar erros anteriores
            document.querySelectorAll('#transactionRows input, #transactionRows select').forEach(function(el) {
                el.classList.remove('is-invalid');
            });
            document.getElementById('step2-error-msg')?.remove();
            // Validar cada linha
            document.querySelectorAll('#transactionRows tr').forEach(function(row) {
                const date = row.querySelector('input[type="date"]');
                const value = row.querySelector('input[type="number"]');
                const desc = row.querySelector('input[type="text"]');
                const cat = row.querySelector('select[name*="[category_id]"]');
                const type = row.querySelector('select[name*="[type_id]"]');
                if (!date.value) { valid = false; date.classList.add('is-invalid'); if (!firstInvalid) firstInvalid = date; }
                if (!desc.value.trim()) { valid = false; desc.classList.add('is-invalid'); if (!firstInvalid) firstInvalid = desc; }
                if (!cat.value) { valid = false; cat.classList.add('is-invalid'); if (!firstInvalid) firstInvalid = cat; }
                if (!type.value) { valid = false; type.classList.add('is-invalid'); if (!firstInvalid) firstInvalid = type; }
                if (!value.value || isNaN(value.value) || Number(value.value) <= 0) {
                    valid = false; value.classList.add('is-invalid'); if (!firstInvalid) firstInvalid = value;
                }
            });
            if (!valid) {
                errorMsg = 'Preencha todos os campos obrigatórios corretamente. Valor deve ser maior que zero.';
                const errorDiv = document.createElement('div');
                errorDiv.id = 'step2-error-msg';
                errorDiv.className = 'alert alert-danger mb-3';
                errorDiv.innerHTML = '<i class="fa fa-exclamation-triangle me-2"></i>' + errorMsg;
                const table = document.querySelector('.stylish-table-responsive');
                if (table) table.parentNode.insertBefore(errorDiv, table);
                if (firstInvalid) firstInvalid.focus();
                e.preventDefault();
                return false;
            }
        });
    }
})();
</script>
<style>
/* Validação visual */
.is-invalid {
    border-color: #e74c3c !important;
    box-shadow: 0 0 0 2px #fcb1b1 !important;
    background: #fff0f0 !important;
}
#upload-feedback.error, .alert-danger {
    color: #e74c3c !important;
    font-weight: 600;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const nextStep1 = document.getElementById('nextStep1');
    const uploadForm = document.getElementById('uploadForm');
    const fileInput = document.getElementById('file-upload');
    const progressBar = document.getElementById('upload-progress-bar');
    const progressBarInner = progressBar.querySelector('.progress-bar');
    const stepProgressBarInner = document.getElementById('step-progress-bar-inner');
    const circleStep1 = document.getElementById('circle-step1');
    const circleStep2 = document.getElementById('circle-step2');
    const dropArea = document.getElementById('drop-area');
    const filePreview = document.getElementById('file-preview');
    const fileIcon = document.getElementById('file-icon');
    const fileNameSpan = document.getElementById('file-name');
    const removeFileBtn = document.getElementById('remove-file');
    const uploadFeedback = document.getElementById('upload-feedback');
    const uploadBtnText = document.getElementById('upload-btn-text');
    const uploadLoading = document.getElementById('upload-loading');

    // Drag and drop
    dropArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropArea.classList.add('dragover');
    });
    dropArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropArea.classList.remove('dragover');
    });
    dropArea.addEventListener('drop', function(e) {
        e.preventDefault();
        dropArea.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    // Preview do arquivo
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            filePreview.classList.remove('d-none');
            fileNameSpan.textContent = file.name;
            if (file.name.endsWith('.pdf')) {
                fileIcon.className = 'fa fa-file-pdf fa-2x me-2';
            } else if (file.name.endsWith('.csv')) {
                fileIcon.className = 'fa fa-file-csv fa-2x me-2';
            } else {
                fileIcon.className = 'fa fa-file-alt fa-2x me-2';
            }
            document.getElementById('file-upload-label').textContent = 'Trocar arquivo';
        } else {
            filePreview.classList.add('d-none');
            fileNameSpan.textContent = '';
            document.getElementById('file-upload-label').textContent = 'Escolher arquivo PDF ou CSV';
        }
    });
    removeFileBtn.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.classList.add('d-none');
        fileNameSpan.textContent = '';
        document.getElementById('file-upload-label').textContent = 'Escolher arquivo PDF ou CSV';
    });

    // Ação do botão de "Próximo"
    nextStep1.addEventListener('click', function() {
        uploadFeedback.classList.add('d-none');
        uploadFeedback.textContent = '';
        uploadFeedback.classList.remove('success', 'error');
        uploadBtnText.textContent = 'Próximo';
        uploadLoading.classList.remove('d-none');
        if (!fileInput.files.length) {
            alert('Por favor, selecione um arquivo antes de continuar.');
            return;
        }
        const formData = new FormData(uploadForm);
        progressBar.style.display = 'block'; // Mostrar a barra de progresso
        let progress = 0;
        const interval = setInterval(function() {
            if (progress < 100) {
                progress += 10; // Simula o progresso
                progressBarInner.style.width = progress + '%';
                progressBarInner.setAttribute('aria-valuenow', progress);
                progressBarInner.textContent = progress + '%'; // Exibe a porcentagem
            } else {
                clearInterval(interval); // Para o intervalo quando chega em 100%
            }
        }, 300);
        // Enviar o arquivo via AJAX
        fetch('{{ route('cashbook.upload') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
            .then(response => {
                // Verificar se a resposta é um JSON
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('A resposta do servidor não é um JSON válido.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Atualiza o Step 2 com as transações retornadas
                    step2.classList.remove('d-none'); // Mostrar Step 2
                    step1.classList.add('d-none'); // Esconder Step 1
                    // Atualiza a barra de progresso e os círculos
                    stepProgressBarInner.style.width = '100%';
                    circleStep1.classList.remove('active');
                    circleStep2.classList.add('active');
                    const transactionRows = document.getElementById('transactionRows');
                    transactionRows.innerHTML = ''; // Limpar as linhas existentes
                    data.transactions.forEach((transaction, index) => {
                        // Verificar se a data está presente e no formato correto
                        if (!transaction.date || !/^\d{2}-\d{2}-\d{4}$/.test(transaction.date)) {
                            console.error(`Data inválida ou ausente para a transação: ${JSON.stringify(transaction)}`);
                            alert('Erro: Uma ou mais transações possuem data inválida ou ausente.');
                            return;
                        }

                        // Converter a data para o formato YYYY-MM-DD para o campo de data
                        const parts = transaction.date.split('-');
                        let formattedDate = transaction.date;
                        if(parts.length === 3 && parts[2].length === 4) {
                            formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                        }
                        const value = String(transaction.value).replace(',', '.').replace(/[^\d.-]/g, '');

                        transactionRows.innerHTML += `
                <tr data-index="${index}">
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row" title="Remover">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-icon icon-calendar"><i class="fa fa-calendar-alt"></i></span>
                            <input type="date" name="transactions[${index}][date]" value="${formattedDate}" class="form-control" required>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-icon icon-money"><i class="fa fa-dollar-sign"></i></span>
                            <input type="number" name="transactions[${index}][value]" value="${value}" class="form-control" step="0.01" required>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-icon icon-desc"><i class="fa fa-align-left"></i></span>
                            <input type="text" name="transactions[${index}][description]" value="${transaction.description}" class="form-control"  required placeholder="Descrição">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-icon icon-tags"><i class="fa fa-tags"></i></span>
                            <select name="transactions[${index}][category_id]" class="form-control stylish-select-icon" style="width: 200px;" required>
                                <option value="" disabled ${transaction.category_id ? '' : 'selected'}>Selecione</option>
                                ${data.categories.map(category => {
                                    const isSelected = category.id == transaction.category_id ? 'selected' : '';
                                    return `<option value="${category.id}" ${isSelected}>${category.name}</option>`;
                                }).join('')}
                            </select>
                            <span class="input-icon input-icon-select icon-tags"><i class="fa fa-tags"></i></span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-icon icon-type"><i class="fa fa-exchange-alt"></i></span>
                            <select name="transactions[${index}][type_id]" class="form-control stylish-select-icon" required>
                                <option value="1" ${transaction.type_id == 1 ? 'selected' : ''}>Receita</option>
                                <option value="2" ${transaction.type_id == 2 ? 'selected' : ''}>Despesa</option>
                            </select>
                            <span class="input-icon input-icon-select icon-type"><i class="fa fa-exchange-alt"></i></span>
                        </div>
                        <span class="badge ${transaction.type_id == 1 ? 'badge-receita' : 'badge-despesa'} ms-1">
                            <i class="fa ${transaction.type_id == 1 ? 'fa-arrow-up' : 'fa-arrow-down'}"></i> ${transaction.type_id == 1 ? 'Receita' : 'Despesa'}
                        </span>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-icon icon-user"><i class="fa fa-user"></i></span>
                            <select name="transactions[${index}][client_id]" class="form-control stylish-select-icon">
                                <option value="" ${!transaction.client_id ? 'selected' : ''}>Nenhum cliente</option>
                                ${data.clients.map(client => {
                                    const isSelected = client.id == transaction.client_id ? 'selected' : '';
                                    return `<option value="${client.id}" ${isSelected}>${client.name}</option>`;
                                }).join('')}
                            </select>
                            <span class="input-icon input-icon-select icon-user"><i class="fa fa-user"></i></span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-icon icon-piggy"><i class="fa fa-piggy-bank"></i></span>
                            <select name="transactions[${index}][cofrinho_id]" class="form-control stylish-select-icon">
                                <option value="">Nenhum</option>
                                ${data.cofrinhos.map(cofrinho => {
                                    const isSelected = cofrinho.id == transaction.cofrinho_id ? 'selected' : '';
                                    return `<option value="${cofrinho.id}" ${isSelected}>${cofrinho.nome}</option>`;
                                }).join('')}
                            </select>
                            <span class="input-icon input-icon-select icon-piggy"><i class="fa fa-piggy-bank"></i></span>
                        </div>
                    </td>
                </tr>
            `;
                    });
                    // Adiciona funcionalidade de remoção de linha
                    const removeButtons = document.querySelectorAll('.remove-row');
                    removeButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const row = this.closest('tr');
                            row.remove();
                        });
                    });
                    // Validação para garantir que não existam valores negativos nas transações
                    const form = document.getElementById('confirmationForm');
                    form.addEventListener('submit', function(event) {
                        const rows = document.querySelectorAll('#transactionRows tr');
                        let hasNegativeValue = false;
                        rows.forEach(function(row) {
                            const valueInput = row.querySelector(
                                'input[name*="[value]"]');
                            const value = parseFloat(valueInput.value);
                            if (value < 0) {
                                hasNegativeValue = true;
                            }
                        });
                        if (hasNegativeValue) {
                            event.preventDefault();
                            alert(
                                'Por favor, insira valores não negativos para todas as transações.'
                            );
                        }
                    });

                } else {
                    alert('Erro ao processar o arquivo. Por favor, tente novamente.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao processar o arquivo: ' + error.message);
            });
    });
});
</script>
