@php
    $componentesIds = $kit->componentes->pluck('componente_produto_id')->toArray();
@endphp
<div class="modal fade" id="modalEditKit{{ $kit->id }}" tabindex="-1" aria-labelledby="modalEditKitLabel{{ $kit->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title" id="modalEditKitLabel{{ $kit->id }}">
          <i class="bi bi-boxes me-2"></i>Editar Kit de Produtos
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <form action="{{ route('products.kit.update', $kit->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label for="kit_name_edit_{{ $kit->id }}" class="form-label">Nome do Kit</label>
            <input type="text" class="form-control" id="kit_name_edit_{{ $kit->id }}" name="name" value="{{ $kit->name }}" required>
          </div>
          <div class="mb-3">
            <label for="kit_price_edit_{{ $kit->id }}" class="form-label">Preço do Kit</label>
            <input type="text" class="form-control" id="kit_price_edit_{{ $kit->id }}" name="price" value="{{ number_format($kit->price, 2, ',', '.') }}" required oninput="maskMoney(this)">
          </div>
          <div class="mb-3">
            <label for="kit_price_sale_edit_{{ $kit->id }}" class="form-label">Preço de Venda do Kit</label>
            <input type="text" class="form-control" id="kit_price_sale_edit_{{ $kit->id }}" name="price_sale" value="{{ number_format($kit->price_sale, 2, ',', '.') }}" required oninput="maskMoney(this)">
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
                  @php
                    $comp = $kit->componentes->where('componente_produto_id', $produto->id)->first();
                  @endphp
                  <tr>
                    <td>
                      <input type="checkbox" name="produtos[{{ $produto->id }}][selecionado]" value="1" class="form-check-input checkbox-produto-kit" {{ $comp ? 'checked' : '' }}>
                    </td>
                    <td>{{ $produto->name }}</td>
                    <td>{{ $produto->stock_quantity }}</td>
                    <td>
                      <input type="number" name="produtos[{{ $produto->id }}][quantidade]" class="form-control input-quantidade-produto-kit" min="1" max="{{ $produto->stock_quantity }}" value="{{ $comp ? $comp->quantidade : 1 }}" {{ $comp ? '' : 'disabled' }}>
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
          <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
// Habilita/desabilita o input de quantidade conforme o checkbox
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#modalEditKit{{ $kit->id }} .checkbox-produto-kit').forEach(function(checkbox) {
      checkbox.addEventListener('change', function() {
        const input = this.closest('tr').querySelector('.input-quantidade-produto-kit');
        input.disabled = !this.checked;
      });
    });
  });
</script> 