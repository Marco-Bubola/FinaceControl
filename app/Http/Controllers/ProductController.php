<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();  // Ou Auth::id()

        // Inicializa a consulta para produtos, filtrando pelo user_id
        $products = Product::where('user_id', $userId);

        // Filtro de pesquisa por nome ou código
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $products->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('product_code', 'like', '%' . $searchTerm . '%');  // Filtrando por nome ou código do produto
            });
        }

        // Filtro por categoria, mas apenas as categorias do usuário
        if ($request->has('category') && $request->category != '') {
            $products->where('category_id', $request->category);
        }

        // Filtro por data de criação, atualização ou outros
        if ($request->has('filter') && $request->filter != '') {
            if ($request->filter == 'created_at') {
                $products->orderBy('created_at', 'desc');
            } elseif ($request->filter == 'updated_at') {
                $products->orderBy('updated_at', 'desc');
            } elseif ($request->filter == 'name_asc') {
                $products->orderBy('name', 'asc');
            } elseif ($request->filter == 'name_desc') {
                $products->orderBy('name', 'desc');
            } elseif ($request->filter == 'price_asc') {
                $products->orderBy('price', 'asc');
            } elseif ($request->filter == 'price_desc') {
                $products->orderBy('price', 'desc');
            }
        }

        // Paginando os resultados
        $products = $products->paginate(10);  // Ajuste o número de itens por página conforme necessário

        // Carregar categorias para o filtro, apenas as do usuário logado
        $categories = Category::where('user_id', $userId)->get();

        // Passando os produtos e categorias para a view
        return view('products.index', compact('products', 'categories'));
    }
    // Método para excluir um produto
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Remover a imagem, se houver
        if ($product->image) {
            Storage::delete('public/products/' . $product->image);
        }

        // Excluir o produto
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso!');
    }
    public function show($id)
    {
        $product = Product::findOrFail($id); // Encontre o produto ou retorne 404
        return view('products.index', compact('product')); // Retorne a view com os detalhes do produto
    }
    // Método para armazenar um novo produto
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'price' => 'required|numeric',
            'price_sale' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:category,id_category',
            'product_code' => 'required|unique:products,product_code',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Tratamento da imagem
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/products');
            $imageName = basename($imagePath);
        } else {
            $imageName = null;
        }

        // Criar o produto
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
            'product_code' => 'required|unique:products,product_code,' . $id,
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
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
