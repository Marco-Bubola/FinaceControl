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
                    <form id="product-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
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
    } // Função para gerar os cards de produtos
    function renderProductCards(products) {
        const productCardsContainer = document.getElementById('product-cards');
        productCardsContainer.innerHTML = ''; // Limpa os produtos anteriores

        products.forEach((product, index) => {
            const card = document.createElement('div');
            card.classList.add('col-md-4');
            card.classList.add('mb-3');

            // Card de produto
            card.innerHTML = `
            <div class="card" style="width: 18rem;">
                <img src="${product.image_url ? '/storage/products/' + product.image_url : '/storage/products/product-placeholder.png'}"
                     class="card-img-top" alt="${product.name}" id="product-image-${index}" onclick="triggerImageUpload(${index})">
                <div class="card-body">
                    <h5 class="card-title text-center">
                        <input type="text" class="form-control text-center" name="products[${index}][name]"
                               value="${product.name || ''}" placeholder="Nome do produto" required>
                    </h5>
                    <p class="card-text text-center">
                        <textarea class="form-control" name="products[${index}][description]" rows="2"
                                  placeholder="Descrição">${product.description || ''}</textarea>
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
                </div>
            </div>
            `;

            productCardsContainer.appendChild(card);
        });
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

    // Atualiza o nome do arquivo no botão após a seleção
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('file-upload').addEventListener('change', function() {
            var fileName = this.files[0] ? this.files[0].name : 'Escolher arquivo PDF ou CSV';
            document.querySelector('label[for="file-upload"]').innerHTML = '<i class="fa fa-file"></i> ' + fileName;
        });
    });
</script>
