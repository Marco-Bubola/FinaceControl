@extends('layouts.user_type.auth')

@section('content')
@php
// Ranking dos cofrinhos que mais cresceram no mês
$ranking = $cofrinhos->map(function($c) {
    $crescimento = $c->cashbooks
        ->where('type_id', 1)
        ->filter(function($cb) {
            return \Carbon\Carbon::parse($cb->created_at)->format('Y-m') === now()->format('Y-m');
        })
        ->sum('value');
    return [
        'id' => $c->id,
        'nome' => $c->nome,
        'crescimento' => $crescimento,
        'meta' => $c->meta_valor,
        'valor_acumulado' => $c->valor_acumulado,
    ];
})->sortByDesc('crescimento')->take(3);
@endphp
<!-- Banner motivacional/topo -->
<div class="cofrinho-banner mb-4 p-4 rounded-4 shadow-lg d-flex flex-wrap align-items-center justify-content-between position-relative" style="background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%), url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80'); background-size: cover; background-blend-mode: multiply; color: #fff; min-height: 140px;">
    <div class="mb-2 mb-md-0">
        <h2 class="fw-bold mb-1"><i class="fas fa-piggy-bank fa-2x me-2"></i> Meus Cofrinhos</h2>
        <p class="mb-0 fs-5">“O segredo do sucesso é a constância do propósito.” <span class="d-none d-md-inline">- Benjamin Disraeli</span></p>
    </div>
    <div class="text-end">
        <div class="fs-4 fw-bold">Total acumulado: <span class="badge bg-light text-dark">R$ {{ number_format($cofrinhos->sum('valor_acumulado'),2,',','.') }}</span></div>
        <div class="fs-6">Metas: <span class="badge bg-light text-dark">{{ $cofrinhos->count() }}</span></div>
    </div>
    <div class="position-absolute bottom-0 end-0 p-2" style="opacity:0.12; pointer-events:none;">
        <i class="fas fa-coins fa-7x"></i>
    </div>
</div>
<!-- Ranking dos cofrinhos que mais cresceram no mês -->
@if($ranking->sum('crescimento') > 0)
<div class="mb-4">
    <div class="card shadow-sm border-0 p-3" style="border-radius: 18px; background: rgba(255,255,255,0.85);">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-trophy text-warning fa-lg me-2"></i>
            <span class="fw-bold fs-5">Ranking dos Cofrinhos que mais cresceram este mês</span>
        </div>
        <ol class="mb-0 ps-3">
            @foreach($ranking as $i => $r)
                <li class="mb-1">
                    <span class="fw-bold">{{ $r['nome'] }}</span>
                    <span class="badge bg-success ms-2">+R$ {{ number_format($r['crescimento'],2,',','.') }}</span>
                    <span class="badge bg-light text-dark ms-2">Meta: R$ {{ number_format($r['meta'],2,',','.') }}</span>
                    <span class="badge bg-info text-dark ms-2">Total: R$ {{ number_format($r['valor_acumulado'],2,',','.') }}</span>
                </li>
            @endforeach
        </ol>
    </div>
</div>
@endif
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="fw-bold display-5 mb-2" style="letter-spacing:-1px; background: linear-gradient(45deg, #f7971e, #ffd200); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Cofrinhos</h1>
            <p class="text-muted mb-0" style="font-size:1.1rem;">Acompanhe, edite e celebre cada conquista financeira!</p>
        </div>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-auto">
            <button class="btn btn-warning btn-lg rounded-pill shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#createCofrinhoModal">
                <i class="fas fa-piggy-bank me-2"></i> Novo Cofrinho
            </button>
        </div>
    </div>
    <div class="row g-4">
        @foreach($cofrinhos as $cofrinho)
            @php
                $valorAcumulado = $cofrinho->valor_acumulado ?? 0;
                $qtdTransacoes = $cofrinho->cashbooks_count ?? 0;
                $meta = $cofrinho->meta_valor;
                $falta = max(0, $meta - $valorAcumulado);
                $percent = $meta > 0 ? min(100, round(($valorAcumulado/$meta)*100)) : 0;
                $isComplete = $percent >= 100;
                $created = \Carbon\Carbon::parse($cofrinho->created_at)->format('d/m/Y');
                $status = $isComplete ? 'Concluído' : 'Ativo';
                $ultimasTransacoes = $cofrinho->cashbooks->sortByDesc('created_at')->take(3);
                $progressoMensal = $cofrinho->cashbooks
                    ->where('type_id', 1)
                    ->filter(function($cb) {
                        return \Carbon\Carbon::parse($cb->created_at)->format('Y-m') === now()->format('Y-m');
                    })
                    ->sum('value');
                // Frases motivacionais variadas
                $frases = [
                    'Meta atingida!' => 'Parabéns! Você conquistou seu objetivo! 🎉',
                    'Falta pouco!' => 'Continue firme, está quase lá!',
                    'Continue assim!' => 'Ótimo progresso, mantenha o ritmo!',
                    'Comece já!' => 'Todo sonho começa com o primeiro passo!'
                ];
                $badgeMotivacao = $isComplete ? 'Meta atingida!' : ($percent >= 80 ? 'Falta pouco!' : ($percent >= 50 ? 'Continue assim!' : 'Comece já!'));
                $badgeClass = $isComplete ? 'bg-success' : ($percent >= 80 ? 'bg-warning text-dark' : ($percent >= 50 ? 'bg-info text-dark' : 'bg-secondary'));
                $fraseMotivacional = $frases[$badgeMotivacao];
                $evolucao = $cofrinho->cashbooks->sortBy('created_at')->map(function($cb, $i) {
                    static $saldo = 0;
                    if ($cb->type_id == 1) $saldo += $cb->value;
                    if ($cb->type_id == 2) $saldo -= $cb->value;
                    return $saldo;
                });
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card cofrinho-card-glass shadow-lg border-0 h-100 position-relative cofrinho-card-anim" style="border-radius: 22px; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#detalheCofrinhoModal-{{ $cofrinho->id }}">
                    @if($isComplete)
                    <!-- Confete animado -->
                    <div class="confetti" id="confetti-{{ $cofrinho->id }}"></div>
                    @endif
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-2 gap-2">
                            <span class="icon-box bg-warning bg-gradient rounded-circle d-flex align-items-center justify-content-center me-2" style="width:48px; height:48px;"><i class="fas fa-piggy-bank fa-2x text-white"></i></span>
                            <h4 class="fw-bold mb-0 flex-grow-1 text-truncate" title="{{ $cofrinho->nome }}">{{ $cofrinho->nome }}</h4>
                            <span class="badge {{ $badgeClass }} ms-2" data-bs-toggle="tooltip" title="{{ $fraseMotivacional }}">{{ $badgeMotivacao }}</span>
                        </div>
                        <div class="mb-2 d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark"><i class="fas fa-bullseye"></i> Meta: R$ {{ number_format($meta,2,',','.') }}</span>
                            <span class="badge bg-light text-dark"><i class="fas fa-calendar-alt"></i> {{ $created }}</span>
                            <span class="badge bg-{{ $isComplete ? 'success' : 'secondary' }}">{{ $status }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="fw-bold text-success"><i class="fas fa-coins"></i> Acumulado: R$ {{ number_format($valorAcumulado,2,',','.') }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="fw-bold text-secondary"><i class="fas fa-arrow-up"></i> Falta: R$ {{ number_format($falta,2,',','.') }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 20px; background: rgba(255,255,255,0.3); border-radius: 12px;">
                            <div class="progress-bar bg-warning progress-bar-striped" role="progressbar" style="width: {{ $percent }}%; font-weight:600; font-size:1.1rem; border-radius: 12px; transition: width 1s;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">{{ $percent }}%</div>
                        </div>
                        <div class="mb-2">
                            <canvas id="sparkline-{{ $cofrinho->id }}" height="30"></canvas>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small text-muted mt-2">
                            <span data-bs-toggle="tooltip" title="Transações vinculadas"><i class="fas fa-exchange-alt"></i> <b>{{ $qtdTransacoes }}</b></span>
                            <span data-bs-toggle="tooltip" title="Progresso mensal"><i class="fas fa-calendar-week"></i> +R$ {{ number_format($progressoMensal,2,',','.') }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark"><i class="fas fa-history"></i> Últimas transações:</span>
                            <ul class="list-unstyled mb-0 mt-1">
                                @forelse($ultimasTransacoes as $t)
                                    <li class="small d-flex align-items-center gap-2">
                                        <span class="badge {{ $t->type_id == 1 ? 'bg-success' : 'bg-danger' }}">{{ $t->type_id == 1 ? '+' : '-' }}R$ {{ number_format($t->value,2,',','.') }}</span>
                                        <span>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m') }}</span>
                                    </li>
                                @empty
                                    <li class="small text-muted">Nenhuma transação</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="cofrinho-actions mt-3 text-end" style="display:none;">
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editCofrinhoModal" data-id="{{ $cofrinho->id }}" title="Editar" onclick="event.stopPropagation();"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCofrinhoModal" data-id="{{ $cofrinho->id }}" title="Excluir" onclick="event.stopPropagation();"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <!-- Modal de detalhes do cofrinho -->
                <div class="modal fade" id="detalheCofrinhoModal-{{ $cofrinho->id }}" tabindex="-1" aria-labelledby="detalheCofrinhoLabel-{{ $cofrinho->id }}" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content cofrinho-modal-glass">
                      <div class="modal-header bg-warning bg-gradient text-white rounded-top-4 border-0">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="detalheCofrinhoLabel-{{ $cofrinho->id }}">
                          <i class="fas fa-piggy-bank fa-2x me-2"></i> Detalhes de "{{ $cofrinho->nome }}"
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-4">
                          <div class="col-md-6">
                            <div class="mb-2"><b>Status:</b> <span class="badge {{ $badgeClass }}">{{ $badgeMotivacao }}</span></div>
                            <div class="mb-2"><b>Meta:</b> R$ {{ number_format($meta,2,',','.') }}</div>
                            <div class="mb-2"><b>Acumulado:</b> R$ {{ number_format($valorAcumulado,2,',','.') }}</div>
                            <div class="mb-2"><b>Falta:</b> R$ {{ number_format($falta,2,',','.') }}</div>
                            <div class="mb-2"><b>Data de criação:</b> {{ $created }}</div>
                            <div class="mb-2"><b>Transações vinculadas:</b> {{ $qtdTransacoes }}</div>
                            <div class="mb-2"><b>Progresso mensal:</b> R$ {{ number_format($progressoMensal,2,',','.') }}</div>
                            <div class="mb-2"><b>Mensagem motivacional:</b> <span class="text-primary">{{ $fraseMotivacional }}</span></div>
                          </div>
                          <div class="col-md-6">
                            <canvas id="detalheChart-{{ $cofrinho->id }}" height="120"></canvas>
                          </div>
                        </div>
                        <hr>
                        <h6 class="fw-bold mb-2"><i class="fas fa-list-ul"></i> Histórico completo de transações</h6>
                        <div style="max-height: 220px; overflow-y:auto;">
                          <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                              <tr>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse($cofrinho->cashbooks->sortByDesc('created_at') as $t)
                                <tr>
                                  <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y') }}</td>
                                  <td><span class="badge {{ $t->type_id == 1 ? 'bg-success' : 'bg-danger' }}">{{ $t->type_id == 1 ? 'Receita' : 'Despesa' }}</span></td>
                                  <td>R$ {{ number_format($t->value,2,',','.') }}</td>
                                </tr>
                              @empty
                                <tr><td colspan="3" class="text-muted">Nenhuma transação</td></tr>
                              @endforelse
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.Chart) {
                        var ctx = document.getElementById('sparkline-{{ $cofrinho->id }}').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: [...Array({{ $evolucao->count() }}).keys()],
                                datasets: [{
                                    data: {!! json_encode($evolucao->values()) !!},
                                    borderColor: '#f7971e',
                                    backgroundColor: 'rgba(255,193,7,0.1)',
                                    borderWidth: 2,
                                    pointRadius: 0,
                                    fill: true,
                                    tension: 0.4,
                                }]
                            },
                            options: {
                                plugins: { legend: { display: false } },
                                scales: { x: { display: false }, y: { display: false } },
                                elements: { line: { borderCapStyle: 'round' } },
                                responsive: true,
                                maintainAspectRatio: false,
                            }
                        });
                        // Gráfico maior no modal de detalhes
                        var ctx2 = document.getElementById('detalheChart-{{ $cofrinho->id }}');
                        if(ctx2) {
                          new Chart(ctx2.getContext('2d'), {
                            type: 'line',
                            data: {
                              labels: [...Array({{ $evolucao->count() }}).keys()],
                              datasets: [{
                                data: {!! json_encode($evolucao->values()) !!},
                                borderColor: '#f7971e',
                                backgroundColor: 'rgba(255,193,7,0.15)',
                                borderWidth: 3,
                                pointRadius: 2,
                                fill: true,
                                tension: 0.4,
                              }]
                            },
                            options: {
                              plugins: { legend: { display: false } },
                              scales: { x: { display: false }, y: { display: false } },
                              elements: { line: { borderCapStyle: 'round' } },
                              responsive: true,
                              maintainAspectRatio: false,
                            }
                          });
                        }
                    }
                    // Confete animado ao atingir meta
                    @if($isComplete)
                    setTimeout(function() {
                        let confetti = document.getElementById('confetti-{{ $cofrinho->id }}');
                        if(confetti) {
                            for(let i=0;i<30;i++) {
                                let el = document.createElement('div');
                                el.className = 'confetti-piece';
                                el.style.left = (Math.random()*100)+'%';
                                el.style.background = 'hsl('+(Math.random()*360)+',90%,60%)';
                                el.style.animationDelay = (Math.random()*0.7)+'s';
                                confetti.appendChild(el);
                            }
                            setTimeout(()=>{ confetti.innerHTML = ''; }, 2500);
                        }
                    }, 500);
                    @endif
                });
                </script>
            </div>
        @endforeach
    </div>
</div>

@include('cofrinho.modals')
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