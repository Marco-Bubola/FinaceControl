@foreach($products as $product)
<!-- Modal de Edição de Produto Moderno e Colorido -->
<div class="modal fade" id="modalEditProduct{{ $product->id }}" tabindex="-1" aria-labelledby="modalEditProductLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg" style="background: linear-gradient(135deg, #f0f4ff 70%, #c7d2fe 100%); border: 3px solid #6366f1; max-width: 900px; margin: auto;">
            <div class="modal-header text-white rounded-top-5 border-0" style="background: linear-gradient(90deg, #6366f1 60%, #22d3ee 100%); box-shadow: 0 4px 16px rgba(99,102,241,0.18); min-height: 90px; align-items: center;">
                <div class="d-flex align-items-center gap-3 w-100">
                    <i class="bi bi-pencil-square fs-1 text-warning shadow-sm p-2 bg-white bg-opacity-25 rounded-4"></i>
                    <div>
                        <span style="font-size:2.1rem; font-weight:900; letter-spacing:1px;">Editar Produto</span>
                        <div class="mt-1" style="font-size:1rem; color:#e0e7ff; opacity:0.85;">Altere os dados do produto e salve</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="progress rounded-0" style="height: 8px; background: #e0e7ff;">
                <div id="progressBarEdit{{ $product->id }}" class="progress-bar bg-gradient rounded-pill shadow-sm" role="progressbar" style="width: 50%; transition: width 0.5s; background: linear-gradient(90deg,#22d3ee,#6366f1);" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="modal-body px-5 py-4" style="background: rgba(255,255,255,0.98); border-radius: 0 0 2.5rem 2.5rem; box-shadow: 0 2px 12px rgba(99,102,241,0.09); min-height: 0;">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="ativo">
                    <!-- Step 1 -->
                    <div id="step1Edit{{ $product->id }}" class="step">
                        <h6 class="mb-4 d-flex align-items-center gap-2" style="font-size: 1.3rem; font-weight: 800; color: #6366f1;">
                            <i class="bi bi-info-circle-fill text-info fs-3"></i> <span>Informações Básicas</span>
                        </h6>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-primary bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-tag text-success fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="text" name="name" id="nameEdit{{ $product->id }}" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-primary" placeholder="Nome do Produto" value="{{ $product->name }}" required>
                                        <label for="nameEdit{{ $product->id }}" class="small">Nome do Produto</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-secondary bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-upc-scan text-secondary fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="text" name="product_code" id="productCodeEdit{{ $product->id }}" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-secondary" placeholder="Código do Produto" value="{{ $product->product_code }}" required>
                                        <label for="productCodeEdit{{ $product->id }}" class="small">Código do Produto</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-success bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-currency-dollar text-success fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="text" name="price" id="priceEdit{{ $product->id }}" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-success" placeholder="Preço" required inputmode="numeric" autocomplete="off" value="{{ number_format($product->price, 2, ',', '.') }}" oninput="maskMoney(this)">
                                        <label for="priceEdit{{ $product->id }}" class="small">Preço</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-warning bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-cash-coin text-warning fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="text" name="price_sale" id="priceSaleEdit{{ $product->id }}" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-warning" placeholder="Preço de Venda" required inputmode="numeric" autocomplete="off" value="{{ number_format($product->price_sale, 2, ',', '.') }}" oninput="maskMoney(this)">
                                        <label for="priceSaleEdit{{ $product->id }}" class="small">Preço de Venda</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-primary bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-collection text-primary fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <select name="category_id" id="categoryEdit{{ $product->id }}" class="form-select border-0 bg-white rounded-4 ps-2 input-focus-primary" required>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id_category }}" {{ $product->category_id == $category->id_category ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="categoryEdit{{ $product->id }}" class="small">Categoria</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-info bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-box-seam text-info fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="number" name="stock_quantity" id="stockQuantityEdit{{ $product->id }}" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-info" placeholder="Quantidade em Estoque" value="{{ $product->stock_quantity }}" required>
                                        <label for="stockQuantityEdit{{ $product->id }}" class="small">Quantidade em Estoque</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-primary bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1 align-items-start pt-3"><i class="bi bi-card-text text-primary fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <textarea name="description" id="descriptionEdit{{ $product->id }}" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-primary" placeholder="Descrição" style="height: 90px; resize: vertical;">{{ $product->description }}</textarea>
                                        <label for="descriptionEdit{{ $product->id }}" class="small">Descrição</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center bg-light rounded-bottom-5 border-0 mt-4" style="box-shadow: 0 -2px 8px rgba(99,102,241,0.07); background: #f0f4ff;">
                            <button type="button" class="btn btn-gradient-primary rounded-pill px-5 py-2 d-flex align-items-center gap-2 shadow-lg fs-5 fw-bold" onclick="nextStepEdit({{ $product->id }})" id="nextButtonEdit{{ $product->id }}" style="background: linear-gradient(90deg,#6366f1,#22d3ee); color: #fff; border: none; transition: background 0.2s, color 0.2s;">
                                <i class="bi bi-arrow-right-circle fs-3"></i> <span>Próximo</span>
                            </button>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div id="step2Edit{{ $product->id }}" class="step d-none">
                        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%;">
                            <img src="{{ asset('storage/products/' . $product->image) }}" id="productImageEdit{{ $product->id }}" class="img-fluid mb-3 border border-4 border-primary rounded-4 shadow-lg" alt="{{ $product->name }}" style="max-height: 50vh; object-fit: contain; background: #fff; transition: box-shadow 0.3s;">
                            <input type="file" name="image" id="imageEdit{{ $product->id }}" class="form-control mt-2" style="display: none;" onchange="previewImageEdit(event, {{ $product->id }})">
                            <label for="imageEdit{{ $product->id }}" class="btn btn-outline-info btn-lg mt-3 rounded-pill d-flex align-items-center gap-2 shadow-sm fs-5 fw-semibold" style="transition: background 0.2s, color 0.2s;">
                                <i class="bi bi-image text-info"></i> <span>Trocar Imagem</span>
                            </label>
                        </div>
                        <div class="modal-footer justify-content-center bg-light rounded-bottom-5 border-0 mt-4" style="box-shadow: 0 -2px 8px rgba(99,102,241,0.07); background: #f0f4ff;">
                            <button type="button" class="btn btn-secondary rounded-pill px-5 py-2 d-flex align-items-center gap-2 shadow-lg fs-5 fw-bold" onclick="prevStepEdit({{ $product->id }})" id="prevButtonEdit{{ $product->id }}" style="transition: background 0.2s, color 0.2s;">
                                <i class="bi bi-arrow-left-circle fs-3"></i> <span>Voltar</span>
                            </button>
                            <button type="submit" class="btn btn-success rounded-pill px-5 py-2 d-flex align-items-center gap-2 shadow-lg fs-5 fw-bold ms-3" id="submitButtonEdit{{ $product->id }}" style="transition: background 0.2s, color 0.2s;">
                                <i class="bi bi-check-circle fs-3"></i> <span>Atualizar Produto</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
.input-focus-primary:focus {
    background: #e0e7ff !important;
}
.input-focus-secondary:focus {
    background: #f3f4f6 !important;
}
.input-focus-success:focus {
    background: #d1fae5 !important;
}
.input-focus-warning:focus {
    background: #fef9c3 !important;
}
.input-focus-info:focus {
    background: #cffafe !important;
}
</style>

<script>
function previewImageEdit(event, productId) {
    const input = event.target;
    const reader = new FileReader();
    reader.onload = function(e) {
        const image = document.getElementById(`productImageEdit${productId}`);
        image.src = e.target.result;
    };
    if (input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
    }
}

function nextStepEdit(productId) {
    document.getElementById(`step1Edit${productId}`).classList.add('d-none');
    document.getElementById(`step2Edit${productId}`).classList.remove('d-none');
    document.getElementById(`progressBarEdit${productId}`).style.width = '100%';
    document.getElementById(`progressBarEdit${productId}`).setAttribute('aria-valuenow', '100');
}

function prevStepEdit(productId) {
    document.getElementById(`step2Edit${productId}`).classList.add('d-none');
    document.getElementById(`step1Edit${productId}`).classList.remove('d-none');
    document.getElementById(`progressBarEdit${productId}`).style.width = '50%';
    document.getElementById(`progressBarEdit${productId}`).setAttribute('aria-valuenow', '50');
}

// Máscara monetária para inputs de valor
function maskMoney(input) {
    let v = input.value.replace(/\D/g, '');
    v = v.padStart(3, '0');
    let intPart = v.slice(0, v.length - 2);
    let decPart = v.slice(-2);
    intPart = intPart.replace(/^0+(?!$)/, '');
    let formatted = intPart + ',' + decPart;
    formatted = formatted.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    input.value = formatted;
}
</script>
