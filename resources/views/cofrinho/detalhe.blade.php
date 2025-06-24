<div class="modal fade" id="detalheCofrinhoModal-{{ $cofrinho['id'] }}" tabindex="-1" aria-labelledby="detalheCofrinhoLabel-{{ $cofrinho['id'] }}" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content cofrinho-modal-glass">
                      <div class="modal-header bg-warning bg-gradient text-white rounded-top-4 border-0">
                        <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="detalheCofrinhoLabel-{{ $cofrinho['id'] }}">
                          <i class="fas fa-piggy-bank fa-2x me-2"></i> Detalhes de "{{ $cofrinho['nome'] }}"
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-4">
                          <div class="col-md-6">
                            <div class="mb-2"><b>Status:</b> <span class="badge {{ $cofrinho['badgeClass'] }}">{{ $cofrinho['badgeMotivacao'] }}</span></div>
                            <div class="mb-2"><b>Meta:</b> R$ {{ number_format($cofrinho['meta'],2,',','.') }}</div>
                            <div class="mb-2"><b>Acumulado:</b> R$ {{ number_format($cofrinho['valorAcumulado'],2,',','.') }}</div>
                            <div class="mb-2"><b>Falta:</b> R$ {{ number_format($cofrinho['falta'],2,',','.') }}</div>
                            <div class="mb-2"><b>Data de criação:</b> {{ $cofrinho['created'] }}</div>
                            <div class="mb-2"><b>Transações vinculadas:</b> {{ $cofrinho['qtdTransacoes'] }}</div>
                            <div class="mb-2"><b>Progresso mensal:</b> R$ {{ number_format($cofrinho['progressoMensal'],2,',','.') }}</div>
                            <div class="mb-2"><b>Mensagem motivacional:</b> <span class="text-primary">{{ $cofrinho['fraseMotivacional'] }}</span></div>
                          </div>
                          <div class="col-md-6">
                            <canvas id="detalheChart-{{ $cofrinho['id'] }}" height="120"></canvas>
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
                              @forelse($cofrinho['ultimasTransacoes'] as $t)
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
                        var ctx2 = document.getElementById('detalheChart-{{ $cofrinho['id'] }}');
                        if(ctx2) {
                          new Chart(ctx2.getContext('2d'), {
                            type: 'line',
                            data: {
                              labels: [...Array({{ count($cofrinho['evolucao']) }}).keys()],
                              datasets: [{
                                data: {!! json_encode($cofrinho['evolucao']) !!},
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
                    @if(isset($cofrinho['isComplete']) && $cofrinho['isComplete'])
                    setTimeout(function() {
                        let confetti = document.getElementById('confetti-{{ $cofrinho['id'] }}');
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