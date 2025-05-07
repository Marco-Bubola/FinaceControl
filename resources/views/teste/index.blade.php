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
                    <img class="w-10 border-radius-lg shadow-sm" src="{{ asset('assets/img/logos/user-icon.png') }}" alt="Cliente" style="max-width: 60px;">
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

    {{-- Tabelas de Faturas e Transferências --}}
    <div class="row">
        {{-- Faturas --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Faturas</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table table-bordered table-hover align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($faturas as $fatura)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($fatura->invoice_date)->format('d/m/Y') }}</td>
                                    <td>{{ $fatura->description }}</td>
                                    <td>R$ {{ number_format($fatura->value, 2, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted">Nenhuma fatura registrada</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transferências --}}
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Transferências</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table table-bordered table-hover align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Data</th>
                                    <th>Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transferencias as $transferencia)
                                <tr>
                                    <td>{{ $transferencia->tipo }}</td>
                                    <td>R$ {{ number_format($transferencia->value, 2, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transferencia->transfer_date)->format('d/m/Y') }}</td>
                                    <td>{{ $transferencia->observation }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted">Nenhuma transferência registrada</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
