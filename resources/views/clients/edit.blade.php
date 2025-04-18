<!-- Modal de Editar Cliente -->
    @foreach($clients as $client)
        <div class="modal fade" id="modalEditClient{{ $client->id }}" tabindex="-1" aria-labelledby="modalEditClientLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditClientLabel">Editar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('clients.update', $client->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $client->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $client->email }}">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $client->phone }}">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Endereço</label>
                                <textarea class="form-control" id="address" name="address">{{ $client->address }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
