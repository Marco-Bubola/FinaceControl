<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório da Venda</title>
    <style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #222;
        background: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .container {
        background: #fff;
      
        max-width: 1000px;
        border-radius: 10px;
        box-shadow: 0 2px 8px #0001;
    }

    .header {
        background: linear-gradient(90deg, #f8fafc 0%, #e0e7ef 100%);

        border-radius: 12px 12px 0 0;
        text-align: center;
        margin-bottom: 1px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2d3748;
        letter-spacing: 1px;
    }

    .header .subtitle {
        font-size: 1.1rem;
        color: #64748b;
    
    }

    .summary {
        display: flex;
        justify-content: space-between;
      
        background: #f1f7ff;
        border-radius: 8px;
  
    }

    .summary .info {
        font-size: 1.05rem;
    }

    .summary .badge {
        font-size: 1rem;
        border-radius: 20px;
        color: #fff;
        font-weight: bold;
    }

    .badge-success {
        background: #28a745;
    }

    .badge-danger {
        background: #dc3545;
    }

    .badge-warning {
        background: #ffc107;
        color: #222;
    }

    .section-title {
        font-size: 1.25rem;
        color: #007bff;
        border-bottom: 2px solid #007bff33;

        font-weight: bold;
    }

    .flex-row {
        display: flex;
        gap: 24px;
        
        flex-wrap: wrap;
    }

    .card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        padding: 24px 20px;
        flex: 1 1 320px;
        min-width: 320px;
       
    }

    .card-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #2563eb;
  
        letter-spacing: 0.5px;
    }

    .card strong {
        color: #334155;
    }
    .totals-row>div {
        width: 22%;
        display: inline-block;
        text-align: center;
        vertical-align: top;
        background-color: #fff;
        page-break-inside: avoid;
    }

    .totals-row strong {
        color: #64748b;
        font-weight: 600;

    }

    .totals-row .highlight {
        font-size: 1.13rem;
        font-weight: 700;
        background: #f1f5fd;
        border-radius: 6px;
        display: inline-block;
    }

    .card-section {
        display: inline-block;
        width: 48%;
        vertical-align: top;
        margin-right: 2%;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #f8fafc;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.04);
        margin-bottom: 0;
    }

    .table th,
    .table td {
        padding: 0.7rem 1rem;
        text-align: center;
        font-size: 1rem;
    }

    .table th {
        background: #2563eb;
        color: #fff;
        font-weight: 600;
        border-bottom: 2px solid #1e40af;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .table tr:nth-child(even) td {
        background: #e0e7ef;
    }
    /* NOVO: Grid de produtos com 4 colunas */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
       
     

    }

    @media (max-width: 900px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
    }

    .product-card {
        width: 22%;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin: 1%;
        vertical-align: top;
        background-color: #fff;
        page-break-inside: avoid;
    }

    .product-card img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background: #f0f0f0;
    }

    .product-card .card-body {
        padding: 12px 10px 10px 10px;
        flex: 1;
        display: flex;
        flex-direction: column;
               text-align: center;
    }

    .product-card .card-title {
        font-size: 1rem;
        margin-bottom: 4px;
        color: #007bff;
        font-weight: bold;
        text-align: center;
    }

    .product-card .card-text {
        font-size: 0.95rem;
        color: #555;
        margin-bottom: 6px;
        text-align: center;
    }

    .product-card div {
        margin-bottom: 2px;
    }



    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Relatório da Venda</h1>
        </div>
        <div class="flex-row">
            <div class="card">
                <div class="card-title">Detalhes da Venda</div>
                <div class="totals-row">
                    <div>
                        <strong>Nome:</strong><br>
                        <span>{{ $sale->client->name }}</span>
                    </div>
                    <div>
                        <strong>Total da Venda:</strong><br>
                        <span class="highlight">R$ {{ number_format($sale->total_price, 2, ',', '.') }}</span>
                    </div>
                    <div>
                        <strong>Total Pago:</strong><br>
                        <span class="highlight" style="color:#2563eb;"> R$
                            {{ number_format($sale->amount_paid, 2, ',', '.') }}</span>
                    </div>
                    <div>
                        <strong>Restante:</strong><br>
                        <span class="highlight" style="color:#dc2626;">R$
                            {{ number_format($sale->total_price - $sale->amount_paid, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-title">Histórico de Pagamentos</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Valor</th>
                            <th>Método</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sale->payments as $payment)
                        <tr>
                            <td>           {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/y') }}</td>
                            <td style="color:#0d9488;font-weight:600;">R$
                                {{ number_format($payment->amount_paid, 2, ',', '.') }}</td>
                            <td>{{ $payment->payment_method }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:#888;">Nenhum pagamento registrado</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="section-title">Produtos da Venda</div>
        <div class="product-grid">
            @foreach($sale->saleItems as $item)
            <div class="product-card">
                @php
                $imagePath = public_path('storage/products/' . $item->product->image);
                $imageData = null;
                $extension = null;
                if (file_exists($imagePath)) {
                $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
                $imageData = base64_encode(file_get_contents($imagePath));
                }
                $imageSrc = $imageData
                ? 'data:image/' . ($extension ?? 'jpeg') . ';base64,' . $imageData
                : asset('images/default-product.png');
                @endphp
                <img src="{{ $imageSrc }}" alt="{{ $item->product->name }}">
                <div class="card-body">
                    <div class="card-title">{{ $item->product->name }}</div>

                    <div><strong>Quantidade:</strong> {{ $item->quantity }}</div>
                    <div><strong>Preço (uni):</strong> R$ {{ number_format($item->price_sale, 2, ',', '.') }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>

</html>