@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">

        {{-- Cabeçalho do Cliente --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Resumo Financeiro de {{ $cliente->name }}</h5>
                            <p class="text-sm text-muted mb-0">Email: {{ $cliente->email ?? 'N/A' }}</p>
                            <p class="text-sm text-muted mb-0">Telefone: {{ $cliente->phone ?? 'N/A' }}</p>
                        </div>
                        <img class="w-10 border-radius-lg shadow-sm" src="{{ asset('assets/img/logos/user-icon.png') }}"
                            alt="Cliente" style="max-width: 60px;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Cartões de Resumo --}}
        <div class="row">
            @php
                $cards = [
                    ['label' => 'Total de Faturas (Despesa)', 'value' => $totalFaturas, 'color' => 'text-danger'],
                    ['label' => 'Total Recebido (Receita)', 'value' => $totalRecebido, 'color' => 'text-success'],
                    ['label' => 'Total Enviado (Despesa)', 'value' => $totalEnviado, 'color' => 'text-warning'],
                    ['label' => 'Saldo Atual', 'value' => $saldoAtual, 'color' => 'text-info'],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-md-3">
                    <div class="card bg-dark text-white shadow-sm mb-4">
                        <div class="card-body">
                            <p class="text-sm mb-1">{{ $card['label'] }}</p>
                            <h6 class="{{ $card['color'] }} font-weight-bold">
                                R$ {{ number_format($card['value'], 2, ',', '.') }}
                            </h6>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <!-- Coluna da esquerda: Faturas e Transferências -->
            <div class="col-md-6">
                {{-- Faturas --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Faturas</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            @forelse ($faturas as $fatura)
                                <div class="card mb-3 border-0 shadow-sm">
                                    <div class="card-body d-flex align-items-center gap-3">
                                        <button class="btn rounded-circle d-flex align-items-center justify-content-center"
                                            style="border: 3px solid {{ $fatura->category->hexcolor_category }}; background-color: {{ $fatura->category->hexcolor_category }}20; width: 50px; height: 50px;"
                                            title="{{ $fatura->category->name }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top">
                                            <i class="{{ $fatura->category->icone }}"
                                                style="font-size: 1.5rem; color: {{ $fatura->category->hexcolor_category }};"></i>
                                        </button>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 text-dark text-truncate">{{ $fatura->description }}</h6>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($fatura->invoice_date)->format('d/m/Y') }}</small>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary fs-6">R$
                                                {{ number_format($fatura->value, 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted">Nenhuma fatura registrada</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Transferências Enviadas --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header pb-0">
                        <h6 class="mb-0 text-danger"><i class="fas fa-arrow-down me-1"></i> Transferências Enviadas</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                @php
                                    $enviadas = collect($transferencias)->where('tipo', 'Enviado');
                                @endphp

                                @forelse ($enviadas as $transferencia)
                                    @php $bgColor = '#dc3545'; @endphp
                                    <div class="card mb-3 border-0 shadow-sm">
                                        <div class="card-body d-flex align-items-center gap-3">
                                            <button class="btn btn-icon-only btn-rounded btn-outline-danger"
                                                style="width: 50px; height: 50px; background-color: {{ $bgColor }}20;">
                                                <i class="fas fa-arrow-down" style="font-size: 1.5rem;"></i>
                                            </button>

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
                                                <small class="text-muted">Categoria:
                                                    {{ $transferencia->category->name ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted">Nenhuma transferência enviada.</div>
                                @endforelse
                            </div>
                        </div>
                        </div>
                    </div>

                    {{-- Transferências Recebidas --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header pb-0">
                            <h6 class="mb-0 text-success"><i class="fas fa-arrow-up me-1"></i> Transferências Recebidas</h6>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card-body">
                                    @php
                                        $recebidas = collect($transferencias)->where('tipo', 'Recebido');
                                    @endphp

                                    @forelse ($recebidas as $transferencia)
                                            @php $bgColor = '#198754'; @endphp
                                            <div class="card mb-3 border-0 shadow-sm">
                                                <div class="card-body d-flex align-items-center gap-3">
                                                    <button class="btn btn-icon-only btn-rounded btn-outline-success"
                                                        style="width: 50px; height: 50px; background-color: {{ $bgColor }}20;">
                                                        <i class="fas fa-arrow-up" style="font-size: 1.5rem;"></i>
                                                    </button>

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
                                    @empty
                                        <div class="text-center text-muted">Nenhuma transferência recebida.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Coluna da direita: Gráfico -->
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header pb-0">
                            <h6 class="mb-0">Gráfico Financeiro</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoFinanceiro" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>


        </div>
@endsection
