<!-- Modal de Upload de Produtos -->
<div class="modal fade custom-upload-modal" id="modalUploadProduct" tabindex="-1" aria-labelledby="modalUploadProductLabel" aria-hidden="true">   
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUploadProductLabel">Upload de Produtos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Barra de Progresso -->
                <div class="progress mb-4">
                    <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="1"
                        aria-valuemin="0" aria-valuemax="2"></div>
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
                        <form id="uploadForm" action="{{ route('products.upload') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <p class="text-center mb-4" style="font-size: 1.1em; color: #5d6d7e;">
                                Carregue seu arquivo PDF ou CSV contendo os dados dos produtos para registrá-los
                                automaticamente em sua conta.
                                Simplifique o processo de acompanhamento!
                            </p>

                            <!-- Input do arquivo -->
                            <div class="form-group text-center mb-4">
                                <label for="file-upload" class="btn btn-outline-primary"
                                    style="display: inline-block; padding: 10px 20px; font-size: 1.1em; border-radius: 5px; cursor: pointer; background-color: #e7f3ff; color: #2c3e50;">
                                    <i class="fa fa-file"></i> Escolher arquivo PDF ou CSV
                                </label>
                                <input type="file" id="file-upload" name="pdf_file" accept=".pdf, .csv"
                                    style="display: none;" required>
                                <br>
                                <small class="form-text text-muted text-center mt-2"
                                    style="font-size: 0.95em; color: #5d6d7e;">
                                    Somente arquivos PDF ou CSV são aceitos.
                                </small>
                            </div>
                            <!-- Barra de progresso -->
                            <div class="progress" style="height: 25px; display: none;" id="upload-progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 0%; background-color: #3498db; font-weight: bold;" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100">
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
                    <form id="product-form" action="{{ route('products.pdf.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div id="product-cards" class="row">


                            <!-- Produtos gerados dinamicamente aqui -->
                        </div>
                        <!-- Botão para Adicionar Produtos -->
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="submit" class="btn btn-success">Salvar Produtos</button>
                            <button type="button" class="btn btn-secondary" id="prevStep2"
                                onclick="moveStep(-1)">Voltar</button>
                        </div>

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
                // Adiciona IDs únicos aos produtos carregados
                data.products.forEach(product => {
                    product.id = generateProductId();
                });
                // Atualiza a lista global de produtos
                products = data.products;
                renderProductCards(products);
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
        document.querySelector('label[for="file-upload"]').innerHTML = '<i class="fa fa-file"></i> ' +
            fileName;
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

// Função para gerar um identificador único para cada produto
function generateProductId() {
    return 'prod_' + Date.now() + '_' + Math.floor(Math.random() * 10000);
}

// Lista de produtos inicial, começa vazia
let products = [];

// Função para gerar os cards de produtos
function renderProductCards(products) {
    const productCardsContainer = document.getElementById('product-cards');
    productCardsContainer.innerHTML = '';

    products.forEach((product, index) => {
        // Garante que cada produto tenha um ID único
        if (!product.id) {
            product.id = generateProductId();
        }

        // Sempre usa o campo image_url, se não existir, usa o placeholder
        let imgSrc = product.image_url && product.image_url.startsWith('data:image')
            ? product.image_url
            : '/storage/products/product-placeholder.png';

        const card = document.createElement('div');
        card.classList.add('col-md-3', 'mb-3');
        card.innerHTML = `
<!-- Card aprimorado visualmente -->
<div class="card product-card shadow-lg border-0 mb-4" id="product-card-${product.id}" style="border-radius: 1rem;">
    <div class="position-relative bg-light" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
        <img src="${imgSrc}"
            class="card-img-top"
            alt="${product.name || ''}"
            id="product-image-${product.id}"
            style="cursor:pointer; border-radius: 1rem 1rem 0 0; object-fit: contain; height: 280px; background: #f8f9fa;"
            onclick="triggerImageUpload('${product.id}')">
        <input type="file" name="products[${index}][image]" class="form-control"
            accept="image/*" style="display: none" id="image-upload-${product.id}">
        <div class="image-preview" id="image-preview-${product.id}"></div>
        <input type="hidden" name="products[${index}][image_base64]" id="image-base64-${product.id}" value="${product.image_url && product.image_url.startsWith('data:image') ? product.image_url : ''}">
        <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-3 shadow"
            onclick="deleteProductCard('${product.id}')" title="Remover produto">
            <i class="fas fa-trash-alt"></i>
         </button>
    </div>
    <div class="card-body py-3">
        <h5 class="card-title text-center mb-3">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-tag"></i></span>
                <input type="text" class="form-control text-center fs-5 fw-semibold border-start-0" name="products[${index}][name]"
                    value="${product.name || ''}" placeholder="Nome do produto" required ${product.name ? 'readonly' : ''}>
            </div>
        </h5>
        <div class="mb-2">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-align-left"></i></span>
                <textarea class="form-control text-center" name="products[${index}][description]" rows="2"
                    placeholder="Descrição" ${product.description ? 'readonly' : ''}>${product.description || ''}</textarea>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-6">
                <div class="mb-2">
                    <label class="form-label mb-1"><i class="fas fa-dollar-sign text-success"></i> <strong>Preço</strong></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-money-bill-wave"></i></span>
                        <input type="number" step="0.01" name="products[${index}][price]"
                            value="${product.price}" class="form-control" placeholder="Preço" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label mb-1"><i class="fas fa-tags text-primary"></i> <strong>Venda</strong></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-percentage"></i></span>
                        <input type="number" step="0.01" name="products[${index}][price_sale]"
                            value="${product.price_sale}" class="form-control" placeholder="Preço de Venda" required>
                    </div>
                </div>
                <div>
                    <label class="form-label mb-1"><i class="fas fa-boxes text-warning"></i> <strong>Qtd</strong></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-cubes"></i></span>
                        <input type="number" name="products[${index}][quantity]"
                            value="${product.stock_quantity}" class="form-control" placeholder="Quantidade" required>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="mb-2">
                    <label class="form-label mb-1"><i class="fas fa-barcode text-info"></i> <strong>Código</strong></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-barcode"></i></span>
                        <input type="text" name="products[${index}][product_code]"
                            value="${product.product_code}" class="form-control" placeholder="Código" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label mb-1"><i class="fas fa-toggle-on text-success"></i> <strong>Status</strong></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-info-circle"></i></span>
                        <select class="form-control" name="products[${index}][status]" required>
                            <option value="active" ${product.status === 'active' ? 'selected' : ''}>Ativo</option>
                            <option value="inactive" ${product.status === 'inactive' ? 'selected' : ''}>Inativo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        `;
        // Adiciona o card ao container
        productCardsContainer.appendChild(card);

        // Adiciona o evento onchange ao input file (depois de inserir no DOM)
        const inputFile = card.querySelector(`#image-upload-${product.id}`);
        if (inputFile) {
            inputFile.onchange = function(event) {
                handleImageUpload(event, product.id);
            };
        }
    });

    // Coloca o botão '+' após os cards (Somente uma vez)
    if (!document.getElementById('add-product-button')) {
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
        addButton.id = 'add-product-button'; // Adiciona id para evitar duplicação
        addButton.addEventListener('click', addNewProduct);

        // Posiciona o botão após os cards
        productCardsContainer.appendChild(addButton);
    }
}

// Função para adicionar um novo produto
function addNewProduct(event) {
    event.preventDefault(); // Impede que o modal feche ao clicar no botão

    // Criando um novo produto vazio com ID único
    const newProduct = {
        id: generateProductId(),
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
function deleteProductCard(productId) {
    // Encontra o índice do produto pelo ID
    const index = products.findIndex(product => product.id === productId);
    
    if (index !== -1) {
        // Remove o produto da lista
        products.splice(index, 1);
        
        // Re-renderiza os produtos atualizados
        renderProductCards(products);
    }
}

// Função para carregar produtos do PDF (simulado para fins de exemplo)
function loadProductsFromPDF(data) {
    // Adiciona IDs únicos aos produtos carregados se não tiverem
    data.forEach(product => {
        if (!product.id) {
            product.id = generateProductId();
        }
    });
    
    // Não substitui a lista de produtos, apenas adiciona
    products.push(...data);

    // Re-renderiza os produtos após carregar
    renderProductCards(products);
}

// Função para abrir o input de arquivo ao clicar na imagem
function triggerImageUpload(productId) {
    const inputElement = document.getElementById(`image-upload-${productId}`);
    if (inputElement) {
        inputElement.click();
    }
}

// Nova função para tratar upload de imagem
function handleImageUpload(event, productId) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Atualiza o campo image_url do produto no array
            const idx = products.findIndex(p => String(p.id) === String(productId));
            if (idx !== -1) {
                products[idx].image_url = e.target.result;
                // Atualiza o campo hidden base64 (opcional)
                const base64Input = document.getElementById('image-base64-' + productId);
                if (base64Input) {
                    base64Input.value = e.target.result;
                }
                // Re-renderiza os cards para refletir a nova imagem
                renderProductCards(products);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Renderiza os produtos inicialmente (vazio no início, se necessário)
renderProductCards(products);

</script>
        
<!-- Adicione este CSS customizado ao seu projeto (ex: em um arquivo style.css ou dentro de uma seção <style> do blade) -->
<style>
    /* Wrapper opcional para garantir isolamento */
.product-card-wrapper .product-card,
.product-card {
    background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
    border-radius: 1rem;
    box-shadow: 0 6px 24px rgba(44, 62, 80, 0.10), 0 1.5px 4px rgba(44, 62, 80, 0.08);
    transition: transform 0.18s cubic-bezier(.4,2,.6,1), box-shadow 0.18s;
    overflow: hidden;
    border: none;
    position: relative;
}

.product-card:hover {
    transform: translateY(-4px) scale(1.015);
    box-shadow: 0 12px 32px rgba(44, 62, 80, 0.16), 0 2px 8px rgba(44, 62, 80, 0.10);
}

.product-card .card-img-top {
    border-radius: 1rem 1rem 0 0;
    object-fit: contain;
    height: 280px;
    background: #f8f9fa;
    transition: filter 0.2s;
}
.product-card .card-img-top:hover {
    filter: brightness(0.95) blur(1px);
}

.product-card .btn-danger {
    width: 36px;
    height: 36px;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.18);
    opacity: 0.85;
    transition: opacity 0.15s;
}
.product-card .btn-danger:hover {
    opacity: 1;
}

.product-card .card-body {
    background: #fff;
    border-radius: 0 0 1rem 1rem;
    padding: 1.5rem 1.2rem 1.2rem 1.2rem;
}

.product-card .card-title .input-group {
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(44, 62, 80, 0.07);
    background: #f3f6fa;
}

.product-card .input-group-text {
    background: #fff;
    border: none;
    color: #6c757d;
    font-size: 1.1rem;
}

.product-card input.form-control,
.product-card textarea.form-control,
.product-card select.form-control {
    border: none;
    background: #f3f6fa;
    border-radius: 0.5rem;
    box-shadow: none;
    font-size: 1rem;
    transition: background 0.15s;
}
.product-card input.form-control:focus,
.product-card textarea.form-control:focus,
.product-card select.form-control:focus {
    background: #e9f0fa;
    outline: none;
    box-shadow: 0 0 0 2px #b6d4fe;
}

.product-card textarea.form-control {
    resize: none;
    min-height: 48px;
}

.product-card label.form-label {
    font-size: 0.97rem;
    color: #495057;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.product-card .input-group {
    margin-bottom: 0.5rem;
}

.product-card .image-preview {
    margin-top: 0.5rem;
    text-align: center;
}

.product-card .image-preview img {
    max-width: 90px;
    max-height: 90px;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
    margin: 0 auto;
}

.product-card select.form-control {
    cursor: pointer;
}

@media (max-width: 600px) {
    .product-card {
        padding: 0.5rem;
    }
    .product-card .card-body {
        padding: 1rem 0.5rem 0.8rem 0.5rem;
    }
    .product-card .card-img-top {
        height: 180px;
    }
}
.custom-upload-modal .modal-dialog {
  max-width: 80vw !important; /* Quase tela cheia */
  width: 98vw !important;
}
/* Barra de rolagem apenas na área dos cards de produtos */
.custom-upload-modal #product-cards {
  max-height: 60vh;
  overflow-y: auto;
  padding-right: 8px; /* espaço para a barra de rolagem */
}

/* Barra de rolagem estilizada (opcional, para navegadores modernos) */
.custom-upload-modal #product-cards::-webkit-scrollbar {
  width: 8px;
  background: #e3f2fd;
  border-radius: 8px;
}
.custom-upload-modal #product-cards::-webkit-scrollbar-thumb {
  background: #3498db;
  border-radius: 8px;
}
.custom-upload-modal #product-cards::-webkit-scrollbar-thumb:hover {
  background: #217dbb;
}

/* Escopo: apenas para o modal de upload de produtos */
.custom-upload-modal {
  font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
}

.custom-upload-modal .modal-content {
  border-radius: 18px;
  box-shadow: 0 8px 32px rgba(44, 62, 80, 0.18);
  border: none;
  background: #fafdff;
}

.custom-upload-modal .modal-header {
  border-bottom: none;
  background: linear-gradient(90deg, #3498db 0%, #6dd5fa 100%);
  color: #fff;
  border-radius: 18px 18px 0 0;
}

.custom-upload-modal .modal-title {
  font-weight: 700;
  font-size: 1.5rem;
  letter-spacing: 1px;
}

.custom-upload-modal .btn-close {
  filter: invert(1);
}

.custom-upload-modal .progress {
  background: #e3f2fd;
  border-radius: 8px;
  height: 18px;
  box-shadow: 0 2px 8px rgba(52, 152, 219, 0.08);
}

.custom-upload-modal .progress-bar {
  background: linear-gradient(90deg, #3498db 0%, #6dd5fa 100%);
  font-weight: 600;
  font-size: 1em;
}

.custom-upload-modal .pdf-upload-form {
  background: #fff;
  border-radius: 14px;
  border: 1px solid #e3eaf1;
  box-shadow: 0 2px 12px rgba(52, 152, 219, 0.07);
}

.custom-upload-modal .pdf-upload-form h1 {
  font-size: 1.3rem;
  color: #3498db;
  font-weight: 600;
}

.custom-upload-modal label[for="file-upload"] {
  background: linear-gradient(90deg, #e7f3ff 0%, #d0eaff 100%);
  color: #2c3e50;
  border: 1.5px solid #3498db;
  transition: background 0.2s, color 0.2s;
}

.custom-upload-modal label[for="file-upload"]:hover {
  background: #3498db;
  color: #fff;
}

.custom-upload-modal .form-control,
.custom-upload-modal textarea {
  border-radius: 7px;
  border: 1.5px solid #e3eaf1;
  box-shadow: none;
  transition: border 0.2s;
}

.custom-upload-modal .form-control:focus,
.custom-upload-modal textarea:focus {
  border-color: #3498db;
  box-shadow: 0 0 0 2px #e3f2fd;
}

.custom-upload-modal .btn-primary,
.custom-upload-modal .btn-success,
.custom-upload-modal .btn-secondary {
  border-radius: 7px;
  font-weight: 600;
  letter-spacing: 0.5px;
  box-shadow: 0 2px 8px rgba(52, 152, 219, 0.08);
  transition: background 0.2s, color 0.2s;
}

.custom-upload-modal .btn-primary:hover,
.custom-upload-modal .btn-success:hover {
  background: #217dbb;
  color: #fff;
}

.custom-upload-modal .btn-secondary:hover {
  background: #b2bec3;
  color: #2d3436;
}

.custom-upload-modal .modal-footer {
  border-top: none;
  background: transparent;
}

.custom-upload-modal .card.modal-card {
  border-radius: 12px;
  border: 1.5px solid #e3eaf1;
  box-shadow: 0 2px 12px rgba(52, 152, 219, 0.07);
  transition: box-shadow 0.2s;
  background: #fff;
  margin-bottom: 18px;
}

.custom-upload-modal .card.modal-card:hover {
  box-shadow: 0 4px 24px rgba(52, 152, 219, 0.13);
}

.custom-upload-modal .card-title input {
  font-weight: 600;
  font-size: 1.1em;
  color: #2980b9;
  background: #fafdff;
}

.custom-upload-modal .fa-trash {
  color: #fff;
  font-size: 1.1em;
}

.custom-upload-modal .btn-danger {
  background: #e74c3c;
  border: none;
  border-radius: 50%;
  padding: 0.4em 0.6em;
  transition: background 0.2s;
}

.custom-upload-modal .btn-danger:hover {
  background: #c0392b;
}

.custom-upload-modal .rounded-circle {
  box-shadow: 0 2px 8px rgba(52, 152, 219, 0.13);
}

.custom-upload-modal #add-product-button {
  background: linear-gradient(90deg, #6dd5fa 0%, #3498db 100%);
  color: #fff;
  border: none;
  box-shadow: 0 2px 8px rgba(52, 152, 219, 0.13);
  transition: background 0.2s, color 0.2s;
}

.custom-upload-modal #add-product-button:hover {
  background: #3498db;
  color: #fff;
}

.custom-upload-modal .img-thumbnail {
  border-radius: 8px;
  border: 1.5px solid #e3eaf1;
  margin-top: 8px;
}

/* Animação para indicar que a imagem foi atualizada */
@keyframes imageUpdated {
  0% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.7); }
  70% { box-shadow: 0 0 0 10px rgba(52, 152, 219, 0); }
  100% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0); }
}

.image-updated {
  animation: imageUpdated 1s ease-in-out;
}

.product-card .image-preview {
  margin-top: 0.5rem;
  text-align: center;
  transition: all 0.3s ease;
}

.product-card .image-preview img {
  max-width: 100%;
  max-height: 180px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}

.product-card .image-preview img:hover {
  transform: scale(1.05);
}

.custom-upload-modal .text-muted {
  color: #7f8c8d !important;
}

.custom-upload-modal .multisteps-form__panel {
  animation: fadeIn 0.5s;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(16px);}
  to { opacity: 1; transform: translateY(0);}
}

/* Responsividade */
@media (max-width: 768px) {
  .custom-upload-modal .modal-dialog {
    max-width: 98vw;
    margin: 1.2rem auto;
  }
  .custom-upload-modal .card.modal-card {
    width: 100% !important;
  }
}

</style>
