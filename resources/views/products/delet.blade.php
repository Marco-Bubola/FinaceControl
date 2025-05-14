
@foreach($products as $product)
<!-- Modal de Confirmar Exclusão para Produtos (Estilo Amigável e Grande) -->
<div class="modal fade" id="modalDeleteProduct{{ $product->id }}" tabindex="-1"
    aria-labelledby="modalDeleteProductLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-0 rounded-top-4">
                <div class="d-flex align-items-center w-100">
                    <i class="bi bi-box-seam text-warning fs-1 me-3"></i>
                    <div>
                        <h5 class="modal-title mb-1 fw-semibold text-warning" id="modalDeleteProductLabel{{ $product->id }}">
                            Excluir produto?
                        </h5>
                        <small class="text-muted">Você está prestes a remover um produto. Tudo certo?</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2 fs-5">
                    Tem certeza de que deseja excluir o produto
                    <strong class="text-warning">{{ $product->name }}</strong>?
                </p>
                <p class="mb-4">
                    Preço do produto:
                    <strong class="text-success">R$ {{ number_format($product->price, 2, ',', '.') }}</strong>
                </p>
                <div class="alert alert-info py-2 px-3 mb-0 small rounded-pill" role="alert">
                    Não se preocupe, você pode cadastrar um novo produto a qualquer momento!
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center bg-light rounded-bottom-4">
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
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
@endforeach
