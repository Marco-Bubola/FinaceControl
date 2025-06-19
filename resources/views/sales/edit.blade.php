@foreach($sales as $sale)
<div class="modal fade animate__animated animate__fadeInDown" id="modalEditSale{{ $sale->id }}" tabindex="-1"
    aria-labelledby="modalEditSaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content shadow-lg rounded-4">
            <!-- HEADER MODAL -->
            <div class="modal-header bg-gradient-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center">
                    <h5 class="modal-title mb-0" id="modalEditSaleLabel">
                        <i class="fas fa-pen-alt me-2"></i> Editar Venda
                    </h5>
                </div>

                <div class="text-end  text-dark rounded px-3 py-2 shadow-sm">
                    <strong>{{ $sale->client->name }}</strong><br>
                    <small class="text-muted"><i class="fas fa-phone-alt me-1"></i>{{ $sale->client->phone }}</small>
                </div>

                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal"
                    aria-label="Fechar"></button>
            </div>

            <!-- BODY MODAL -->
            <div class="modal-body">
                <form action="{{ route('sales.update', $sale->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="client_id" value="{{ $sale->client_id }}">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tipo_pagamento{{ $sale->id }}" class="form-label"><i class="bi bi-credit-card-2-front"></i> Tipo de Pagamento</label>
                            <select name="tipo_pagamento" id="tipo_pagamento{{ $sale->id }}" class="form-select" required>
                                <option value="a_vista" {{ $sale->tipo_pagamento == 'a_vista' ? 'selected' : '' }}>À vista</option>
                                <option value="parcelado" {{ $sale->tipo_pagamento == 'parcelado' ? 'selected' : '' }}>Parcelado</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="parcelas-group-edit{{ $sale->id }}" style="display: {{ $sale->tipo_pagamento == 'parcelado' ? '' : 'none' }};">
                            <label for="parcelas{{ $sale->id }}" class="form-label"><i class="bi bi-list-ol"></i> Parcelas</label>
                            <input type="number" name="parcelas" id="parcelas{{ $sale->id }}" class="form-control" min="2" max="36" value="{{ $sale->parcelas ?? 2 }}">
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const tipoPagamento = document.getElementById('tipo_pagamento{{ $sale->id }}');
                            const parcelasGroup = document.getElementById('parcelas-group-edit{{ $sale->id }}');
                            tipoPagamento.addEventListener('change', function() {
                                if (tipoPagamento.value === 'parcelado') {
                                    parcelasGroup.style.display = '';
                                } else {
                                    parcelasGroup.style.display = 'none';
                                }
                            });
                        });
                    </script>
                    
                    <div class="row" style="max-height: 65vh; overflow-y: auto;">
                        @foreach($sale->saleItems as $item)
                        <div class="col-md-3 mb-3">
                            <div class="card h-100 border shadow-sm">
                                <img src="{{ asset('storage/products/' . $item->product->image) }}"
                                    class="card-img-top rounded-top" alt="{{ $item->product->name }}"
                                    style="height: 230px; object-fit: cover;">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h6 class="card-title text-center text-truncate" title="{{ $item->product->name }}">
                                        {{ $item->product->name }}
                                    </h6>
                                    <p class="text-center text-success mb-1">
                                        <i class="fas fa-dollar-sign"></i>
                                        <strong>R$ {{ number_format($item->price_sale, 2, ',', '.') }}</strong>
                                    </p>
                                    <input type="number" name="products[{{ $item->product->id }}][price_sale]"
                                        value="{{ number_format($item->price_sale, 2, '.', '') }}" min="0"
                                        class="form-control mb-2 text-center" step="0.01" required>

                                    <label class="small text-center mb-1">Quantidade:</label>
                                    <input type="number" name="products[{{ $item->product->id }}][quantity]" min="1"
                                        class="form-control text-center" value="{{ $item->quantity }}">

                                    <input type="hidden" name="products[{{ $item->product->id }}][product_id]"
                                        value="{{ $item->product->id }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- FOOTER MODAL COM BOTÕES -->
                    <div class="modal-footer d-flex justify-content-center gap-3 mt-3">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-check-circle me-1"></i> Atualizar Venda
                        </button>
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times-circle me-1"></i> Cancelar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
