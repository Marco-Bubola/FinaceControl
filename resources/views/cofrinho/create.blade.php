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
                    <div class="step-indicator mb-4 d-flex justify-content-center gap-2">
                        <div id="step1-indicator-create" class="step-circle active"><i class="fas fa-info"></i></div>
                        <div class="step-line"></div>
                        <div id="step2-indicator-create" class="step-circle"><i class="fas fa-icons"></i></div>
                    </div>
                    <div id="step1-create">
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
                        <div class="mt-4 text-center">
                            <button type="button" class="btn btn-warning rounded-pill px-4 btn-lg fw-bold" id="goToStep2Create">Próximo <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                    <div id="step2-create" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Escolha um ícone para seu cofrinho:</label>
                            <div class="d-flex flex-wrap gap-3 justify-content-center">
                                @php
                                $iconList = ['fa-piggy-bank','fa-coins','fa-gem','fa-star','fa-heart','fa-rocket','fa-gift','fa-trophy','fa-crown','fa-seedling','fa-car','fa-plane','fa-home','fa-umbrella-beach','fa-book','fa-laptop','fa-bicycle','fa-tree','fa-music','fa-camera'];
                                @endphp
                                @foreach($iconList as $icon)
                                <label class="icon-radio">
                                    <input type="radio" name="icone" value="{{ $icon }}" @if($icon=='fa-piggy-bank') checked @endif>
                                    <span class="icon-preview"><i class="fas {{ $icon }} fa-2x"></i></span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 btn-lg" id="backToStep1Create"><i class="fas fa-arrow-left"></i> Voltar</button>
                            <button type="submit" class="btn btn-warning fw-bold rounded-pill px-4 btn-lg"><i class="fas fa-save me-1"></i>Salvar</button>
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
    border:2.5px solid #f7971e; box-shadow:0 0 0 2px #ffd20055;
    background: #fffbe6;
}
.step-indicator { user-select:none; }
.step-circle {
    width: 38px; height: 38px; border-radius: 50%; background: #e9ecef; color: #adb5bd; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: bold; transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.step-circle.active { background: #f7971e; color: #fff; box-shadow: 0 2px 8px #ffd20033; }
.step-line { width: 40px; height: 4px; background: #e9ecef; align-self: center; border-radius: 2px; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const step1 = document.getElementById('step1-create');
    const step2 = document.getElementById('step2-create');
    const step1Indicator = document.getElementById('step1-indicator-create');
    const step2Indicator = document.getElementById('step2-indicator-create');
    document.getElementById('goToStep2Create').onclick = function() {
        step1.style.display = 'none';
        step2.style.display = 'block';
        step1Indicator.classList.remove('active');
        step2Indicator.classList.add('active');
    };
    document.getElementById('backToStep1Create').onclick = function() {
        step2.style.display = 'none';
        step1.style.display = 'block';
        step2Indicator.classList.remove('active');
        step1Indicator.classList.add('active');
    };
});
</script>