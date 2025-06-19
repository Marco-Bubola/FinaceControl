<!-- Modal de Adicionar Produto Moderno e Colorido -->
<div class="modal fade" id="modalAddProduct" tabindex="-1" aria-labelledby="modalAddProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow-lg" style="background: linear-gradient(135deg, #f0f4ff 70%, #c7d2fe 100%); border: 3px solid #6366f1; max-width: 900px; margin: auto;">
            <div class="modal-header text-white rounded-top-5 border-0" style="background: linear-gradient(90deg, #6366f1 60%, #22d3ee 100%); box-shadow: 0 4px 16px rgba(99,102,241,0.18); min-height: 90px; align-items: center;">
                <div class="d-flex align-items-center gap-3 w-100">
                    <i class="bi bi-plus-square fs-1 text-warning shadow-sm p-2 bg-white bg-opacity-25 rounded-4"></i>
                    <div>
                        <span style="font-size:2.1rem; font-weight:900; letter-spacing:1px;">Adicionar Novo Produto</span>
                        <div class="mt-1" style="font-size:1rem; color:#e0e7ff; opacity:0.85;">Preencha os dados do produto para cadastrar</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="progress rounded-0" style="height: 8px; background: #e0e7ff;">
                <div id="progressBarCreate" class="progress-bar bg-gradient rounded-pill shadow-sm" role="progressbar" style="width: 50%; transition: width 0.5s; background: linear-gradient(90deg,#22d3ee,#6366f1);" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="modal-body px-5 py-4" style="background: rgba(255,255,255,0.98); border-radius: 0 0 2.5rem 2.5rem; box-shadow: 0 2px 12px rgba(99,102,241,0.09); min-height: 0;">
                <form action="{{ route('products.manual.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="status" value="ativo">
                    <!-- Step 1 -->
                    <div id="step1Create" class="step">
                        <h6 class="mb-4 d-flex align-items-center gap-2" style="font-size: 1.3rem; font-weight: 800; color: #6366f1;">
                            <i class="bi bi-info-circle-fill text-info fs-3"></i> <span>Informações Básicas</span>
                        </h6>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-primary bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-tag text-success fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="text" name="name" id="name" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-primary" placeholder="Nome do Produto" required>
                                        <label for="name" class="small">Nome do Produto</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-secondary bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-upc-scan text-secondary fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="text" name="product_code" id="product_code" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-secondary" placeholder="Código do Produto" required>
                                        <label for="product_code" class="small">Código do Produto</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-success bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-currency-dollar text-success fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="text" name="price" id="price" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-success" placeholder="Preço" required inputmode="numeric" autocomplete="off" value="0,00" oninput="maskMoney(this)">
                                        <label for="price" class="small">Preço</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-warning bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-cash-coin text-warning fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="text" name="price_sale" id="price_sale" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-warning" placeholder="Preço de Venda" required inputmode="numeric" autocomplete="off" value="0,00" oninput="maskMoney(this)">
                                        <label for="price_sale" class="small">Preço de Venda</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-primary bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-collection text-primary fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <select name="category_id" id="category_id" class="form-select border-0 bg-white rounded-4 ps-2 input-focus-primary" required>
                                            @if($categories->isEmpty())
                                            <option value="N/A" selected>N/A</option>
                                            @else
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <label for="category_id" class="small">Categoria</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-info bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1"><i class="bi bi-box-seam text-info fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <input type="number" name="stock_quantity" id="stock_quantity" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-info" placeholder="Quantidade em Estoque" required>
                                        <label for="stock_quantity" class="small">Quantidade em Estoque</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-lg shadow-sm rounded-4 border border-3 border-primary bg-white position-relative">
                                    <span class="input-group-text bg-white border-0 pe-1 align-items-start pt-3"><i class="bi bi-card-text text-primary fs-4"></i></span>
                                    <div class="form-floating flex-grow-1">
                                        <textarea name="description" id="description" class="form-control border-0 bg-white rounded-4 ps-2 input-focus-primary" placeholder="Descrição" style="height: 90px; resize: vertical;"></textarea>
                                        <label for="description" class="small">Descrição</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center bg-light rounded-bottom-5 border-0 mt-4" style="box-shadow: 0 -2px 8px rgba(99,102,241,0.07); background: #f0f4ff;">
                            <button type="button" class="btn btn-gradient-primary rounded-pill px-5 py-2 d-flex align-items-center gap-2 shadow-lg fs-5 fw-bold" onclick="nextStepCreate()" id="nextButtonCreate" style="background: linear-gradient(90deg,#6366f1,#22d3ee); color: #fff; border: none; transition: background 0.2s, color 0.2s;">
                                <i class="bi bi-arrow-right-circle fs-3"></i> <span>Próximo</span>
                            </button>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div id="step2Create" class="step d-none">
                        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%;">
                            <img src="{{ asset('storage/products/product-placeholder.png') }}" id="productImageCreate" class="img-fluid mb-3 border border-4 border-primary rounded-4 shadow-lg" alt="Imagem do Produto" style="max-height: 50vh; object-fit: contain; background: #fff; transition: box-shadow 0.3s;">
                            <input type="file" name="image" id="imageCreate" class="form-control mt-2" style="display: none;" onchange="previewImageCreate(event)">
                            <label for="imageCreate" class="btn btn-outline-info btn-lg mt-3 rounded-pill d-flex align-items-center gap-2 shadow-sm fs-5 fw-semibold" style="transition: background 0.2s, color 0.2s;">
                                <i class="bi bi-image text-info"></i> <span>Trocar Imagem</span>
                            </label>
                        </div>
                        <div class="modal-footer justify-content-center bg-light rounded-bottom-5 border-0 mt-4" style="box-shadow: 0 -2px 8px rgba(99,102,241,0.07); background: #f0f4ff;">
                            <button type="button" class="btn btn-secondary rounded-pill px-5 py-2 d-flex align-items-center gap-2 shadow-lg fs-5 fw-bold" onclick="prevStepCreate()" id="prevButtonCreate" style="transition: background 0.2s, color 0.2s;">
                                <i class="bi bi-arrow-left-circle fs-3"></i> <span>Voltar</span>
                            </button>
                            <button type="submit" class="btn btn-success rounded-pill px-5 py-2 d-flex align-items-center gap-2 shadow-lg fs-5 fw-bold ms-3" id="submitButtonCreate" style="transition: background 0.2s, color 0.2s;">
                                <i class="bi bi-check-circle fs-3"></i> <span>Adicionar Produto</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
    }

    function prevStepCreate() {
        document.getElementById('step2Create').classList.add('d-none');
        document.getElementById('step1Create').classList.remove('d-none');
        document.getElementById('progressBarCreate').style.width = '50%';
        document.getElementById('progressBarCreate').setAttribute('aria-valuenow', '50');
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
