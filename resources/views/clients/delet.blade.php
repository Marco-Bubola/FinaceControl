<!-- Modal de Confirmar Exclusão -->
@foreach($clients as $client)
    <div class="modal fade" id="modalDeleteClient{{ $client->id }}" tabindex="-1" aria-labelledby="modalDeleteClientLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteClientLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir o cliente <strong>{{ $client->name }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
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
