<!-- Modal para criar categoria -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $userId }}">
                <input type="hidden" name="type" id="categoryType">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createCategoryModalLabel">Criar Nova Categoria: <span
                            id="modalCategoryType"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Step 1 -->
                    <div class="step" id="step1">
                        <h6 class="text-primary">Passo 1: Informações Básicas</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="categoryName" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="categoryName" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="categoryDescription" class="form-label">Descrição</label>
                                <textarea class="form-control" id="categoryDescription" name="desc_category"
                                    rows="2"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="categoryColor" class="form-label">Cor (Hexadecimal)</label>
                                <input type="color" class="form-control form-control-color" id="categoryColor"
                                    name="hexcolor_category" value="#000000" title="Escolha uma cor">
                            </div>
                            <div class="col-md-6">
                                <label for="categoryParentId" class="form-label">Categoria Pai</label>
                                <select class="form-control" id="categoryParentId" name="parent_id">
                                    <option value="">Nenhuma</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="categoryDetailedDescription" class="form-label">Descrição Detalhada</label>
                                <textarea class="form-control" id="categoryDetailedDescription"
                                    name="descricao_detalhada" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="categoryTags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="categoryTags" name="tags"
                                    placeholder="Ex: tag1, tag2">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="categoryRegras" class="form-label">Regras de Auto-Categorização</label>
                                <textarea class="form-control" id="categoryRegras" name="regras_auto_categorizacao"
                                    rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="categoryIsActive" class="form-label">Ativo</label>
                                <select class="form-control" id="categoryIsActive" name="is_active">
                                    <option value="1" selected>Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="step d-none" id="createStep2">
                        <h6 class="text-primary">Passo 2: Escolha um Ícone</h6>
                        <div class="row" id="createIconSelection">
                            <!-- Ícones serão carregados dinamicamente aqui -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary d-none" id="createPrevStep" disabled>Anterior</button>
                    <button type="button" class="btn btn-primary" id="createNextStep">Próximo</button>
                    <button type="submit" class="btn btn-success d-none" id="createSaveCategory">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var createSteps = document.querySelectorAll('#createCategoryModal .step');
        var createCurrentStep = 0;

        function updateCreateStepButtons() {
            document.getElementById('createPrevStep').disabled = createCurrentStep === 0;
            document.getElementById('createNextStep').classList.toggle('d-none', createCurrentStep === createSteps.length - 1);
            document.getElementById('createSaveCategory').classList.toggle('d-none', createCurrentStep !== createSteps.length - 1);
            document.getElementById('createPrevStep').classList.toggle('d-none', createCurrentStep === 0);
        }

        document.getElementById('createNextStep').addEventListener('click', function () {
            createSteps[createCurrentStep].classList.add('d-none');
            createCurrentStep++;
            createSteps[createCurrentStep].classList.remove('d-none');
            updateCreateStepButtons();
        });

        document.getElementById('createPrevStep').addEventListener('click', function () {
            createSteps[createCurrentStep].classList.add('d-none');
            createCurrentStep--;
            createSteps[createCurrentStep].classList.remove('d-none');
            updateCreateStepButtons();
        });

        var createCategoryModal = document.getElementById('createCategoryModal');
        createCategoryModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var type = button.getAttribute('data-type'); // Obtenha o tipo do botão
            document.getElementById('modalCategoryType').textContent = button.getAttribute('data-title');
            document.getElementById('categoryType').value = type; // Preencha o campo hidden com o tipo

            // Carregar ícones com base no tipo
            loadIconsBasedOnTypeForCreate(type);
        });

        function loadIconsBasedOnTypeForCreate(type) {
            var iconSelection = document.getElementById('createIconSelection');
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
            document.querySelectorAll('#createIconSelection .icon-radio').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    document.querySelectorAll('#createIconSelection .icon-card').forEach(function (card) {
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

<style>
    .icon-card {
        cursor: pointer;
        transition: transform 0.2s ease-in-out;
    }

    .icon-card:hover {
        transform: scale(1.1);
    }

    .icon-card.border-success {
        transform: scale(1.2);
    }

    .modal-header {
        border-bottom: 2px solid #007bff;
    }

    .modal-footer {
        border-top: 2px solid #e9ecef;
    }

   
</style>
