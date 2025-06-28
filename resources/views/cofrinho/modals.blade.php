<div class="modal fade" id="createCofrinhoModal" tabindex="-1" aria-labelledby="createCofrinhoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="createCofrinhoForm" method="POST" action="{{ route('cofrinho.store') }}">
            @csrf
            <div class="modal-content rounded-4 shadow-lg cofrinho-modal-glass">
                <div class="modal-header bg-warning bg-gradient text-white rounded-top-4 border-0">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="createCofrinhoModalLabel">
                        <i class="fas fa-piggy-bank fa-2x me-2"></i> Novo Cofrinho
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-lightbulb text-warning fa-lg"></i>
                        <span>Crie cofrinhos para organizar seus sonhos, reservas ou objetivos!<br><small>Exemplo: <b>Viagem dos sonhos</b>, <b>Reserva de emergência</b>, <b>Novo notebook</b>.</small></span>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text bg-warning text-white"><i class="fas fa-tag"></i></span>
                        <input type="text" class="form-control form-control-lg rounded-end-pill" id="nome" name="nome" required maxlength="255" placeholder="Ex: Viagem dos sonhos" list="sugestoes-nome">
                        <datalist id="sugestoes-nome">
                            <option value="Viagem dos sonhos">
                            <option value="Reserva de emergência">
                            <option value="Novo notebook">
                            <option value="Reforma da casa">
                            <option value="Férias em família">
                            <option value="Curso de especialização">
                        </datalist>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text bg-warning text-white"><i class="fas fa-bullseye"></i></span>
                        <input type="number" class="form-control form-control-lg rounded-end-pill" id="meta_valor" name="meta_valor" min="0" step="0.01" required placeholder="Ex: 5000.00" list="sugestoes-meta">
                        <datalist id="sugestoes-meta">
                            <option value="500.00">
                            <option value="1000.00">
                            <option value="2000.00">
                            <option value="5000.00">
                            <option value="10000.00">
                        </datalist>
                    </div>
                    <div class="text-muted small"><i class="fas fa-info-circle"></i> Defina quanto deseja juntar para este objetivo.</div>
                    <div class="mt-3 text-center">
                        <span class="badge bg-light text-dark" id="motivacaoCreate">Você está começando um novo objetivo! 🚀</span>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold rounded-pill"><i class="fas fa-save me-1"></i>Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editCofrinhoModal" tabindex="-1" aria-labelledby="editCofrinhoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCofrinhoForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content rounded-4 shadow-lg cofrinho-modal-glass">
                <div class="modal-header bg-primary bg-gradient text-white rounded-top-4 border-0">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="editCofrinhoModalLabel">
                        <i class="fas fa-edit fa-2x me-2"></i> Editar Cofrinho
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-info-circle text-primary fa-lg"></i>
                        <span>Altere o nome ou a meta do seu cofrinho.<br><small>O valor acumulado é atualizado automaticamente conforme suas transações.</small></span>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text bg-primary text-white"><i class="fas fa-tag"></i></span>
                        <input type="text" class="form-control form-control-lg rounded-end-pill" id="edit_nome" name="nome" required maxlength="255" list="sugestoes-nome">
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text bg-primary text-white"><i class="fas fa-bullseye"></i></span>
                        <input type="number" class="form-control form-control-lg rounded-end-pill" id="edit_meta_valor" name="meta_valor" min="0" step="0.01" required list="sugestoes-meta">
                    </div>
                    <div id="editCofrinhoInfo" class="mb-2"></div>
                    <div class="mt-3 text-center">
                        <span class="badge bg-light text-dark" id="motivacaoEdit">Mantenha o foco no seu objetivo! 💪</span>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-pill"><i class="fas fa-save me-1"></i>Salvar Alterações</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="deleteCofrinhoModal" tabindex="-1" aria-labelledby="deleteCofrinhoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteCofrinhoForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content rounded-4 shadow-lg cofrinho-modal-glass">
                <div class="modal-header bg-danger bg-gradient text-white rounded-top-4 border-0">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="deleteCofrinhoModalLabel">
                        <i class="fas fa-trash fa-2x me-2"></i> Excluir Cofrinho
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-exclamation-triangle text-danger fa-lg"></i>
                        <span>Tem certeza que deseja excluir este cofrinho? <br><small>Esta ação não poderá ser desfeita.</small></span>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold rounded-pill"><i class="fas fa-trash me-1"></i>Excluir</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.cofrinho-modal-glass {
    background: rgba(255,255,255,0.85);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
    backdrop-filter: blur(7px);
    border-radius: 22px;
    border: 1px solid rgba(255,255,255,0.18);
}
</style>
@endpush
<script>
// Mensagem motivacional dinâmica no modal de criar/editar
function atualizarMotivacao(percent, elId) {
    let msg = 'Você está começando um novo objetivo! 🚀';
    if (percent >= 100) msg = 'Parabéns! Meta atingida! 🎉';
    else if (percent >= 80) msg = 'Falta pouco! Continue assim!';
    else if (percent >= 50) msg = 'Você está na metade do caminho!';
    else if (percent > 0) msg = 'Ótimo começo!';
    document.getElementById(elId).innerText = msg;
}
</script> 