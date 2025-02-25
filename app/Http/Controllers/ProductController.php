<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Método para exibir todos os produtos
    public function index()
    {
        $products = Product::all();  // Carregar categorias associadas
        $categories = Category::all(); // Pegar todas as categorias para o modal
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

    // Método para armazenar um novo produto
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'price' => 'required|numeric',
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
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'product_code' => $request->product_code,
            'image' => $imageName,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }
}
