<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    // Método para listar todos os bancos
    public function index()
    {
        $banks = Bank::all();  // Recupera todos os bancos
        return view('banks.index', compact('banks'));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        return view('banks.create');
    }

    // Método para armazenar um novo banco
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'user_id' => 'required|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        Bank::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('banks.index')->with('success', 'Banco criado com sucesso!');
    }

    // Método para editar um banco
    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        return view('banks.edit', compact('bank'));
    }

    // Método para atualizar um banco
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $bank = Bank::findOrFail($id);
        $bank->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('banks.index')->with('success', 'Banco atualizado com sucesso!');
    }

    // Método para deletar um banco
    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return redirect()->route('banks.index')->with('success', 'Banco deletado com sucesso!');
    }
}
