<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Client;
use App\Models\SalePayment as ModelsSalePayment;
use Illuminate\Http\Request;
use SalePayment;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        // Obtém o ID do usuário logado
        $userId = auth()->id();  // Ou Auth::id()

        // Inicializa a consulta para vendas, filtrando por user_id
        $sales = Sale::where('user_id', $userId);

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
            if ($request->filter == 'created_at') {
                $sales->orderBy('created_at', 'desc');
            } elseif ($request->filter == 'updated_at') {
                $sales->orderBy('updated_at', 'desc');
            } elseif ($request->filter == 'name_asc') {
                $sales->orderBy('client.name', 'asc'); // Ordenar pelo nome do cliente A-Z
            } elseif ($request->filter == 'name_desc') {
                $sales->orderBy('client.name', 'desc'); // Ordenar pelo nome do cliente Z-A
            }
        }

        // Controle do número de itens por página (valor padrão é 10)
        $perPage = $request->input('per_page', 9);  // Pega o valor de 'per_page' ou usa 10 como padrão

        // Paginando os resultados com a quantidade definida
        $sales = $sales->with(['client', 'saleItems.product'])->paginate($perPage);

        $products = Product::where('user_id', $userId)->get(); // Filtra os produtos associados ao usuário

        // Carregar clientes associados ao usuário logado para o filtro
        $clients = Client::where('user_id', $userId)->get(); // Filtra os clientes associados ao usuário

        // Passar vendas, clientes e produtos para a view
        return view('sales.index', compact('sales', 'clients', 'products', 'perPage'));
    }


    public function search(Request $request)
    {
        // Pegue a string da pesquisa
        $searchTerm = $request->get('query');

        // Encontre os produtos que correspondem ao termo de pesquisa
        $products = Product::where('name', 'like', "%{$searchTerm}%")
            ->orWhere('product_code', 'like', "%{$searchTerm}%")
            ->get();

        // Retorne os produtos encontrados como uma resposta JSON
        return response()->json($products);
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

        // Verificar se veio da página index ou show
        $redirectTo = $request->input('from') === 'show'
            ? route('sales.show', $sale->id) // Redireciona para a página de show
            : route('sales.index'); // Redireciona para a página de index

        return redirect($redirectTo)->with('success', 'Produto(s) adicionado(s) à venda!');
    }



    public function store(Request $request)
    {
        // Validação dos campos
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'products' => 'required|string', // Espera-se que 'products' seja uma string JSON
        ]);

        // Decodificando os dados dos produtos para um array
        $products = json_decode($data['products'], true);

        // Validação dos produtos depois de decodificados
        foreach ($products as $product) {
            $this->validate($request, [
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price_sale' => 'required|numeric|min:0',
            ]);
        }

        // Inicializando o preço total
        $total_price = 0;

        // Calcular o preço total com base nos produtos selecionados
        foreach ($products as $product) {
            if (isset($product['product_id']) && isset($product['quantity']) && $product['quantity'] > 0) {
                $productModel = Product::find($product['product_id']);
                // Verifica se o produto existe antes de acessar suas propriedades
                if (!$productModel) {
                    return redirect()->route('sales.index')->with('error', 'Produto não encontrado.');
                }

                $item_price = $productModel->price * $product['quantity']; // Preço total do item
                $total_price += $item_price;
            }
        }

        // Criar a venda com o preço total calculado
        $sale = Sale::create([
            'client_id' => $data['client_id'],
            'user_id' => auth()->id(),
            'status' => 'pending', // A venda começa com o status 'pending'
            'total_price' => $total_price, // Adicionando o preço total ao criar a venda
        ]);

        // Registrar os itens da venda
        foreach ($products as $product) {
            if (isset($product['product_id']) && isset($product['quantity']) && $product['quantity'] > 0) {
                $productModel = Product::find($product['product_id']);
                // Verifica se o produto existe antes de criar o SaleItem
                if (!$productModel) {
                    return redirect()->route('sales.index')->with('error', 'Produto não encontrado ao registrar item.');
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $productModel->price, // Preço unitário do produto
                    'price_sale' => $product['price_sale'], // Preço de venda do produto
                ]);

                // Atualiza o estoque do produto
                $productModel->stock_quantity -= $product['quantity']; // Subtrai a quantidade do estoque
                $productModel->save();
            }
        }

        return redirect()->route('sales.index')->with('success', 'Venda registrada com sucesso!');
    }
    public function addPayment(Request $request, $saleId)
    {
        $sale = Sale::findOrFail($saleId);

        // Validando que o valor pago, método de pagamento e a data são enviados corretamente
        $validated = $request->validate([
            'amount_paid' => 'required|array|min:1',
            'amount_paid.*' => 'required|numeric|min:0.01', // Garantir que os valores sejam números positivos
            'payment_method' => 'required|array|min:1',
            'payment_method.*' => 'required|string', // O método de pagamento deve ser uma string
            'payment_date' => 'required|array|min:1',
            'payment_date.*' => 'required|date', // Validando a data
        ]);

        // Percorrer os pagamentos recebidos e salvar na tabela sale_payments
        foreach ($request->input('amount_paid') as $key => $amountPaid) {
            $paymentMethod = $request->input('payment_method')[$key];
            $paymentDate = $request->input('payment_date')[$key]; // Adicionando a data de pagamento

            // Criar o registro de pagamento com a data
            ModelsSalePayment::create([
                'sale_id' => $sale->id,
                'amount_paid' => $amountPaid,
                'payment_method' => $paymentMethod,
                'payment_date' => $paymentDate, // Salvando a data do pagamento
            ]);
        }

        // Atualizar o total pago na venda
        $totalPaid = ModelsSalePayment::where('sale_id', $sale->id)->sum('amount_paid');
        $sale->amount_paid = $totalPaid;
        $sale->save();

        // Verificar a origem da requisição para o redirecionamento
        $redirectTo = $request->input('from') === 'show'
            ? route('sales.show', $sale->id) // Redireciona para a página de show
            : route('sales.index'); // Redireciona para a página de index

        return redirect($redirectTo)->with('success', 'Pagamentos adicionados com sucesso!');
    }

    public function show($id)
    {
        $sale = Sale::with(['saleItems.product', 'client', 'payments'])->findOrFail($id);
        $products = Product::all(); // ou outra lógica para pegar os produtos
        return view('sales.show', compact('sale', 'products'));
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
        $sale->save();

        // Verificar a origem da requisição para o redirecionamento
        $redirectTo = $request->input('from') === 'show'
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
            'client_id' => 'required|exists:clients,id',  // Verifica se o cliente existe
            'products' => 'required|array', // Verifica se os produtos foram selecionados
            'products.*.product_id' => 'required|exists:products,id', // Verifica se o produto existe
            'products.*.quantity' => 'required|integer|min:1', // Verifica se a quantidade é válida
            'products.*.price_sale' => 'required|numeric|min:0', // Verifica se o preço de venda é válido
        ]);

        $sale = Sale::findOrFail($id);
        $sale->client_id = $request->client_id;
        $sale->user_id = auth()->id();
        $sale->status = 'pending'; // Caso queira manter o status 'pending' por padrão
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

        return redirect()->route('sales.index')->with('success', 'Venda atualizada com sucesso!');
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

        return redirect()->route('sales.index')->with('success', 'Venda excluída com sucesso!');
    }
}
