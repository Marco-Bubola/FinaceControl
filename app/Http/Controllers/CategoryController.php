<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Bank;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // Método para listar todas as categorias
    public function index()
    {
        $categories = Category::with('bank', 'client')->get();
        return view('categories.index', compact('categories'));
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
        $request->validate([
            'name' => 'required|max:100',
            'desc_category' => 'required|max:100',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:product,transaction',
            'parent_id' => 'nullable|integer',
            'hexcolor_category' => 'nullable|string',
            'icone' => 'nullable|string',
            'descricao_detalhada' => 'nullable|string',
            'tipo' => 'nullable|in:gasto,receita,ambos',
            'limite_orcamento' => 'nullable|numeric',
            'compartilhavel' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'regras_auto_categorizacao' => 'nullable|json',
            'id_bank' => 'nullable|exists:banks,id_bank',
            'id_clients' => 'nullable|exists:clients,id',
            'historico_alteracoes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'desc_category' => $request->desc_category,
            'hexcolor_category' => $request->hexcolor_category,
            'icone' => $request->icone,
            'descricao_detalhada' => $request->descricao_detalhada,
            'tipo' => $request->tipo,
            'limite_orcamento' => $request->limite_orcamento,
            'compartilhavel' => $request->compartilhavel,
            'tags' => $request->tags,
            'regras_auto_categorizacao' => $request->regras_auto_categorizacao,
            'id_bank' => $request->id_bank,
            'id_clients' => $request->id_clients,
            'id_produtos_clientes' => $request->id_produtos_clientes,
            'historico_alteracoes' => $request->historico_alteracoes,
            'is_active' => $request->is_active ?? 1,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'type' => $request->type,
        ]);

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
        $request->validate([
            'name' => 'required|max:100',
            'desc_category' => 'required|max:100',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:product,transaction',
            'parent_id' => 'nullable|integer',
            'hexcolor_category' => 'nullable|string',
            'icone' => 'nullable|string',
            'descricao_detalhada' => 'nullable|string',
            'tipo' => 'nullable|in:gasto,receita,ambos',
            'limite_orcamento' => 'nullable|numeric',
            'compartilhavel' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'regras_auto_categorizacao' => 'nullable|json',
            'id_bank' => 'nullable|exists:banks,id_bank',
            'id_clients' => 'nullable|exists:clients,id',
            'historico_alteracoes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'desc_category' => $request->desc_category,
            'hexcolor_category' => $request->hexcolor_category,
            'icone' => $request->icone,
            'descricao_detalhada' => $request->descricao_detalhada,
            'tipo' => $request->tipo,
            'limite_orcamento' => $request->limite_orcamento,
            'compartilhavel' => $request->compartilhavel,
            'tags' => $request->tags,
            'regras_auto_categorizacao' => $request->regras_auto_categorizacao,
            'id_bank' => $request->id_bank,
            'id_clients' => $request->id_clients,
            'id_produtos_clientes' => $request->id_produtos_clientes,
            'historico_alteracoes' => $request->historico_alteracoes,
            'is_active' => $request->is_active ?? 1,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'type' => $request->type,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    // Método para excluir uma categoria
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso!');
    }
}
