<!-- Modal de confirmação para exclusão do produto (Estilo Amigável e Grande) -->
        <div class="modal fade" id="modalDeleteProduct" tabindex="-1" aria-labelledby="modalDeleteProductLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-light border-0 rounded-top-4">
                        <div class="d-flex align-items-center w-100">
                            <i class="bi bi-box-seam text-warning fs-1 me-3"></i>
                            <div>
                                <h5 class="modal-title mb-1 fw-semibold text-warning" id="modalDeleteProductLabel">
                                    Excluir produto?
                                </h5>
                                <small class="text-muted">Você está prestes a remover um produto desta venda. Tudo certo?</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body text-center">
                        <!-- Espaço para inserir dinamicamente a mensagem -->
                        <p class="mb-2 fs-5" id="modal-delete-message"></p>
                        <div class="alert alert-info py-2 px-3 mb-0 small rounded-pill" role="alert">
                            Não se preocupe, você pode adicionar outro produto à venda a qualquer momento!
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center bg-light rounded-bottom-4">
                        <form id="form-delete-product" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">
                                <i class="bi bi-trash me-1"></i> Excluir produto
                            </button>
                        </form>
                        <button type="button" class="btn btn-primary px-4 rounded-pill ms-2" data-bs-dismiss="modal">
                            Manter produto
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Seleciona todos os botões de exclusão
                var deleteButtons = document.querySelectorAll('.btn-delete-product');
                deleteButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        // Recupera os dados do item da venda
                        var saleItemId = this.getAttribute('data-sale-item-id');
                        var productName = this.getAttribute('data-product-name');
                        var productPrice = this.getAttribute('data-product-price');

                        // Atualiza a mensagem do modal com formato mais amigável
                        var message = "Tem certeza de que deseja excluir o produto <strong class=\"text-warning\">" +
                            productName + "</strong>?<br><br>Preço do produto: <strong class=\"text-success\">R$ " +
                            productPrice + "</strong>";
                        document.getElementById('modal-delete-message').innerHTML = message;

                        // Atualiza a action do formulário usando a rota nomeada
                        var form = document.getElementById('form-delete-product');
                        // Substitui o placeholder ":id" pelo saleItemId
                        form.action = "{{ route('sales.item.destroy', ':id') }}".replace(':id',
                            saleItemId);
                    });
                });
            });
        </script>
