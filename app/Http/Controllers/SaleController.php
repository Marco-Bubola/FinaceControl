<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Client;
use App\Models\SalePayment as ModelsSalePayment;
use Illuminate\Http\Request;
use Log;
use SalePayment;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\VendaParcela;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();  // Ou Auth::id()

        // Inicializa a consulta para vendas, filtrando por user_id
        $sales = Sale::where('sales.user_id', $userId); // Adicionado o prefixo 'sales.' para evitar ambiguidade

        // Filtro de pesquisa por nome do cliente
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            // Adiciona o filtro para o nome do cliente
            $sales->whereHas('client', function ($query) use ($searchTerm) {
                // Verifica se o nome do cliente contém o termo de pesquisa, ignorando o case
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtro de status da venda
        if ($request->has('status') && $request->status != '') {
            $sales->where('status', $request->status);
        }

        // Filtro de ordenação por data ou outros campos
        if ($request->has('filter') && $request->filter != '') {
            switch ($request->filter) {
                case 'created_at':
                    $sales->orderBy('sales.created_at', 'desc'); // Adicionado o prefixo 'sales.'
                    break;
                case 'updated_at':
                    $sales->orderBy('sales.updated_at', 'desc'); // Adicionado o prefixo 'sales.'
                    break;
                case 'name_asc':
                    $sales->join('clients', 'sales.client_id', '=', 'clients.id')
                          ->orderBy('clients.name', 'asc');
                    break;
                case 'name_desc':
                    $sales->join('clients', 'sales.client_id', '=', 'clients.id')
                          ->orderBy('clients.name', 'desc');
                    break;
                case 'price_asc':
                    $sales->orderBy('sales.total_price', 'asc'); // Adicionado o prefixo 'sales.'
                    break;
                case 'price_desc':
                    $sales->orderBy('sales.total_price', 'desc'); // Adicionado o prefixo 'sales.'
                    break;
            }
        }

        // Ordenar por status: "pendente" e outros primeiro, "pago" por último
        $sales->orderByRaw("CASE WHEN status = 'pago' THEN 1 ELSE 0 END");

        // Controle do número de itens por página (valor padrão é 4)
        $perPage = $request->input('per_page', 4);  // Pega o valor de 'per_page' ou usa 4 como padrão

        // Se perPage for -1, retorna todos os resultados sem paginação
        if ($perPage == -1) {
            $sales = $sales->with(['client', 'saleItems.product', 'parcelasVenda'])->get();
            $isPaginated = false;
        } else {
            $sales = $sales->with(['client', 'saleItems.product', 'parcelasVenda'])->paginate($perPage);
            $isPaginated = true;
        }

        $products = Product::where('user_id', $userId)->get(); // Filtra os produtos associados ao usuário

        $clients = Client::where('user_id', $userId)->get(); // Filtra os clientes associados ao usuário

        // Passar vendas, clientes e produtos para a view
        return view('sales.index', compact('sales', 'clients', 'products', 'perPage', 'isPaginated'));
    }




    // Exibir o formulário para adicionar uma nova venda
    public function create()
    {
        // Carregar todos os clientes e produtos para exibição no modal
        $clients = Client::all();
        $products = Product::all();
        return view('sales.index', compact('clients', 'products'));
    }
    public function addProduct(Request $request, Sale $sale)
    {
        // Verifique o conteúdo do request
        $request->validate([
            'products' => 'required|array', // Produtos devem ser um array
            'products.*.product_id' => 'required|exists:products,id', // Verificar se o produto existe
            'products.*.quantity' => 'required|integer|min:1', // Quantidade deve ser um número positivo
            'products.*.price_sale' => 'required|numeric|min:0', // Preço de venda deve ser válido
        ]);

        // Processar os produtos selecionados
        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);

            // Verificar se o estoque é suficiente
            if ($product->stock_quantity < $productData['quantity']) {
                return redirect()->back()->withErrors(['quantity' => 'Quantidade insuficiente no estoque.']);
            }

            // Adicionar o item à venda
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'price' => $productData['price'], // Usando o preço original do produto
                'price_sale' => $productData['price_sale'], // Usando o preço de venda
            ]);

            // Atualizar o estoque do produto
            $product->stock_quantity -= $productData['quantity'];
            $product->save();
        }

        // Atualizar o preço total da venda
        $total_price = $sale->saleItems->sum(function ($item) {
            return $item->quantity * $item->price_sale; // Calculando com o preço de venda
        });

        $sale->update(['total_price' => $total_price]);

        // Verificar a origem da requisição para o redirecionamento
        $redirectTo = request()->input('from') === 'show'
            ? route('sales.show', $sale->id) // Redireciona para a página de show
            : route('sales.index'); // Redireciona para a página de index

        return redirect($redirectTo)->with('success', 'Produto(s) adicionado(s) à venda!');
    }

    public function destroySaleItem($id)
    {
        // Localiza o item da venda a ser removido ou aborta se não existir
        $saleItem = SaleItem::findOrFail($id);

        // Atualiza o estoque do produto, somando de volta a quantidade removida
        $product = Product::find($saleItem->product_id);
        if ($product) {
            $product->stock_quantity += $saleItem->quantity;
            $product->save();
        }

        // Atualiza o preço total da venda subtraindo o valor deste item
        $sale = Sale::find($saleItem->sale_id);
        if ($sale) {
            $sale->total_price -= $saleItem->price_sale * $saleItem->quantity;
            $sale->save();
        }

        // Exclui o item da venda
        $saleItem->delete();

        // Verificar a origem da requisição para o redirecionamento
        $redirectTo = request()->input('from') === 'show'
            ? route('sales.show', $sale->id) // Redireciona para a página de show
            : route('sales.index'); // Redireciona para a página de index

        return redirect($redirectTo)->with('success', 'Produto removido da venda com sucesso!');
    }


    public function store(Request $request)
    {
        // Validação dos campos
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'products' => 'required|string', // Espera-se que 'products' seja uma string JSON
            'tipo_pagamento' => 'required|in:a_vista,parcelado',
            'parcelas' => 'nullable|integer|min:1',
        ]);

        $products = json_decode($data['products'], true);

        foreach ($products as $product) {
            $this->validate($request, [
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price_sale' => 'required|numeric|min:0',
            ]);
        }

        $total_price = 0;
        foreach ($products as $product) {
            if (isset($product['product_id']) && isset($product['quantity']) && $product['quantity'] > 0) {
                $productModel = Product::find($product['product_id']);
                if (!$productModel) {
                    return redirect()->route('sales.index')->with('error', 'Produto não encontrado.');
                }
                $item_price = $productModel->price_sale * $product['quantity'];
                $total_price += $item_price;
            }
        }

        $sale = Sale::create([
            'client_id' => $data['client_id'],
            'user_id' => auth()->id(),
            'status' => 'pendente',
            'total_price' => $total_price,
            'tipo_pagamento' => $data['tipo_pagamento'],
            'parcelas' => $data['tipo_pagamento'] === 'parcelado' ? ($data['parcelas'] ?? 1) : 1,
        ]);

        foreach ($products as $product) {
            if (isset($product['product_id']) && isset($product['quantity']) && $product['quantity'] > 0) {
                $productModel = Product::find($product['product_id']);
                if (!$productModel) {
                    return redirect()->route('sales.index')->with('error', 'Produto não encontrado ao registrar item.');
                }
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $productModel->price,
                    'price_sale' => $product['price_sale'],
                ]);
                $productModel->stock_quantity -= $product['quantity'];
                $productModel->save();
            }
        }

        // Gerar parcelas se for parcelado
        if ($sale->tipo_pagamento === 'parcelado' && $sale->parcelas > 1) {
            $this->gerarParcelasVenda($sale);
        }

        return redirect()->route('sales.index')->with('success', 'Venda registrada com sucesso!');
    }

    private function gerarParcelasVenda($sale)
    {
        $valorParcela = round($sale->total_price / $sale->parcelas, 2);
        $dataPrimeira = now();
        for ($i = 1; $i <= $sale->parcelas; $i++) {
            $dataVencimento = $dataPrimeira->copy()->addMonths($i - 1);
            VendaParcela::create([
                'sale_id' => $sale->id,
                'numero_parcela' => $i,
                'valor' => $valorParcela,
                'data_vencimento' => $dataVencimento->format('Y-m-d'),
                'status' => 'pendente',
            ]);
        }
    }

    public function addPayment(Request $request, $saleId)
{
    $sale = Sale::findOrFail($saleId);

  
    // Validação dos dados
    $validated = $request->validate([
        'amount_paid' => 'required|array|min:1',
        'amount_paid.*' => 'required|string|max:255',
        'payment_method' => 'required|array|min:1',
        'payment_method.*' => 'required|string',
        'payment_date' => 'required|array|min:1',
        'payment_date.*' => 'required|date',
    ]);

    foreach ($request->input('amount_paid') as $key => $amountPaid) {
        $paymentMethod = $request->input('payment_method')[$key];
        $paymentDate = $request->input('payment_date')[$key];

        ModelsSalePayment::create([
            'sale_id' => $sale->id,
            'amount_paid' => $amountPaid,
            'payment_method' => $paymentMethod,
            'payment_date' => $paymentDate,
        ]);
    }

    $totalPaid = ModelsSalePayment::where('sale_id', $sale->id)->sum('amount_paid');
    $sale->amount_paid = $totalPaid;

    // Verifica se o total pago é igual ao total_price e atualiza o status
    if ($totalPaid >= $sale->total_price) {
        $sale->status = 'pago';
    }
    $sale->save();

    $redirectTo = request()->input('from') === 'show'
        ? route('sales.show', $sale->id)
        : route('sales.index');

    return redirect($redirectTo)->with('success', 'Pagamentos adicionados com sucesso!');
}


    public function show($id)
    {
        $sale = Sale::with(['saleItems.product', 'client', 'payments'])->findOrFail($id);
        $products = Product::all();
        $parcelas = VendaParcela::where('sale_id', $sale->id)->orderBy('numero_parcela')->get();
        return view('sales.show', compact('sale', 'products', 'parcelas'));
    }
    public function updateSaleItem(Request $request, Sale $sale, SaleItem $item)
    {
        // Validação dos dados
        $data = $request->validate([
            'price_sale' => 'required|numeric|min:0',
            'quantity'   => 'required|integer|min:1',
        ]);

        // Atualiza o item da venda
        $item->price_sale = $data['price_sale'];
        $item->quantity = $data['quantity'];
        $item->save();

        // (Opcional) Atualize o total da venda se necessário
        // Exemplo: recalcular o total a partir de todos os itens da venda
        $sale->total_price = $sale->saleItems->sum(function ($saleItem) {
            return $saleItem->price_sale * $saleItem->quantity;
        });
        $sale->save();

        return redirect()->route('sales.show', $sale->id)->with('success', 'Produto da venda atualizado com sucesso!');
    }

    public function updatePayment(Request $request, $saleId, $paymentId)
    {
        $sale = Sale::findOrFail($saleId);
        $payment = ModelsSalePayment::findOrFail($paymentId);

        // Validando os dados de entrada
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        // Atualizando os dados do pagamento
        $payment->amount_paid = $request->input('amount_paid');
        $payment->payment_method = $request->input('payment_method');
        $payment->payment_date = $request->input('payment_date');
        $payment->save();

        // Atualizando o total pago na venda
        $totalPaid = ModelsSalePayment::where('sale_id', $sale->id)->sum('amount_paid');
        $sale->amount_paid = $totalPaid;

        // Verifica se o total pago é igual ao total_price e atualiza o status
        if ($totalPaid >= $sale->total_price) {
            $sale->status = 'pago';
        } else {
            $sale->status = 'pendente'; 
        }
        $sale->save();

        // Verificar a origem da requisição para o redirecionamento
        $redirectTo = request()->input('from') === 'show'
            ? route('sales.show', $sale->id) // Redireciona para a página de show
            : route('sales.index'); // Redireciona para a página de index

        return redirect($redirectTo)->with('success', 'Pagamento atualizado com sucesso!');
    }

    public function deletePayment($saleId, $paymentId)
    {
        $sale = Sale::findOrFail($saleId);
        $payment = ModelsSalePayment::findOrFail($paymentId);

        // Excluindo o pagamento
        $payment->delete();

        // Atualizando o total pago na venda
        $totalPaid = ModelsSalePayment::where('sale_id', $sale->id)->sum('amount_paid');
        $sale->amount_paid = $totalPaid;

        // Verifica se o total pago é igual ao total_price e atualiza o status
        if ($totalPaid >= $sale->total_price) {
            $sale->status = 'pago';
        } else {
            $sale->status = 'pendente'; 
        }
        $sale->save();

        // Verificar a origem da requisição para o redirecionamento
        $redirectTo = request()->input('from') === 'show'
            ? route('sales.show', $sale->id) // Redireciona para a página de show
            : route('sales.index'); // Redireciona para a página de index

        return redirect($redirectTo)->with('success', 'Pagamento excluído com sucesso!');
    }

    public function updateStock(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $product->stock_quantity -= $request->quantity; // Subtrai a quantidade da venda
        $product->save();

        return response()->json(['success' => true]);
    }



    // Exibir o formulário de edição de uma venda
    public function edit($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id); // Carrega a venda com os itens
        $clients = Client::all();
        $products = Product::all();

        return view('sales.index', compact('sale', 'clients', 'products'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id', 
            'products' => 'required|array', 
            'products.*.product_id' => 'required|exists:products,id', 
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price_sale' => 'required|numeric|min:0', 
        ]);

        $sale = Sale::findOrFail($id);
        $sale->client_id = $request->client_id;
        $sale->user_id = auth()->id();
        $sale->status = 'pendente'; 
        $sale->save();

        $total_price = 0;

        // Deletando itens antigos da venda
        $sale->saleItems()->delete();

        // Registrando novos itens da venda
        foreach ($request->products as $product) {
            $productModel = Product::find($product['product_id']);
            $item_price = $product['price_sale'] * $product['quantity']; // Preço total do item, agora usando o price_sale do formulário

            // Atualiza o preço total
            $total_price += $item_price;

            // Criar os itens da venda
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $productModel->price, // Preço unitário do produto
                'price_sale' => $product['price_sale'], // Usando o preço de venda atualizado
            ]);
        }

        // Atualizar o preço total da venda
        $sale->update(['total_price' => $total_price]);

        // Verificar a origem da requisição para o redirecionamento
        $redirectTo = request()->input('from') === 'show'
            ? route('sales.show', $sale->id) // Redireciona para a página de show
            : route('sales.index'); // Redireciona para a página de index

        return redirect($redirectTo)->with('success', 'Venda atualizada com sucesso!');
    }

    // Excluir a venda
    public function destroy($id)
    {
        // Encontrar a venda
        $sale = Sale::findOrFail($id);

        // Restaurar o estoque dos produtos
        foreach ($sale->saleItems as $saleItem) {
            $product = Product::find($saleItem->product_id);
            $product->stock_quantity += $saleItem->quantity; // Restaura a quantidade ao estoque
            $product->save();
        }

        // Excluir os itens da venda
        $sale->saleItems()->delete();

        // Excluir a venda
        $sale->delete();

        // Verificar a origem da requisição para o redirecionamento
        $redirectTo = request()->input('from') === 'show'
            ? route('sales.show', $sale->id) // Redireciona para a página de show
            : route('sales.index'); // Redireciona para a página de index

        return redirect($redirectTo)->with('success', 'Venda excluída com sucesso!');
    }

    public function getClientData($id)
    {
        $client = Client::findOrFail($id); // Garante que o cliente existe

        return response()->json([
            'name' => $client->name,
            'email' => $client->email,
            'phone' => $client->phone,
            'address' => $client->address,
            'created_at' => $client->created_at->format('d/m/Y'), // Formata a data
        ]);
    }

    public function export($id)
    {
        $sale = Sale::with(['client', 'payments', 'saleItems.product'])->findOrFail($id);

        // Renderiza apenas a parte específica da view
        $html = view('sales.partials.export', compact('sale'))->render();

        // Configurações do DOMPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Retorna o PDF para download
        return $dompdf->stream("relatorio_venda_{$sale->client->name}.pdf");
    }

    public function pagarParcela(Request $request, $parcelaId)
    {
        $parcela = VendaParcela::findOrFail($parcelaId);
        $request->validate([
            'payment_date' => 'required|date',
            'valor_pago' => 'required|numeric|min:0.01',
        ]);
        // Atualiza status da parcela
        $parcela->status = 'paga';
        $parcela->pago_em = $request->payment_date;
        $parcela->save();
        // Registra pagamento na tabela SalePayment
        \App\Models\SalePayment::create([
            'sale_id' => $parcela->sale_id,
            'amount_paid' => $request->valor_pago,
            'payment_method' => 'parcela',
            'payment_date' => $request->payment_date,
        ]);
        // Atualiza o total pago na venda
        $sale = $parcela->sale;
        $totalPaid = \App\Models\SalePayment::where('sale_id', $sale->id)->sum('amount_paid');
        $sale->amount_paid = $totalPaid;
        // Atualiza status da venda se necessário
        if ($totalPaid >= $sale->total_price) {
            $sale->status = 'pago';
        } else {
            $sale->status = 'pendente';
        }
        $sale->save();
        return back()->with('success', 'Parcela registrada como paga!');
    }
}