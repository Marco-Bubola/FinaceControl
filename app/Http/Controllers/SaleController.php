<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Client;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    // Exibir todas as vendas
    public function index()
    {
        // Carregar vendas, clientes e itens da venda (com os produtos associados)
        $sales = Sale::with(['client', 'saleItems.product'])->get();
        $clients = Client::all();
        $products = Product::all();
        return view('sales.index', compact('sales', 'clients', 'products'));
    }

    // Exibir o formulário para adicionar uma nova venda
    public function create()
    {
        // Carregar todos os clientes e produtos para exibição no modal
        $clients = Client::all();
        $products = Product::all();  // Todos os produtos
        return view('sales.index', compact('clients', 'products'));
    }

    // Método para adicionar um produto a uma venda
    public function addProduct(Request $request, Sale $sale)
    {
        // Validação dos dados recebidos
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Obter o produto e calcular o valor total do item
        $product = Product::find($request->product_id);
        $total_price_item = $product->price * $request->quantity;

        // Adicionar o item à venda
        $saleItem = SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $product->price, // Preço unitário
        ]);

        // Atualizar o preço total da venda
        $total_price = $sale->saleItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        $sale->update(['total_price' => $total_price]);

        // Retornar para a página de vendas com uma mensagem de sucesso
        return redirect()->route('sales.index')->with('success', 'Produto adicionado à venda!');
    }
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',   // Verificando se o cliente existe
            'products' => 'required|array', // Verificando se os produtos foram selecionados
            'products.*.product_id' => 'required|exists:products,id', // Verificando se o produto existe
            'products.*.quantity' => 'required|integer|min:1', // Verificando se a quantidade é válida
        ]);

        // Inicializando o preço total
        $total_price = 0;

        // Calcular o preço total com base nos produtos selecionados
        foreach ($request->products as $product) {
            $productModel = Product::find($product['product_id']);
            $item_price = $productModel->price * $product['quantity']; // Preço total do item
            $total_price += $item_price;
        }

        // Criar a venda com o preço total calculado
        $sale = Sale::create([
            'client_id' => $request->client_id,
            'user_id' => auth()->id(),
            'status' => 'pending', // A venda começa com o status 'pending'
            'total_price' => $total_price, // Adicionando o preço total ao criar a venda
        ]);

        // Registrar os itens da venda
        foreach ($request->products as $product) {
            $productModel = Product::find($product['product_id']);
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $productModel->price, // Preço unitário do produto
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Venda registrada com sucesso!');
    }


    // Exibir o formulário de edição de uma venda
    public function edit($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id); // Carrega a venda com os itens
        $clients = Client::all();
        $products = Product::all();

        return view('sales.edit', compact('sale', 'clients', 'products'));
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
            ]);
        }

        // Atualizar o preço total da venda
        $sale->update(['total_price' => $total_price]);

        return redirect()->route('sales.index')->with('success', 'Venda atualizada com sucesso!');
    }

    // Excluir a venda
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->saleItems()->delete(); // Deleta os itens relacionados
        $sale->delete(); // Deleta a venda

        return redirect()->route('sales.index')->with('success', 'Venda excluída com sucesso!');
    }
}
