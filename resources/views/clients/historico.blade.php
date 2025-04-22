
                <!-- Modal de Histórico Completo -->
                <div class="modal fade" id="modalFullHistory{{ $client->id }}" tabindex="-1"
                    aria-labelledby="modalFullHistoryLabel{{ $client->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalFullHistoryLabel{{ $client->id }}">Histórico Completo de Vendas
                                    - {{ $client->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Data</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->sales as $sale)
                                            <tr>
                                                <td>{{ $sale->id }}</td>
                                                <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                                                <td>R$ {{ number_format($sale->total_price, 2, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $sale->status == 'Paga' ? 'success' : 'danger' }}">
                                                        {{ $sale->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                                                        Ver Detalhes
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
