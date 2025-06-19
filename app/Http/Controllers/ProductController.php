<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Inicializa a consulta para produtos, filtrando pelo user_id
        $products = Product::where('user_id', $userId);

        // Filtro de pesquisa por nome ou código
        if ($request->has('search') && $request->search != '') {
            $searchTerm = str_replace('.', '', $request->search);
            $products->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere(DB::raw("REPLACE(product_code, '.', '')"), 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtro por categoria
        if ($request->has('category') && $request->category != '') {
            $products->where('category_id', $request->category);
        }

        // Filtro de status
        $statusValidos = ['ativo', 'inativo', 'descontinuado'];
        if ($request->has('status_filtro') && in_array($request->status_filtro, $statusValidos)) {
            $products->where('status', $request->status_filtro);
        }

        // Filtro por tipo (simples ou kit)
        if ($request->has('tipo') && in_array($request->tipo, ['simples', 'kit'])) {
            $products->where('tipo', $request->tipo);
        }

        // Filtro por faixa de preço
        if ($request->has('preco_min') && is_numeric($request->preco_min)) {
            $products->where('price_sale', '>=', $request->preco_min);
        }
        if ($request->has('preco_max') && is_numeric($request->preco_max)) {
            $products->where('price_sale', '<=', $request->preco_max);
        }

        // Filtro por estoque
        if ($request->has('estoque')) {
            if ($request->estoque === 'zerado') {
                $products->where('stock_quantity', 0);
            } elseif ($request->estoque === 'abaixo' && is_numeric($request->estoque_valor)) {
                $products->where('stock_quantity', '<', $request->estoque_valor);
            }
        }

        // Filtro por data de criação
        if ($request->has('data_inicio') && $request->data_inicio) {
            $products->whereDate('created_at', '>=', $request->data_inicio);
        }
        if ($request->has('data_fim') && $request->data_fim) {
            $products->whereDate('created_at', '<=', $request->data_fim);
        }

        // Ordenação
        if ($request->has('ordem') && $request->ordem !== '') {
            switch ($request->ordem) {
                case 'recentes':
                    $products->orderBy('created_at', 'desc');
                    break;
                case 'antigas':
                    $products->orderBy('created_at', 'asc');
                    break;
                case 'az':
                    $products->orderBy('name', 'asc');
                    break;
                case 'za':
                    $products->orderBy('name', 'desc');
                    break;
            }
        } else {
            // Ordenar produtos fora de estoque para o final se nenhuma ordenação for aplicada
            $products->orderByRaw('stock_quantity > 0 DESC');
        }

        // Controle do número de itens por página
        $perPage = $request->input('per_page', 18);
        $products = $products->paginate($perPage)->appends($request->query()); // Preserva os parâmetros na paginação

        // Carregar categorias para o filtro
        $categories = Category::where('user_id', $userId)->get();

        // Retornar a view completa
        return view('products.index', compact('products', 'categories', 'perPage'));
    }


    public function destroy($id)
    {
        // Buscar o produto pelo ID
        $product = Product::findOrFail($id);

        // Verificar se a imagem do produto não é a imagem padrão
        if ($product->image && $product->image !== 'product-placeholder.png') {
            // Excluir a imagem do produto, removendo o arquivo do diretório de armazenamento
            Storage::delete('public/products/' . $product->image);
        }

        // Excluir o produto
        $product->delete();

        // Obter os parâmetros de paginação diretamente da URL ou usar valores padrão
        $page = request('page', 1); // Se não houver, o valor padrão será 1
        $perPage = request('per_page', 10); // Se não houver, o valor padrão será 10

        // Redirecionar para a mesma página com os parâmetros 'per_page' e 'page' preservados
        return redirect()->route('products.index', [
            'page' => $page,
            'per_page' => $perPage  // Mantém o número de itens por página
        ])->with('success', 'Produto excluído com sucesso!');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id); // Encontre o produto ou retorne 404
        return view('products.index', compact('product')); // Retorne a view com os detalhes do produto
    }
    public function store(Request $request)
    {
        // Conversão correta dos valores monetários do formato brasileiro para americano
        $request->merge([
            'price' => str_replace(',', '.', str_replace('.', '', $request->price)),
            'price_sale' => str_replace(',', '.', str_replace('.', '', $request->price_sale)),
        ]);

        // Validação do formulário
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'price' => 'required|numeric',
            'price_sale' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:category,id_category',
            'product_code' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048',
            'status' => 'required|in:ativo,inativo,descontinuado',
        ]);

        // Tratamento da imagem
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/products');
            $imageName = basename($imagePath);
        } else {
            $imageName = null;
        }

        // Verifica se já existe um produto com o mesmo código e preço
        $existingProduct = Product::where('product_code', $request->product_code)
            ->where('price', $request->price)
            ->where('price_sale', $request->price_sale)
            ->first();

        if ($existingProduct) {
            // Se o produto já existe com o mesmo código e preço, apenas atualizamos a quantidade
            $existingProduct->stock_quantity += $request->stock_quantity;
            $existingProduct->save();

            return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
        } else {
            // Se o produto não existe com o mesmo código e preço, cria um novo
            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'price_sale' => $request->price_sale,
                'stock_quantity' => $request->stock_quantity,
                'category_id' => $request->category_id,
                'user_id' => Auth::id(),
                'product_code' => $request->product_code,
                'image' => $imageName,
                'status' => $request->status,
                'tipo' => 'simples',
                'custos_adicionais' => 0,
            ]);

            return redirect()->route('products.index')->with('success', 'Produto adicionado com sucesso!');
        }
    }

    // Método para editar um produto
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();  // Pegar todas as categorias
        return view('products.edit', compact('product', 'categories'));
    }

    // Método para atualizar um produto
    public function update(Request $request, $id)
    {
        // Conversão correta dos valores monetários do formato brasileiro para americano
        $request->merge([
            'price' => str_replace(',', '.', str_replace('.', '', $request->price)),
            'price_sale' => str_replace(',', '.', str_replace('.', '', $request->price_sale)),
        ]);
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'price' => 'required|numeric',
            'price_sale' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:category,id_category',
            'product_code' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048',
            'status' => 'required|in:ativo,inativo,descontinuado',
            // Não validar tipo nem custos_adicionais para edição manual
        ]);

        $product = Product::findOrFail($id);

        // Atualizar imagem se necessário
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/products');
            $imageName = basename($imagePath);
        } else {
            $imageName = $product->image;
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'product_code' => $request->product_code,
            'image' => $imageName,
            'status' => $request->status,
            'tipo' => 'simples',
            'custos_adicionais' => 0,
        ]);

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function storeKit(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'price_sale' => 'required|numeric|min:0',
            'produtos' => 'required|array',
        ]);

        // Cria o produto do tipo kit
        $kit = Product::create([
            'name' => $request->name,
            'description' => null,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
            'stock_quantity' => 0, // O estoque do kit é gerenciado via componentes
            'category_id' => 1, // Pode ser ajustado se desejar selecionar categoria
            'user_id' => auth()->id(),
            'product_code' => 'KIT-' . strtoupper(uniqid()),
            'image' => null,
            'status' => 'ativo',
            'tipo' => 'kit',
            'custos_adicionais' => 0,
        ]);

        // Salva os componentes do kit
        foreach ($request->produtos as $produtoId => $dados) {
            if (isset($dados['selecionado']) && $dados['selecionado'] && isset($dados['quantidade']) && $dados['quantidade'] > 0) {
                \App\Models\ProdutoComponente::create([
                    'kit_produto_id' => $kit->id,
                    'componente_produto_id' => $produtoId,
                    'quantidade' => $dados['quantidade'],
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Kit criado com sucesso!');
    }

    public function updateKit(Request $request, $id)
    {
        // Conversão correta dos valores monetários do formato brasileiro para americano
        $request->merge([
            'price' => str_replace(',', '.', str_replace('.', '', $request->price)),
            'price_sale' => str_replace(',', '.', str_replace('.', '', $request->price_sale)),
        ]);
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'price_sale' => 'required|numeric',
            'produtos' => 'required|array',
        ]);
        $kit = Product::findOrFail($id);
        $kit->update([
            'name' => $request->name,
            'price' => $request->price,
            'price_sale' => $request->price_sale,
        ]);
        // Atualizar componentes do kit
        \App\Models\ProdutoComponente::where('kit_produto_id', $kit->id)->delete();
        foreach ($request->produtos as $produtoId => $dados) {
            if (isset($dados['selecionado']) && $dados['selecionado'] && isset($dados['quantidade']) && $dados['quantidade'] > 0) {
                \App\Models\ProdutoComponente::create([
                    'kit_produto_id' => $kit->id,
                    'componente_produto_id' => $produtoId,
                    'quantidade' => $dados['quantidade'],
                ]);
            }
        }
        return redirect()->route('products.index')->with('success', 'Kit atualizado com sucesso!');
    }
}
