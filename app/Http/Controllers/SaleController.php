<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Client;
use Illuminate\Http\Request;

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

        // Paginando os resultados
        $sales = $sales->with(['client', 'saleItems.product'])->paginate(10);
        $products = Product::where('user_id', $userId)->get(); // Filtra os produtos associados ao usuário

        // Carregar clientes associados ao usuário logado para o filtro
        $clients = Client::where('user_id', $userId)->get(); // Filtra os clientes associados ao usuário

        // Passar vendas e clientes para a view
        return view('sales.index', compact('sales', 'clients', 'products'));
    }

    // Em ProductController.php// Em ProductController.php

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

    // Método para adicionar um produto a uma venda
    public function addProduct(Request $request, Sale $sale)
    {
        // Validação dos dados recebidos
        $request->validate([
            'product_id' => 'required|exists:products,id', // Produto deve existir
            'quantity' => 'required|integer|min:1', // A quantidade deve ser um número positivo
        ]);

        // Obter o produto e verificar o estoque
        $product = Product::find($request->product_id);
        if ($product->stock_quantity < $request->quantity) {
            // Se o estoque não for suficiente, retornar com erro
            return redirect()->back()->withErrors(['quantity' => 'Quantidade insuficiente no estoque.']);
        }

        // Calcular o valor total do item (preço * quantidade)
        $total_price_item = $product->price * $request->quantity;

        // Adicionar o item à venda
        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $product->price, // Preço unitário
        ]);

        // Atualizar o preço total da venda
        $total_price = $sale->saleItems->sum(function ($item) {
            return $item->quantity * $item->price; // Multiplicar a quantidade pelo preço unitário
        });

        // Atualizar o estoque do produto
        $product->stock_quantity -= $request->quantity;
        $product->save(); // Salvar a alteração no estoque

        // Atualizar o preço total da venda
        $sale->update(['total_price' => $total_price]);

        // Retornar para a página de vendas com uma mensagem de sucesso
        return redirect()->route('sales.index')->with('success', 'Produto adicionado à venda!');
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

    // Atualizar a venda
    public function update(Request $request, $id)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',  // Verifica se o cliente existe
            'products' => 'required|array', // Verifica se os produtos foram selecionados
            'products.*.product_id' => 'required|exists:products,id', // Verifica se o produto existe
            'products.*.quantity' => 'required|integer|min:1', // Verifica se a quantidade é válida
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
            $item_price = $productModel->price * $product['quantity']; // Preço total do item

            // Atualiza o preço total
            $total_price += $item_price;

            // Criar os itens da venda
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $productModel->price, // Preço unitário do produto
                'price_sale' => $productModel->price_sale,
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
