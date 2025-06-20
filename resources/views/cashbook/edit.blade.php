<style>
#editTransactionModal .modal-content {
    background: linear-gradient(135deg, #232526 0%, #414345 100%);
    border-radius: 18px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    border: none;
    color: #fff;
    backdrop-filter: blur(6px);
}
#editTransactionModal .modal-header {
    background: rgba(34, 193, 195, 0.18);
    border-top-left-radius: 18px;
    border-top-right-radius: 18px;
    border-bottom: none;
    padding-bottom: 0.5rem;
}
#editTransactionModal .modal-title {
    font-weight: 700;
    font-size: 1.5rem;
    color: #22c1c3;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
#editTransactionModal .btn-close {
    filter: invert(1);
    opacity: 0.7;
    transition: opacity 0.2s;
}
#editTransactionModal .btn-close:hover {
    opacity: 1;
}
#editTransactionModal .modal-body {
    padding: 2rem 2.5rem;
}
#editTransactionModal .form-label {
    font-weight: 600;
    color: #22c1c3;
    margin-bottom: 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
#editTransactionModal .form-control {
    background: rgba(255,255,255,0.08);
    border: 1.5px solid #22c1c3;
    border-radius: 10px;
    color: #fff;
    padding-left: 2.2rem;
    font-size: 1rem;
    transition: border 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(34,193,195,0.07);
}
#editTransactionModal .form-control:focus {
    border-color: #fd6e6a;
    box-shadow: 0 0 0 2px #fd6e6a44;
    background: rgba(255,255,255,0.13);
    color: #fff;
}
/* Ícones nos inputs */
#editTransactionModal .input-group {
    position: relative;
}
#editTransactionModal .input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1.2rem;
    height: 1.2rem;
    fill: #22c1c3;
    opacity: 0.85;
    pointer-events: none;
}
#editTransactionModal .form-control,
#editTransactionModal .choices__inner {
    padding-left: 2.5rem !important;
}
#editTransactionModal .modal-footer {
    border-top: none;
    background: transparent;
    padding-bottom: 1.5rem;
    padding-top: 0.5rem;
}
#editTransactionModal .btn {
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
#editTransactionModal .btn-primary {
    background: linear-gradient(90deg, #22c1c3 0%, #fd6e6a 100%);
    border: none;
    color: #fff;
}
#editTransactionModal .btn-primary:hover {
    background: linear-gradient(90deg, #fd6e6a 0%, #22c1c3 100%);
    color: #fff;
    box-shadow: 0 4px 16px #fd6e6a44;
}
#editTransactionModal .btn-success {
    background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
    border: none;
    color: #232526;
}
#editTransactionModal .btn-success:hover {
    background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
    color: #232526;
    box-shadow: 0 4px 16px #43e97b44;
}
#editTransactionModal .btn-secondary {
    background: #232526;
    border: 1.5px solid #22c1c3;
    color: #22c1c3;
}
#editTransactionModal .btn-secondary:hover {
    background: #414345;
    color: #fd6e6a;
    border-color: #fd6e6a;
}
#editTransactionModal select.form-control {
    appearance: none;
    background: rgba(255,255,255,0.08);
    border: 1.5px solid #22c1c3;
    border-radius: 10px;
    color: #fff;
    font-size: 1rem;
    padding-left: 2.2rem;
    padding-right: 2.5rem;
    background-image: url('data:image/svg+xml;utf8,<svg fill="none" stroke="%2322c1c3" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>');
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1.2em;
    box-shadow: 0 2px 8px rgba(34,193,195,0.07);
    transition: border 0.2s, box-shadow 0.2s;
}
#editTransactionModal select.form-control:focus {
    border-color: #fd6e6a;
    box-shadow: 0 0 0 2px #fd6e6a44;
    background: rgba(255,255,255,0.13);
    color: #fff;
}
#editTransactionModal select.form-control option {
    background: #232526;
    color: #fff;
}
#editTransactionModal input[type="file"].form-control {
    padding-left: 0.75rem;
}
</style>

<div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="editTransactionForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTransactionModalLabel">
                        <i class="bi bi-pencil-square"></i>
                        Editar Transação
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_value" class="form-label">
                                <i class="bi bi-currency-dollar"></i>
                                Valor
                            </label>
                            <div class="input-group">
                                <span class="input-icon"><i class="bi bi-currency-dollar"></i></span>
                                <input type="number" step="0.01" class="form-control" id="edit_value" name="value" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_description" class="form-label">
                                <i class="bi bi-card-text"></i>
                                Descrição
                            </label>
                            <div class="input-group">
                                <span class="input-icon"><i class="bi bi-card-text"></i></span>
                                <input type="text" class="form-control" id="edit_description" name="description">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_date" class="form-label">
                                <i class="bi bi-calendar-event"></i>
                                Data
                            </label>
                            <div class="input-group">
                                <span class="input-icon"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" class="form-control" id="edit_date" name="date" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_is_pending" class="form-label">
                                <i class="bi bi-hourglass-split"></i>
                                Pendente
                            </label>
                            <div class="input-group">
                                <span class="input-icon"><i class="bi bi-hourglass-split"></i></span>
                                <select class="form-control choices-select" id="edit_is_pending" name="is_pending" required>
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_category_id" class="form-label">
                                <i class="bi bi-tags"></i>
                                Categoria
                            </label>
                            <div class="input-group">
                                <span class="input-icon"><i class="bi bi-tags"></i></span>
                                <select class="form-control choices-select" id="edit_category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_type_id" class="form-label">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Tipo
                            </label>
                            <div class="input-group">
                                <span class="input-icon"><i class="bi bi-box-arrow-in-right"></i></span>
                                <select class="form-control choices-select" id="edit_type_id" name="type_id" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id_type }}">{{ $type->desc_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_note" class="form-label">
                                <i class="bi bi-stickies"></i>
                                Nota
                            </label>
                            <div class="input-group">
                                <span class="input-icon"><i class="bi bi-stickies"></i></span>
                                <textarea class="form-control" id="edit_note" name="note"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_segment_id" class="form-label">
                                <i class="bi bi-diagram-3"></i>
                                Segmento
                            </label>
                            <div class="input-group">
                                <span class="input-icon"><i class="bi bi-diagram-3"></i></span>
                                <select class="form-control choices-select" id="edit_segment_id" name="segment_id">
                                    <option value="">Nenhum</option>
                                    @foreach($segments as $segment)
                                        <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_client_id" class="form-label">
                                <i class="bi bi-person"></i>
                                Cliente
                            </label>
                            <div class="input-group">
                                <span class="input-icon"><i class="bi bi-person"></i></span>
                                <select class="form-control choices-select" id="edit_client_id" name="client_id">
                                    <option value="" selected>Nenhum cliente</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_attachment" class="form-label">
                                <i class="bi bi-paperclip"></i>
                                Anexo
                            </label>
                            <div class="input-group">
                                <span class="input-icon"><i class="bi bi-paperclip"></i></span>
                                <input type="file" class="form-control" id="edit_attachment" name="attachment">
                                <span id="edit_attachment_filename" class="ms-2 text-info small"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_cofrinho_id" class="form-label">Cofrinho</label>
                        <select class="form-control choices-select" id="edit_cofrinho_id" name="cofrinho_id">
                            <option value="">Nenhum</option>
                            @foreach($cofrinhos as $cofrinho)
                                <option value="{{ $cofrinho->id }}">{{ $cofrinho->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Fechar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i>
                        Salvar Alterações
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('#editTransactionModal .choices-select');
    const choicesInstances = [];
    selects.forEach(function(select) {
        const instance = new Choices(select, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false,
            placeholder: true,
            placeholderValue: select.querySelector('option[value=""]') ? select.querySelector('option[value=""]').textContent : 'Selecione...',
            classNames: {
                containerOuter: 'choices choices--modal'
            }
        });
        choicesInstances.push({select, instance});
    });

    // Mostrar nome do arquivo selecionado
    const fileInput = document.getElementById('edit_attachment');
    const fileNameSpan = document.getElementById('edit_attachment_filename');
    if(fileInput && fileNameSpan) {
        fileInput.addEventListener('change', function() {
            fileNameSpan.textContent = fileInput.files.length ? fileInput.files[0].name : '';
        });
    }

    // Preencher selects ao abrir o modal de edição
    document.addEventListener('show.bs.modal', function(event) {
        const modal = event.target;
        if(modal.id === 'editTransactionModal') {
            // O botão que abriu o modal deve ter os data-* com os valores
            const button = event.relatedTarget;
            if(!button) return;

            // Exemplo: data-client_id="3"
            const clientId = button.getAttribute('data-client_id');
            if(clientId !== null) {
                const clientSelect = document.getElementById('edit_client_id');
                clientSelect.value = clientId;
                // Atualiza Choices.js
                const found = choicesInstances.find(obj => obj.select === clientSelect);
                if(found) found.instance.setChoiceByValue(clientId);
            }
            // Preencher cofrinho
            const cofrinhoId = button.getAttribute('data-cofrinho_id');
            if(cofrinhoId !== null) {
                const cofrinhoSelect = document.getElementById('edit_cofrinho_id');
                cofrinhoSelect.value = cofrinhoId;
                const found = choicesInstances.find(obj => obj.select === cofrinhoSelect);
                if(found) found.instance.setChoiceByValue(cofrinhoId);
            }
            // Repita para outros selects se necessário (category_id, type_id, etc)
        }
    });
});
</script>

