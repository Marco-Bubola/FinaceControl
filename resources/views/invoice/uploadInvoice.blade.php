@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
    #uploadModal {
        /* Garante que o escopo seja só do modal */
    }
    #uploadModal .modal-content {
        background: linear-gradient(135deg, #f8fafc 80%, #e3f0fc 100%);
        border-radius: 2rem;
        box-shadow: 0 8px 32px 0 rgba(52, 152, 219, 0.15);
        border: none;
    }
    #uploadModal .modal-header {
        background: linear-gradient(90deg, #3498db 60%, #1d72b8 100%);
        border-top-left-radius: 2rem;
        border-top-right-radius: 2rem;
        box-shadow: 0 2px 8px #b6d4fa;
    }
    #uploadModal .modal-title {
        font-size: 2.2rem;
        letter-spacing: 0.02em;
    }
    #uploadModal .btn-close {
        filter: brightness(1.2);
    }
    #uploadModal .upload-modal-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e3eaf6;
        color: #3498db;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin: 0 auto;
        box-shadow: 0 1px 4px #e0e7ef;
        transition: background 0.3s, color 0.3s, box-shadow 0.3s;
        border: 2px solid #e3eaf6;
    }
    #uploadModal .upload-modal-circle.active {
        background: linear-gradient(135deg, #3498db 60%, #1d72b8 100%);
        color: #fff;
        border-color: #3498db;
        box-shadow: 0 2px 8px #b6d4fa;
    }
    #uploadModal .upload-modal-step-label {
        font-size: 0.98rem;
        color: #1d3557;
        font-weight: 500;
        letter-spacing: 0.01em;
        opacity: 0.85;
        margin-top: 0.1rem;
        display: inline-block;
    }
    #uploadModal .upload-modal-step-progress {
        height: 6px !important;
        background: #e3eaf6 !important;
        border-radius: 4px;
        box-shadow: none;
    }
    #uploadModal .upload-modal-step-progress .progress-bar {
        background: linear-gradient(90deg, #3498db 60%, #1d72b8 100%);
        border-radius: 4px;
        box-shadow: none;
        font-size: 1.1rem;
    }
    @media (max-width: 600px) {
        #uploadModal .upload-modal-circle {
            width: 28px;
            height: 28px;
            font-size: 1rem;
        }
        #uploadModal .upload-modal-step-label {
            font-size: 0.85rem;
        }
    }
    #uploadModal .upload-modal-confirm-title {
        font-size: 1.7rem;
        color: #1d3557;
        background: linear-gradient(90deg, #e3f0fc 60%, #f8fafc 100%);
        border-radius: 1rem;
        padding: 0.7rem 0;
        box-shadow: 0 2px 8px #e0e7ef;
    }
    #uploadModal .upload-modal-table-area {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px 0 rgba(52, 152, 219, 0.10);
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #e3f0fc;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #3498db #e0e7ef;
    }
    #uploadModal .upload-modal-table-area::-webkit-scrollbar {
        width: 10px;
        background: #e0e7ef;
        border-radius: 8px;
    }
    #uploadModal .upload-modal-table-area::-webkit-scrollbar-thumb {
        background: #3498db;
        border-radius: 8px;
    }
    #uploadModal .upload-modal-table {
        font-size: 1.18rem;
        border-radius: 1rem;
        overflow: hidden;
    }
    #uploadModal .upload-modal-table thead th {
        vertical-align: middle;
        background: linear-gradient(90deg, #e3f0fc 60%, #f8fafc 100%);
        color: #1d3557;
        font-size: 1.18rem;
        border-bottom: 2px solid #3498db;
    }
    #uploadModal .upload-modal-table tbody tr {
        transition: background 0.2s;
    }
    #uploadModal .upload-modal-table tbody tr:hover {
        background: #e3f0fc;
        box-shadow: 0 2px 8px #e0e7ef;
    }
    #uploadModal .upload-modal-table td, 
    #uploadModal .upload-modal-table th {
        vertical-align: middle !important;
        border: none;
    }
    #uploadModal .upload-modal-table input,
    #uploadModal .upload-modal-table select {
        border-radius: 0.7rem !important;
        border: 1.5px solid #b6d4fa !important;
        font-size: 1.1rem;
        background: #f8fafc;
        transition: border 0.2s;
    }
    #uploadModal .upload-modal-table input:focus,
    #uploadModal .upload-modal-table select:focus {
        border-color: #3498db !important;
        box-shadow: 0 0 0 2px #b6d4fa33;
    }
    #uploadModal .upload-modal-table .btn-danger {
        border-radius: 50%;
        padding: 0.5rem 0.7rem;
        font-size: 1.1rem;
        transition: background 0.2s, box-shadow 0.2s;
    }
    #uploadModal .upload-modal-table .btn-danger:hover {
        background: #e74c3c;
        box-shadow: 0 2px 8px #e0e7ef;
    }
    #uploadModal .upload-modal-back-btn {
        border-radius: 1.5rem;
        box-shadow: 0 2px 8px #b6d4fa;
        padding: 0.8rem 3rem;
        transition: background 0.2s, box-shadow 0.2s, color 0.2s;
        border-width: 2px;
        color: #1d3557;
        background: #fff;
    }
    #uploadModal .upload-modal-back-btn:hover {
        background: #e3f0fc !important;
        color: #3498db !important;
        box-shadow: 0 4px 16px #b6d4fa;
        border-color: #3498db;
    }
    #uploadModal .upload-modal-confirm-btn {
        font-size: 1.3rem;
        border-radius: 1.5rem;
        box-shadow: 0 2px 8px #b6d4fa;
        padding: 0.8rem 3rem;
        transition: background 0.2s, box-shadow 0.2s;
    }
    #uploadModal .upload-modal-confirm-btn:hover {
        background: #218838 !important;
        box-shadow: 0 4px 16px #b6d4fa;
    }
    #uploadModal .form-group.d-flex {
        gap: 1.5rem;
    }
    @media (max-width: 600px) {
        #uploadModal .form-group.d-flex {
            flex-direction: column;
            gap: 0.7rem;
        }
    }
    </style>
@endpush

<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 80vw;">
        <div class="modal-content shadow-lg rounded-4 border-0" style="min-height: 60vh;">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h3 class="modal-title d-flex align-items-center gap-2" id="uploadModalLabel" style="font-size: 2rem;">
                    <i class="fa-solid fa-cloud-arrow-up fa-lg"></i>
                    Upload e Confirmação de Faturas
                </h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body px-5 py-4" style="background: #f8fafc;">
                <!-- Step Indicator Compacto e Estiloso -->
                <div class="mb-4" id="step-indicator">
                    <div class="d-flex justify-content-between align-items-center" style="min-height: 48px;">
                        <div class="text-center flex-grow-1">
                            <div class="upload-modal-circle active" id="circle-step1">
                                <i class="fa-solid fa-file-arrow-up"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress upload-modal-step-progress">
                                <div class="progress-bar" id="step-progress-bar-inner" style="width: 50%;"></div>
                            </div>
                        </div>
                        <div class="text-center flex-grow-1">
                            <div class="upload-modal-circle" id="circle-step2">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <div class="text-center flex-grow-1">
                            <span class="upload-modal-step-label">Upload de Faturas</span>
                        </div>
                        <div class="text-center flex-grow-1">
                            <span class="upload-modal-step-label">Confirmação de Transações</span>
                        </div>
                    </div>
                </div>
                <div id="uploadSteps">
                    <!-- Step 1: Upload de Faturas -->
                    <div id="step1" class="step">
                        <h4 class="text-center mb-4 fw-bold" style="font-size: 1.6rem;">
                            <i class="fa-solid fa-file-import me-2"></i>
                            Envio de Fatura em PDF ou CSV
                        </h4>
                        <form id="uploadForm" method="POST" action="{{ route('invoices.upload') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group text-center mb-4">
                                <label for="file-upload" class="btn btn-outline-primary btn-lg px-5 py-3 fw-bold" style="font-size: 1.3rem;">
                                    <i class="fa-solid fa-file-arrow-up fa-xl me-2"></i>
                                    Escolher arquivo PDF ou CSV
                                </label>
                                <input type="file" id="file-upload" name="file" accept=".pdf, .csv"
                                    style="display: none;" required />
                                <br>
                                <small class="form-text text-muted mt-2" style="font-size: 1.1rem;">
                                    <i class="fa-solid fa-circle-info me-1"></i>
                                    Somente arquivos PDF ou CSV são aceitos.
                                </small>
                            </div>

                            <!-- Barra de Progresso -->
                            <div class="progress mb-3" style="height: 30px; display: none;" id="upload-progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated fw-bold"
                                    role="progressbar"
                                    style="width: 0%; background-color: #3498db; font-size: 1.2rem;"
                                    aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100">0%</div>
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="button" class="btn btn-primary btn-lg px-5 py-2 fw-bold" id="nextStep1" style="font-size: 1.3rem;">
                                    Próximo <i class="fa-solid fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Step 2: Confirmar Transações -->
                    <div id="step2" class="step d-none">
                       
                        <form id="confirmationForm" method="POST" action="{{ route('invoices.confirm') }}">
                            @csrf
                            <input type="hidden" name="id_bank" value="{{ $bank->id_bank }}">

                            <div class="upload-modal-table-area table-responsive" style="max-height: 60vh;">
                                <table class="table table-striped table-bordered align-middle text-center mb-0 upload-modal-table">
                                    <thead class="table-primary sticky-top">
                                        <tr>
                                            <th><i class="fa-solid fa-trash"></i></th>
                                            <th><i class="fa-solid fa-calendar-day"></i> Data</th>
                                            <th><i class="fa-solid fa-money-bill-wave"></i> Valor</th>
                                            <th><i class="fa-solid fa-align-left"></i> Descrição</th>
                                            <th><i class="fa-solid fa-layer-group"></i> Parcelas</th>
                                            <th><i class="fa-solid fa-tags"></i> Categoria</th>
                                            <th><i class="fa-solid fa-user"></i> Cliente</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transactionRows">
                                        <!-- As transações serão carregadas dinamicamente via JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group d-flex justify-content-center gap-3 mt-4 flex-wrap">
                                <button type="button" class="btn btn-outline-secondary btn-lg px-5 py-2 fw-bold upload-modal-back-btn" id="backToStep1" style="font-size: 1.2rem;">
                                    <i class="fa-solid fa-arrow-left me-2"></i>
                                    Voltar
                                </button>
                                <button type="submit" class="btn btn-success btn-lg px-5 py-2 fw-bold upload-modal-confirm-btn">
                                    <i class="fa-solid fa-circle-check me-2"></i>
                                    Confirmar Transações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const nextStep1 = document.getElementById('nextStep1');
    const backToStep1 = document.getElementById('backToStep1');
    const uploadForm = document.getElementById('uploadForm');
    const fileInput = document.getElementById('file-upload');
    const progressBar = document.getElementById('upload-progress-bar');
    const progressBarInner = progressBar.querySelector('.progress-bar');
    const stepProgressBarInner = document.getElementById('step-progress-bar-inner');
    const circleStep1 = document.getElementById('circle-step1');
    const circleStep2 = document.getElementById('circle-step2');
    // Alterar nome do arquivo após a seleção
    fileInput.addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'Escolher arquivo PDF ou CSV';
        document.querySelector('label[for="file-upload"]').innerHTML =
            `<i class="fa-solid fa-file-arrow-up fa-xl me-2"></i> ${fileName}`;
    });
    // Ação do botão de "Próximo"
    nextStep1.addEventListener('click', function() {
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
        fetch('{{ route('invoices.upload') }}', {
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
                        transactionRows.innerHTML += renderInvoiceRow(transaction, index, data);
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
                    
                    // Botão de voltar para o Step 1
                    if (backToStep1) {
                        backToStep1.addEventListener('click', function() {
                            step2.classList.add('d-none');
                            step1.classList.remove('d-none');
                            // Volta a barra de progresso e os círculos
                            stepProgressBarInner.style.width = '50%';
                            circleStep1.classList.add('active');
                            circleStep2.classList.remove('active');
                        });
                    }

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
<style>
.input-icon-invoice {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.18rem;
    pointer-events: none;
    opacity: 0.95;
    z-index: 2;
}
.input-icon-invoice.icon-calendar { color: #ff9966; }
.input-icon-invoice.icon-money { color: #2575fc; }
.input-icon-invoice.icon-desc { color: #ff5e62; }
.input-icon-invoice.icon-tags { color: #6a11cb; }
.input-icon-invoice.icon-user { color: #a6c1ee; }
.input-icon-invoice.icon-parcel { color: #f39c12; }
.input-icon-invoice.input-icon-select { right: 13px; left: auto; }
.stylish-table .input-group { position: relative; display: flex; align-items: center; width: 100%; }
.stylish-table .input-group > .form-control,
.stylish-table .input-group > select.form-control { width: 100%; padding-left: 2.5rem !important; padding-right: 2.5rem !important; }
</style>
<script>
function renderInvoiceRow(transaction, index, data) {
    return `
    <tr data-index="${index}">
        <td>
            <button type="button" class="btn btn-danger btn-sm remove-row" title="Remover">
                <i class="fa fa-trash"></i>
            </button>
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <span class="input-icon-invoice icon-calendar"><i class="fa fa-calendar-alt"></i></span>
                <input type="date" name="transactions[${index}][date]" value="${transaction.invoice_date || ''}" class="form-control ms-0" style="width: 130px;" required>
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <span class="input-icon-invoice icon-money"><i class="fa fa-dollar-sign"></i></span>
                <input type="text" inputmode="decimal" pattern="^\\d+(\\.\\d{2})$" name="transactions[${index}][value]" value="${Number(transaction.valor).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}" class="form-control input-valor ms-0" style="width: 100px;" required placeholder="Value">
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <span class="input-icon-invoice icon-desc"><i class="fa fa-align-left"></i></span>
                <input type="text" name="transactions[${index}][description]" value="${transaction.descricao || ''}" class="form-control ms-0" required placeholder="Descrição">
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <span class="input-icon-invoice icon-parcel"><i class="fa fa-layer-group"></i></span>
                <input type="text" name="transactions[${index}][installments]" value="${transaction.parcelas || ''}" class="form-control ms-0" style="width: 90px;" required placeholder="Parcelas">
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <span class="input-icon-invoice icon-tags"><i class="fa fa-tags"></i></span>
                <select name="transactions[${index}][category_id]" class="form-control stylish-select-icon ms-0" style="width: 200px;" required>
                    <option value="" disabled ${transaction.category_id ? '' : 'selected'}>Selecione</option>
                    ${data.categories.map(category => {
                        const isSelected = category.id == transaction.category_id ? 'selected' : '';
                        return `<option value="${category.id}" ${isSelected}>${category.name}</option>`;
                    }).join('')}
                </select>
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <span class="input-icon-invoice icon-user"><i class="fa fa-user"></i></span>
                <select name="transactions[${index}][client_id]" class="form-control stylish-select-icon ms-0">
                    <option value="" ${!transaction.client_id ? 'selected' : ''}>Nenhum cliente</option>
                    ${data.clients.map(client => {
                        const isSelected = client.id == transaction.client_id ? 'selected' : '';
                        return `<option value="${client.id}" ${isSelected}>${client.name}</option>`;
                    }).join('')}
                </select>
            </div>
        </td>
    </tr>
    `;
}
</script>
