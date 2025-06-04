@extends('layouts.user_type.auth')

@section('content')
{{-- Cabeçalho do Cliente + Cartões de Resumo --}}
<div class="card bg-transparent  shadow-none">
    <div class="card-body d-flex flex-wrap gap-4 align-items-center justify-content-between">
        {{-- Dados do Cliente --}}
        <div class="d-flex align-items-center gap-3 flex-shrink-0" style="min-width: 260px;">
            <img src="{{ asset('assets/img/logos/user-icon.png') }}" class="rounded shadow" alt="Cliente"
                style="max-width: 60px;">
            <div>
                <h5 class="mb-1">Resumo Financeiro de {{ e($cliente->name) }}</h5>
                <p class="text-muted mb-0">Email: {{ e($cliente->email) ?? 'N/A' }}</p>
                <p class="text-muted mb-0">Telefone: {{ e($cliente->phone) ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Cartões de Resumo --}}
        <div class="d-flex gap-3 flex-wrap justify-content-end flex-grow-1">
            @php
            $cards = [
            ['label' => 'Total de Faturas', 'value' => $totalFaturas, 'colors' => 'danger','color' => 'text-danger',
            'icon' => 'fas fa-file-invoice'],
            ['label' => 'Total Recebido', 'value' => $totalRecebido, 'colors' => 'success','color' => 'text-success',
            'icon' => 'fas fa-arrow-down'],
            ['label' => 'Total Enviado', 'value' => $totalEnviado,'colors' => 'warning', 'color' => 'text-warning',
            'icon' => 'fas fa-arrow-up'],
            ['label' => 'Saldo Atual', 'value' => $saldoAtual,'colors' => 'info', 'color' => 'text-info', 'icon' => 'fas
            fa-wallet'],
            ];
            @endphp
            @foreach ($cards as $card)
            <div class="card bg-transparent  shadow-none mb-0 flex-fill" style="min-width:180px; max-width:220px;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-{{ $card['colors'] }} bg-opacity-75 rounded-circle d-flex justify-content-center align-items-center shadow"
                        style="width: 48px; height: 48px;">
                        <i class="{{ $card['icon'] }} text-white fs-4"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-sm text-muted fw-semibold">{{ $card['label'] }}</p>
                        <h6 class="{{ $card['color'] }} fw-bold mb-0">
                            R$ {{ number_format($card['value'], 2, ',', '.') }}
                        </h6>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<div class="row">
    {{-- Coluna Esquerda --}}
    <div class="col-lg-8">
        {{-- Faturas --}}
        <div class="card bg-transparent  shadow-none mb-2">
            <div class="card-header bg-transparent  shadow-none pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Faturas</h6>
                {{-- Paginação das faturas no header --}}
                <div id="faturas-pagination" class="mb-0">
                    {{-- Inclua apenas a paginação, sem as faturas --}}
                    @if ($faturas->hasPages())
                    @include('resumo.partials.faturas-pagination', ['faturas' => $faturas, 'clienteId' => $cliente->id])
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div id="faturas-container">
                    @include('resumo.partials.faturas', ['faturas' => $faturas, 'clienteId' => $cliente->id,
                    'onlyFaturas' => true])
                </div>
            </div>
        </div>
        {{-- Transferências Enviadas --}}
        <div class="card bg-transparent  shadow-none mb-2">
            <div class="card-header bg-transparent  shadow-none pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-danger"><i class="fas fa-arrow-down me-1"></i> Transferências Enviadas</h6>
                <div id="enviadas-pagination" class="mb-0">
                    @php
                    $enviadasPag = \App\Models\Cashbook::where('client_id', $cliente->id)
                    ->where('type_id', 2)
                    ->with(['category:id_category,name,icone,hexcolor_category'])
                    ->orderBy('date', 'desc')
                    ->paginate(6);
                    @endphp
                    @if ($enviadasPag->hasPages())
                    @include('resumo.partials.transferencias-enviadas-pagination', ['enviadas' => $enviadasPag,
                    'clienteId' => $cliente->id])
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div id="enviadas-container">
                    @include('resumo.partials.transferencias-enviadas', ['enviadas' => $enviadasPag, 'clienteId' =>
                    $cliente->id])
                </div>
            </div>
        </div>
        {{-- Transferências Recebidas --}}
        <div class="card bg-transparent  shadow-none mb-2">
            <div class="card-header bg-transparent  shadow-none pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-success"><i class="fas fa-arrow-up me-1"></i> Transferências Recebidas</h6>
                <div id="recebidas-pagination" class="mb-0">
                    @php
                    $recebidasPag = \App\Models\Cashbook::where('client_id', $cliente->id)
                    ->where('type_id', 1)
                    ->with(['category:id_category,name,icone,hexcolor_category'])
                    ->orderBy('date', 'desc')
                    ->paginate(6);
                    @endphp
                    @if ($recebidasPag->hasPages())
                    @include('resumo.partials.transferencias-recebidas-pagination', ['recebidas' => $recebidasPag,
                    'clienteId' => $cliente->id])
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div id="recebidas-container">
                    @include('resumo.partials.transferencias-recebidas', ['recebidas' => $recebidasPag, 'clienteId' =>
                    $cliente->id])
                </div>
            </div>
        </div>
    </div>

    <!-- Coluna da direita: Gráfico -->
    <div class="col-md-4 ">
        <div class="card bg-transparent  shadow-none mb-2">
            <div class="card-header bg-transparent  shadow-none pb-0">
                <h6 class="mb-0">Gráfico de Receitas e Despesas</h6>
            </div>
            <div class="card-body ">
                <canvas id="transaction-pie-chart" style="max-height: 500px;"></canvas>
            </div>
        </div>
        <div class="card bg-transparent  shadow-none mb-2">
            <div class="card-header bg-transparent  shadow-none  pb-0">
                <h6 class="mb-0 ">Gráfico de Categorias</h6>
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
window.resumoTotalInvoices = {{$totalFaturas ?? 0}};
window.resumoTotals = @json($totals);
</script>
<link rel="stylesheet" href="{{ asset('css/resumo.css') }}">
<script src="{{ asset('js/resumo.js') }}"></script>
@endpush