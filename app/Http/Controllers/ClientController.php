<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::query();
    
        // Filtro de pesquisa por nome
        if ($request->has('search') && $request->search != '') {
            $clients->where('name', 'like', '%' . $request->search . '%');
        }
    
        // Filtro por data de criação ou atualização
        if ($request->has('filter') && $request->filter != '') {
            if ($request->filter == 'created_at') {
                $clients->orderBy('created_at', 'desc');
            } elseif ($request->filter == 'updated_at') {
                $clients->orderBy('updated_at', 'desc');
            } elseif ($request->filter == 'name_asc') {
                $clients->orderBy('name', 'asc');
            } elseif ($request->filter == 'name_desc') {
                $clients->orderBy('name', 'desc');
            }
        }
    
        // Paginando os resultados
        $clients = $clients->paginate(10); // Ajuste o número de itens por página conforme necessário
    
        return view('clients.index', compact('clients'));
    }
    

    // Método para exibir o formulário de criação de cliente
    public function create()
    {
        return view('clients.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:15',
            'address' => 'nullable',
        ]);

        // Criar o cliente
        Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_id' => Auth::id(), // Associar o cliente ao usuário autenticado
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente criado com sucesso!');
    }


    // Método para editar um cliente
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    // Método para atualizar o cliente
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:15',
            'address' => 'nullable',
        ]);

        $client = Client::findOrFail($id);
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    // Método para excluir um cliente
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente deletado com sucesso!');
    }
}
