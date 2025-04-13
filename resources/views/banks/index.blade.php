@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">

    @if ($errors->any())
    <div id="error-message" class="alert alert-danger alert-dismissible fade show" role="alert">
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
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
            onclick="closeAlert('success-message')"></button>
        <div id="success-timer" class="alert-timer">30s</div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-5">
            <div class="card h-100 mb-4">
                <div class="card-header pb-0 px-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-0">Suas Transações</h6>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <i class="far fa-calendar-alt me-2"></i>
                            <small>{{ \Carbon\Carbon::now()->format('d - m Y') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Título do mês, fora da div transactionsContainer -->
                <div class="card-body">
                    <h3 id="monthTitle" class="text-center mb-3">
                        <!-- O título será atualizado dinamicamente -->
                    </h3>
                    <div class="d-flex justify-content-center mb-3">
                        <button id="prevMonth" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-chevron-left"></i> Mês Anterior
                        </button>
                        <button id="nextMonth" class="btn btn-outline-primary btn-sm">
                            Próximo Mês <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <!-- Informações adicionais -->
                    <div class="text-center mb-3">
                        <h5>Total do Mês:</h5>
                        <h4 id="totalMonth" class="text-success">$ {{ number_format($totalMonth, 2) }}</h4>
                    </div>
                </div>

                <!-- Contêiner de transações -->
                <div class="card-body" id="transactionsContainer">
                    @if ($groupedInvoices->isEmpty())
                    <p>Você ainda não tem transações para este mês.</p>
                    @else
                    @foreach ($groupedInvoices as $date => $invoices)
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d \d\e F \d\e Y') }}
                    </h6>
                    <ul class="list-group">
                        @foreach ($invoices as $invoice)
                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                            <div class="d-flex align-items-center">
                                <button
                                    class="btn btn-icon-only btn-rounded {{ $invoice->value < 0 ? 'btn-outline-danger' : 'btn-outline-success' }} mb-0 me-3 btn-sm d-flex align-items-center justify-content-center">
                                    <i class="{{ $invoice->value < 0 ? 'fas fa-arrow-down' : 'fas fa-arrow-up' }}"></i>
                                </button>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">{{ $invoice->description }}</h6>
                                    <span class="text-xs">
                                        {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('H:i A') }}
                                    </span>
                                </div>
                            </div>
                            <div
                                class="d-flex align-items-center {{ $invoice->value < 0 ? 'text-danger text-gradient' : 'text-success text-gradient' }} text-sm font-weight-bold">
                                {{ $invoice->value < 0 ? '-' : '+' }} $ {{ number_format(abs($invoice->value), 2) }}
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="row">
                <div id="cardCarousel" class="carousel slide col-xl-12 mb-xl-0 mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @forelse ($banks as $index => $bank)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div class="card bg-transparent shadow-xl">
                                <div class="overflow-hidden position-relative border-radius-xl"
                                    style="background-image: url('../assets/img/curved-images/curved14.jpg');">
                                    <span class="mask bg-gradient-dark"></span>
                                    <!-- Botões de Ação -->
                                    <div class="card-body position-relative z-index-1 p-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="text-white mt-4 mb-4 pb-2">{{ $bank->description }}</h5>
                                            <a href="{{ route('invoices.index', ['bank_id' => $bank->id_bank]) }}"
                                                class="btn bg-gradient-info mb-0" title="Visualizar Cartão"
                                                data-bank-id="{{ $bank->id_bank }}">
                                                <i class="fas fa-eye"></i>&nbsp;Visualizar
                                            </a>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex flex-column me-4">
                                                <div class="mb-3">
                                                    <p class="text-white text-lg opacity-8 mb-0">Titular do Cartão</p>
                                                    <h6 class="text-white mb-0 text-lg">{{ $bank->name }}</h6>
                                                </div>
                                                <div class="mb-3">
                                                    <p class="text-white text-lg opacity-8 mb-0">Data Início Cartão</p>
                                                    <h6 class="text-white mb-0 text-lg">
                                                        {{ \Carbon\Carbon::parse($bank->start_date)->format('d/m') }}
                                                    </h6>
                                                </div>
                                                <div>
                                                    <p class="text-white text-lg opacity-8 mb-0">Fechamento</p>
                                                    <h6 class="text-white mb-0 text-lg">
                                                        {{ \Carbon\Carbon::parse($bank->end_date)->format('d/m') }}
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="ms-auto d-flex align-items-end justify-content-end">
                                                <img class="w-60 mt-2" src="../assets/img/logos/mastercard.png"
                                                    alt="logo">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="carousel-item active text-center">
                            <p class="text-muted">Você ainda não tem cartões cadastrados.</p>
                            <a class="btn bg-gradient-dark mb-0" data-bs-toggle="modal"
                                data-bs-target="#addCardModal"><i class="fas fa-plus"></i>&nbsp;&nbsp;Adicionar Novo
                                Cartão</a>
                        </div>
                        @endforelse
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#cardCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#cardCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                </div>



                <div class="col-md-12 mb-lg-0 mb-4">
                    <div class="card mt-4">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h6 class="mb-0">Método de Pagamento</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <a class="btn bg-gradient-dark mb-0" data-bs-toggle="modal"
                                        data-bs-target="#addCardModal"><i class="fas fa-plus"></i>&nbsp;&nbsp;Adicionar
                                        Novo Cartão</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                @foreach ($banks as $bank)
                                <div class="col-md-6 mb-md-0 mb-4">
                                    <div
                                        class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                                        <img class="w-10 me-3 mb-0" src="../assets/img/logos/mastercard.png" alt="logo">
                                        <h6 class="mb-0">{{ $bank->name }}</h6>
                                        <div class="ms-auto d-flex">
                                            <a href="{{ route('invoices.index', ['bank_id' => $bank->id_bank]) }}"
                                                class="btn btn-info btn-sm me-2" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <i class="fas fa-pencil-alt text-dark cursor-pointer me-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editBankModal{{ $bank->id_bank }}" title="Editar"></i>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteBankModal{{ $bank->id_bank }}" title="Excluir">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para Confirmar Exclusão -->
                                <div class="modal fade" id="deleteBankModal{{ $bank->id_bank }}" tabindex="-1"
                                    aria-labelledby="deleteBankModalLabel{{ $bank->id_bank }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteBankModalLabel{{ $bank->id_bank }}">
                                                    Confirmar Exclusão</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body">
                                                Tem certeza de que deseja excluir o cartão
                                                <strong>{{ $bank->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST"
                                                    action="{{ route('banks.destroy', $bank->id_bank) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para Editar Cartão -->
                                <div class="modal fade" id="editBankModal{{ $bank->id_bank }}" tabindex="-1"
                                    aria-labelledby="editBankModalLabel{{ $bank->id_bank }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header custom-modal-header">
                                                <h5 class="modal-title text-center"
                                                    id="editBankModalLabel{{ $bank->id_bank }}">
                                                    Editar Cartão</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Fechar"></button>
                                            </div>
                                            <div class="modal-body custom-modal-body">
                                                <form method="POST"
                                                    action="{{ route('banks.update', $bank->id_bank) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3"> <label
                                                                for="name{{ $bank->id_bank }}"
                                                                class="form-label custom-label">Titular do
                                                                Cartão</label>
                                                            <input type="text" class="form-control custom-input"
                                                                id="name{{ $bank->id_bank }}" name="name"
                                                                value="{{ $bank->name }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3"> <label
                                                                for="description{{ $bank->id_bank }}"
                                                                class="form-label custom-label">Número do Cartão</label>
                                                            <input type="text" class="form-control custom-input"
                                                                id="description{{ $bank->id_bank }}" name="description"
                                                                value="{{ $bank->description }}" required>
                                                        </div>


                                                        <div class="col-md-6 mb-3"> <label
                                                                for="start_date{{ $bank->id_bank }}"
                                                                class="form-label custom-label">Data de Início</label>
                                                            <input type="date" class="form-control custom-input"
                                                                id="start_date{{ $bank->id_bank }}" name="start_date"
                                                                value="{{ $bank->start_date }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3"> <label
                                                                for="end_date{{ $bank->id_bank }}"
                                                                class="form-label custom-label">Data de Término</label>
                                                            <input type="date" class="form-control custom-input"
                                                                id="end_date{{ $bank->id_bank }}" name="end_date"
                                                                value="{{ $bank->end_date }}" required>
                                                        </div>
                                                        <div
                                                            class="modal-footer justify-content-center custom-modal-footer">
                                                            <button type="button" class="btn btn-secondary custom-btn"
                                                                data-bs-dismiss="modal">Fechar</button>
                                                            <button type="submit"
                                                                class="btn btn-primary custom-btn">Salvar
                                                                Alterações</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Adicionar Novo Cartão -->
        <div class="modal fade custom-modal-edit" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header custom-modal-header">
                        <h5 class="modal-title text-center" id="addCardModalLabel">Adicionar Novo Cartão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <form method="POST" action="{{ route('banks.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label custom-label">Titular do Cartão</label>
                                    <input type="text" class="form-control custom-input" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="description" class="form-label custom-label">Número do Cartão</label>
                                    <input type="text" class="form-control custom-input" id="description"
                                        name="description" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label custom-label">Data de Início</label>
                                    <input type="date" class="form-control custom-input" id="start_date"
                                        name="start_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label custom-label">Data de Término</label>
                                    <input type="date" class="form-control custom-input" id="end_date" name="end_date"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center custom-modal-footer">
                                <button type="button" class="btn btn-secondary custom-btn"
                                    data-bs-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary custom-btn">Salvar Cartão</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<script>
// Passando variáveis do PHP para o JavaScript
window.currentMonth = {{$month}};
window.currentYear = {{$year}};
</script>
@endsection

@push('scripts')
<script src="{{ asset('js/transactions.js') }}"></script>
@endpush

<style>
.modal-lg {
    max-width: 80%;
}

.alert-timer {
    position: absolute;
    top: 10px;
    right: 40px;
    background-color: #ff9800;
    color: white;
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-top: 5px;
}

.alert-dismissible .btn-close {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px 10px;
    color: #fff;
    background: transparent;
    border: none;
}

</style>