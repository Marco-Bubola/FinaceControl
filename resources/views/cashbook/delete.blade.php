
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteTransactionModal" tabindex="-1" aria-labelledby="deleteTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form method="POST" id="deleteTransactionForm">
                @csrf
                @method('DELETE')
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-light border-0 rounded-top-4">
                        <div class="d-flex align-items-center w-100">
                            <i class="bi bi-cash-stack text-success fs-1 me-3"></i>
                            <div>
                                <h5 class="modal-title mb-1 fw-semibold text-success" id="deleteTransactionModalLabel">
                                    Excluir transação?
                                </h5>
                                <small class="text-muted">Você está prestes a remover uma transação do caixa. Tudo certo?</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-2 fs-5">
                            Tem certeza de que deseja excluir a transação
                            <strong class="text-success" id="deleteTransactionDescription"></strong>?
                        </p>
                        <div class="alert alert-info py-2 px-3 mb-0 small rounded-pill" role="alert">
                            Não se preocupe, você pode registrar uma nova transação a qualquer momento!
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center bg-light rounded-bottom-4">
                        <button type="submit" class="btn btn-outline-danger px-4 rounded-pill">
                            <i class="bi bi-trash me-1"></i> Excluir transação
                        </button>
                        <button type="button" class="btn btn-primary px-4 rounded-pill ms-2" data-bs-dismiss="modal">
                            Manter transação
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
