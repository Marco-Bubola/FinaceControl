<!-- Modal de Confirmar Exclusão para Vendas (Estilo Amigável e Grande) -->
@if(isset($sales))
    @foreach($sales as $sale)
        <!-- modal ... -->
         <div class="modal fade" id="modalDeleteSale{{ $sale->id }}" tabindex="-1"
    aria-labelledby="modalDeleteSaleLabel{{ $sale->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-0 rounded-top-4">
                <div class="d-flex align-items-center w-100">
                    <i class="bi bi-emoji-smile text-primary fs-1 me-3"></i>
                    <div>
                        <h5 class="modal-title mb-1 fw-semibold text-primary" id="modalDeleteSaleLabel{{ $sale->id }}">
                            Excluir venda?
                        </h5>
                        <small class="text-muted">Você está prestes a remover uma venda. Tudo certo?</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2 fs-5">
                    Tem certeza de que deseja excluir a venda do cliente
                    <strong class="text-primary">{{ $sale->client->name }}</strong>?
                </p>
                <p class="mb-4">
                    Preço total da venda:
                    <strong class="text-success">R$ {{ number_format($sale->total_price, 2, ',', '.') }}</strong>
                </p>
                <div class="alert alert-info py-2 px-3 mb-0 small rounded-pill" role="alert">
                    Não se preocupe, você pode cadastrar uma nova venda a qualquer momento!
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center bg-light rounded-bottom-4">
                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">
                        <i class="bi bi-trash me-1"></i> Excluir venda
                    </button>
                    <input type="hidden" name="from" value="index">
                </form>
                <button type="button" class="btn btn-primary px-4 rounded-pill ms-2" data-bs-dismiss="modal">
                    Manter venda
                </button>
            </div>
        </div>
    </div>
</div>
    @endforeach
@elseif(isset($sale))
    <!-- Modal de exclusão de venda (id dinâmico) -->
    <div class="modal fade" id="modalDeleteSale{{ $sale->id }}" tabindex="-1"
        aria-labelledby="modalDeleteSaleLabel{{ $sale->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-light border-0 rounded-top-4">
                    <div class="d-flex align-items-center w-100">
                        <i class="bi bi-emoji-smile text-primary fs-1 me-3"></i>
                        <div>
                            <h5 class="modal-title mb-1 fw-semibold text-primary" id="modalDeleteSaleLabel{{ $sale->id }}">
                                Excluir venda?
                            </h5>
                            <small class="text-muted">Você está prestes a remover uma venda. Tudo certo?</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-2 fs-5">
                        Tem certeza de que deseja excluir a venda do cliente
                        <strong class="text-primary">{{ $sale->client->name }}</strong>?
                    </p>
                    <p class="mb-4">
                        Preço total da venda:
                        <strong class="text-success">R$ {{ number_format($sale->total_price, 2, ',', '.') }}</strong>
                    </p>
                    <div class="alert alert-info py-2 px-3 mb-0 small rounded-pill" role="alert">
                        Não se preocupe, você pode cadastrar uma nova venda a qualquer momento!
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center bg-light rounded-bottom-4">
                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">
                            <i class="bi bi-trash me-1"></i> Excluir venda
                        </button>
                        <input type="hidden" name="from" value="show">
                    </form>
                    <button type="button" class="btn btn-primary px-4 rounded-pill ms-2" data-bs-dismiss="modal">
                        Manter venda
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- 
        ATENÇÃO: 
        Para abrir este modal corretamente, o botão deve usar:
        data-bs-toggle="modal" data-bs-target="#modalDeleteSale{{ $sale->id }}"
        Exemplo:
        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteSale{{ $sale->id }}">
            <i class="bi bi-trash"></i> Excluir Venda
        </button>
    -->
@endif



