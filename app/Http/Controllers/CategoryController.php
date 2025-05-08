<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Bank;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        // Obtém o ID do usuário logado
        $userId = auth()->id();

        // Filtra as categorias ativas e associadas ao usuário logado
        $productCategories = Category::where('is_active', 1)
                                     ->where('user_id', $userId)
                                     ->where('type', 'product')  // Filtro para produtos
                                     ->get(); // Retorna todas as categorias de produto

        $transactionCategories = Category::where('is_active', 1)
                                         ->where('user_id', $userId)
                                         ->where('type', 'transaction')  // Filtro para transações
                                         ->get(); // Retorna todas as categorias de transação

        // Obter todas as categorias para o formulário (não é necessário filtro adicional aqui)
        $categories = Category::where('user_id', $userId)->get();

        $clients = Client::all(); // Obter todos os clientes

        // Retorna a view com as categorias
        return view('categories.index', compact('productCategories', 'transactionCategories', 'categories', 'userId', 'clients'));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        $banks = Bank::all();  // Lista de bancos
        $clients = Client::all();  // Lista de clientes
        return view('categories.create', compact('banks', 'clients'));
    }

    // Método para armazenar uma nova categoria
    public function store(Request $request)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'parent_id' => 'nullable|integer',
            'name' => 'required|string|max:100',
            'desc_category' => 'nullable|string|max:100',
            'hexcolor_category' => 'nullable|string|max:45',
            'icone' => 'nullable|string|max:100',
            'descricao_detalhada' => 'nullable|string',
            'tipo' => 'nullable|in:gasto,receita,ambos',
            'limite_orcamento' => 'nullable|numeric',
            'compartilhavel' => 'nullable|boolean',
            'tags' => 'nullable|string|max:255',
            'regras_auto_categorizacao' => 'nullable|json',
            'id_bank' => 'nullable|integer',
            'id_clients' => 'nullable|integer',
            'id_produtos_clientes' => 'nullable|integer',
            'historico_alteracoes' => 'nullable|string',
            'is_active' => 'required|integer|in:0,1',
            'description' => 'nullable|string',
            'user_id' => 'required|integer',
            'type' => 'required|in:product,transaction', // Certifique-se de que o campo type é obrigatório
        ]);

        // Criação da categoria
        Category::create($validatedData);

        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    // Método para editar uma categoria
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $banks = Bank::all();
        $clients = Client::all();
        return view('categories.edit', compact('category', 'banks', 'clients'));
    }

    // Método para atualizar uma categoria
    public function update(Request $request, $id)
    {
        // Validação dos dados
        $validatedData = $request->validate([
            'parent_id' => 'nullable|integer',
            'name' => 'required|string|max:100',
            'desc_category' => 'nullable|string|max:100',
            'hexcolor_category' => 'nullable|string|max:45',
            'icone' => 'nullable|string|max:100',
            'descricao_detalhada' => 'nullable|string',
            'tipo' => 'nullable|in:gasto,receita,ambos',
            'limite_orcamento' => 'nullable|numeric',
            'compartilhavel' => 'nullable|boolean',
            'tags' => 'nullable|string|max:255',
            'regras_auto_categorizacao' => 'nullable|json',
            'id_bank' => 'nullable|integer',
            'id_clients' => 'nullable|integer',
            'id_produtos_clientes' => 'nullable|integer',
            'historico_alteracoes' => 'nullable|string',
            'is_active' => 'required|integer|in:0,1',
            'description' => 'nullable|string',
            'user_id' => 'required|integer',
            'type' => 'required|in:product,transaction', // Certifique-se de que o campo type é obrigatório
        ]);

        // Atualização da categoria
        $category = Category::findOrFail($id);
        $category->update($validatedData);

        return redirect()->back()->with('success', 'Categoria atualizada com sucesso!');
    }

    // Método para excluir uma categoria
    public function destroy($id)
    {
        // Encontre e exclua a categoria usando a chave primária correta
        $category = Category::findOrFail($id); // Certifique-se de que $id é id_category
        $category->delete();

        return redirect()->back()->with('success', 'Categoria excluída com sucesso!');
    }
}
