@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">

    @include('message.alert')
    <div class="row">

        <div class="col-lg-12 mb-4 ">
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
                        <div class="col-md-3 mb-4">
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
                    </div>
                </div>
            </div>
            @include('banks.create')
        </div>
        <div class="col-md-12  px-3">
            <div class="card  h-100  shadow-none" style="background-color: transparent; border: none;">
                <div class="card-header  pb-0 px-3" style="background-color: transparent; border-bottom: none;">
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
                    <div class="row text-center mb-3">
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
                        <!-- Added missing column class -->
                        <!-- Contêiner de transações -->
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
