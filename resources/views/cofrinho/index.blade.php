@extends('layouts.user_type.auth')

@section('content')


<!-- Banner motivacional/topo -->
<div class="cofrinho-banner mb-4 p-4 rounded-4 shadow-lg d-flex flex-wrap align-items-center justify-content-between position-relative"
    style="background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%), url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80'); background-size: cover; background-blend-mode: multiply; color: #fff; min-height: 140px;">
    <div class="mb-2 mb-md-0">
        <h2 class="fw-bold mb-1"><i class="fas fa-piggy-bank fa-2x me-2"></i> Meus Cofrinhos</h2>
        <p class="mb-0 fs-5">“O segredo do sucesso é a constância do propósito.” <span class="d-none d-md-inline">-
                Benjamin Disraeli</span></p>
    </div>
    <button class="btn btn-warning btn-lg rounded-pill shadow-sm fw-bold" data-bs-toggle="modal"
        data-bs-target="#createCofrinhoModal">
        <i class="fas fa-piggy-bank me-2"></i> Novo Cofrinho
    </button>
    <div class="text-end">
        <div class="fs-4 fw-bold">Total acumulado: <span class="badge bg-light text-dark">R$
                {{ number_format($cofrinhos->sum('valor_acumulado'),2,',','.') }}</span></div>
        <div class="fs-6">Metas: <span class="badge bg-light text-dark">{{ $cofrinhos->count() }}</span></div>
    </div>
    <div class="position-absolute bottom-0 end-0 p-2" style="opacity:0.12; pointer-events:none;">
        <i class="fas fa-coins fa-7x"></i>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($cofrinhos as $cofrinho)
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow p-6 flex flex-col gap-3 cursor-pointer hover:shadow-lg transition"
             @click="openModal && openModal({{ $cofrinho['id'] }})">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-400">
                    <i class="fas {{ $cofrinho['icone'] ?? 'fa-piggy-bank' }} text-white text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-lg truncate" title="{{ $cofrinho['nome'] }}">{{ $cofrinho['nome'] }}</h4>
                    <span class="text-xs text-gray-500">{{ $cofrinho['status'] }}</span>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-green-600 font-semibold">R$ {{ number_format($cofrinho['valorAcumulado'],2,',','.') }}</span>
                <span class="text-gray-400 text-xs">Meta: R$ {{ number_format($cofrinho['meta'],2,',','.') }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-yellow-400 h-3 rounded-full transition-all duration-700" style="width: {{ $cofrinho['percent'] }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500">
                <span>Falta: R$ {{ number_format($cofrinho['falta'],2,',','.') }}</span>
                <span>{{ $cofrinho['qtdTransacoes'] }} transações</span>
            </div>
            <div class="mt-2">
                <span class="text-xs text-gray-400 font-semibold">Últimas transações:</span>
                <ul class="mt-1 space-y-1">
                    @forelse($cofrinho['ultimasTransacoes'] as $t)
                        <li class="flex items-center gap-2 text-xs">
                            <span class="px-2 py-0.5 rounded-full {{ $t->type_id == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $t->type_id == 1 ? '+' : '-' }}R$ {{ number_format($t->value,2,',','.') }}
                            </span>
                            <span>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m') }}</span>
                        </li>
                    @empty
                        <li class="text-gray-400">Nenhuma transação</li>
                    @endforelse
                </ul>
            </div>
        </div>
        @include('COFRINHO.detalhe')
    @empty
        <div class="col-span-3 text-center text-gray-400">Nenhum cofrinho cadastrado.</div>
    @endforelse
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
    toast.innerHTML =
        `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.remove();
    }, 3500);
}

// Loader simples
function setModalLoading(modalId, loading = true) {
    const modal = document.querySelector(modalId + ' .modal-body');
    if (!modal) return;
    if (loading) {
        modal.dataset.original = modal.innerHTML;
        modal.innerHTML =
            '<div class="d-flex justify-content-center align-items-center" style="height:120px"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
    } else if (modal.dataset.original) {
        modal.innerHTML = modal.dataset.original;
        delete modal.dataset.original;
    }
}

// Abrir modal de edição e buscar dados
const editModal = document.getElementById('editCofrinhoModal');
editModal.addEventListener('show.bs.modal', function(event) {
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
            document.getElementById('editCofrinhoInfo').innerHTML =
                `<div class='alert alert-secondary p-2 mb-0'>Valor acumulado: <b>R$ ${parseFloat(data.valor_acumulado).toLocaleString('pt-BR', {minimumFractionDigits:2})}</b><br>Transações: <b>${data.cashbooks_count}</b></div>`;
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
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
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
deleteModal.addEventListener('show.bs.modal', function(event) {
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
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new URLSearchParams({
                _method: 'DELETE'
            })
        })
        .then(res => res.json())
        .then(data => {
            setModalLoading('#deleteCofrinhoModal', false);
            if (data.success) {
                showToast('Cofrinho excluído com sucesso!', 'success');
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
        this.querySelector('.modal-dialog').classList.add('animate__animated', 'animate__fadeInDown');
    });
    modal.addEventListener('hide.bs.modal', function() {
        this.querySelector('.modal-dialog').classList.remove('animate__animated',
            'animate__fadeInDown');
    });
});
</script>
