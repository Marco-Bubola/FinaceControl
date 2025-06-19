<!-- Modal de Upload de Produtos -->
<div class="modal fade custom-upload-modal" id="modalUploadProduct" tabindex="-1" aria-labelledby="modalUploadProductLabel" aria-hidden="true">   
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header stylish-modal-header">
                <h5 class="modal-title stylish-modal-title" id="modalUploadProductLabel">
                    <i class="bi bi-cloud-arrow-up stylish-modal-title-icon"></i> Upload de Produtos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Barra de Progresso Estilizada -->
                <div class="custom-progress-wrapper mb-4">
                    <div class="custom-progress-bar-bg position-relative">
                        <div class="custom-progress-bar-anim" id="progress-bar" style="width: 33%"></div>
                        <div class="custom-progress-icons">
                            <span class="custom-progress-icon" id="progress-icon-1"><i class="bi bi-file-earmark-arrow-up"></i></span>
                            <span class="custom-progress-icon" id="progress-icon-2"><i class="bi bi-check2-circle"></i></span>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Enviar PDF ou CSV -->
                <div id="step-1" class="multisteps-form__panel">
                    <div class="pdf-upload-form p-4 rounded shadow-sm stylish-upload-form">
                        
                        <form id="uploadForm" action="{{ route('products.upload') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                         
                            <div class="stylish-upload-dropzone mb-4" id="upload-dropzone">
                                <label class="stylish-upload-drop-label w-100 h-100" id="upload-drop-label" for="file-upload" style="cursor:pointer;">
                                    <span class="upload-drop-icon animated-upload-icon"><i class="bi bi-cloud-arrow-up"></i></span>
                                    <span class="upload-drop-title">Envio de Arquivo PDF ou CSV</span>
                                    <span class="upload-drop-desc">Carregue seu arquivo PDF ou CSV contendo os dados dos produtos para registrá-los automaticamente em sua conta.<br><span class="text-gradient">Simplifique o processo de acompanhamento!</span></span>
                                    <span class="upload-drop-text">Arraste e solte o arquivo PDF ou CSV aqui<br><span class="upload-drop-or">ou</span></span>
                                </label>
                                <span class="btn stylish-upload-btn mt-2" id="upload-btn"><i class="bi bi-upload"></i> Escolher arquivo PDF ou CSV</span>
                                <input type="file" id="file-upload" name="pdf_file" accept=".pdf, .csv" style="display: none;" required>
                                <small class="form-text text-muted text-center mt-2 stylish-upload-tip">
                                    <i class="bi bi-info-circle"></i> Somente arquivos PDF ou CSV são aceitos.
                                </small>
                            </div>
                            <div id="selected-file-info" class="selected-file-info mt-3" style="display:none;"></div>
                            <div class="stylish-upload-footer stylish-upload-footer-fixed">
                                <button type="button" class="btn stylish-btn-next" id="nextStep1">
                                    <i class="bi bi-arrow-right-circle"></i> Próximo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Step 2: Confirmação de Produtos -->
                <div id="step-2" class="multisteps-form__panel" style="display: none;">
                    <form id="product-form" action="{{ route('products.pdf.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div id="product-cards" class="row">


                            <!-- Produtos gerados dinamicamente aqui -->
                        </div>
                        <!-- Botão para Adicionar Produtos -->
                        <div class="stylish-upload-footer stylish-upload-footer-fixed gap-2">
                            <button type="submit" class="btn stylish-btn-save">
                                <i class="bi bi-check2-circle"></i> Salvar Produtos
                            </button>
                            <button type="button" class="btn stylish-btn-back" id="prevStep2" onclick="moveStep(-1)">
                                <i class="bi bi-arrow-left-circle"></i> Voltar
                            </button>
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
    // Não mostrar barra de progresso de 0 a 100
    const form = document.querySelector('#uploadForm');
    const formData = new FormData(form);

    fetch("{{ route('products.upload') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(async response => {
            // Tenta parsear JSON, se falhar mostra erro amigável
            try {
                const data = await response.json();
                if (data.products && data.products.length > 0) {
                    data.products.forEach(product => { product.id = generateProductId(); });
                    products = data.products;
                    renderProductCards(products);
                    moveStep(1);
                } else {
                    alert('Nenhum produto encontrado no arquivo!');
                }
            } catch (err) {
                alert('Erro ao processar o arquivo. Verifique se o arquivo está correto ou se sua sessão expirou.');
            }
        })
        .catch(error => {
            alert('Erro inesperado ao enviar o arquivo. Tente novamente.');
        });
});

// Dropzone: clique em qualquer lugar da área ou botão abre o seletor de arquivo
const dropzone = document.getElementById('upload-dropzone');
const dropLabel = document.getElementById('upload-drop-label');
const fileInput = document.getElementById('file-upload');
const uploadBtn = document.getElementById('upload-btn');
const selectedFileInfo = document.getElementById('selected-file-info');

if (dropzone && dropLabel && fileInput) {
    // Corrigido: só abre o seletor se não for o botão
    dropzone.addEventListener('click', function(e) {
        // Só abre o seletor se NÃO for o botão de upload
        if ((e.target === dropzone || e.target === dropLabel) && e.target.id !== 'upload-btn') {
            fileInput.click();
        }
    });
    uploadBtn.addEventListener('click', function(e) {
        e.stopPropagation(); // Impede propagação para o dropzone/label
        fileInput.click();
    });
    // Drag and drop
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });
    dropzone.addEventListener('dragleave', function(e) {
        dropzone.classList.remove('dragover');
    });
    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            showSelectedFile();
        }
    });
    fileInput.addEventListener('change', showSelectedFile);
}

function showSelectedFile() {
    if (!fileInput.files || !fileInput.files[0]) {
        selectedFileInfo.style.display = 'none';
        selectedFileInfo.innerHTML = '';
        return;
    }
    const file = fileInput.files[0];
    const icon = file.type === 'application/pdf' ? 'bi-file-earmark-pdf-fill' : 'bi-filetype-csv';
    selectedFileInfo.innerHTML = `
        <span class="selected-file-icon"><i class="bi ${icon}"></i></span>
        <span class="selected-file-name">${file.name}</span>
        <button type="button" class="selected-file-remove" onclick="removeSelectedFile()"><i class="bi bi-x"></i></button>
    `;
    selectedFileInfo.style.display = 'flex';
}
function removeSelectedFile() {
    fileInput.value = '';
    showSelectedFile();
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
        card.classList.add('col-md-2', 'mb-3');
        card.innerHTML = `
<!-- Card no estilo cartinha -->
<div class="product-card-modern position-relative d-flex flex-column h-100" id="product-card-${product.id}">
    <div class="product-img-area" style="position: relative; overflow: visible;">
        <img src="${imgSrc}"
            class="product-img"
            alt="${product.name || ''}"
            id="product-image-${product.id}"
            style="cursor:pointer;"
            onclick="triggerImageUpload('${product.id}')">
        <input type="file" name="products[${index}][image]" class="form-control"
            accept="image/*" style="display: none" id="image-upload-${product.id}">
        <div class="image-preview" id="image-preview-${product.id}"></div>
        <input type="hidden" name="products[${index}][image_base64]" id="image-base64-${product.id}" value="${product.image_url && product.image_url.startsWith('data:image') ? product.image_url : ''}">
        <input type="hidden" name="products[${index}][quantity]" value="${product.stock_quantity || 1}">
        <input type="hidden" name="products[${index}][product_code]" value="${product.product_code || ''}">
        <input type="hidden" name="products[${index}][status]" value="${product.status || 'ativo'}">
        <button type="button" class="btn btn-danger rounded-circle" style="position: absolute; top: 0.3em; right: 0.3em; z-index: 10; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 1.6em; box-shadow: 0 2px 8px #d7263d33;" onclick="deleteProductCard('${product.id}')" title="Remover produto">
            <i class="fas fa-trash-alt" style="font-size: 1.1em;"></i>
         </button>
        <span class="badge-product-code" title="Código do Produto" style="top: 0.5em; left: 0.5em; position: absolute; font-size: 1.08em; padding: 0.38em 1.3em; min-width: 80px;">
            <i class="bi bi-upc-scan"></i> ${product.product_code || ''}
        </span>
        <span class="badge-quantity" title="Quantidade em Estoque" style="bottom: 0.5em; right: 0.5em; position: absolute; font-size: 1.08em; padding: 0.38em 1.3em; min-width: 60px;">
            <i class="bi bi-stack"></i> ${product.stock_quantity || 0}
        </span>
    </div>
    <div class="card-body d-flex flex-column gap-2 justify-content-start align-items-stretch" style="padding: 1.1em 1em 0.7em 1em;">
        <div class="product-title mb-2 d-flex align-items-center justify-content-center gap-2" title="${product.name || ''}">
            <i class="bi bi-box-seam" style="color: #fff; font-size: 1.2em;"></i>
            <input type="text" class="form-control text-center fw-bold border-0 bg-transparent p-0 m-0 w-100 nome-produto-upload"
                name="products[${index}][name]"
                value="${product.name || ''}" placeholder="Nome do produto" required ${product.name ? 'readonly' : ''}
                style="font-size:1.22em; min-width:0; height: 2.5em; padding: 0.3em 0.2em; line-height: 1.3; color: #232323;">
        </div>
        <div class="d-flex justify-content-between gap-2 mb-2 area-precos-upload">
            <span class="badge-price d-flex align-items-center gap-2" title="Preço de Custo">
                <i class="bi bi-tag"></i>
                <input type="number" step="0.01" name="products[${index}][price]"
                    value="${product.price}" class="form-control border-0 bg-transparent p-0 m-0 text-center fw-bold"
                    placeholder="Preço" required style="font-size:1.18em; width: 80px; max-width: 110px; color: #232323;">
            </span>
            <span class="badge-price-sale d-flex align-items-center gap-2" title="Preço de Venda">
                <i class="bi bi-currency-dollar"></i>
                <input type="number" step="0.01" name="products[${index}][price_sale]"
                    value="${product.price_sale}" class="form-control border-0 bg-transparent p-0 m-0 text-center fw-bold"
                    placeholder="Preço de Venda" required style="font-size:1.18em; width: 80px; max-width: 110px; color: #232323;">
            </span>
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
        addButton.classList.add('btn', 'stylish-btn-add', 'rounded-circle');
        addButton.style.position = 'absolute';
        addButton.style.bottom = '20px';
        addButton.style.right = '20px';
        addButton.style.width = '64px';
        addButton.style.height = '64px';
        addButton.style.fontSize = '2.2em';
        addButton.style.padding = '0';
        addButton.innerHTML = '<i class="bi bi-plus-lg"></i>';
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
        status: 'ativo',
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

<style>
    /* Botão de adicionar estiloso */
    .stylish-btn-add {
        background: linear-gradient(135deg, #43cea2 0%, #6a82fb 100%);
        color: #fff;
        border: none;
        box-shadow: 0 4px 16px #43cea233, 0 2px 8px #6a82fb33;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.18s;
        font-size: 2.2em;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 20;
    }
    .stylish-btn-add:hover {
        background: linear-gradient(135deg, #6a82fb 0%, #43cea2 100%);
        color: #fff;
        box-shadow: 0 8px 32px #43cea233, 0 4px 16px #6a82fb33;
        transform: scale(1.08) rotate(-8deg);
    }
    /* Área de dropzone estilizada */
    .stylish-upload-dropzone {
        background: linear-gradient(135deg, #e3f2fd 0%, #f8fafc 100%);
        border: 2.5px dashed #43cea2;
        border-radius: 22px;
        padding: 3.2em 1.2em 2.2em 1.2em;
        text-align: center;
        position: relative;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        box-shadow: 0 4px 24px #43cea233;
        cursor: pointer;
        animation: dropzonePulse 2.2s infinite alternate;
        min-height: 260px;
        margin-bottom: 2.2em;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .stylish-upload-dropzone:hover, .stylish-upload-dropzone.dragover {
        border-color: #6a82fb;
        box-shadow: 0 8px 32px #6a82fb33;
    }
    @keyframes dropzonePulse {
        0% { box-shadow: 0 2px 12px #43cea233; border-color: #43cea2; }
        100% { box-shadow: 0 12px 36px #6a82fb33; border-color: #6a82fb; }
    }
    .stylish-upload-drop-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        cursor: pointer;
        user-select: none;
    }
    .upload-drop-icon.animated-upload-icon {
        font-size: 4.2em;
        color: #43cea2;
        margin-bottom: 0.3em;
        animation: uploadIconPulse 1.6s infinite alternate;
        filter: drop-shadow(0 2px 12px #43cea233);
        transition: color 0.2s;
    }
    .upload-drop-title {
        font-size: 1.25em;
        color: #3498db;
        font-weight: 700;
        margin-bottom: 0.2em;
        letter-spacing: 0.01em;
        text-shadow: 0 1px 4px #fff;
    }
    .upload-drop-desc {
        font-size: 1.08em;
        color: #5d6d7e;
        font-weight: 500;
        margin-bottom: 0.5em;
        letter-spacing: 0.01em;
        text-shadow: 0 1px 4px #fff;
    }
    .upload-drop-text {
        font-size: 1.15em;
        color: #3a3a3a;
        font-weight: 600;
        margin-bottom: 0.2em;
        letter-spacing: 0.01em;
        text-shadow: 0 1px 4px #fff;
    }
    .upload-drop-or {
        display: block;
        font-size: 1.08em;
        color: #8e44ad;
        font-weight: 700;
        margin: 0.2em 0 0.2em 0;
        letter-spacing: 0.01em;
    }
    .stylish-upload-dropzone input[type="file"] {
        display: none;
    }
    .selected-file-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.7em;
        background: #f8fafc;
        border: 1.5px solid #43cea2;
        border-radius: 10px;
        padding: 0.7em 1.2em;
        font-size: 1.08em;
        color: #3498db;
        font-weight: 600;
        box-shadow: 0 2px 8px #43cea233;
        margin: 0 auto 0 auto;
        max-width: 340px;
        position: relative;
        animation: fadeInFile 0.5s;
    }
    .selected-file-info .selected-file-icon {
        font-size: 1.5em;
        color: #43cea2;
    }
    .selected-file-info .selected-file-remove {
        background: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1em;
        margin-left: 0.5em;
        box-shadow: 0 2px 8px #e74c3c33;
        transition: background 0.2s;
        cursor: pointer;
    }
    .selected-file-info .selected-file-remove:hover {
        background: #c0392b;
    }
    @keyframes fadeInFile {
        0% { opacity: 0; transform: translateY(12px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    /* Botão de upload integrado */
    .stylish-upload-btn {
        background: linear-gradient(90deg, #43cea2 0%, #6a82fb 100%);
        color: #fff;
        font-weight: 600;
        font-size: 1.1em;
        border-radius: 8px;
        border: none;
        padding: 0.7em 2.2em;
        box-shadow: 0 2px 8px #43cea233;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        margin-top: 0.5em;
    }
    .stylish-upload-btn:hover {
        background: linear-gradient(90deg, #6a82fb 0%, #43cea2 100%);
        color: #fff;
        box-shadow: 0 4px 16px #43cea233;
    }
    .stylish-upload-footer-fixed {
        position: sticky;
        bottom: 0;
        left: 0;
        width: 100%;
        background: transparent;
        z-index: 10;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0.7em 0 0.7em 0;
        box-shadow: none;
        margin-top: 1.2em;
    }
    .stylish-btn-next {
        background: linear-gradient(90deg, #43cea2 0%, #6a82fb 100%);
        color: #fff;
        font-weight: 700;
        font-size: 1.1em;
        border-radius: 8px;
        border: none;
        padding: 0.7em 2.2em;
        box-shadow: 0 2px 8px #43cea233;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    }
    .stylish-btn-next:hover {
        background: linear-gradient(90deg, #6a82fb 0%, #43cea2 100%);
        color: #fff;
        box-shadow: 0 4px 16px #43cea233;
    }
    .stylish-btn-save {
        background: linear-gradient(90deg, #8e44ad 0%, #43cea2 100%);
        color: #fff;
        font-weight: 700;
        font-size: 1.1em;
        border-radius: 8px;
        border: none;
        padding: 0.7em 2.2em;
        box-shadow: 0 2px 8px #8e44ad33;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        margin-right: 0.5em;
    }
    .stylish-btn-save:hover {
        background: linear-gradient(90deg, #43cea2 0%, #8e44ad 100%);
        color: #fff;
        box-shadow: 0 4px 16px #8e44ad33;
    }
    .stylish-btn-back {
        background: #e3f2fd;
        color: #6a82fb;
        font-weight: 700;
        font-size: 1.1em;
        border-radius: 8px;
        border: none;
        padding: 0.7em 2.2em;
        box-shadow: 0 2px 8px #43cea233;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    }
    .stylish-btn-back:hover {
        background: #6a82fb;
        color: #fff;
        box-shadow: 0 4px 16px #43cea233;
    }
    .stylish-modal-header {
        background: linear-gradient(90deg, #43cea2 0%, #6a82fb 100%);
        color: #fff;
        border-radius: 18px 18px 0 0;
        border-bottom: none;
        box-shadow: 0 2px 8px #43cea233;
        padding: 1.2em 2em 1em 2em;
        display: flex;
        align-items: center;
    }
    .stylish-modal-title {
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 0.5em;
    }
    .stylish-modal-title-icon {
        font-size: 1.5em;
        color: #fff;
        filter: drop-shadow(0 2px 8px #43cea233);
    }
    .custom-progress-bar-bg {
        width: 100%;
        height: 14px;
        background: linear-gradient(90deg, #e3f2fd 0%, #e0e7ef 100%);
        border-radius: 8px;
        overflow: visible;
        box-shadow: 0 2px 8px #43cea233;
        position: relative;
    }
    .custom-progress-bar-anim {
        height: 100%;
        background: linear-gradient(90deg, #43cea2 0%, #6a82fb 100%);
        border-radius: 8px;
        box-shadow: 0 2px 8px #43cea233;
        transition: width 0.7s cubic-bezier(.4,2,.6,1);
        will-change: width;
    }
    @keyframes progressBarAnim {
        0% { width: 0; }
        100% { width: 100%; }
    }
    .custom-progress-icons {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 0;
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        z-index: 2;
    }
    .custom-progress-icon {
        position: relative;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #43cea2 0%, #6a82fb 100%);
        color: #fff;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.7em;
        box-shadow: 0 2px 8px #43cea233;
        border: 3px solid #fff;
        transition: background 0.3s, box-shadow 0.3s;
        z-index: 3;
        margin-top: -7px;
    }
    .custom-progress-icon.active {
        background: linear-gradient(135deg, #8e44ad 0%, #43cea2 100%);
        box-shadow: 0 4px 16px #8e44ad33;
        color: #fff;
        border: 3px solid #8e44ad;
        animation: stepPulse 0.7s;
    }
    @keyframes stepPulse {
        0% { box-shadow: 0 0 0 0 #8e44ad44; }
        70% { box-shadow: 0 0 0 12px #8e44ad11; }
        100% { box-shadow: 0 4px 16px #8e44ad33; }
    }
    /* Barra de rolagem para a área dos cards de produtos no modal de upload */
    .custom-upload-modal #product-cards {
        max-height: 75vh;
        overflow-y: auto;
        padding-right: 8px;
    }
    .custom-upload-modal #product-cards::-webkit-scrollbar {
        width: 8px;
        background: #e3f2fd;
        border-radius: 8px;
    }
    .custom-upload-modal #product-cards::-webkit-scrollbar-thumb {
        background: #3498db;
        border-radius: 8px;
    }
    /* Para Firefox */
    .custom-upload-modal #product-cards {
        scrollbar-width: thin;
        scrollbar-color: #3498db #e3f2fd;
    }
    /* Modal maior: quase tela cheia, centralizado e responsivo */
    .custom-upload-modal .modal-dialog {
        max-width: 98vw !important;
        width: 98vw !important;
        max-height: 98vh !important;
        height: 98vh !important;
        margin: auto;
        display: flex;
        align-items: center;
    }
    .custom-upload-modal .modal-content {
        max-height: 98vh !important;
        height: 100%;
        overflow-y: auto;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(44, 62, 80, 0.18);
        border: none;
        background: #fafdff;
    }
</style>
  
