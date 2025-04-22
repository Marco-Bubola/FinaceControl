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

        // Inicializa a variável para rastrear se um filtro foi aplicado
        $filterApplied = false;

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

        // Filtro por data de criação, atualização ou outros
        if ($request->has('filter') && $request->filter != '') {
            $filterApplied = true;
            switch ($request->filter) {
                case 'created_at':
                    $products->orderBy('created_at', 'desc');
                    break;
                case 'updated_at':
                    $products->orderBy('updated_at', 'desc');
                    break;
                case 'name_asc':
                    $products->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $products->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $products->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $products->orderBy('price', 'desc');
                    break;
            }
        }

        // Ordenar produtos fora de estoque para o final apenas se nenhum filtro for aplicado
        if (!$filterApplied) {
            $products->orderByRaw('stock_quantity > 0 DESC');
        }

        // Controle do número de itens por página
        $perPage = $request->input('per_page', 12);
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
        // Validação do formulário
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'price' => 'required|numeric',
            'price_sale' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:category,id_category',
            'product_code' => 'required', // Removemos 'unique' para permitir múltiplos com o mesmo código
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048',
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
                'status' => $request->status ?? 'active',
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
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'price' => 'required|numeric',
            'price_sale' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:category,id_category',
            'product_code' => 'required', // Ignora o produto atual
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048',
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
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }
}
