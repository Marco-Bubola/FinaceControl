@foreach($sales as $sale)
    <!-- Modal de Editar Venda -->
    <div class="modal fade" id="modalEditSale{{ $sale->id }}" tabindex="-1" aria-labelledby="modalEditSaleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditSaleLabel">Editar Venda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sales.update', $sale->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Selecione o Cliente -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="client_id">Cliente</label>
                                <select name="client_id" id="client_id" class="form-control" required>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ $sale->client_id == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Produtos da venda -->
                        <div class="row" style="max-height: 400px; overflow-y: auto;"> <!-- Barra de rolagem aqui -->
                            @foreach($sale->saleItems as $item)
                                <div class="col-md-3 mb-3">
                                    <div class="card d-flex flex-column h-100">
                                        <img src="{{ asset('storage/products/' . $item->product->image) }}" class="card-img-top"
                                            alt="{{ $item->product->name }}" style="height: 150px; object-fit: cover;">
                                        <div class="card-body">
                                            <!-- Nome do produto com truncamento -->
                                            <h6 class="card-title text-center"
                                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                title="{{ $item->product->name }}">
                                                {{ $item->product->name }}
                                            </h6>
                                            <p class="card-text text-center" id="price-display-{{ $item->product->id }}">R$
                                                {{ number_format($item->price_sale, 2, ',', '.') }}
                                            </p>

                                            <!-- Campo de edição do preço de venda -->
                                            <input type="number" name="products[{{ $item->product->id }}][price_sale]"
                                                value="{{ number_format($item->price_sale, 2, '.', '') }}" min="0"
                                                class="form-control mb-2 text-center" step="0.01" required>
                                            <!-- Campo de quantidade -->
                                            <p class="card-text text-center">Qtd: {{ $item->quantity }}</p>
                                            <input type="number" name="products[{{ $item->product->id }}][quantity]" min="1"
                                                class="form-control" value="{{ $item->quantity }}">
                                            <input type="hidden" name="products[{{ $item->product->id }}][product_id]"
                                                value="{{ $item->product->id }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Botão para Atualizar Venda -->
                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Atualizar Venda</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach