<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="" method="POST" id="editCategoryForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <input type="text" name="type" id="editCategoryType">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoria</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Step 1 -->
                    <div class="step" id="editStep1">
                        <h6 class="text-primary">Passo 1: Informações Básicas</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="editCategoryName" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="editCategoryName" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editCategoryDescription" class="form-label">Descrição</label>
                                <textarea class="form-control" id="editCategoryDescription" name="desc_category" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="editCategoryColor" class="form-label">Cor (Hexadecimal)</label>
                                <input type="color" class="form-control form-control-color" id="editCategoryColor" name="hexcolor_category" value="#000000" title="Escolha uma cor">
                            </div>
                            <div class="col-md-6">
                                <label for="editCategoryParentId" class="form-label">Categoria Pai</label>
                                <select class="form-control" id="editCategoryParentId" name="parent_id">
                                    <option value="">Nenhuma</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="editCategoryDetailedDescription" class="form-label">Descrição Detalhada</label>
                                <textarea class="form-control" id="editCategoryDetailedDescription" name="descricao_detalhada" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="editCategoryTags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="editCategoryTags" name="tags" placeholder="Ex: tag1, tag2">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="editCategoryRegras" class="form-label">Regras de Auto-Categorização</label>
                                <textarea class="form-control" id="editCategoryRegras" name="regras_auto_categorizacao" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="editCategoryIsActive" class="form-label">Ativo</label>
                                <select class="form-control" id="editCategoryIsActive" name="is_active">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="step d-none" id="editStep2">
                        <h6 class="text-primary">Passo 2: Escolha um Ícone</h6>
                        <div class="row" id="editIconSelection">
                            <!-- Ícones serão carregados dinamicamente aqui -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary d-none" id="editPrevStep" disabled>Anterior</button>
                    <button type="button" class="btn btn-primary" id="editNextStep">Próximo</button>
                    <button type="submit" class="btn btn-warning d-none" id="editSaveCategory">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editSteps = document.querySelectorAll('#editCategoryModal .step');
        var editCurrentStep = 0;

        function updateEditStepButtons() {
            document.getElementById('editPrevStep').disabled = editCurrentStep === 0;
            document.getElementById('editNextStep').classList.toggle('d-none', editCurrentStep === editSteps.length - 1);
            document.getElementById('editSaveCategory').classList.toggle('d-none', editCurrentStep !== editSteps.length - 1);
            document.getElementById('editPrevStep').classList.toggle('d-none', editCurrentStep === 0);
        }

        document.getElementById('editNextStep').addEventListener('click', function () {
            editSteps[editCurrentStep].classList.add('d-none');
            editCurrentStep++;
            editSteps[editCurrentStep].classList.remove('d-none');
            updateEditStepButtons();
        });

        document.getElementById('editPrevStep').addEventListener('click', function () {
            editSteps[editCurrentStep].classList.add('d-none');
            editCurrentStep--;
            editSteps[editCurrentStep].classList.remove('d-none');
            updateEditStepButtons();
        });

        var editCategoryModal = document.getElementById('editCategoryModal');
        editCategoryModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var categoryId = button.getAttribute('data-id');
            var type = button.getAttribute('data-type'); // Obtenha o tipo do botão

            if (!type) {
                console.error('Atributo data-type ausente no botão que abriu o modal de edição.');
                alert('Erro: O tipo da categoria não foi especificado. Por favor, verifique o botão que abriu o modal.');
                return;
            }

            document.getElementById('editCategoryForm').action = "{{ route('categories.update', '') }}/" + categoryId;
            document.getElementById('editCategoryType').value = type; // Preencha o campo hidden com o tipo
            document.getElementById('editCategoryName').value = button.getAttribute('data-name');
            document.getElementById('editCategoryDescription').value = button.getAttribute('data-desc');
            document.getElementById('editCategoryColor').value = button.getAttribute('data-color');
            document.getElementById('editCategoryParentId').value = button.getAttribute('data-parent-id');
            document.getElementById('editCategoryDetailedDescription').value = button.getAttribute('data-detailed-desc');
            document.getElementById('editCategoryTags').value = button.getAttribute('data-tags');
            document.getElementById('editCategoryRegras').value = button.getAttribute('data-regras');
            document.getElementById('editCategoryIsActive').value = button.getAttribute('data-is-active');

            // Carregar ícones com base no tipo
            loadIconsBasedOnTypeForEdit(type);
        });

        function loadIconsBasedOnTypeForEdit(type) {
            var iconSelection = document.getElementById('editIconSelection');
            if (!iconSelection) return;

            iconSelection.innerHTML = ''; // Limpa os ícones existentes

            var icons = [];

            // Ícones para 'product'
            if (type === 'product') {
                icons = [
                    'bi-shop', 'bi-bag', 'bi-cart', 'bi-box', 'bi-gift', 'bi-camera', 'bi-laptop',
                    'bi-music-note', 'bi-umbrella', 'bi-basket', 'bi-tv', 'bi-cup', 'bi-apple', 'bi-phone',
                    'bi-headphones', 'bi-watch', 'bi-cloud', 'bi-clock', 'bi-calendar', 'bi-palette', 'icons8-urso',
                    'icons8-perfume', 'icons8-roupa', 'icons8-celular', 'icons8-computador', 'icons8-livro', 'icons8-box',
                ];
            }
            // Ícones para 'transaction'
            else if (type === 'transaction') {
                icons = [
                    'icons8-pagamento', 'icons8-pix', 'icons8-rendimento', 'icons8-cartao ', 'icons8-supermercado', 'icons8-posto',
                    'icons8-online', 'icons8-transferencia', 'bi-geo', 'bi-file-earmark', 'bi-bell', 'bi-calendar-check',
                    'bi-clipboard', 'bi-bell', 'bi-briefcase', 'icons8-academia', 'icons8-xp', 'bi-graph-up',
                    'bi-cart-check', 'fi-rs-dumbbell-weightlifting', 'icons8-nubank', 'icons8-inter', 'icons8-restaurante','icons8-beleza','icons8-farmacia', 'icons8-viagem','icons8-steaming ','icons8-academia',
                ];
            } else {
                console.error('Tipo inválido fornecido: ' + type);
                return;
            }

            // Adicionar ícones ao modal
            icons.forEach(function (icon) {
                var iconHTML = `
                    <div class="col-2 text-center">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input d-none icon-radio" name="icone" value="${icon}">
                            <div class="card p-2 icon-card">
                                <i class="${icon}" style="font-size: 60px;"></i>
                            </div>
                        </label>
                    </div>`;
                iconSelection.innerHTML += iconHTML;
            });

            // Destacar ícone selecionado
            document.querySelectorAll('#editIconSelection .icon-radio').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    document.querySelectorAll('#editIconSelection .icon-card').forEach(function (card) {
                        card.classList.remove('border', 'border-success', 'shadow-lg');
                    });
                    if (radio.checked) {
                        radio.closest('.icon-card').classList.add('border', 'border-success', 'shadow-lg');
                    }
                });
            });
        }
    });
</script>
