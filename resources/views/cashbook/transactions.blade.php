@forelse ($transactions as $transaction)
    <div class="col-md-4 mb-4">
        <div class="card border-{{ $transaction->type_id == 2 ? 'danger' : 'success' }}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <button class="btn btn-icon-only btn-rounded btn-outline-{{ $transaction->type_id == 2 ? 'danger' : 'success' }} mb-0 me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-arrow-{{ $transaction->type_id == 2 ? 'down' : 'up' }}"></i>
                        </button>
                        <button class="btn btn-icon-only btn-rounded mb-0 me-3 btn-sm d-flex align-items-center justify-content-center"
                            style="border: 3px solid {{ $transaction->category->hexcolor_category ?? '#cccccc' }}; width: 50px; height: 50px;"
                            title="{{ $transaction->category->name ?? 'Categoria não definida' }}"
                            data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="{{ $transaction->category->icone ?? 'fas fa-question' }}" style="font-size: 1.5rem;"></i>
                        </button>
                    </div>
                    <div class="col-md-10">
                        <h6 class="mb-1 text-dark text-sm">{{ $transaction->description }}</h6>
                        <span class="text-xs">Data: {{ $transaction->time }}</span><br>
                        <span class="text-xs">Categoria: {{ $transaction->category->name ?? 'N/A' }}</span>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex justify-content-start text-{{ $transaction->type_id == 2 ? 'danger' : 'success' }} text-gradient text-sm font-weight-bold">
                                {{ $transaction->type_id == 2 ? '-' : '+' }} R$ {{ number_format(abs($transaction->value), 2) }}
                            </div>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editTransactionModal" onclick="loadEditModal({{ $transaction->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTransactionModal" onclick="loadDeleteModal({{ $transaction->id }}, '{{ $transaction->description }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center">
        <h6 class="text-muted">Nenhuma transação encontrada para o mês selecionado.</h6>
    </div>
@endforelse
