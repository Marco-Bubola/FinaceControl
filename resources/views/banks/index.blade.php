@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">

    @include('message.alert')
    <div class="row">

        <div class="col-lg-12 ">
            <div class="card shadow-none" style="background-color: transparent; border: none; ">
                <div class="card-header" style="background-color: transparent; border-bottom: none;">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h6 class="mb-0">Método de Pagamento</h6>
                        </div>
                        <div class="col-6 text-end">
                        <a class="btn bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#addCardModal"><i class="fas fa-plus"></i>&nbsp;&nbsp;Adicionar Novo Cartão</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($banks as $bank)
                        <div class="col-md-3">
                            <div
                                class="card card-body border shadow-sm border-radius-lg d-flex align-items-center flex-row">

                                <div class="d-flex flex-column align-items-center">
                                <img class="w-45 me-3 mb-0 "
                                src="../assets/img/logos/mastercard.png" alt="logo">
                                    <h6 class="mb-0 text-dark">{{ $bank->name }}</h6>
                                    <small class="text-muted">{{ $bank->description }}</small>
                                </div>
                                <div class="ms-auto d-flex flex-column">
                                    <a href="{{ route('invoices.index', ['bank_id' => $bank->id_bank]) }}"
                                        class="btn btn-info me-2" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Botão com ícone de lápis (pincel) -->
                                    <button class="btn btn-light d-flex align-items-center justify-content-center me-2"
                                        data-bs-toggle="modal" data-bs-target="#editBankModal{{ $bank->id_bank }}"
                                        title="Editar">
                                        <i class="fas fa-pencil-alt text-dark"></i>
                                    </button>

                                    <button class="btn btn-danger d-flex align-items-center justify-content-center me-2"
                                        data-bs-toggle="modal" data-bs-target="#deleteBankModal{{ $bank->id_bank }}"
                                        title="Excluir">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                        @include('banks.delet')
                        @include('banks.edit')
                        @endforeach

                        @if ($banks->isEmpty())
                        <div class="col-12">
                            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                <div class="animated-icon mb-2">
                                    <svg width="70" height="70" viewBox="0 0 130 130" fill="none">
                                        <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3" fill="#f8fafc"/>
                                        <rect x="40" y="60" width="50" height="30" rx="10" fill="#e9f2ff" stroke="#6ea8fe" stroke-width="3"/>
                                        <rect x="55" y="40" width="20" height="25" rx="6" fill="#f8fafc" stroke="#6ea8fe" stroke-width="3"/>
                                        <rect x="50" y="95" width="30" height="8" rx="4" fill="#6ea8fe" opacity="0.18"/>
                                        <ellipse cx="60" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25"/>
                                        <ellipse cx="70" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25"/>
                                        <path d="M60 85 Q65 80 70 85" stroke="#6ea8fe" stroke-width="2" fill="none"/>
                                    </svg>
                                </div>
                                <h2 class="fw-bold mb-2 text-primary" style="font-size:1.3rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                                    Nenhum Banco ou Cartão Cadastrado
                                </h2>
                                <p class="mb-2 text-secondary text-center" style="max-width: 350px; font-size:1rem; font-weight:500; line-height:1.4;">
                                    <span style="color:#0d6efd; font-weight:700;">Ops!</span> Você ainda não cadastrou nenhum método de pagamento.<br>
                                    <span style="color:#6ea8fe;">Adicione um novo banco ou cartão</span> para começar a gerenciar suas finanças!
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @include('banks.create')
        </div>
        <div class="col-md-12  px-3">
            <div class="card  h-100  shadow-none" style="background-color: transparent; border: none;">
                <div class="card-header  pb-0" style="background-color: transparent; border-bottom: none;">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="mb-0">Suas Transações</h6>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center mb-3">

                            <button id="prevMonth" class="btn btn-outline-primary me-3">
                                <i class="fas fa-chevron-left"></i> Mês Anterior
                            </button>

                            <i class="fas fa-calendar-alt text-info me-2" style="font-size: 1.7rem;"></i>
                            <h3 id="monthTitle" class="text-center me-3">
                                <!-- O título será atualizado dinamicamente -->
                            </h3>


                            <button id="nextMonth" class="btn btn-outline-primary ">
                                Próximo Mês <i class="fas fa-chevron-right"></i>
                            </button>

                        </div>
                        <div class="col-md-3 text-end">
                            <i class="far fa-calendar-alt me-2"></i>
                            <small>{{ \Carbon\Carbon::now()->format('d - m Y') }}</small>
                        </div>
                    </div>


                    <!-- Informações adicionais -->
                    <div class="row text-center ">
                        <div class="col-md-3">
                            <div class="card bg-dark text-white shadow-sm">
                                <div class="card-body">
                                    <p class="text-sm mb-1">Total do Mês:</p>
                                    <h6 id="totalMonth" class="text-success font-weight-bold">
                                        R$ {{ number_format($totalMonth, 2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-dark text-white shadow-sm">
                                <div class="card-body">
                                    <p class="text-sm mb-1">Maior Fatura:</p>
                                    <h6 class="text-danger font-weight-bold" id="highest-invoice">
                                        R$ {{ $highestInvoice ? number_format($highestInvoice->value, 2) : '0,00' }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-dark text-white shadow-sm">
                                <div class="card-body">
                                    <p class="text-sm mb-1">Menor Fatura:</p>
                                    <h6 class="text-warning font-weight-bold" id="lowest-invoice">
                                        R$ {{ $lowestInvoice ? number_format($lowestInvoice->value, 2) : '0,00' }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-dark text-white shadow-sm">
                                <div class="card-body">
                                    <p class="text-sm mb-1">Total de Transações:</p>
                                    <h6 class="text-info font-weight-bold" id="total-transactions">
                                        {{ $totalTransactions }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-8">

                        <div class="card-body" id="transactionsContainer">
                        </div>
                    </div>
                    <!-- Gráfico à Direita -->
                    <div class="col-md-4 d-flex flex-column align-items-center">
                        <div class="position-relative w-100">
                            <!-- Exibe mensagem se não houver dados -->
                            <span id="no-data-message" style="display: none;">Sem dados</span>
                            <canvas id="updateCategoryChart" style="width: 100%; height: 500px;"></canvas>
                            <!-- Altura fixa -->
                            <canvas id="lineChart" class="mt-4"></canvas> <!-- Gráfico de linha -->
                        </div>
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


<link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
