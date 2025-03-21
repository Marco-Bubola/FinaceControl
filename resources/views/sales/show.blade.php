<!-- resources/views/sales/show.blade.php -->

@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Detalhes da Venda #{{ $sale->id }}</h1>

    <!-- Exibindo as informações da venda -->
    <div class="mb-3">
        <strong>Cliente:</strong> {{ $sale->client->name }}<br>
        <strong>Status:</strong> {{ $sale->status }}<br>
        <strong>Total Pago:</strong> R$ {{ number_format($sale->amount_paid, 2, ',', '.') }}<br>
        <strong>Total da Venda:</strong> R$ {{ number_format($sale->total_price, 2, ',', '.') }}
    </div>

    <!-- Exibindo os itens da venda -->
    <h3>Itens da Venda</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Preço Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->saleItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>R$ {{ number_format($item->product->price_sale, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($item->product->price_sale * $item->quantity, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Adicionar pagamento -->
    <h3>Adicionar Pagamento</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $sale->id }}">Adicionar Pagamento</button>

</div>
@endsection