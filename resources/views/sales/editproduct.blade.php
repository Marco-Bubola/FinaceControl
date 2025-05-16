        <!-- Modal Único para Edição do Produto da Venda (Estilizado) -->
        <div class="modal fade" id="modalEditProduct" tabindex="-1" aria-labelledby="modalEditProductLabel"
            aria-hidden="true" data-sale-id="{{ $sale->id }}">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-light border-0 rounded-top-4">
                        <div class="d-flex align-items-center w-100">
                            <i class="bi bi-pencil-square text-primary fs-1 me-3"></i>
                            <div>
                                <h5 class="modal-title mb-1 fw-semibold text-primary" id="modalEditProductLabel">
                                    Editar Produto da Venda
                                </h5>
                                <small class="text-muted">Atualize o preço de venda e a quantidade do produto selecionado.</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-edit-product" action="" method="POST" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <!-- Campo para editar o preço de venda -->
                                <div class="col-md-6">
                                    <label for="price_edit" class="form-label fw-semibold">Preço de Venda</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-success fw-bold">R$</span>
                                        <input type="number" step="0.01" name="price_sale" id="price_edit" class="form-control border-success"
                                            min="0.01" required>
                                    </div>
                                </div>
                                <!-- Campo para editar a quantidade -->
                                <div class="col-md-6">
                                    <label for="quantity_edit" class="form-label fw-semibold">Quantidade</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-primary fw-bold">
                                            <i class="bi bi-stack"></i>
                                        </span>
                                        <input type="number" name="quantity" id="quantity_edit" class="form-control border-primary"
                                            min="1" required>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info py-2 px-3 mt-4 mb-0 small rounded-pill" role="alert">
                                Dica: Você pode ajustar o preço de venda para promoções ou descontos especiais!
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill"
                                    data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary px-4 rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i> Atualizar Produto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        @endpush
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var editButtons = document.querySelectorAll('.btn-edit-product');
                
                editButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        var productId = this.getAttribute('data-product-id');
                        var productPrice = this.getAttribute('data-product-price');
                        var productQuantity = this.getAttribute('data-product-quantity');
                        var saleId = document.getElementById('modalEditProduct').getAttribute('data-sale-id');
                        
                        document.getElementById('price_edit').value = productPrice;
                        document.getElementById('quantity_edit').value = productQuantity;
                        
                        var form = document.getElementById('form-edit-product');
                        form.action = "{{ url('sales') }}/" + saleId + "/item/" + productId;
                    });
                });
            });
        </script>
