@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid ">

<!-- Banner motivacional/topo -->
<div class="cofrinho-banner mb-4 p-4 rounded-4 shadow-lg d-flex flex-wrap align-items-center justify-content-between position-relative" style="background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%), url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80'); background-size: cover; background-blend-mode: multiply; color: #fff; min-height: 140px;">
    <div class="mb-2 mb-md-0">
        <h2 class="fw-bold mb-1"><i class="fas fa-piggy-bank fa-2x me-2"></i> Meus Cofrinhos</h2>
        <p class="mb-0 fs-5">“O segredo do sucesso é a constância do propósito.” <span class="d-none d-md-inline">- Benjamin Disraeli</span></p>
    </div>
    <button class="btn btn-warning btn-lg rounded-pill shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#createCofrinhoModal">
                <i class="fas fa-piggy-bank me-2"></i> Novo Cofrinho
            </button>
    <div class="text-end">
        <div class="fs-4 fw-bold">Total acumulado: <span class="badge bg-light text-dark">R$ {{ number_format($cofrinhos->sum('valor_acumulado'),2,',','.') }}</span></div>
        <div class="fs-6">Metas: <span class="badge bg-light text-dark">{{ $cofrinhos->count() }}</span></div>
    </div>
    <div class="position-absolute bottom-0 end-0 p-2" style="opacity:0.12; pointer-events:none;">
        <i class="fas fa-coins fa-7x"></i>
    </div>
</div>
  
    <div class="row g-4">
        @foreach($cofrinhos as $cofrinho)
            <div class="col-md-6 col-lg-4">
                <div class="card cofrinho-card-glass shadow-lg border-0 h-100 position-relative cofrinho-card-anim" style="border-radius: 22px; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#detalheCofrinhoModal-{{ $cofrinho['id'] }}">
                    @if($cofrinho['isComplete'])
                    <!-- Confete animado -->
                    <div class="confetti" id="confetti-{{ $cofrinho['id'] }}"></div>
                    @endif
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-2 gap-2 justify-content-between">
                            <div class="d-flex align-items-center gap-2 flex-grow-1">
                                <span class="icon-box {{ $cofrinho['colorClass'] }} bg-gradient rounded-circle d-flex align-items-center justify-content-center me-2" style="width:48px; height:48px;"><i class="fas {{ $cofrinho['icone'] ?? 'fa-piggy-bank' }} fa-2x text-white"></i></span>
                                <h4 class="fw-bold mb-0 text-truncate" title="{{ $cofrinho['nome'] }}">{{ $cofrinho['nome'] }}</h4>
                                <span class="badge {{ $cofrinho['badgeClass'] }} ms-2" data-bs-toggle="tooltip" title="{{ $cofrinho['fraseMotivacional'] }}">{{ $cofrinho['badgeMotivacao'] }}</span>
                            </div>
                            <div class="cofrinho-actions d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCofrinhoModal" data-id="{{ $cofrinho['id'] }}" title="Editar" onclick="event.stopPropagation();"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCofrinhoModal" data-id="{{ $cofrinho['id'] }}" title="Excluir" onclick="event.stopPropagation();"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="mb-2 d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark"><i class="fas fa-bullseye"></i> Meta: R$ {{ number_format($cofrinho['meta'],2,',','.') }}</span>
                            <span class="badge bg-light text-dark"><i class="fas fa-calendar-alt"></i> {{ $cofrinho['created'] }}</span>
                            <span class="badge bg-{{ $cofrinho['isComplete'] ? 'success' : 'secondary' }}">{{ $cofrinho['status'] }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="fw-bold text-success"><i class="fas fa-coins"></i> Acumulado: R$ {{ number_format($cofrinho['valorAcumulado'],2,',','.') }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="fw-bold text-secondary"><i class="fas fa-arrow-up"></i> Falta: R$ {{ number_format($cofrinho['falta'],2,',','.') }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 20px; background: rgba(255,255,255,0.3); border-radius: 12px;">
                            <div class="progress-bar bg-warning progress-bar-striped" role="progressbar" style="width: {{ $cofrinho['percent'] }}%; font-weight:600; font-size:1.1rem; border-radius: 12px; transition: width 1s;" aria-valuenow="{{ $cofrinho['percent'] }}" aria-valuemin="0" aria-valuemax="100">{{ $cofrinho['percent'] }}%</div>
                        </div>
                        <div class="mb-2">
                            <canvas id="sparkline-{{ $cofrinho['id'] }}" height="30"></canvas>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small text-muted mt-2">
                            <span data-bs-toggle="tooltip" title="Transações vinculadas"><i class="fas fa-exchange-alt"></i> <b>{{ $cofrinho['qtdTransacoes'] }}</b></span>
                            <span data-bs-toggle="tooltip" title="Progresso mensal"><i class="fas fa-calendar-week"></i> +R$ {{ number_format($cofrinho['progressoMensal'],2,',','.') }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark"><i class="fas fa-history"></i> Últimas transações:</span>
                            <ul class="list-unstyled mb-0 mt-1">
                                @forelse($cofrinho['ultimasTransacoes'] as $t)
                                    <li class="small d-flex align-items-center gap-2">
                                        <span class="badge {{ $t->type_id == 1 ? 'bg-success' : 'bg-danger' }}">{{ $t->type_id == 1 ? '+' : '-' }}R$ {{ number_format($t->value,2,',','.') }}</span>
                                        <span>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m') }}</span>
                                    </li>
                                @empty
                                    <li class="small text-muted">Nenhuma transação</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Modal de detalhes do cofrinho -->
               @include('COFRINHO.detalhe')
            </div>
        @endforeach
    </div>
</div>

@include('cofrinho.create')
@include('cofrinho.delet')
@include('cofrinho.edit')

@endsection

@push('scripts')
<script>
// Utilitário para Toast Bootstrap
function showToast(message, type = 'success') {
    let toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${type} border-0 show position-fixed top-0 end-0 m-4`;
    toast.style.zIndex = 9999;
    toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.remove(); }, 3500);
}

// Loader simples
function setModalLoading(modalId, loading = true) {
    const modal = document.querySelector(modalId + ' .modal-body');
    if (!modal) return;
    if (loading) {
        modal.dataset.original = modal.innerHTML;
        modal.innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height:120px"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
    } else if (modal.dataset.original) {
        modal.innerHTML = modal.dataset.original;
        delete modal.dataset.original;
    }
}

// Abrir modal de edição e buscar dados
const editModal = document.getElementById('editCofrinhoModal');
editModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const form = document.getElementById('editCofrinhoForm');
    form.action = `/cofrinho/${id}`;
    setModalLoading('#editCofrinhoModal', true);
    fetch(`/cofrinho/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            setModalLoading('#editCofrinhoModal', false);
            document.getElementById('edit_nome').value = data.nome;
            document.getElementById('edit_meta_valor').value = data.meta_valor;
            document.getElementById('editCofrinhoInfo').innerHTML = `<div class='alert alert-secondary p-2 mb-0'>Valor acumulado: <b>R$ ${parseFloat(data.valor_acumulado).toLocaleString('pt-BR', {minimumFractionDigits:2})}</b><br>Transações: <b>${data.cashbooks_count}</b></div>`;
        })
        .catch(() => {
            setModalLoading('#editCofrinhoModal', false);
            showToast('Erro ao carregar dados do cofrinho!', 'danger');
        });
});

// Submeter edição via AJAX
const editForm = document.getElementById('editCofrinhoForm');
editForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(editForm);
    const action = editForm.action;
    setModalLoading('#editCofrinhoModal', true);
    fetch(action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        setModalLoading('#editCofrinhoModal', false);
        if (data.success) {
            showToast('Cofrinho atualizado com sucesso!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erro ao atualizar cofrinho!', 'danger');
        }
    })
    .catch(() => {
        setModalLoading('#editCofrinhoModal', false);
        showToast('Erro ao atualizar cofrinho!', 'danger');
    });
});

// Abrir modal de exclusão e setar action
const deleteModal = document.getElementById('deleteCofrinhoModal');
deleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const form = document.getElementById('deleteCofrinhoForm');
    form.action = `/cofrinho/${id}`;
});

// Submeter exclusão via AJAX
const deleteForm = document.getElementById('deleteCofrinhoForm');
deleteForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const action = deleteForm.action;
    setModalLoading('#deleteCofrinhoModal', true);
    fetch(action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: new URLSearchParams({ _method: 'DELETE' })
    })
    .then(res => res.json())
    .then(data => {
        setModalLoading('#deleteCofrinhoModal', false);
        if (data.success) {
            showToast('Cofrinho excluído com sucesso!','success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erro ao excluir cofrinho!', 'danger');
        }
    })
    .catch(() => {
        setModalLoading('#deleteCofrinhoModal', false);
        showToast('Erro ao excluir cofrinho!', 'danger');
    });
});

// Animação extra nos modais (fade-in)
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('show.bs.modal', function() {
        this.querySelector('.modal-dialog').classList.add('animate__animated','animate__fadeInDown');
    });
    modal.addEventListener('hide.bs.modal', function() {
        this.querySelector('.modal-dialog').classList.remove('animate__animated','animate__fadeInDown');
    });
});
</script>
<style>
/* Loader centralizado no modal */
.modal-body .spinner-border { width: 3rem; height: 3rem; }
/* Toast customizado */
.toast { transition: opacity 0.5s, transform 0.5s; }
.toast.show { opacity: 1; transform: translateY(0); }
.toast.hide { opacity: 0; transform: translateY(-20px); }
/* Animação extra para modal */
.animate__animated.animate__fadeInDown { animation: fadeInDown 0.6s; }
@keyframes fadeInDown { from { opacity:0; transform:translateY(-40px);} to { opacity:1; transform:translateY(0);} }
.cofrinho-card-glass {
    background: rgba(255,255,255,0.85);
    box-shadow: 0 8px 32px 0 rgba(247, 151, 30, 0.15), 0 1.5px 8px 0 #ffd20033;
    backdrop-filter: blur(10px);
    border-radius: 22px;
    border: 1.5px solid rgba(255,255,255,0.25);
    transition: box-shadow 0.3s, transform 0.3s;
}
.cofrinho-card-glass:hover {
    box-shadow: 0 12px 40px 0 #ffd20055, 0 2px 12px 0 #f7971e33;
    transform: scale(1.03);
}
.cofrinho-actions { transition: opacity 0.2s; }
.cofrinho-card-glass:hover .cofrinho-actions { display: block !important; opacity: 1; }
.cofrinho-card-glass .cofrinho-actions { opacity: 0; }
.confetti {
    pointer-events: none;
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    z-index: 10;
}
.confetti-piece {
    position: absolute;
    top: 0;
    width: 8px; height: 18px;
    border-radius: 3px;
    opacity: 0.85;
    animation: confetti-fall 1.7s cubic-bezier(.62,.04,.36,1.01) forwards;
}
@keyframes confetti-fall {
    0% { transform: translateY(-30px) rotate(0deg); opacity:1; }
    80% { opacity:1; }
    100% { transform: translateY(220px) rotate(360deg); opacity:0; }
}
</style>
@endpush

@push('styles')
<!-- Fonte moderna Inter via CDN -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body, .cofrinho-banner, .cofrinho-card-glass, .modal-content {
    font-family: 'Inter', Arial, sans-serif;
}
.cofrinho-banner {
    background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%);
    color: #fff;
    box-shadow: 0 8px 32px 0 rgba(247, 151, 30, 0.15), 0 1.5px 8px 0 #ffd20033;
    border-radius: 22px;
    position: relative;
    overflow: hidden;
}
.cofrinho-banner .fa-coins {
    filter: blur(1px);
}
.cofrinho-card-glass {
    background: rgba(255,255,255,0.85);
    box-shadow: 0 8px 32px 0 rgba(247, 151, 30, 0.15), 0 1.5px 8px 0 #ffd20033;
    backdrop-filter: blur(10px);
    border-radius: 22px;
    border: 1.5px solid rgba(255,255,255,0.25);
    transition: box-shadow 0.3s, transform 0.3s;
    cursor: pointer;
}
.cofrinho-card-glass:hover {
    box-shadow: 0 12px 40px 0 #ffd20055, 0 2px 12px 0 #f7971e33;
    transform: scale(1.03);
}
.cofrinho-actions { transition: opacity 0.2s; }
.cofrinho-card-glass:hover .cofrinho-actions { display: block !important; opacity: 1; }
.cofrinho-card-glass .cofrinho-actions { opacity: 0; }
.confetti {
    pointer-events: none;
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    z-index: 10;
}
.confetti-piece {
    position: absolute;
    top: 0;
    width: 8px; height: 18px;
    border-radius: 3px;
    opacity: 0.85;
    animation: confetti-fall 1.7s cubic-bezier(.62,.04,.36,1.01) forwards;
}
@keyframes confetti-fall {
    0% { transform: translateY(-30px) rotate(0deg); opacity:1; }
    80% { opacity:1; }
    100% { transform: translateY(220px) rotate(360deg); opacity:0; }
}
</style>
@endpush 