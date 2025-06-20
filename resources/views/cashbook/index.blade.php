@extends('layouts.user_type.auth')

@section('content')
<link rel="stylesheet" href="{{ asset('css/cashbook.css') }}">

<div class="row ">
    <div class="col-md-12">
        <div class="card h-100 shadow-none" style="background-color: transparent; border: none;">
            {{-- Mensagens de sucesso e erro (usando o modal padrão) --}}
            @include('message.alert')

            {{-- Aviso resumido de duplicados --}}
            @if(session('warning'))
                <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm border-0"
                     style="background: linear-gradient(90deg, #fffbe6 80%, #fef9c3 100%); color: #b68400; font-size: 1.08em; font-weight: 500;">
                    <span class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2" style="font-size:1.3em;color:#f59e42;"></i>
                        <span>Algumas transações não foram inseridas pois já existiam.</span>
                    </span>
                    <div>
                        <button class="btn btn-sm btn-outline-warning me-2" style="border-radius: 20px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#warningDetailsModal">
                            <i class="fas fa-list me-1"></i> Ver detalhes
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-close-warning" title="Fechar" style="border-radius: 20px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Modal de detalhes de inserção/duplicados --}}
            @if(session('warning_details'))
                @include('message.warning_details', [
                    'inserted' => session('warning_details')['inserted'] ?? [],
                    'duplicated' => session('warning_details')['duplicated'] ?? []
                ])
            @endif

            <!-- HEADER EM UMA LINHA SÓ, ESTILIZADO E RESPONSIVO -->
            <div class="card-header " style="background-color: transparent; border-bottom: none;">
                <div class="row align-items-center g-3">
                    <div class="col-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 flex-lg-nowrap">
                            <!-- Ícone e Título -->
                            <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                <i class="fas fa-wallet text-primary" style="font-size: 2.2rem;"></i>
                                <h2 class="mb-0 fw-bold" style="letter-spacing: 0.01em; font-size: 1.7rem;">Suas
                                    Transações</h2>
                            </div>
                            <!-- Navegação de Mês com Cards Estilizados -->
                            <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                                <!-- Card Mês Anterior -->
                                <div id="prev-month-card" class="month-nav-card" onclick="loadMonth('previous')">
                                    <span class="month-nav-icon bg-white">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                    <div class="month-nav-content">
                                        <span class="month-nav-title" id="prev-month-name">...</span>
                                        <span class="month-nav-label">Saldo</span>
                                        <span class="month-nav-balance" id="prev-month-balance">...</span>
                                    </div>
                                </div>
                                <!-- Card Mês Atual -->
                                <div class="month-nav-card active">
                                    <span class="month-nav-icon" style="background: #bae6fd;">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <div class="month-nav-content">
                                        <span class="month-nav-title" id="month-name">{{ $monthName ?? '' }}</span>
                                        <span class="month-nav-label">Saldo</span>
                                        <span class="month-nav-balance" id="month-balance">
                                            {{ $totals['balance'] >= 0 ? '+' : '-' }} R$
                                            {{ number_format(abs($totals['balance']), 2) }}
                                        </span>
                                    </div>
                                </div>
                                <!-- Card Próximo Mês -->
                                <div id="next-month-card" class="month-nav-card" onclick="loadMonth('next')">

                                    <div class="month-nav-content">
                                        <span class="month-nav-title" id="next-month-name">...</span>
                                        <span class="month-nav-label">Saldo</span>
                                        <span class="month-nav-balance" id="next-month-balance">...</span>
                                    </div>
                                    <span class="month-nav-icon bg-white">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </div>
                            </div>
                            <!-- Botões de Ação -->
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <button class="btn btn-outline-success d-flex align-items-center px-4 py-2"
                                    data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                                    <i class="fas fa-plus me-2"></i> Adicionar
                                </button>
                                <button class="btn btn-outline-info d-flex align-items-center px-4 py-2"
                                    data-bs-toggle="modal" data-bs-target="#uploadCashbookModal">
                                    <i class="fas fa-upload me-2"></i> Upload
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-4 p-4">
                <div class="row">
                    <!-- Coluna da Esquerda - Transações -->
                    <div class="col-lg-8" id="transactions-container">
                        @if($transactions->isEmpty())
                        <div class="col-12">
                            <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                <div class="animated-icon mb-4">
                                    <svg width="130" height="130" viewBox="0 0 130 130" fill="none">
                                        <circle cx="65" cy="65" r="62" stroke="#e3eafc" stroke-width="3"
                                            fill="#f8fafc" />
                                        <!-- Ícone de carteira triste -->
                                        <rect x="40" y="60" width="50" height="30" rx="10" fill="#e9f2ff"
                                            stroke="#6ea8fe" stroke-width="3" />
                                        <rect x="55" y="40" width="20" height="25" rx="6" fill="#f8fafc"
                                            stroke="#6ea8fe" stroke-width="3" />
                                        <rect x="50" y="95" width="30" height="8" rx="4" fill="#6ea8fe"
                                            opacity="0.18" />
                                        <ellipse cx="60" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25" />
                                        <ellipse cx="70" cy="75" rx="3" ry="2" fill="#6ea8fe" opacity="0.25" />
                                        <!-- Boca triste -->
                                        <path d="M60 85 Q65 80 70 85" stroke="#6ea8fe" stroke-width="2" fill="none" />
                                    </svg>
                                </div>
                                <h2 class="fw-bold mb-3 text-primary"
                                    style="font-size:2.2rem; letter-spacing:0.01em; text-shadow:0 2px 8px #e3eafc;">
                                    Nenhuma Transação Encontrada
                                </h2>
                                <p class="mb-4 text-secondary text-center"
                                    style="max-width: 480px; font-size:1.15rem; font-weight:500; line-height:1.6;">
                                    <span style="color:#0d6efd; font-weight:700;">Ops!</span> Não encontramos
                                    transações para o mês selecionado.<br>
                                    <span style="color:#6ea8fe;">Adicione uma nova transação</span> para começar a
                                    registrar seu fluxo financeiro!
                                </p>
                            </div>
                        </div>
                        @else
                        <!-- Conteúdo do mês será carregado dinamicamente -->
                        @endif
                    </div>

                    <!-- Coluna da Direita - Gráfico -->
                    <div class="col-lg-4" id="chart-container">
                        <div class="row mb-4 justify-content-center">
                            <div class="col-12">
                                <div class="d-flex justify-content-between gap-3 flex-wrap">
                                    <!-- Card Receitas -->
                                    <div class="card shadow-sm border-0 flex-fill"
                                        style="min-width: 120px; background: linear-gradient(135deg, #e6f9ec 60%, #f8fafc 100%);">
                                        <div class="card-body text-center p-3">
                                            <div class="mb-2">
                                                <i class="fas fa-arrow-up text-success" style="font-size: 1.6rem;"></i>
                                            </div>
                                            <h6 class="text-sm fw-bold mb-1 text-success">Receitas</h6>
                                            <div class="fs-5 fw-bold text-success mb-0" id="income-value">
                                                + R$ {{ number_format(abs($totals['income']), 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card Despesas -->
                                    <div class="card shadow-sm border-0 flex-fill"
                                        style="min-width: 120px; background: linear-gradient(135deg, #ffeaea 60%, #f8fafc 100%);">
                                        <div class="card-body text-center p-3">
                                            <div class="mb-2">
                                                <i class="fas fa-arrow-down text-danger" style="font-size: 1.6rem;"></i>
                                            </div>
                                            <h6 class="text-sm fw-bold mb-1 text-danger">Despesas</h6>
                                            <div class="fs-5 fw-bold text-danger mb-0" id="expense-value">
                                                - R$ {{ number_format(abs($totals['expense']), 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card Saldo -->
                                    <div class="card shadow-sm border-0 flex-fill"
                                        style="min-width: 120px; background: linear-gradient(135deg, #eaf6ff 60%, #f8fafc 100%);">
                                        <div class="card-body text-center p-3">
                                            <div class="mb-2">
                                                <i class="fas fa-wallet text-{{ $totals['balance'] >= 0 ? 'success' : 'danger' }}"
                                                    style="font-size: 1.6rem;"></i>
                                            </div>
                                            <h6
                                                class="text-sm fw-bold mb-1 text-{{ $totals['balance'] >= 0 ? 'success' : 'danger' }}">
                                                Saldo
                                            </h6>
                                            <div class="fs-5 fw-bold text-{{ $totals['balance'] >= 0 ? 'success' : 'danger' }} mb-0"
                                                id="balance-value">
                                                {{ $totals['balance'] >= 0 ? '+' : '-' }} R$
                                                {{ number_format(abs($totals['balance']), 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Gráfico de pizza será inserido aqui -->
                        <div class="bg-white rounded shadow-sm p-3 mb-4">
                            <canvas id="transaction-pie-chart" width="400" height="400"></canvas>
                        </div>
                        <div class="mt-4 bg-white rounded shadow-sm p-3">
                            <h6 class="text-center mb-3">Receitas por Categoria</h6>
                            <canvas id="income-bar-chart" width="400" height="400"></canvas>
                        </div>
                        <div class="mt-4 bg-white rounded shadow-sm p-3">
                            <h6 class="text-center mb-3">Despesas por Categoria</h6>
                            <canvas id="expense-bar-chart" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('cashbook.uploadCashbook', ['cofrinhos' => $cofrinhos])
@include('cashbook.create', ['cofrinhos' => $cofrinhos])
@include('cashbook.edit', ['cofrinhos' => $cofrinhos])
@include('cashbook.delete')

<script src="{{ asset('js/cashbook.js') }}"></script>

@endsection