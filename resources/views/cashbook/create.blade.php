<!-- Modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('cashbook.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionModalLabel">Adicionar Transação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Progress Bar -->
                    <div class="progress-container mb-4">
                        <div class="progress">
                            <div id="progress-bar" class="progress-bar" style="width: 50%;"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="step-circle active">1</div>
                            <div class="step-circle">2</div>
                        </div>
                        <div class="d-flex justify-content-between text-center mt-1">
                            <small>Informações Básicas</small>
                            <small>Detalhes Adicionais</small>
                        </div>
                    </div>

                    <!-- Step 1 -->
                    <div id="step-1">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">Valor</label>
                                <input type="number" step="0.01" class="form-control" id="value" name="value" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Descrição</label>
                                <input type="text" class="form-control" id="description" name="description">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Data</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="is_pending" class="form-label">Pendente</label>
                                <select class="form-control" id="is_pending" name="is_pending" required>
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Categoria</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="type_id" class="form-label">Tipo</label>
                                <select class="form-control" id="type_id" name="type_id" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id_type }}">{{ $type->desc_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="client_id" class="form-label">Cliente</label>
                                <select class="form-control" id="client_id" name="client_id">
                                    <option value="" selected>Nenhum cliente</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div id="step-2" class="d-none">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="note" class="form-label">Nota</label>
                                <textarea class="form-control" id="note" name="note"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="segment_id" class="form-label">Segmento</label>
                                <select class="form-control" id="segment_id" name="segment_id">
                                    <option value="">Nenhum</option>
                                    @foreach($segments as $segment)
                                        <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="attachment" class="form-label">Anexo</label>
                                <input type="file" class="form-control" id="attachment" name="attachment">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="prev-step" onclick="toggleSteps('prev')"
                        disabled>Voltar</button>
                    <button type="button" class="btn btn-primary" id="next-step"
                        onclick="toggleSteps('next')">Próximo</button>
                    <button type="submit" class="btn btn-success d-none" id="save-button">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
