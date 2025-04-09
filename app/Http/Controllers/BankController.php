<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class BankController extends Controller
{
    // Método para exibir todos os bancos
    public function index()
    {
        // Obtendo todos os bancos
        $banks = Bank::all();

        // Obtendo as transações do usuário logado, incluindo o banco e a categoria associada
        $invoices = Invoice::where('user_id', auth()->id())
            ->with(['bank', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Verificar se existem transações
        $hasInvoices = $invoices->isEmpty();

        // Retornando a view e passando as transações e o estado de ter ou não transações
        return view('banks.index', compact('banks', 'invoices', 'hasInvoices'));
    }



    // Método para mostrar o formulário de criação
    public function create()
    {
        return view('banks.create');
    }
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start_date' => 'required|date',  // A data precisa ser válida
            'end_date' => 'required|date',    // A data precisa ser válida
        ]);

        // Criando um novo banco/cartão
        $bank = new Bank();
        $bank->name = $request->name;
        $bank->description = $request->description;

        // Salvar as datas completas, incluindo ano, mês e dia
        $bank->start_date = $request->start_date;  // Salva a data como está
        $bank->end_date = $request->end_date;      // Salva a data como está

        // Atribui o ID do usuário logado
        $bank->user_id = auth()->id();

        // Salva os dados no banco
        $bank->save();

        return redirect()->route('banks.index')->with('success', 'Cartão adicionado com sucesso!');
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
