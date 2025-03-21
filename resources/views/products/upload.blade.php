<!-- Modal de Upload de Produtos -->
<div class="modal fade" id="modalUploadProduct" tabindex="-1" aria-labelledby="modalUploadProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUploadProductLabel">Upload de Produtos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Barra de Progresso -->
                <div class="progress mb-4">
                    <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="2"></div>
                </div>

                <!-- Nomes das Etapas -->
                <div class="d-flex justify-content-between text-center mb-4">
                    <span id="step-1-name" class="text-center">1. Enviar PDF</span>
                    <span id="step-2-name" class="text-center">2. Confirmação</span>
                </div>

                <!-- Step 1: Enviar PDF ou CSV -->
                <div id="step-1" class="multisteps-form__panel">
                    <div class="pdf-upload-form p-4 rounded shadow-sm">
                        <h1 class="text-center mb-3">Envio de Arquivo PDF ou CSV</h1>
                        <form id="uploadForm" action="{{ route('products.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <p class="text-center mb-4" style="font-size: 1.1em; color: #5d6d7e;">
                                Carregue seu arquivo PDF ou CSV contendo os dados dos produtos para registrá-los automaticamente em sua conta.
                                Simplifique o processo de acompanhamento!
                            </p>

                            <!-- Input do arquivo -->
                            <div class="form-group text-center mb-4">
                                <label for="file-upload" class="btn btn-outline-primary"
                                    style="display: inline-block; padding: 10px 20px; font-size: 1.1em; border-radius: 5px; cursor: pointer; background-color: #e7f3ff; color: #2c3e50;">
                                    <i class="fa fa-file"></i> Escolher arquivo PDF ou CSV
                                </label>
                                <input type="file" id="file-upload" name="pdf_file" accept=".pdf, .csv" style="display: none;" required>
                                <br>
                                <small class="form-text text-muted text-center mt-2" style="font-size: 0.95em; color: #5d6d7e;">
                                    Somente arquivos PDF ou CSV são aceitos.
                                </small>
                            </div>
                            <!-- Barra de progresso -->
                            <div class="progress" style="height: 25px; display: none;" id="upload-progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 0%; background-color: #3498db; font-weight: bold;" aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100">
                                    0%
                                </div>
                            </div>
                            <div class="form-group text-center mt-4">
                                <button type="button" class="btn btn-primary" id="nextStep1">Próximo</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Step 2: Confirmação de Produtos -->
                <div id="step-2" class="multisteps-form__panel" style="display: none;">
                    <h4>Passo 2: Confirmar Produtos</h4>
                    <form id="product-form" action="{{ route('products.pdf.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div id="product-cards" class="row">


                            <!-- Produtos gerados dinamicamente aqui -->
                        </div>
                        <button type="submit" class="btn btn-success">Salvar Produtos</button>
                        <button type="button" class="btn btn-secondary" id="prevStep2" onclick="moveStep(-1)">Voltar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;

    // Função para mover entre as etapas
    function moveStep(stepChange) {
        const steps = document.querySelectorAll('.multisteps-form__panel');
        steps.forEach(step => step.style.display = 'none');
        currentStep += stepChange;

        if (currentStep === 1) {
            document.getElementById('step-1').style.display = 'block';
            document.getElementById('nextStep1').style.display = 'inline';
            document.getElementById('prevStep2').style.display = 'none';
        } else if (currentStep === 2) {
            document.getElementById('step-2').style.display = 'block';
            document.getElementById('nextStep1').style.display = 'none';
            document.getElementById('prevStep2').style.display = 'inline';
        }

        const progressBar = document.getElementById('progress-bar');
        const progressPercentage = (currentStep / 2) * 100;
        progressBar.style.width = `${progressPercentage}%`;
        progressBar.setAttribute('aria-valuenow', currentStep);
    }

    // Função para enviar o primeiro formulário (upload do arquivo PDF/CSV)
    document.getElementById('nextStep1').addEventListener('click', function(e) {
        e.preventDefault();
        showProgressBar();
        const form = document.querySelector('#uploadForm');
        const formData = new FormData(form);

        fetch("{{ route('products.upload') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.products && data.products.length > 0) {
                    renderProductCards(data.products);
                    moveStep(1);
                } else {
                    alert('Nenhum produto encontrado no arquivo!');
                }
            })
            .catch(error => {
                console.error('Erro no upload do arquivo:', error);
            });
    });
    // Atualiza o nome do arquivo no botão após a seleção
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('file-upload').addEventListener('change', function() {
            var fileName = this.files[0] ? this.files[0].name : 'Escolher arquivo PDF ou CSV';
            document.querySelector('label[for="file-upload"]').innerHTML = '<i class="fa fa-file"></i> ' + fileName;
        });
    });
    // Função para mostrar a barra de progresso
    function showProgressBar() {
        const progressBar = document.getElementById('upload-progress-bar');
        const progressBarInner = progressBar.querySelector('.progress-bar');
        progressBar.style.display = 'block';
        let progress = 0;
        const interval = setInterval(() => {
            if (progress < 100) {
                progress += 10;
                progressBarInner.style.width = progress + '%';
                progressBarInner.setAttribute('aria-valuenow', progress);
                progressBarInner.textContent = progress + '%';
            } else {
                clearInterval(interval);
            }
        }, 300);
    }

    // Lista de produtos inicial, começa vazia
    let products = [];

    // Função para gerar os cards de produtos
    function renderProductCards(products) {
        const productCardsContainer = document.getElementById('product-cards');

        // Não limpar os produtos anteriores, apenas adicionar os novos
        products.forEach((product, index) => {
            const card = document.createElement('div');
            card.classList.add('col-md-4');
            card.classList.add('mb-3');

            // Card de produto
            card.innerHTML = `
        <div class="card modal-card" style="width: 18rem;" id="product-card-${index}">
            <img src="${product.image_url ? '/storage/products/' + product.image_url : '/storage/products/product-placeholder.png'}"
                class="card-img-top" alt="${product.name}" id="product-image-${index}" onclick="triggerImageUpload(${index})">
            <div class="card-body">
                <h5 class="card-title text-center">
                    <input type="text" class="form-control text-center" name="products[${index}][name]"
                        value="${product.name || ''}" placeholder="Nome do produto" required ${product.name ? 'readonly' : ''}>
                </h5>
                <p class="card-text text-center">
                    <textarea class="form-control" name="products[${index}][description]" rows="2"
                        placeholder="Descrição" ${product.description ? 'readonly' : ''}>${product.description || ''}</textarea>
                </p>
                <div class="row">
                    <div class="col-6">
                        <p><strong>Preço:</strong>
                            <input type="number" step="0.01" name="products[${index}][price]"
                                value="${product.price}" class="form-control" placeholder="Preço" required>
                        </p>
                        <p><strong>Venda:</strong>
                            <input type="number" step="0.01" name="products[${index}][price_sale]"
                                value="${product.price_sale}" class="form-control" placeholder="Preço de Venda" required>
                        </p>
                        <p><strong>Qtd:</strong>
                            <input type="number" name="products[${index}][quantity]"
                                value="${product.stock_quantity}" class="form-control" placeholder="Quantidade" required>
                        </p>
                    </div>
                    <div class="col-6">
                        <p><strong>Código:</strong>
                            <input type="text" name="products[${index}][product_code]"
                                value="${product.product_code}" class="form-control" placeholder="Código" required>
                        </p>
                        <p><strong>Status:</strong>
                            <select class="form-control" name="products[${index}][status]" required>
                                <option value="active" ${product.status === 'active' ? 'selected' : ''}>Ativo</option>
                                <option value="inactive" ${product.status === 'inactive' ? 'selected' : ''}>Inativo</option>
                            </select>
                        </p>
                        <p><strong>Foto:</strong>
                            <input type="file" name="products[${index}][image]" class="form-control" accept="image/*"
                                onchange="previewImage(event, ${index})" style="display: none" id="image-upload-${index}">
                            <small class="form-text text-muted">Escolha uma imagem para o produto.</small>
                        </p>
                        <div id="image-preview-${index}">
                            ${product.image_url ? `<img src="/storage/products/${product.image_url}" class="img-thumbnail" width="100%">` : ''}
                        </div>
                    </div>
                </div>
                <button class="btn btn-danger btn-sm mt-2" onclick="deleteProductCard(${index})">Excluir</button>
            </div>
        </div>
        `;
            productCardsContainer.appendChild(card);
        });

        // Coloca o botão '+' após os cards
        const addButton = document.createElement('button');
        addButton.classList.add('btn', 'btn-success', 'rounded-circle');
        addButton.style.position = 'absolute';
        addButton.style.bottom = '20px';
        addButton.style.right = '20px';
        addButton.style.width = '60px';
        addButton.style.height = '60px';
        addButton.style.fontSize = '36px';
        addButton.style.padding = '0';
        addButton.innerHTML = '<i class="fas fa-plus"></i>';
        addButton.addEventListener('click', addNewProduct);

        // Posiciona o botão após os cards
        productCardsContainer.appendChild(addButton);
    }

    // Função para adicionar um novo produto
    function addNewProduct(event) {
        event.preventDefault(); // Impede que o modal feche ao clicar no botão

        // Criando um novo produto vazio
        const newProduct = {
            name: '',
            description: '',
            price: 0,
            price_sale: 0,
            stock_quantity: 1,
            product_code: '',
            status: 'active',
            image_url: ''
        };

        // Adiciona o novo produto ao array
        products.push(newProduct);

        // Re-renderiza os produtos para incluir o novo produto
        renderProductCards(products);
    }

    // Função para excluir um card de produto
    function deleteProductCard(index) {
        const card = document.getElementById(`product-card-${index}`);
        if (card) {
            card.remove(); // Remove o card do DOM
        }

        // Remover o produto da lista de produtos
        products.splice(index, 1);

        // Re-renderiza os produtos atualizados
        renderProductCards(products);
    }

    // Função para carregar produtos do PDF (simulado para fins de exemplo)
    function loadProductsFromPDF(data) {
        // Não substitui a lista de produtos, apenas adiciona
        products.push(...data);

        // Re-renderiza os produtos após carregar
        renderProductCards(products);
    }

    // Função para pré-visualizar a imagem antes de enviar
    function previewImage(event, index) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const imagePreview = document.getElementById(`image-preview-${index}`);
            imagePreview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" width="100%">`;

            // Atualiza a imagem exibida na parte superior
            const productImage = document.getElementById(`product-image-${index}`);
            productImage.src = e.target.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    // Função para abrir o input de arquivo ao clicar na imagem
    function triggerImageUpload(index) {
        const inputElement = document.getElementById(`image-upload-${index}`);
        inputElement.click();
    }

    // Renderiza os produtos inicialmente (vazio no início, se necessário)
    renderProductCards(products);
</script>
<style>
    /* Estilo do botão flutuante + */
    .modal-body {
        position: relative;
    }

    .btn-success.rounded-circle {
        position: absolute;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        font-size: 36px;
        padding: 0;
        z-index: 10;
    }

    /* Garantir que o card dentro do modal tenha uma largura fixa, mas a altura será ajustada automaticamente */
    .modal-card {
        width: 15rem;
        /* Largura fixa para o card */
        height: auto;
        /* A altura será ajustada conforme o conteúdo */
        overflow: hidden;
        /* Impede que o conteúdo transborde */
    }

    /* Para a imagem, defina um tamanho fixo e ajuste o conteúdo */
    .modal-card .card-img-top {
        width: 100%;
        height: 150px;
        /* Altura fixa para a imagem */
        object-fit: cover;
        /* Ajuste da imagem sem distorcer */
    }

    /* Truncar o nome e a descrição com "..." e permitir que o nome completo seja mostrado em um tooltip */
    .modal-card .card-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
        /* Ajuste no tamanho máximo do título */
        text-align: center;
        position: relative;
    }

    /* Tooltip para o nome completo do produto */
    .modal-card .card-title[data-bs-toggle="tooltip"]:hover::after {
        content: attr(data-bs-original-title);
        position: absolute;
        top: 100%;
        left: 0;
        padding: 5px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        font-size: 12px;
        border-radius: 3px;
        width: 100%;
        z-index: 10;
    }

    /* Garantir que o conteúdo da descrição também seja truncado corretamente */
    .modal-card .card-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 200px;
        /* Ajuste na largura da descrição */
    }

    /* Para os campos de preço e quantidade, garantir que eles não ultrapassem o tamanho do card */
    .modal-card .card-body .row .col-6 {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        max-width: 100%;
    }

    /* Definir altura fixa para os inputs de preço, venda e quantidade */
    .modal-card .card-body input.form-control {
        height: 30px;
        /* Menor altura para os inputs */
        width: 100%;
    }

    /* Ajustar os botões para o layout mais compacto */
    .modal-card .card-body button {
        width: 100%;
        padding: 8px;
        /* Menor padding nos botões */
        font-size: 0.9em;
        /* Font menor para botões */
    }

    /* Manter uma proporção de layout consistente dentro do card */
    .modal-card .card-body {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        /* Flex-start para que o conteúdo não se sobreponha */
        height: auto;
        /* A altura será ajustada conforme o conteúdo */
        padding: 10px;
        /* Padding adicional para espaçamento */
    }

    /* Ajuste de altura das colunas para garantir que elas não se sobreponham */
    .modal-card .col-md-3 {
        height: auto;
    }

    /* Ajuste do card para que a altura total seja compatível com o conteúdo */
    .modal-card .card-body .row {
        margin-bottom: 10px;
        /* Mantendo o espaçamento entre as linhas */
    }
</style>
