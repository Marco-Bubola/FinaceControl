<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // Método para listar todos os clientes
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    // Método para mostrar o formulário de criação de cliente
    public function create()
    {
        return view('clients.create');
    }

    // Método para armazenar um novo cliente
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:15',
            'address' => 'nullable',
        ]);

        Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente criado com sucesso!');
    }

    // Método para editar um cliente
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    // Método para atualizar um cliente
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
