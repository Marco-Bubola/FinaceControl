@foreach($products as $product)
<!-- Modal de Edição de Produto -->
<div class="modal fade" id="modalEditProduct{{ $product->id }}" tabindex="-1"
    aria-labelledby="modalEditProductLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <!-- Adicionado modal-dialog-scrollable para rolagem -->
        <div class="modal-content" style="min-height: 70vh; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
            <!-- Aumentada a altura mínima e bordas arredondadas -->
            <div class="modal-header bg-primary text-white text-center" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <!-- Centralizado o título -->
                <h5 class="modal-title w-100" id="modalEditProductLabel{{ $product->id }}">Editar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; margin: 10px;">
                <div id="progressBar{{ $product->id }}" class="progress-bar bg-success" role="progressbar"
                    style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Step 1 -->
                    <div id="step1{{ $product->id }}" class="step">
                        <h6 class="text-primary mb-4" style="font-size: 1.5rem; text-align: center; font-weight: 500;">Informações Básicas</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nome do Produto</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ $product->name }}" required style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label for="product_code" class="form-label">Código do Produto</label>
                                <input type="text" name="product_code" id="product_code" class="form-control"
                                    value="{{ $product->product_code }}" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Preço</label>
                                <input type="text" name="price" id="price" class="form-control"
                                    value="{{ $product->price }}" required oninput="convertCommaToDot(this)" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label for="price_sale" class="form-label">Preço de Venda</label>
                                <input type="text" name="price_sale" id="price_sale" class="form-control"
                                    value="{{ $product->price_sale }}" required oninput="convertCommaToDot(this)" style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Categoria</label>
                                <select name="category_id" id="category_id" class="form-select" required style="border-radius: 8px;">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id_category }}"
                                        {{ $product->category_id == $category->id_category ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="stock_quantity" class="form-label">Quantidade em Estoque</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control"
                                    value="{{ $product->stock_quantity }}" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <label for="description" class="form-label">Descrição</label>
                                <textarea name="description" id="description" class="form-control"
                                    rows="5" style="border-radius: 8px;">{{ $product->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div id="step2{{ $product->id }}" class="step d-none">
                        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 70%;">
                            <img src="{{ asset('storage/products/' . $product->image) }}"
                                id="productImage{{ $product->id }}" class="img-fluid mb-3" alt="{{ $product->name }}"
                                style="max-height: 60vh; object-fit: contain; border-radius: 8px;">
                            <input type="file" name="image" id="image{{ $product->id }}" class="form-control mt-2"
                                style="display: none;" onchange="previewImage(event, {{ $product->id }})">
                            <label for="image{{ $product->id }}" class="btn btn-outline-secondary btn-lg mt-3" style="border-radius: 8px;">Trocar Imagem</label>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-primary" onclick="nextStep({{ $product->id }})"
                            id="nextButton{{ $product->id }}" style="border-radius: 8px;">Próximo</button>
                        <button type="button" class="btn btn-secondary" onclick="prevStep({{ $product->id }})"
                            id="prevButton{{ $product->id }}" style="display: none; border-radius: 8px;">Voltar</button>
                        <button type="submit" class="btn btn-success" id="submitButton{{ $product->id }}"
                            style="display: none; border-radius: 8px;">Atualizar Produto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
<style>/* Centraliza os títulos (labels) e aumenta o tamanho da fonte */
/* Centraliza os títulos (labels) e aumenta o tamanho da fonte */
.form-label {
    text-align: center; /* Centraliza o texto */
    font-size: 1.1rem; /* Aumenta o tamanho da fonte */
    font-weight: 600; /* Deixa o texto mais destacado */
    display: block;
    margin-bottom: 8px; /* Espaço entre o título e o campo */
}

/* Estilo para a seção de Informações Básicas */
#step1 .step {
    margin-bottom: 20px;
    background-color: #f8f9fa; /* Cor de fundo suave */
    padding: 20px; /* Padding para deixar mais espaçado */
    border-radius: 8px; /* Bordas arredondadas */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
}



/* Estilo das colunas dentro de 'Informações Básicas' */
#step1 .step .row .col-md-6 {
    margin-bottom: 15px; /* Espaço entre as colunas */
}

/* Estilo das inputs dentro de 'Informações Básicas' */
#step1 .step .form-control {
    border-radius: 8px; /* Bordas arredondadas para as inputs */
    padding: 10px; /* Padding maior para inputs */
    font-size: 1rem; /* Tamanho da fonte maior */
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); /* Sombra suave nas inputs */
}

/* Adicionando bordas arredondadas nas colunas */
#step1 .step .col-md-6 {
    border-radius: 8px;
}

</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar os tooltips do Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});

function deleteProduct(button) {
    // Captura o ID do produto do botão clicado
    const productId = button.getAttribute('data-id');

    // Captura o formulário de exclusão com base no ID
    const form = document.getElementById(`deleteForm-${productId}`);

    // Armazenar os parâmetros de paginação no localStorage antes da exclusão
    const page = new URLSearchParams(window.location.search).get('page') || 1;
    const perPage = new URLSearchParams(window.location.search).get('per_page') || 10;
    localStorage.setItem('currentPage', page);
    localStorage.setItem('perPage', perPage);

    // Enviar o formulário via AJAX
    const formData = new FormData(form);

    fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Produto excluído com sucesso, agora remova o produto da página
                removeProductFromPage(productId);
                // Atualiza a paginação com os parâmetros preservados
                updatePagination(data.page, data.per_page); // Mantém a paginação intacta
            } else {
                alert('Erro ao excluir o produto!');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
}

// Função para remover o produto do DOM
function removeProductFromPage(productId) {
    const productCard = document.getElementById(`product-card-${productId}`);
    if (productCard) {
        productCard.remove();
    }
}

// Função para atualizar a paginação sem recarregar a página
function updatePagination(page, perPage) {
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        const href = new URL(link.href);
        href.searchParams.set('page', page);
        href.searchParams.set('per_page', perPage);
        link.href = href.toString();
    });
}

// Função para restaurar a paginação a partir do localStorage
function restorePaginationParams() {
    // Recupera os valores do localStorage
    const page = localStorage.getItem('currentPage') || 1; // Se não houver, página 1
    const perPage = localStorage.getItem('perPage') || 10; // Se não houver, 10 por padrão

    // Redireciona para a URL com os parâmetros corretos
    const url = new URL(window.location);
    url.searchParams.set('page', page);
    url.searchParams.set('per_page', perPage);
    window.location.href = url.toString();
}

// Verifica se há parâmetros no localStorage e aplica
if (localStorage.getItem('currentPage') && localStorage.getItem('perPage')) {
    restorePaginationParams();
}

function previewImage(event, productId) {
    const input = event.target;
    const reader = new FileReader();

    reader.onload = function(e) {
        const image = document.getElementById(`productImage${productId}`);
        image.src = e.target.result;
    };

    if (input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
    }
}

function nextStep(productId) {
    document.getElementById(`step1${productId}`).classList.add('d-none');
    document.getElementById(`step2${productId}`).classList.remove('d-none');
    document.getElementById(`progressBar${productId}`).style.width = '100%';
    document.getElementById(`progressBar${productId}`).setAttribute('aria-valuenow', '100');
    document.getElementById(`nextButton${productId}`).style.display = 'none';
    document.getElementById(`prevButton${productId}`).style.display = 'inline-block';
    document.getElementById(`submitButton${productId}`).style.display = 'inline-block';
}

function prevStep(productId) {
    document.getElementById(`step2${productId}`).classList.add('d-none');
    document.getElementById(`step1${productId}`).classList.remove('d-none');
    document.getElementById(`progressBar${productId}`).style.width = '50%';
    document.getElementById(`progressBar${productId}`).setAttribute('aria-valuenow', '50');
    document.getElementById(`nextButton${productId}`).style.display = 'inline-block';
    document.getElementById(`prevButton${productId}`).style.display = 'none';
    document.getElementById(`submitButton${productId}`).style.display = 'none';
}
</script>
