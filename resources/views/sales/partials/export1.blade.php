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
            padding-bottom: 5px;
            margin-bottom: 12px;
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

        .card {
            background-color: #fdfdfd;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #007bff;
        }

        .card-section {
            display: inline-block;
            width: 48%;
            vertical-align: top;
            margin-right: 2%;
        }

        .card-section:nth-child(2n) {
            margin-right: 0;
        }

        .product-grid {
            width: 100%;
            font-size: 12px;
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
            height: 100px;
            object-fit: cover;
            border-bottom: 1px solid #ccc;
        }

        .product-card .card-body {
            padding: 8px;
            text-align: center;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Relatório da Venda</h1>
            <p>Detalhes completos da venda e seus produtos</p>
        </div>

        <!-- Informações e Pagamentos -->
        <div class="section">
            <div class="section-title">Informações e Pagamentos</div>

            <div class="card-section">
                <div class="card">
                    <div class="card-title">Informações do Cliente</div>
                    <p><strong>Nome:</strong> {{ $sale->client->name }}</p>
                    <p><strong>Total da Venda:</strong> R$ {{ number_format($sale->total_price, 2, ',', '.') }}</p>

                </div>
            </div>
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
                                        <p><strong>Preço Total:</strong> R$
                                            {{ number_format($item->price_sale * $item->quantity, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>
