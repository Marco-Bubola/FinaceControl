<!-- Modal de Adicionar Produto -->
<div class="modal fade" id="modalAddProduct" tabindex="-1" aria-labelledby="modalAddProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="min-height: 90vh;">
            <div class="modal-header bg-primary text-white text-center">
                <h5 class="modal-title w-100" id="modalAddProductLabel">Adicionar Novo Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="progress" style="height: 5px;">
                <div id="progressBarCreate" class="progress-bar bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.manual.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Step 1 -->
                    <div id="step1Create" class="step">
                    <h6 class="text-primary mb-4" style="font-size: 1.5rem; text-align: center; font-weight: 500;">Informações Básicas</h6>
                    <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nome do Produto</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="product_code" class="form-label">Código do Produto</label>
                                <input type="text" name="product_code" id="product_code" class="form-control" required>
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Preço</label>
                                <input type="text" name="price" id="price" class="form-control" required oninput="convertCommaToDot(this)">
                            </div>
                            <div class="col-md-6">
                                <label for="price_sale" class="form-label">Preço de Venda</label>
                                <input type="text" name="price_sale" id="price_sale" class="form-control" required oninput="convertCommaToDot(this)">
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Categoria</label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    @if($categories->isEmpty())
                                    <option value="N/A" selected>N/A</option>
                                    @else
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="stock_quantity" class="form-label">Quantidade em Estoque</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" required>
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-md-12">
                                <label for="description" class="form-label">Descrição</label>
                                <textarea name="description" id="description" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div id="step2Create" class="step d-none">
                        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%;">
                            <img src="{{ asset('storage/products/product-placeholder.png') }}" id="productImageCreate" class="img-fluid mb-3" alt="Imagem do Produto" style="max-height: 60vh; object-fit: contain;">
                            <input type="file" name="image" id="imageCreate" class="form-control mt-2" style="display: none;" onchange="previewImageCreate(event)">
                            <label for="imageCreate" class="btn btn-outline-secondary btn-lg mt-3">Trocar Imagem</label>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-primary" onclick="nextStepCreate()" id="nextButtonCreate">Próximo</button>
                        <button type="button" class="btn btn-secondary" onclick="prevStepCreate()" id="prevButtonCreate" style="display: none;">Voltar</button>
                        <button type="submit" class="btn btn-success" id="submitButtonCreate" style="display: none;">Adicionar Produto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImageCreate(event) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const image = document.getElementById('productImageCreate');
            image.src = e.target.result;
        };
        if (event.target.files && event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    function nextStepCreate() {
        document.getElementById('step1Create').classList.add('d-none');
        document.getElementById('step2Create').classList.remove('d-none');
        document.getElementById('progressBarCreate').style.width = '100%';
        document.getElementById('progressBarCreate').setAttribute('aria-valuenow', '100');
        document.getElementById('nextButtonCreate').style.display = 'none';
        document.getElementById('prevButtonCreate').style.display = 'inline-block';
        document.getElementById('submitButtonCreate').style.display = 'inline-block';
    }

    function prevStepCreate() {
        document.getElementById('step2Create').classList.add('d-none');
        document.getElementById('step1Create').classList.remove('d-none');
        document.getElementById('progressBarCreate').style.width = '50%';
        document.getElementById('progressBarCreate').setAttribute('aria-valuenow', '50');
        document.getElementById('nextButtonCreate').style.display = 'inline-block';
        document.getElementById('prevButtonCreate').style.display = 'none';
        document.getElementById('submitButtonCreate').style.display = 'none';
    }

    function convertCommaToDot(input) {
        input.value = input.value.replace(',', '.');
    }
</script>
