@extends('layouts.user_type.auth')

@section('content')

    <div class="container-fluid py-2">
          <!-- Exibir erros de validação -->
          @if ($errors->any())
            <div id="error-message" class="alert alert-danger alert-dismissible fade show custom-error-message" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="closeAlert('error-message')"></button>
                <div id="error-timer" class="alert-timer">30s</div>
            </div>
        @endif

        <!-- Exibir sucesso -->
        @if (session('success'))
            <div id="success-message" class="alert alert-success alert-dismissible fade show custom-success-message"
                role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="closeAlert('success-message')"></button>
                <div id="success-timer" class="alert-timer">30s</div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card h-100 mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-4 justify-content-center d-flex">
                                <h6 class="mb-0">Suas Transações:</h6>
                            </div>
                            <div class="col-md-4 justify-content-center d-flex">
                                <h6 class="mb-0"> <span id="month-name">{{ $monthName ?? '' }}</span></h6>
                            </div>
                            <div class="col-md-4 d-flex justify-content-end align-items-center">
                                <button class="btn btn-primary me-2" onclick="loadMonth('previous')">Mês Anterior</button>
                                <button class="btn btn-primary me-2" onclick="loadMonth('next')">Próximo Mês</button>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTransactionModal">Adicionar Transação</button>
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#uploadCashbookModal">Upload de Transações</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4 p-4">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="text-sm">Receitas</h6>
                                        <span class="text-success">+ R$ {{ number_format(abs($totals['income']), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="text-sm">Despesas</h6>
                                        <span class="text-danger">- R$
                                            {{ number_format(abs($totals['expense']), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="text-sm">Saldo</h6>
                                        <span
                                            class="text-{{ $totals['balance'] >= 0 ? 'success' : 'danger' }} text-lg font-weight-bold">
                                            {{ $totals['balance'] >= 0 ? '+' : '-' }} R$
                                            {{ number_format(abs($totals['balance']), 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="transactions-container">
                            @if($transactions->isEmpty())
                                <div class="text-center">
                                    <h6 class="text-muted">Nenhuma transação encontrada para o mês selecionado.</h6>
                                </div>
                            @else
                                <!-- Conteúdo do mês será carregado dinamicamente -->
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        let currentMonth = "{{ $currentMonth }}";

        function loadMonth(direction) {
            const container = document.getElementById('transactions-container');
            const monthNameElement = document.getElementById('month-name');
            const incomeElement = document.querySelector('.text-success');
            const expenseElement = document.querySelector('.text-danger');
            const balanceElement = document.querySelector('.text-lg.font-weight-bold.text-success, .text-lg.font-weight-bold.text-danger');

            fetch(`/cashbook/month/${direction}?currentMonth=${currentMonth}`)
                .then(response => response.json())
                .then(data => {
                    currentMonth = data.currentMonth;
                    monthNameElement.textContent = data.monthName; // Atualizar o nome do mês

                    // Atualizar os valores de receitas, despesas e saldo
                    incomeElement.textContent = `+ R$ ${data.totals.income.toFixed(2)}`; // Sem parseFloat para evitar erros de formatação
                    expenseElement.textContent = `- R$ ${data.totals.expense.toFixed(2)}`; // Mesmo para despesas
                    balanceElement.textContent = `${data.totals.balance >= 0 ? '+' : '-'} R$ ${Math.abs(data.totals.balance).toFixed(2)}`; // Saldo, mantendo o valor correto
                    balanceElement.className = `text-lg font-weight-bold text-${data.totals.balance >= 0 ? 'success' : 'danger'}`;

                    // Verificar se há transações para o mês selecionado
                    if (data.transactionsByDay && Object.keys(data.transactionsByDay).length > 0) {
                        container.innerHTML = `
                    ${Object.keys(data.transactionsByDay).map(day => `
                        <div class="mb-4">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">${day}</h6>
                            <ul class="list-group">
                                ${data.transactionsByDay[day].map(transaction => `
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-icon-only btn-rounded btn-outline-${transaction.type_id == 2 ? 'danger' : 'success'} mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                                <i class="fas fa-arrow-${transaction.type_id == 2 ? 'down' : 'up'}"></i>
                                            </button>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">${transaction.description}</h6>
                                                <span class="text-xs">Data: ${transaction.time}</span>
                                                <span class="text-xs">Categoria: ${transaction.category_name || 'N/A'}</span>
                                                <span class="text-xs">Nota: ${transaction.note || 'Sem nota'}</span>
                                                <span class="text-xs">Segmento: ${transaction.segment_name || 'Nenhum'}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center text-${transaction.type_id == 2 ? 'danger' : 'success'} text-gradient text-sm font-weight-bold">
                                            ${transaction.type_id == 2 ? '-' : '+'} $ ${Math.abs(transaction.value).toFixed(2)}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-icon-only btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editTransactionModal" onclick="loadEditModal(${transaction.id})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-icon-only btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteTransactionModal" onclick="loadDeleteModal(${transaction.id}, '${transaction.description}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                    `).join('')}
                `;
                    } else {
                        container.innerHTML = `
                    <div class="text-center">
                        <h6 class="text-muted">Nenhuma transação encontrada para o mês selecionado.</h6>
                    </div>
                `;
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar os dados do mês:', error);
                });
        }

        // Carregar o mês atual ao carregar a página
        document.addEventListener('DOMContentLoaded', () => {
            loadMonth('current');
        });

        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('uploadCashbookModal');
            console.log(modal); // Deve exibir o elemento do modal no console
            if (!modal) {
                console.error('O modal uploadCashbookModal não foi encontrado no DOM.');
            }
        });
    </script>
   @include('cashbook.uploadCashbook')
    <!-- Modal -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('cashbook.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTransactionModalLabel">Adicionar Transação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Progress Bar -->
                        <div class="progress-container mb-4">
                            <div class="progress">
                                <div id="progress-bar" class="progress-bar" style="width: 50%;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div class="step-circle active">1</div>
                                <div class="step-circle">2</div>
                            </div>
                            <div class="d-flex justify-content-between text-center mt-1">
                                <small>Informações Básicas</small>
                                <small>Detalhes Adicionais</small>
                            </div>
                        </div>

                        <!-- Step 1 -->
                        <div id="step-1">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="value" class="form-label">Valor</label>
                                    <input type="number" step="0.01" class="form-control" id="value" name="value" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="description" class="form-label">Descrição</label>
                                    <input type="text" class="form-control" id="description" name="description">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Data</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="is_pending" class="form-label">Pendente</label>
                                    <select class="form-control" id="is_pending" name="is_pending" required>
                                        <option value="0">Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Categoria</label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="type_id" class="form-label">Tipo</label>
                                    <select class="form-control" id="type_id" name="type_id" required>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id_type }}">{{ $type->desc_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div id="step-2" class="d-none">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="note" class="form-label">Nota</label>
                                    <textarea class="form-control" id="note" name="note"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="segment_id" class="form-label">Segmento</label>
                                    <select class="form-control" id="segment_id" name="segment_id">
                                        <option value="">Nenhum</option>
                                        @foreach($segments as $segment)
                                            <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="attachment" class="form-label">Anexo</label>
                                    <input type="file" class="form-control" id="attachment" name="attachment">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="prev-step" onclick="toggleSteps('prev')"
                            disabled>Voltar</button>
                        <button type="button" class="btn btn-primary" id="next-step"
                            onclick="toggleSteps('next')">Próximo</button>
                        <button type="submit" class="btn btn-success d-none" id="save-button">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" id="editTransactionForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTransactionModalLabel">Editar Transação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_value" class="form-label">Valor</label>
                                <input type="number" step="0.01" class="form-control" id="edit_value" name="value" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_description" class="form-label">Descrição</label>
                                <input type="text" class="form-control" id="edit_description" name="description">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_date" class="form-label">Data</label>
                                <input type="date" class="form-control" id="edit_date" name="date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_is_pending" class="form-label">Pendente</label>
                                <select class="form-control" id="edit_is_pending" name="is_pending" required>
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_category_id" class="form-label">Categoria</label>
                                <select class="form-control" id="edit_category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_type_id" class="form-label">Tipo</label>
                                <select class="form-control" id="edit_type_id" name="type_id" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id_type }}">{{ $type->desc_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_note" class="form-label">Nota</label>
                                <textarea class="form-control" id="edit_note" name="note"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_segment_id" class="form-label">Segmento</label>
                                <select class="form-control" id="edit_segment_id" name="segment_id">
                                    <option value="">Nenhum</option>
                                    @foreach($segments as $segment)
                                        <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_attachment" class="form-label">Anexo</label>
                                <input type="file" class="form-control" id="edit_attachment" name="attachment">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteTransactionModal" tabindex="-1" aria-labelledby="deleteTransactionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteTransactionForm">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTransactionModalLabel">Excluir Transação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza de que deseja excluir a transação <strong
                                id="deleteTransactionDescription"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentStep = 1;

        function toggleSteps(direction) {
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const prevButton = document.getElementById('prev-step');
            const nextButton = document.getElementById('next-step');
            const saveButton = document.getElementById('save-button');
            const progressBar = document.getElementById('progress-bar');
            const stepCircles = document.querySelectorAll('.step-circle');

            if (direction === 'next' && currentStep === 1) {
                step1.classList.add('d-none');
                step2.classList.remove('d-none');
                prevButton.disabled = false;
                nextButton.classList.add('d-none');
                saveButton.classList.remove('d-none');
                progressBar.style.width = '100%';
                stepCircles[1].classList.add('active');
                currentStep++;
            } else if (direction === 'prev' && currentStep === 2) {
                step2.classList.add('d-none');
                step1.classList.remove('d-none');
                prevButton.disabled = true;
                nextButton.classList.remove('d-none');
                saveButton.classList.add('d-none');
                progressBar.style.width = '50%';
                stepCircles[1].classList.remove('active');
                currentStep--;
            }
        }

        function loadEditModal(id) {
            fetch(`/cashbook/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('editTransactionForm');
                    form.action = `/cashbook/${id}`;
                    document.getElementById('edit_value').value = data.cashbook.value;
                    document.getElementById('edit_description').value = data.cashbook.description;
                    document.getElementById('edit_date').value = data.cashbook.date;
                    document.getElementById('edit_is_pending').value = data.cashbook.is_pending;
                    document.getElementById('edit_category_id').value = data.cashbook.category_id;
                    document.getElementById('edit_type_id').value = data.cashbook.type_id;
                    document.getElementById('edit_note').value = data.cashbook.note;
                    document.getElementById('edit_segment_id').value = data.cashbook.segment_id;
                });
        }

        function loadDeleteModal(id, description) {
            const form = document.getElementById('deleteTransactionForm');
            form.action = `/cashbook/${id}`;
            document.getElementById('deleteTransactionDescription').textContent = description;
        }
    </script>

    <style>
        .progress-container {
            position: relative;
        }

        .progress {
            height: 5px;
            background-color: #e9ecef;
        }

        .progress-bar {
            height: 5px;
            background-color: #0d6efd;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            color: #6c757d;
        }

        .step-circle.active {
            background-color: #0d6efd;
            color: #fff;
        }
    </style>

@endsection