@if(isset($sales))
@foreach($sales as $sale)
<!-- Modal de Adicionar Produto à Venda -->
<div class="modal fade add-product-modal" id="modalAddProductToSale{{ $sale->id }}" tabindex="-1"
    aria-labelledby="modalAddProductToSaleLabel{{ $sale->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddProductToSaleLabel{{ $sale->id }}">Adicionar Produto à Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulário para Adicionar Produto à Venda -->
                <form action="{{ route('sales.addProduct', $sale->id) }}" method="POST" id="saleForm{{ $sale->id }}"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Adicionando o campo oculto 'from' -->
                    <input type="hidden" name="from" value="index">
                    <!-- Barra de Pesquisa -->
                    <div class="d-flex mb-3">
                        <input type="text" class="form-control" id="productSearch{{ $sale->id }}"
                            placeholder="Pesquise por nome ou código do produto..." />
                        <div class="form-check form-switch ms-2 mt-2">
                            <input class="form-check-input" type="checkbox" role="switch"
                                id="showSelectedBtn{{ $sale->id }}" />
                            <label class="form-check-label" for="showSelectedBtn{{ $sale->id }}">Mostrar apenas
                                selecionados</label>
                        </div>
                    </div>

                    <!-- Produtos Disponíveis -->
                    <div class="row mb-4 product-list-container" id="productList{{ $sale->id }}">
                        @foreach($products as $product)
                        @if($product->stock_quantity > 0)
                        <div class="col-md-3 mb-4 product-card" data-product-id="{{ $product->id }}"
                            style="opacity: 0.5;">
                            <div class="card product-item" style="cursor: pointer;">
                                <!-- Checkbox sobre a imagem -->
                                <div class="form-check form-switch"
                                    style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                    <input class="form-check-input product-checkbox" type="checkbox" role="switch"
                                        id="flexSwitchCheckDefault{{ $product->id }}"
                                        data-product-id="{{ $product->id }}" />
                                </div>

                                <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top"
                                    alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title text-center text-truncate" title="{{ $product->name }}">
                                        {{ $product->name }}</h5>
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Código</th>
                                            <td>{{ $product->product_code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Estoque</th>
                                            <td><span class="product-stock">{{ $product->stock_quantity }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Preço Original</th>
                                            <td>
                                                <input type="number" class="form-control product-price-original"
                                                    name="products[{{ $product->id }}][price_original]"
                                                    value="{{ old('products.' . $product->id . '.price_original', $product->price) }}"
                                                    min="0" step="any" disabled />
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Preço de Venda</th>
                                            <td>
                                                <input type="number" class="form-control product-price-sale"
                                                    name="products[{{ $product->id }}][price_sale]"
                                                    value="{{ old('products.' . $product->id . '.price_sale', $product->price_sale) }}"
                                                    min="0" step="any" disabled />
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Qtd</th>
                                            <td>
                                                <input type="number" class="form-control product-quantity"
                                                    name="products[{{ $product->id }}][quantity]" min="1" value="1"
                                                    disabled />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <!-- Botão para Adicionar Produtos -->
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Adicionar Produto à Venda</button>
                        </div>
                    </div>
                    <input type="hidden" name="from" value="index">
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@elseif(isset($sale))
<!-- Modal de Adicionar Produto à Venda -->
<div class="modal fade add-product-modal" id="modalAddProductToSale{{ $sale->id }}" tabindex="-1"
    aria-labelledby="modalAddProductToSaleLabel{{ $sale->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddProductToSaleLabel{{ $sale->id }}">Adicionar Produto à Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulário para Adicionar Produto à Venda -->
                <form action="{{ route('sales.addProduct', $sale->id) }}" method="POST" id="saleForm{{ $sale->id }}"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Adicionando o campo oculto 'from' -->
                    <input type="hidden" name="from" value="index">
                    <!-- Barra de Pesquisa -->
                    <div class="d-flex mb-3">
                        <input type="text" class="form-control" id="productSearch{{ $sale->id }}"
                            placeholder="Pesquise por nome ou código do produto..." />
                        <div class="form-check form-switch ms-2 mt-2">
                            <input class="form-check-input" type="checkbox" role="switch"
                                id="showSelectedBtn{{ $sale->id }}" />
                            <label class="form-check-label" for="showSelectedBtn{{ $sale->id }}">Mostrar apenas
                                selecionados</label>
                        </div>
                    </div>

                    <!-- Produtos Disponíveis -->
                    <div class="row mb-4 product-list-container" id="productList{{ $sale->id }}">
                        @foreach($products as $product)
                        @if($product->stock_quantity > 0)
                        <div class="col-md-3 mb-4 product-card" data-product-id="{{ $product->id }}"
                            style="opacity: 0.5;">
                            <div class="card product-item" style="cursor: pointer;">
                                <!-- Checkbox sobre a imagem -->
                                <div class="form-check form-switch"
                                    style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                    <input class="form-check-input product-checkbox" type="checkbox" role="switch"
                                        id="flexSwitchCheckDefault{{ $product->id }}"
                                        data-product-id="{{ $product->id }}" />
                                </div>

                                <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top"
                                    alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title text-center text-truncate" title="{{ $product->name }}">
                                        {{ $product->name }}</h5>
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Código</th>
                                            <td>{{ $product->product_code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Estoque</th>
                                            <td><span class="product-stock">{{ $product->stock_quantity }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Preço Original</th>
                                            <td>
                                                <input type="number" class="form-control product-price-original"
                                                    name="products[{{ $product->id }}][price_original]"
                                                    value="{{ old('products.' . $product->id . '.price_original', $product->price) }}"
                                                    min="0" step="any" disabled />
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Preço de Venda</th>
                                            <td>
                                                <input type="number" class="form-control product-price-sale"
                                                    name="products[{{ $product->id }}][price_sale]"
                                                    value="{{ old('products.' . $product->id . '.price_sale', $product->price_sale) }}"
                                                    min="0" step="any" disabled />
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Qtd</th>
                                            <td>
                                                <input type="number" class="form-control product-quantity"
                                                    name="products[{{ $product->id }}][quantity]" min="1" value="1"
                                                    disabled />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <!-- Botão para Adicionar Produtos -->
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Adicionar Produto à Venda</button>
                        </div>
                    </div>
                    <input type="hidden" name="from" value="show">
                </form>
            </div>
        </div>
    </div>
</div>
@endif


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Para cada modal de adicionar produto à venda
    document.querySelectorAll('.add-product-modal').forEach(function(modal) {
        let saleId = modal.getAttribute('id').replace('modalAddProductToSale', '');
        let form = document.getElementById('saleForm' + saleId);
        let productList = document.getElementById('productList' + saleId);

        // Pesquisa de produtos
        let searchInput = document.getElementById('productSearch' + saleId);
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                let filter = this.value.toLowerCase().replace(/\./g, '');
                let products = productList.querySelectorAll('.product-card');
                products.forEach(function(product) {
                    let productName = product.querySelector('.card-title').textContent
                        .toLowerCase();
                    let productCode = product.querySelector('table tr td').textContent
                        .toLowerCase().replace(/\./g, '');
                    if (productName.includes(filter) || productCode.includes(filter)) {
                        product.style.display = '';
                    } else {
                        product.style.display = 'none';
                    }
                });
            });
        }

        // Mostrar apenas selecionados
        let showSelectedBtn = document.getElementById('showSelectedBtn' + saleId);
        if (showSelectedBtn) {
            showSelectedBtn.addEventListener('click', function() {
                let productCards = productList.querySelectorAll('.product-card');
                let isShowingSelectedOnly = this.dataset.selected === 'true';
                if (isShowingSelectedOnly) {
                    productCards.forEach(function(productCard) {
                        productCard.style.display = '';
                    });
                    this.dataset.selected = 'false';
                    this.nextElementSibling.textContent = 'Mostrar apenas selecionados';
                } else {
                    productCards.forEach(function(productCard) {
                        let checkbox = productCard.querySelector('.product-checkbox');
                        if (checkbox.checked) {
                            productCard.style.display = '';
                        } else {
                            productCard.style.display = 'none';
                        }
                    });
                    this.dataset.selected = 'true';
                    this.nextElementSibling.textContent = 'Mostrar todos os produtos';
                }
            });
        }

        // Checkbox de seleção de produto
        productList.querySelectorAll('.product-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var productCard = this.closest('.product-card');
                var quantityInput = productCard.querySelector('.product-quantity');
                var priceSaleInput = productCard.querySelector('.product-price-sale');
                var priceOriginalInput = productCard.querySelector(
                    '.product-price-original');
                var productId = this.dataset.productId;

                if (this.checked) {
                    productCard.style.opacity = 1;
                    quantityInput.disabled = false;
                    priceSaleInput.disabled = false;
                    priceOriginalInput.disabled = false;
                    addProductToForm(productId, quantityInput.value, priceSaleInput
                        .value, priceOriginalInput.value, form);
                    productCard.classList.add('selected');
                } else {
                    productCard.style.opacity = 0.5;
                    quantityInput.disabled = true;
                    priceSaleInput.disabled = true;
                    priceOriginalInput.disabled = true;
                    removeProductFromForm(productId, form);
                    productCard.classList.remove('selected');
                }
            });
        });

        // Função para adicionar o produto ao formulário
        function addProductToForm(productId, quantity, priceSale, priceOriginal, form) {
            // Remove se já existir (evita duplicidade)
            removeProductFromForm(productId, form);

            // Adiciona os inputs hidden
            let inputQuantity = document.createElement("input");
            inputQuantity.type = "hidden";
            inputQuantity.name = `products[${productId}][quantity]`;
            inputQuantity.value = quantity;
            form.appendChild(inputQuantity);

            let inputProductId = document.createElement("input");
            inputProductId.type = "hidden";
            inputProductId.name = `products[${productId}][product_id]`;
            inputProductId.value = productId;
            form.appendChild(inputProductId);

            let inputPriceSale = document.createElement("input");
            inputPriceSale.type = "hidden";
            inputPriceSale.name = `products[${productId}][price_sale]`;
            inputPriceSale.value = priceSale;
            form.appendChild(inputPriceSale);

            let inputPriceOriginal = document.createElement("input");
            inputPriceOriginal.type = "hidden";
            inputPriceOriginal.name = `products[${productId}][price]`;
            inputPriceOriginal.value = priceOriginal;
            form.appendChild(inputPriceOriginal);
        }

        // Função para remover o produto do formulário
        function removeProductFromForm(productId, form) {
            let inputs = form.querySelectorAll(`input[name^="products[${productId}]"]`);
            inputs.forEach(input => input.remove());
        }

        // Validação ao submeter
        form.addEventListener("submit", function(event) {
            let selectedProducts = form.querySelectorAll('input[name^="products["]');
            if (selectedProducts.length === 0) {
                event.preventDefault();
                alert("Selecione pelo menos um produto.");
            }
        });
    });
});
</script>
@endpush

<link rel="stylesheet" href="{{ asset('css/add-product-modal.css') }}">