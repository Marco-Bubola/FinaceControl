<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório da Venda</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f4f4f9;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            color: #007bff;
            margin: 0;
        }

        .header p {
            font-size: 14px;
            color: #555;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            color: #007bff;
            border-bottom: 1px solid #007bff;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #007bff;
            color: #fff;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            color: #fff;
            font-size: 12px;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .product-grid {
            width: 100%;
        }

        .product-card {
            display: inline-block;
            width: 32%;
            margin-right: 1%;
            margin-bottom: 20px;
            vertical-align: top;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            text-align: center;
        }

        .product-card:nth-child(3n) {
            margin-right: 0;
        }

        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .product-card .card-body {
            padding: 10px;
        }

        .product-card .card-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-card .card-text {
            font-size: 12px;
            color: #555;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Relatório da Venda</h1>
            <p>Detalhes completos da venda e seus produtos</p>
        </div>

        <!-- Informações da Venda e Histórico de Pagamentos -->
        <div class="section">
            <table class="table">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <div class="section-title">Informações da Venda</div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><strong>Cliente:</strong></td>
                                    <td>{{ $sale->client->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $sale->client->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telefone:</strong></td>
                                    <td>{{ $sale->client->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Endereço:</strong></td>
                                    <td>{{ $sale->client->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $sale->status == 'Paga' ? 'success' : 'danger' }}">
                                            {{ $sale->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Pago:</strong></td>
                                    <td>R$ {{ number_format($sale->amount_paid, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total da Venda:</strong></td>
                                    <td>R$ {{ number_format($sale->total_price, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Restante:</strong></td>
                                    <td>R$ {{ number_format($sale->total_price - $sale->amount_paid, 2, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top;">
                        <div class="section-title">Histórico de Pagamentos</div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Método</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                    <td>R$ {{ number_format($payment->amount_paid, 2, ',', '.') }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Produtos da Venda -->
        <div class="section">
            <div class="section-title">Produtos da Venda</div>
            <div class="product-grid">
                @foreach($sale->saleItems as $item)
                <div class="product-card">
                    @php
                        $imagePath = public_path('storage/products/' . $item->product->image);
                        $imageData = null;

                        if (file_exists($imagePath)) {
                            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
                            if ($extension === 'webp' && function_exists('imagecreatefromwebp')) {
                                $imageData = base64_encode(file_get_contents($imagePath));
                            } elseif ($extension !== 'webp') {
                                $imageData = base64_encode(file_get_contents($imagePath));
                            }
                        }

                        $imageSrc = $imageData ? 'data:image/' . ($extension ?? 'jpeg') . ';base64,' . $imageData : asset('images/default-product.png');
                    @endphp
                    <img src="{{ $imageSrc }}" alt="{{ $item->product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->product->name }}</h5>
                        <p class="card-text">{{ $item->product->description }}</p>
                        <p><strong>Quantidade:</strong> {{ $item->quantity }}</p>
                        <p><strong>Preço Unitário:</strong> R$ {{ number_format($item->price_sale, 2, ',', '.') }}</p>
                        <p><strong>Preço Total:</strong> R$ {{ number_format($item->price_sale * $item->quantity, 2, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>
