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
                    <div class="step-indicator mb-4 d-flex justify-content-center gap-2">
                        <div id="step1-indicator-edit" class="step-circle active"><i class="fas fa-info"></i></div>
                        <div class="step-line"></div>
                        <div id="step2-indicator-edit" class="step-circle"><i class="fas fa-icons"></i></div>
                    </div>
                    <div id="step1-edit">
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
                        <div class="mt-4 text-center">
                            <button type="button" class="btn btn-primary rounded-pill px-4 btn-lg fw-bold" id="goToStep2Edit">Próximo <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                    <div id="step2-edit" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Escolha um ícone para seu cofrinho:</label>
                            <div class="d-flex flex-wrap gap-3 justify-content-center">
                                @php
                                $iconList = ['fa-piggy-bank','fa-coins','fa-gem','fa-star','fa-heart','fa-rocket','fa-gift','fa-trophy','fa-crown','fa-seedling','fa-car','fa-plane','fa-home','fa-umbrella-beach','fa-book','fa-laptop','fa-bicycle','fa-tree','fa-music','fa-camera'];
                                @endphp
                                @foreach($iconList as $icon)
                                <label class="icon-radio">
                                    <input type="radio" name="icone" value="{{ $icon }}" id="edit_icone_{{ $icon }}">
                                    <span class="icon-preview"><i class="fas {{ $icon }} fa-2x"></i></span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 btn-lg" id="backToStep1Edit"><i class="fas fa-arrow-left"></i> Voltar</button>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4 btn-lg"><i class="fas fa-save me-1"></i>Salvar Alterações</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<style>
.icon-radio input[type="radio"] { display:none; }
.icon-radio .icon-preview {
    display:inline-block; border:2px solid #eee; border-radius:12px; padding:12px 16px; cursor:pointer; transition: border 0.2s, box-shadow 0.2s, background 0.2s;
    background: #fff;
    font-size: 2rem;
}
.icon-radio input[type="radio"]:checked + .icon-preview {
    border:2.5px solid #0d6efd; box-shadow:0 0 0 2px #0d6efd55;
    background: #eaf4ff;
}
.step-indicator { user-select:none; }
.step-circle {
    width: 38px; height: 38px; border-radius: 50%; background: #e9ecef; color: #adb5bd; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: bold; transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.step-circle.active { background: #0d6efd; color: #fff; box-shadow: 0 2px 8px #0d6efd33; }
.step-line { width: 40px; height: 4px; background: #e9ecef; align-self: center; border-radius: 2px; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const step1 = document.getElementById('step1-edit');
    const step2 = document.getElementById('step2-edit');
    const step1Indicator = document.getElementById('step1-indicator-edit');
    const step2Indicator = document.getElementById('step2-indicator-edit');
    document.getElementById('goToStep2Edit').onclick = function() {
        step1.style.display = 'none';
        step2.style.display = 'block';
        step1Indicator.classList.remove('active');
        step2Indicator.classList.add('active');
    };
    document.getElementById('backToStep1Edit').onclick = function() {
        step2.style.display = 'none';
        step1.style.display = 'block';
        step2Indicator.classList.remove('active');
        step1Indicator.classList.add('active');
    };
    // Preencher ícone selecionado ao abrir modal de edição
    const editModal = document.getElementById('editCofrinhoModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        setTimeout(function() {
            const data = window.editCofrinhoData || {};
            if(data.icone) {
                const radio = document.getElementById('edit_icone_' + data.icone);
                if(radio) radio.checked = true;
            } else {
                const radio = document.getElementById('edit_icone_fa-piggy-bank');
                if(radio) radio.checked = true;
            }
        }, 300);
        // Sempre começa no step 1
        step2.style.display = 'none';
        step1.style.display = 'block';
        step2Indicator.classList.remove('active');
        step1Indicator.classList.add('active');
    });
});
</script>
