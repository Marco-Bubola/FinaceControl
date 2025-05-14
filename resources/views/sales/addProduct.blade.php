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
                                                <h5 class="card-title text-center text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let form = document.getElementById('saleForm{{ $sale->id }}');
            let productList = document.getElementById('productList{{ $sale->id }}');
            // Função para a pesquisa de produtos
            document.getElementById('productSearch{{ $sale->id }}').addEventListener('input', function () {
                let filter = this.value.toLowerCase().replace(/\./g, ''); // Remove os pontos do input

                let products = document.querySelectorAll('#productList{{ $sale->id }} .product-card');
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

            // Mostrar apenas os produtos selecionados
            document.getElementById('showSelectedBtn{{ $sale->id }}').addEventListener('click', function () {
                let productCards = document.querySelectorAll('#productList{{ $sale->id }} .product-card');
                let selectedProducts = document.querySelectorAll('#productList{{ $sale->id }} .product-checkbox:checked');

                let isShowingSelectedOnly = this.dataset.selected === 'true';

                if (isShowingSelectedOnly) {
                    productCards.forEach(function (productCard) {
                        productCard.style.display = ''; // Exibe todos os produtos
                    });
                    this.dataset.selected = 'false'; // Atualiza o estado para mostrar todos
                    this.textContent = 'Mostrar apenas selecionados'; // Atualiza o texto do botão
                } else {
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

            document.querySelectorAll('.product-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    var productCard = this.closest('.product-card'); // Obtém o card do produto mais próximo
                    var quantityInput = productCard.querySelector('.product-quantity'); // Obtém o campo de quantidade
                    var priceSaleInput = productCard.querySelector('.product-price-sale'); // Obtém o campo de preço de venda
                    var priceOriginalInput = productCard.querySelector('.product-price-original'); // Obtém o campo de preço original

                    var productId = this.dataset.productId; // Obtém o ID do produto

                    if (this.checked) {
                        // Quando o checkbox é marcado
                        productCard.style.opacity = 1; // Torna o produto totalmente visível
                        quantityInput.disabled = false; // Habilita o campo de quantidade
                        priceSaleInput.disabled = false; // Habilita o campo de preço de venda
                        priceOriginalInput.disabled = false; // Habilita o campo de preço original
                        addProductToForm(productId, quantityInput.value, priceSaleInput.value, priceOriginalInput.value); // Adiciona os dados ao formulário
                        productCard.classList.add('selected'); // Adiciona a classe 'selected' para aplicar estilos adicionais
                    } else {
                        // Quando o checkbox é desmarcado
                        productCard.style.opacity = 0.5; // Torna o produto meio cinza (transparente)
                        quantityInput.disabled = true; // Desabilita o campo de quantidade
                        priceSaleInput.disabled = true; // Desabilita o campo de preço de venda
                        priceOriginalInput.disabled = true; // Desabilita o campo de preço original
                        removeProductFromForm(productId); // Remove os dados do formulário
                        productCard.classList.remove('selected'); // Remove a classe 'selected' quando desmarcado
                    }
                });
            });

            // Função para adicionar o produto ao formulário
            function addProductToForm(productId, quantity, priceSale, priceOriginal) {
                let form = document.getElementById('saleForm{{ $sale->id }}'); // Formulário específico da venda

                // Adicionar o input hidden para a quantidade
                let inputQuantity = document.createElement("input");
                inputQuantity.type = "hidden";
                inputQuantity.name = `products[${productId}][quantity]`;
                inputQuantity.value = quantity;
                form.appendChild(inputQuantity);

                // Adicionar o input hidden para o id do produto
                let inputProductId = document.createElement("input");
                inputProductId.type = "hidden";
                inputProductId.name = `products[${productId}][product_id]`;
                inputProductId.value = productId;
                form.appendChild(inputProductId);

                // Adicionar o input hidden para o preço de venda
                let inputPriceSale = document.createElement("input");
                inputPriceSale.type = "hidden";
                inputPriceSale.name = `products[${productId}][price_sale]`;
                inputPriceSale.value = priceSale;
                form.appendChild(inputPriceSale);

                // Adicionar o input hidden para o preço original
                let inputPriceOriginal = document.createElement("input");
                inputPriceOriginal.type = "hidden";
                inputPriceOriginal.name = `products[${productId}][price]`; // Nome correto para o preço
                inputPriceOriginal.value = priceOriginal;
                form.appendChild(inputPriceOriginal);
            }

            // Função para remover o produto do formulário
            function removeProductFromForm(productId) {
                let form = document.getElementById('saleForm{{ $sale->id }}'); // Formulário específico da venda
                let inputs = form.querySelectorAll(`input[name="products[${productId}]"]`);
                inputs.forEach(input => input.remove());
            }



            // Verifica se há pelo menos um produto selecionado antes de submeter o formulário
            form.addEventListener("submit", function (event) {
                let selectedProducts = form.querySelectorAll('input[name^="products"]');

                if (selectedProducts.length === 0) {
                    event.preventDefault();
                    alert("Selecione pelo menos um produto.");
                }
            });
        });
    </script>
    <link rel="stylesheet" href="{{ asset('css/add-product-modal.css') }}">
@endforeach
