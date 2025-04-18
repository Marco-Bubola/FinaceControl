<!-- Modal de Confirmar Exclusão para Vendas -->
@foreach($sales as $sale)
<div class="modal fade" id="modalDeleteSale{{ $sale->id }}" tabindex="-1"
    aria-labelledby="modalDeleteSaleLabel{{ $sale->id }}" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteSaleLabel{{ $sale->id }}">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Exibir nome do cliente e preço total da venda -->
                <p>Tem certeza de que deseja excluir a venda do cliente <strong>{{ $sale->client->name }}</strong>?</p>
                <p>Preço total da venda: <strong>R$ {{ number_format($sale->total_price, 2, ',', '.') }}</strong></p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
@endforeach