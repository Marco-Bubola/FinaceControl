

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