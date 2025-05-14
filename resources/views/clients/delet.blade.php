
<!-- Modal de Confirmar Exclusão para Clientes (Estilo Amigável e Grande) -->
@foreach($clients as $client)
<div class="modal fade" id="modalDeleteClient{{ $client->id }}" tabindex="-1"
    aria-labelledby="modalDeleteClientLabel{{ $client->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-0 rounded-top-4">
                <div class="d-flex align-items-center w-100">
                    <i class="bi bi-person-x text-danger fs-1 me-3"></i>
                    <div>
                        <h5 class="modal-title mb-1 fw-semibold text-danger" id="modalDeleteClientLabel{{ $client->id }}">
                            Excluir cliente?
                        </h5>
                        <small class="text-muted">Você está prestes a remover um cliente. Tudo certo?</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2 fs-5">
                    Tem certeza de que deseja excluir o cliente
                    <strong class="text-danger">{{ $client->name }}</strong>?
                </p>
                <div class="alert alert-info py-2 px-3 mb-0 small rounded-pill" role="alert">
                    Não se preocupe, você pode cadastrar um novo cliente a qualquer momento!
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center bg-light rounded-bottom-4">
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">
                        <i class="bi bi-trash me-1"></i> Excluir cliente
                    </button>
                </form>
                <button type="button" class="btn btn-primary px-4 rounded-pill ms-2" data-bs-dismiss="modal">
                    Manter cliente
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
