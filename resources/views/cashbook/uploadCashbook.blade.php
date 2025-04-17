<div class="modal fade" id="uploadCashbookModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload e Confirmação de Transações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <!-- Barra de Progresso com Círculos e Títulos -->
                <div class="mb-4" id="step-indicator">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-center">
                            <div class="circle active" id="circle-step1">1</div>
                        </div>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar" id="step-progress-bar-inner" style="width: 50%;"></div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="circle" id="circle-step2">2</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="text-center flex-grow-1">
                            <small>Upload de Transações</small>
                        </div>
                        <div class="text-center flex-grow-1">
                            <small>Confirmação de Transações</small>
                        </div>
                    </div>
                </div>
                <div id="uploadSteps">
                    <!-- Step 1: Upload de Arquivo -->
                    <div id="step1" class="step">
                        <h5 class="text-center mb-3">Envio de Arquivo em PDF ou CSV</h5>
                        <form id="uploadForm" method="POST" action="{{ route('cashbook.upload') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group text-center mb-4">
                                <label for="file-upload" class="btn btn-outline-primary">
                                    <i class="fa fa-file"></i> Escolher arquivo PDF ou CSV
                                </label>
                                <input type="file" id="file-upload" name="file" accept=".pdf, .csv"
                                    style="display: none;" required />
                                <br>
                                <small class="form-text text-muted mt-2">
                                    Somente arquivos PDF ou CSV são aceitos.
                                </small>
                            </div>

                            <!-- Barra de Progresso -->
                            <div class="progress" style="height: 25px; display: none;" id="upload-progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 0%; background-color: #3498db;" aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100">0%</div>
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="button" class="btn btn-primary" id="nextStep1">
                                    Próximo <i class="fa fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Step 2: Confirmar Transações -->
                    <div id="step2" class="step d-none">
                        <h5 class="text-center mb-3">Confirmar Transações</h5>
                        <form id="confirmationForm" method="POST" action="{{ route('cashbook.confirm') }}">
                            @csrf
                           
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Remover </th>
                                            <th>Data</th>
                                            <th>Valor</th>
                                            <th>Descrição</th>
                                            
                                            <th>Categoria</th>
                                            <th>Tipo</th>
                                            <th>Nota</th>
                                            <th>Segmento</th>
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
                                                <input type="date" name="transactions[${index}][date]" value="${transaction.date}" class="form-control" required>
                                            </td>
                                            <td>
                                                <input type="number" name="transactions[${index}][value]" value="${transaction.value}" class="form-control" step="0.01" required>
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
                                                <input type="text" name="transactions[${index}][note]" value="${transaction.note || ''}" class="form-control">
                                            </td>
                                            <td>
                                                <select name="transactions[${index}][segment_id]" class="form-control">
                                                    <option value="" ${!transaction.segment_id ? 'selected' : ''}>Nenhum</option>
                                                    ${(data.segments || []).map(segment => {
                                                        const isSelected = segment.id == transaction.segment_id ? 'selected' : '';
                                                        return `<option value="${segment.id}" ${isSelected}>${segment.name}</option>`;
                                                    }).join('')}
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-success">
                                    Confirmar Transações <i class="fa fa-check"></i>
                                </button>
                            </div>
                        </form>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>

<style>
.circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #ddd;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.circle.active {
    background-color: #3498db;
}

.progress-bar {
    background-color: #3498db;
}

#step-indicator small {
    font-size: 0.9rem;
    color: #555;
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
    // Alterar nome do arquivo após a seleção
    fileInput.addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : 'Escolher arquivo PDF ou CSV';
        document.querySelector('label[for="file-upload"]').innerHTML =
            `<i class="fa fa-file"></i> ${fileName}`;
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
                        const formattedDate = transaction.date.split('-').reverse().join('-');

                        transactionRows.innerHTML += `
                <tr data-index="${index}">
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row" title="Remover">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                    <td>
                        <input type="date" name="transactions[${index}][date]" value="${formattedDate}" class="form-control" style="width: 130px;" required>
                    </td>
                    <td>
                        <input type="number" name="transactions[${index}][value]" value="${transaction.value}" class="form-control" style="width: 100px;" step="0.01" required>
                    </td>
                    <td>
                        <input type="text" name="transactions[${index}][description]" value="${transaction.description}" class="form-control"  required>
                    </td>
                    <td>
                        <select name="transactions[${index}][category_id]" class="form-control" style="width: 200px;" required>
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
                        <input type="text" name="transactions[${index}][note]" value="${transaction.note || ''}" class="form-control">
                    </td>
                    <td>
                        <select name="transactions[${index}][segment_id]" class="form-control">
                            <option value="" ${!transaction.segment_id ? 'selected' : ''}>Nenhum</option>
                            ${(data.segments || []).map(segment => {
                                const isSelected = segment.id == transaction.segment_id ? 'selected' : '';
                                return `<option value="${segment.id}" ${isSelected}>${segment.name}</option>`;
                            }).join('')}
                        </select>
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
