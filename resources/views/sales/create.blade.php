<!-- Modal de Adicionar Venda -->
<div class="modal fade" id="modalAddSale" tabindex="-1" aria-labelledby="modalAddSaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable"> <!-- Adicionado modal-dialog-scrollable -->
        <div class="modal-content" style="max-height: 100vh;"> <!-- Limite de altura para o modal -->
            <div class="modal-header">
                <div class="w-100">
                    <div class="progress-container mb-4">
                        <div class="progress"> <!-- Corrigido para usar a classe "progress" -->
                            <div id="progress-bar" class="progress-bar" style="width: 50%;" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="3"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="step-circle active">1</div>
                            <div class="step-circle">2</div>
                            <div class="step-circle">3</div>
                        </div>
                        <div class="d-flex justify-content-between text-center mt-1">
                            <small>Clientes</small>
                            <small>Produtos</small>
                            <small>Resumo</small>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sales.store') }}" method="POST" enctype="multipart/form-data" id="saleForm">
                    @csrf
                    <!-- Conteúdo das Etapas -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Etapa 1: Cliente -->
                        <div class="tab-pane fade show active" id="step-1" role="tabpanel" aria-labelledby="step-1-tab">
                            <div class="row mb-4" style="min-height: 45vh;">
                                <!-- Pesquisa e Seleção de Cliente -->
                                <div class="col-md-6">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-header bg-primary text-white text-center">
                                            <h5 class="mb-0">Selecione um Cliente</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3 position-relative">
                                                <input type="text" class="form-control" id="clientSearch" placeholder="Digite o nome ou telefone..." onkeyup="filterClients()" onclick="showDropdown()" autocomplete="off" />
                                                <div id="clientDropdown" class="list-group position-absolute w-100" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;">
                                                    @foreach($clients as $client)
                                                        <button type="button" class="list-group-item list-group-item-action"
                                                            onclick="selectClient('{{ $client->id }}', '{{ $client->name }} ({{ $client->phone }})')">
                                                            {{ $client->name }} ({{ $client->phone }})
                                                        </button>
                                                    @endforeach
                                                </div>
                                                <!-- Campo hidden para enviar o client_id -->
                                                <input type="hidden" name="client_id" id="client-id-hidden" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informações do Cliente Selecionado -->
                                <div class="col-md-6" id="client-info" style="display:none;">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-header bg-secondary text-white text-center">
                                            <h5 class="mb-0">Informações do Cliente</h5>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="text-center" id="client-name"></h6>
                                            <p class="text-center text-muted" id="client-email"></p>
                                            <div class="row">
                                                <div class="col-6">
                                                    <p><strong>Telefone:</strong> <span id="client-phone"></span></p>
                                                    <p><strong>Endereço:</strong> <span id="client-address"></span></p>
                                                </div>
                                                <div class="col-6">
                                                    <p><strong>Data de Cadastro:</strong> <span
                                                            id="client-registration_date"></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Etapa 2: Produtos -->
                        <div class="tab-pane fade" id="step-2" role="tabpanel" aria-labelledby="step-2-tab">
                            <!-- Conteúdo fixo no topo -->
                            <div class="sticky-top bg-white pb-3">
                                <div class="col-md-12">
                                    <div class="d-flex mb-3">
                                        <input type="text" class="form-control" id="productSearch"
                                            placeholder="Pesquise por nome ou código do produto..."
                                            onkeyup="searchProducts()" />
                                        <div class="form-check form-switch ms-2 mt-2">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="showSelectedBtn" />
                                            <label class="form-check-label" for="showSelectedBtn">Mostrar apenas
                                                selecionados</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4" style="max-height: 50vh; overflow-y: auto;">
                                <!-- Barra de rolagem no conteúdo -->


                                @foreach($products as $product)
                                    @if($product->stock_quantity > 0) <!-- Apenas produtos com estoque maior que 0 -->
                                        <div class="col-md-3 mb-4 product-card" data-product-id="{{ $product->id }}"
                                            style="opacity: 0.5;">
                                            <div class="card product-item" style="cursor: pointer;">
                                                <!-- Checkbox sobre a imagem -->
        <div class="form-check form-switch">
            <input class="form-check-input product-checkbox" type="checkbox"
                role="switch" id="flexSwitchCheckDefault{{ $product->id }}"
                data-product-id="{{ $product->id }}" />
        </div>
        <img src="{{ asset('storage/products/' . $product->image) }}"
            class="card-img-top" alt="{{ $product->name }}"
            style="height: 200px; object-fit: cover;">
     
                                                <div class="card-body">
                                                    <h5 class="card-title text-center text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                                                    <table class="table table-bordered table-sm">
                                                        <tr>
                                                            <th>Código</th>
                                                            <td>{{ $product->product_code }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Estoque</th>
                                                            <td><span
                                                                    class="product-stock">{{ $product->stock_quantity }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Preço</th>
                                                            <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Preço Venda</th>
                                                            <td>
                                                                <input type="number" class="form-control price-sale-input"
                                                                    name="products[{{ $product->id }}][price_sale]"
                                                                    value="{{ $product->price_sale }}" min="0" step="0.01"
                                                                    disabled />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Qtd</th>
                                                            <td>
                                                                <input type="number"
                                                                    name="products[{{ $product->id }}][quantity]"
                                                                    class="form-control product-quantity" min="1" value="1"
                                                                    disabled />
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <input type="hidden" name="products[{{ $product->id }}][price]"
                                                        value="{{ $product->price }}">
                                                    <input type="hidden" name="products[{{ $product->id }}][product_id]"
                                                        value="{{ $product->id }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Etapa 3: Resumo -->
                        <div class="tab-pane fade" id="step-3" role="tabpanel" aria-labelledby="step-3-tab" style="max-height: 65vh; overflow-y: auto;">

                            <!-- Resumo do Cliente -->
                            <div class="row mb-4" >
                                <div class="col-md-12">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h6 class="card-title text-center mb-4">Cliente</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p><strong>Nome:</strong> <span id="summary-client-name">N/A</span></p>
                                                <p><strong>Telefone:</strong> <span id="summary-client-phone">N/A</span>
                                                </p>
                                                <p><strong>Endereço:</strong> <span
                                                        id="summary-client-address">N/A</span></p>
                                                <p><strong>Data de Cadastro:</strong> <span
                                                        id="summary-client-registration_date">N/A</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumo dos Produtos Selecionados -->
                            <h6 class="text-center mb-3">Produtos Selecionados</h6>
                            <div class="row" id="selected-products-summary">
                                <!-- Aqui os produtos selecionados serão inseridos dinamicamente -->
                            </div>

                            <!-- Tipo de Pagamento e Parcelas -->
                            <div class="row mt-4 justify-content-center">
                                <div class="col-md-4">
                                    <label for="tipo_pagamento" class="form-label"><i class="bi bi-credit-card-2-front"></i> Tipo de Pagamento</label>
                                    <select name="tipo_pagamento" id="tipo_pagamento" class="form-select" required>
                                        <option value="a_vista">À vista</option>
                                        <option value="parcelado">Parcelado</option>
                                    </select>
                                </div>
                                <div class="col-md-4" id="parcelas-group" style="display:none;">
                                    <label for="parcelas" class="form-label"><i class="bi bi-list-ol"></i> Parcelas</label>
                                    <input type="number" name="parcelas" id="parcelas" class="form-control" min="2" max="36" value="2">
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const tipoPagamento = document.getElementById('tipo_pagamento');
                                    const parcelasGroup = document.getElementById('parcelas-group');
                                    tipoPagamento.addEventListener('change', function() {
                                        if (tipoPagamento.value === 'parcelado') {
                                            parcelasGroup.style.display = '';
                                        } else {
                                            parcelasGroup.style.display = 'none';
                                        }
                                    });
                                });
                            </script>
                        </div>

                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary" id="prevBtn"
                                onclick="moveStep(-1)">Voltar</button>

                            <!-- Botão "Próximo" para todas as etapas exceto a última -->
                            <button type="button" class="btn btn-primary" id="nextBtn" onclick="moveStep(1)"
                                style="display: inline;">Próximo</button>

                            <!-- Botão "Finalizar Venda" só aparece na última etapa -->
                            <button type="submit" class="btn btn-primary" id="finishBtn"
                                style="display: none;">Finalizar Venda</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showDropdown() {
        document.getElementById("clientDropdown").style.display = "block";
    }

    function filterClients() {
        const input = document.getElementById("clientSearch").value.toLowerCase();
        const items = document.querySelectorAll("#clientDropdown .list-group-item");

        let hasResults = false;
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            const show = text.includes(input);
            item.style.display = show ? "block" : "none";
            if (show) hasResults = true;
        });

        document.getElementById("clientDropdown").style.display = hasResults ? "block" : "none";
    }

    function selectClient(id, text) {
        // Atualiza o campo de pesquisa com o texto do cliente selecionado
        document.getElementById("clientSearch").value = text;

        // Atualiza o campo hidden com o ID do cliente selecionado
        document.getElementById("client-id-hidden").value = id;

        // Esconde o dropdown
        document.getElementById("clientDropdown").style.display = "none";

        // Log para depuração
        console.log(`Cliente selecionado: ID=${id}, Nome=${text}`);

        // Atualiza as informações do cliente (se necessário)
        displayClientInfo();
    }

    // Fecha dropdown se clicar fora
    document.addEventListener("click", function (event) {
        if (!event.target.closest(".position-relative")) {
            document.getElementById("clientDropdown").style.display = "none";
        }
    });

    // Validação antes de enviar o formulário
    document.getElementById("saleForm").addEventListener("submit", function (event) {
        const clientId = document.getElementById("client-id-hidden").value;

        // Verifica se o campo client_id está vazio
        if (!clientId) {
            event.preventDefault();
            alert("Por favor, selecione um cliente antes de continuar.");
        }
    });
</script>

<script>
    // Controlador de navegação entre as etapas
    let currentStep = 1;
    const totalSteps = 3; // Temos 3 etapas

    function showStep(step) {
        // Atualiza a barra de progresso
        const progressBar = document.getElementById('progress-bar');
        const progressPercentage = (step / totalSteps) * 100;
        progressBar.style.width = `${progressPercentage}%`;
        progressBar.setAttribute('aria-valuenow', step);

        // Atualiza os círculos das etapas
        const stepCircles = document.querySelectorAll('.step-circle');
        stepCircles.forEach((circle, index) => {
            if (index + 1 === step) {
                circle.classList.add('active');
            } else {
                circle.classList.remove('active');
            }
        });

        // Atualiza a exibição das etapas
        const steps = document.querySelectorAll('.tab-pane');
        steps.forEach((stepElement, index) => {
            if (index + 1 === step) {
                stepElement.classList.add('show', 'active');
            } else {
                stepElement.classList.remove('show', 'active');
            }
        });

        // Exibe ou esconde os botões "Próximo" e "Finalizar"
        document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'inline-block';
        document.getElementById('finishBtn').style.display = step === totalSteps ? 'inline-block' : 'none';

        // Exibe ou esconde o botão "Voltar"
        document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'inline-block';
    }

    // Função para mudar de etapa
    function moveStep(stepChange) {
        const newStep = currentStep + stepChange;
        if (newStep >= 1 && newStep <= totalSteps) {
            currentStep = newStep;
            showStep(currentStep);
        }
    }

    // Inicializa a barra de progresso na primeira etapa
    document.addEventListener('DOMContentLoaded', function () {
        showStep(currentStep);
    });

    function displayClientInfo() {
        const clientSelect = document.getElementById('client-id-hidden'); // Obtém o elemento select
        const clientId = clientSelect.value; // Obtém o ID do cliente selecionado

        if (clientId) {
            console.log(`Cliente selecionado: ${clientId}`); // Log para depuração

            // Fazendo uma requisição AJAX para buscar os dados do cliente
            fetch(`/client/${clientId}/data`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao buscar os dados do cliente.');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Dados do cliente retornados:', data); // Log para depuração

                    // Verifica se os dados do cliente foram retornados corretamente
                    if (data && data.name) {
                        // Atualiza apenas os elementos dentro do modal de criação de vendas
                        const modal = document.getElementById('modalAddSale');
                        modal.querySelector('#client-name').textContent = data.name || 'N/A';
                        modal.querySelector('#client-email').textContent = data.email || 'N/A';
                        modal.querySelector('#client-phone').textContent = data.phone || 'N/A';
                        modal.querySelector('#client-address').textContent = data.address || 'N/A';
                        modal.querySelector('#client-registration_date').textContent = data.created_at || 'N/A';

                        // Atualiza o resumo do cliente na etapa de resumo
                        modal.querySelector('#summary-client-name').textContent = data.name || 'N/A';
                        modal.querySelector('#summary-client-phone').textContent = data.phone || 'N/A';
                        modal.querySelector('#summary-client-address').textContent = data.address || 'N/A';
                        modal.querySelector('#summary-client-registration_date').textContent = data.created_at || 'N/A';

                        // Exibe a seção de informações do cliente
                        modal.querySelector('#client-info').style.display = 'block';
                    } else {
                        throw new Error('Dados do cliente não encontrados.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar os dados do cliente:', error);
                    alert('Não foi possível carregar as informações do cliente. Tente novamente.');
                });
        } else {
            console.log('Nenhum cliente selecionado.'); // Log para depuração
            // Se nenhum cliente for selecionado, esconde a área de informações
            document.getElementById('client-info').style.display = 'none';
        }
    }

    // Atualiza o resumo do cliente e dos produtos selecionados
    function updateSummary() {
        const clientId = document.getElementById('client-id-hidden').value;
        const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
        const selectedProductsSummary = document.getElementById('selected-products-summary');

        // Limpa o conteúdo anterior do resumo
        selectedProductsSummary.innerHTML = '';

        // Verifica se o cliente foi selecionado
        if (clientId) {
            // Busca os dados do cliente diretamente
            const clientName = document.getElementById('client-name').textContent;
            const clientPhone = document.getElementById('client-phone').textContent;
            const clientAddress = document.getElementById('client-address').textContent;
            const clientRegistrationDate = document.getElementById('client-registration_date').textContent;

            // Preenche o resumo com os dados do cliente
            document.getElementById('summary-client-name').textContent = clientName || 'N/A';
            document.getElementById('summary-client-phone').textContent = clientPhone || 'N/A';
            document.getElementById('summary-client-address').textContent = clientAddress || 'N/A';
            document.getElementById('summary-client-registration_date').textContent = clientRegistrationDate || 'N/A';
        } else {
            // Caso o cliente não seja selecionado, esconde a área de resumo do cliente
            document.getElementById('summary-client-name').textContent = 'N/A';
            document.getElementById('summary-client-phone').textContent = 'N/A';
            document.getElementById('summary-client-address').textContent = 'N/A';
            document.getElementById('summary-client-registration_date').textContent = 'N/A';
        }

        // Preenche o resumo dos produtos selecionados
        selectedProducts.forEach(function (checkbox) {
            const productCard = checkbox.closest('.product-card');
            const productName = productCard.querySelector('.card-title').textContent;
            const productQuantity = productCard.querySelector('.product-quantity').value;
            const productPriceSale = productCard.querySelector('.price-sale-input').value;

            // Cria e adiciona cada produto ao resumo de forma estilosa
            const summaryItem = document.createElement('div');
            summaryItem.classList.add('col-md-2', 'mb-4'); // Usando classes do Bootstrap para responsividade
            summaryItem.innerHTML = `
                <div class="card">
                    <img src="${productCard.querySelector('.card-img-top').src}" class="card-img-top" alt="${productName}" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title text-truncate" title="${productName}">${productName}</h5>
                        <p><strong>Qtd:</strong> ${productQuantity}</p>
                        <p><strong>Preço:</strong> R$ ${parseFloat(productPriceSale).toFixed(2).replace('.', ',')}</p>
                    </div>
                </div>
            `;
            selectedProductsSummary.appendChild(summaryItem);
        });
    }

    document.getElementById('productSearch').addEventListener('input', function () {
        let filter = this.value.toLowerCase().replace(/\./g, ''); // Remove os pontos do input
        let products = document.querySelectorAll('.product-card');

        products.forEach(function (product) {
            let productName = product.querySelector('.card-title').textContent.toLowerCase();
            let productCode = product.querySelector('table tr td').textContent.toLowerCase().replace(/\./g, ''); // Remove os pontos

            if (productName.includes(filter) || productCode.includes(filter)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    });

    // Filtro para pesquisa de clientes
    function searchClients() {
        let filter = document.getElementById('clientSearch').value.toLowerCase();
        let clients = document.querySelectorAll('#client_id option');

        clients.forEach(function (client) {
            let name = client.textContent.toLowerCase();
            if (name.includes(filter)) {
                client.style.display = '';
            } else {
                client.style.display = 'none';
            }
        });
    }

    // Função para lidar com o estado de seleção do produto
    document.querySelectorAll('.product-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            var productCard = this.closest('.product-card'); // Obtém o card do produto mais próximo
            var quantityInput = productCard.querySelector('.product-quantity'); // Obtém o campo de quantidade
            var priceSaleInput = productCard.querySelector('.price-sale-input'); // Obtém o campo de preço de venda

            if (this.checked) {
                // Quando o checkbox é marcado
                productCard.style.opacity = 1; // Torna o produto totalmente visível
                quantityInput.disabled = false; // Habilita o campo de quantidade
                priceSaleInput.disabled = false; // Habilita o campo de preço de venda
                productCard.classList.add('selected'); // Adiciona a classe 'selected' para aplicar estilos adicionais
            } else {
                // Quando o checkbox é desmarcado
                productCard.style.opacity = 0.5; // Torna o produto meio cinza (transparente)
                quantityInput.disabled = true; // Desabilita o campo de quantidade
                priceSaleInput.disabled = true; // Desabilita o campo de preço de venda
                productCard.classList.remove('selected'); // Remove a classe 'selected' quando desmarcado
            }
        });
    });

    // Mostrar apenas os produtos selecionados
    document.getElementById('showSelectedBtn').addEventListener('click', function () {
        let productCards = document.querySelectorAll('.product-card');
        let selectedProducts = document.querySelectorAll('.product-checkbox:checked');

        // Verifica se estamos mostrando apenas os selecionados ou todos os produtos
        let isShowingSelectedOnly = this.dataset.selected === 'true';

        // Se estamos mostrando apenas os selecionados, alteramos para mostrar todos
        if (isShowingSelectedOnly) {
            productCards.forEach(function (productCard) {
                productCard.style.display = ''; // Exibe todos os produtos
            });
            this.dataset.selected = 'false'; // Atualiza o estado para mostrar todos
            this.textContent = 'Mostrar apenas selecionados'; // Atualiza o texto do botão
        } else {
            // Caso contrário, mostramos apenas os produtos selecionados
            productCards.forEach(function (productCard) {
                let checkbox = productCard.querySelector('.product-checkbox');
                if (checkbox.checked) {
                    productCard.style.display = ''; // Exibe o produto selecionado
                } else {
                    productCard.style.display = 'none'; // Esconde os não selecionados
                }
            });
            this.dataset.selected = 'true'; // Atualiza o estado para mostrar apenas selecionados
            this.textContent = 'Mostrar todos os produtos'; // Atualiza o texto do botão
        }
    });

    document.getElementById('saleForm').addEventListener('submit', function (event) {
        // Seleciona os produtos que estão marcados
        let selectedProducts = document.querySelectorAll('.product-checkbox:checked');

        // Se nenhum produto foi selecionado, impede o envio
        if (selectedProducts.length === 0) {
            event.preventDefault();
            alert('Por favor, selecione ao menos um produto.');
        } else {
            // Cria uma lista com os dados dos produtos selecionados
            let selectedProductData = [];

            selectedProducts.forEach(function (checkbox) {
                let productCard = checkbox.closest('.product-card');
                let quantityInput = productCard.querySelector('.product-quantity');
                let priceSaleInput = productCard.querySelector('.price-sale-input');

                // Verifica se a quantidade e o preço de venda estão definidos e são válidos
                if (quantityInput && priceSaleInput && quantityInput.value > 0 && priceSaleInput.value > 0) {
                    selectedProductData.push({
                        product_id: checkbox.dataset.productId, // Use dataset para acessar o ID do produto
                        quantity: quantityInput.value,
                        price_sale: priceSaleInput.value
                    });
                }
            });

            // Verifica se temos produtos válidos para enviar
            if (selectedProductData.length === 0) {
                event.preventDefault();
                alert('Por favor, preencha corretamente a quantidade e preço de venda dos produtos selecionados.');
                return;
            }

            // Envia os dados dos produtos selecionados como JSON
            let form = document.getElementById('saleForm');
            let productsInput = form.querySelector('input[name="products"]');

            if (!productsInput) {
                // Cria o campo hidden se ele não existir
                productsInput = document.createElement('input');
                productsInput.type = 'hidden';
                productsInput.name = 'products'; // Este será o nome que o Laravel espera
                form.appendChild(productsInput);
            }

            // Atualiza o campo hidden com os dados dos produtos selecionados
            productsInput.value = JSON.stringify(selectedProductData);
        }
    });

    // Atualiza o resumo sempre que a navegação for realizada (próximo passo)
    document.getElementById('nextBtn').addEventListener('click', updateSummary);

    // Inicializa a exibição da primeira etapa
    document.addEventListener('DOMContentLoaded', function () {
        showStep(currentStep);
    });

    document.addEventListener("DOMContentLoaded", function () {
        // Encontrar todos os botões de expandir para cada venda
        document.querySelectorAll("[id^='expandProducts-']").forEach(function (expandBtn) {
            const saleId = expandBtn.id.split('-')[1]; // Extrai o ID da venda do botão
            let collapseBtn = document.getElementById(`collapseProducts-${saleId}`);

            // Expansão dos produtos
            expandBtn.addEventListener("click", function () {
                // Mostrar os produtos extras dessa venda
                document.querySelectorAll(`#sale-products-${saleId} .extra-product`).forEach(el => el.classList.remove("d-none"));
                expandBtn.classList.add("d-none"); // Esconder o botão de expandir
                collapseBtn.classList.remove("d-none"); // Mostrar o botão de colapsar
            });

            // Colapsar os produtos
            collapseBtn.addEventListener("click", function () {
                // Esconder os produtos extras dessa venda
                document.querySelectorAll(`#sale-products-${saleId} .extra-product`).forEach(el => el.classList.add("d-none"));
                collapseBtn.classList.add("d-none"); // Esconder o botão de colapsar
                expandBtn.classList.remove("d-none"); // Mostrar o botão de expandir
            });
        });
    });

    // Função para buscar produtos durante a digitação
    document.getElementById('searchProduct').addEventListener('keyup', function () {
        var filter = this.value.toLowerCase();
        var products = document.querySelectorAll('.product-item');

        products.forEach(function (product) {
            var name = product.querySelector('.card-title').textContent.toLowerCase();
            var code = product.querySelector('.card-text').textContent.toLowerCase();

            if (name.includes(filter) || code.includes(filter)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    });

    // Botão para filtrar e mostrar apenas os produtos selecionados
    document.getElementById('filterSelectedProducts').addEventListener('click', function () {
        var products = document.querySelectorAll('.product-item');

        products.forEach(function (product) {
            var checkbox = product.querySelector('.product-checkbox');
            if (checkbox.checked) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    });
</script>
<link rel="stylesheet" href="{{ asset('css/modal-sale-enhanced.css') }}">
<style>
    .progress-container {
        position: relative;
    }

    .progress {
        height: 5px;
        background-color: #e9ecef;
    }

    .progress-bar {
        height: 5px;
        background-color: #0d6efd;
    }

    .step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        color: #6c757d;
    }

    .step-circle.active {
        background-color: #0d6efd;
        color: #fff;
    }
</style>
