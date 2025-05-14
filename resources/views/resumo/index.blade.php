@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">

    {{-- Cabeçalho do Cliente --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1">Resumo Financeiro de {{ $cliente->name }}</h5>
                        <p class="text-muted mb-0">Email: {{ $cliente->email ?? 'N/A' }}</p>
                        <p class="text-muted mb-0">Telefone: {{ $cliente->phone ?? 'N/A' }}</p>
                    </div>
                    <img src="{{ asset('assets/img/logos/user-icon.png') }}" class="rounded shadow" alt="Cliente"
                        style="max-width: 60px;">
                </div>
            </div>
        </div>
    </div>

    {{-- Cartões de Resumo --}}
    <div class="row g-3 mb-4">
        @php
        $cards = [
        ['label' => 'Total de Faturas', 'value' => $totalFaturas, 'colors' => 'danger','color' => 'text-danger', 'icon'
        => 'fas fa-file-invoice'],
        ['label' => 'Total Recebido', 'value' => $totalRecebido, 'colors' => 'success','color' => 'text-success', 'icon'
        => 'fas fa-arrow-down'],
        ['label' => 'Total Enviado', 'value' => $totalEnviado,'colors' => 'warning', 'color' => 'text-warning', 'icon'
        => 'fas fa-arrow-up'],
        ['label' => 'Saldo Atual', 'value' => $saldoAtual,'colors' => 'info', 'color' => 'text-info', 'icon' => 'fas
        fa-wallet'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-sm-6 col-md-3">
            <div class="card bg-dark text-white shadow-sm mb-4 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-{{ $card['colors'] }} rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 50px; height: 50px;">
                        <i class="{{ $card['icon'] }} text-white"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-sm">{{ $card['label'] }}</p>
                        <h6 class="{{ $card['color'] }} font-weight-bold">
                            R$ {{ number_format($card['value'], 2, ',', '.') }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        {{-- Coluna Esquerda --}}
        <div class="col-lg-6">

            {{-- Faturas --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Faturas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($faturas as $fatura)
                        <div class="col-md-6">
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="border: 3px solid {{ $fatura->category->hexcolor_category }};
                                background-color: {{ $fatura->category->hexcolor_category }}20;
                                width: 50px; height: 50px;">
                                        <i class="{{ $fatura->category->icone }}"
                                            style="font-size: 1.5rem; color: {{ $fatura->category->hexcolor_category }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark text-truncate">{{ $fatura->description }}</h6>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($fatura->invoice_date)->format('d/m/Y') }}</small>
                                    </div>
                                    <span class="badge bg-primary fs-6">R$
                                        {{ number_format($fatura->value, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted">Nenhuma fatura registrada.</div>
                        @endforelse
                    </div>
                </div>
            </div>


            {{-- Transferências Enviadas --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0 text-danger"><i class="fas fa-arrow-down me-1"></i> Transferências Enviadas</h6>
                </div>
                <div class="card-body">
                    @php
                    $enviadas = collect($transferencias)->where('tipo', 'Enviado');
                    @endphp

                    <div class="row">
                        @forelse ($enviadas as $transferencia)
                        @php $bgColor = '#dc3545'; @endphp
                        <div class="col-md-6">
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body d-flex align-items-center gap-3">


                                    <button
                                        class="btn btn-icon-only btn-rounded d-flex align-items-center justify-content-center"
                                        style="border: 3px solid {{ $transferencia->category->hexcolor_category ?? '#ccc' }};
                                background-color: {{ $transferencia->category->hexcolor_category ?? '#ccc' }}20;
                                width: 50px; height: 50px;"
                                        title="{{ $transferencia->category->name ?? 'Categoria não definida' }}"
                                        data-bs-toggle="tooltip">
                                        <i class="{{ $transferencia->category->icone ?? 'fas fa-question' }}"
                                            style="font-size: 1.3rem;"></i>
                                    </button>

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark">{{ $transferencia->description }}</h6>
                                        <small class="text-muted">Data:
                                            {{ \Carbon\Carbon::parse($transferencia->transfer_date)->format('d/m/Y') }}</small><br>
                                        <small class="badge bg-primary fs-6">R$
                                            {{ number_format($transferencia->value, 2, ',', '.') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted">Nenhuma transferência enviada.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Transferências Recebidas --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0 text-success"><i class="fas fa-arrow-up me-1"></i> Transferências Recebidas</h6>
                </div>
                <div class="card-body">
                    @php
                    $recebidas = collect($transferencias)->where('tipo', 'Recebido');
                    @endphp

                    <div class="row">
                        @forelse ($recebidas as $transferencia)
                        @php $bgColor = '#198754'; @endphp
                        <div class="col-md-6">
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body d-flex align-items-center gap-3">

                                    <button
                                        class="btn btn-icon-only btn-rounded d-flex align-items-center justify-content-center"
                                        style="border: 3px solid {{ $transferencia->category->hexcolor_category ?? '#ccc' }};
                                    background-color: {{ $transferencia->category->hexcolor_category ?? '#ccc' }}20;
                                    width: 50px; height: 50px;"
                                        title="{{ $transferencia->category->name ?? 'Categoria não definida' }}"
                                        data-bs-toggle="tooltip">
                                        <i class="{{ $transferencia->category->icone ?? 'fas fa-question' }}"
                                            style="font-size: 1.3rem;"></i>
                                    </button>

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark">{{ $transferencia->description }}</h6>
                                        <small class="text-muted">Data:
                                            {{ \Carbon\Carbon::parse($transferencia->transfer_date)->format('d/m/Y') }}</small><br>
                                        <small class="badge bg-primary fs-6">R$
                                            {{ number_format($transferencia->value, 2, ',', '.') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted">Nenhuma transferência recebida.</div>
                        @endforelse
                    </div>
                </div>
            </div>


        </div>

        <!-- Coluna da direita: Gráfico -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Gráfico de Receitas e Despesas</h6>
                </div>
                <div class="card-body">
                    <canvas id="transaction-pie-chart" style="max-height: 500px;"></canvas>
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Gráfico de Categorias</h6>
                </div>
                <div class="card-body">
                    <canvas id="updateCategoryChart" style="max-height: 500px;"></canvas>
                    <div id="no-data-message" class="text-center text-muted" style="display: none;">Sem dados</div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
        // Passando dados do PHP para o JavaScript
        window.resumoCategories = @json($categories);
        window.resumoTotalInvoices = {{ $totalFaturas ?? 0 }};
        window.resumoTotals = @json($totals);
        
    </script>
    <script src="{{ asset('js/resumo.js') }}"></script>
    @endpush
