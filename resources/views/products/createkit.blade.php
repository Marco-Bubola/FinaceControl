
<!-- Modal Criar Kit -->
<div class="modal fade" id="modalCriarKit" tabindex="-1" aria-labelledby="modalCriarKitLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title" id="modalCriarKitLabel">
          <i class="bi bi-boxes me-2"></i>Criar Kit de Produtos
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <form action="{{ route('products.kit.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="kit_name" class="form-label">Nome do Kit</label>
            <input type="text" class="form-control" id="kit_name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="kit_price" class="form-label">Preço do Kit</label>
            <input type="number" step="0.01" min="0" class="form-control" id="kit_price" name="price" required>
          </div>
          <div class="mb-3">
            <label for="kit_price_sale" class="form-label">Preço de Venda do Kit</label>
            <input type="number" step="0.01" min="0" class="form-control" id="kit_price_sale" name="price_sale" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Produtos do Kit</label>
            <div class="table-responsive">
              <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                  <tr>
                    <th>Selecionar</th>
                    <th>Produto</th>
                    <th>Estoque</th>
                    <th>Quantidade no Kit</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($products as $produto)
                  <tr>
                    <td>
                      <input type="checkbox" name="produtos[{{ $produto->id }}][selecionado]" value="1" class="form-check-input checkbox-produto-kit">
                    </td>
                    <td>{{ $produto->name }}</td>
                    <td>{{ $produto->stock_quantity }}</td>
                    <td>
                      <input type="number" name="produtos[{{ $produto->id }}][quantidade]" class="form-control input-quantidade-produto-kit" min="1" max="{{ $produto->stock_quantity }}" value="1" disabled>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Criar Kit</button>
        </div>
      </form>
    </div>
  </div>
</div>