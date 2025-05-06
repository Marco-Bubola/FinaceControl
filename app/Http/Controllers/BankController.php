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
    public function index(Request $request)
    {
        // Obtendo apenas os bancos do usuário logado
        $banks = Bank::where('user_id', auth()->id())->get();

        // Obtendo o mês e o ano da requisição ou usando o mês e ano atual como padrão
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Validando os valores de mês e ano
        if (!is_numeric($month) || $month < 1 || $month > 12) {
            $month = now()->month;
        }
        if (!is_numeric($year) || $year < 1900 || $year > now()->year + 1) {
            $year = now()->year;
        }

        // Obtendo as transações do usuário logado para o mês e ano selecionados
        $invoices = Invoice::where('user_id', auth()->id())
            ->whereMonth('invoice_date', $month)
            ->whereYear('invoice_date', $year)
            ->with(['bank', 'category'])
            ->orderBy('invoice_date', 'asc') // Ordem crescente
            ->get();

        // Calculando o valor total das transações do mês
        $totalMonth = $invoices->sum('value');
        $highestInvoice = $invoices->sortByDesc('value')->first();
        $lowestInvoice = $invoices->sortBy('value')->first();
        $totalTransactions = $invoices->count();

        // Agrupar as transações por data (dia)
        $groupedInvoices = $invoices->groupBy(function ($invoice) {
            return \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d');
        });

        // Adicionar dados de categorias para o gráfico de categorias
        $categoryTotals = $invoices->groupBy('category_id')->map(function ($group) {
            $category = $group->first()->category;
            return [
                'label' => $category->name ?? 'Sem Categoria',
                'value' => $group->sum('value'),
                'hexcolor' => $category->hexcolor_category ?? '#cccccc',
                'icone' => $category->icone ?? 'fas fa-question', // Adiciona o ícone da categoria
            ];
        })->values();

      

        // Verificar se é uma requisição AJAX
        if ($request->ajax()) {
            return response()->json([
                'groupedInvoices' => $groupedInvoices->map(function ($invoices) {
                    return $invoices->map(function ($invoice) {
                        return [
                            'description' => $invoice->description,
                            'value' => $invoice->value,
                            'type_id' => $invoice->type_id,
                            'invoice_date' => $invoice->invoice_date,
                            'category_hexcolor_category' => $invoice->category->hexcolor_category ?? '#cccccc',
                            'category_icone' => $invoice->category->icone ?? 'fas fa-question',
                       
                        ];
                    });
                }),
                'month' => $month,
                'year' => $year,
                'totalMonth' => $totalMonth,
                'highestInvoice' => $highestInvoice ? number_format($highestInvoice->value, 2) : '0,00',
                'lowestInvoice' => $lowestInvoice ? number_format($lowestInvoice->value, 2) : '0,00',
                'totalTransactions' => $totalTransactions,
                'totals' => $categoryTotals, // Dados para o gráfico de categorias
            
            ]);
        }

        // Retornando a view com as transações agrupadas, os dados do mês/ano e o total
        return view('banks.index', compact(
            'banks',
            'groupedInvoices',
            'month',
            'year',
            'totalMonth',
            'highestInvoice',
            'lowestInvoice',
            'totalTransactions'
        ));
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $bank = Bank::findOrFail($id);
        $bank->update($request->all());

        return redirect()->route('banks.index')->with('success', 'Cartão atualizado com sucesso!');
    }

    // Método para deletar um banco
    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);

        // Excluir todas as faturas relacionadas ao banco
        Invoice::where('id_bank', $bank->id_bank)->delete();

        // Excluir o banco
        $bank->delete();

        return redirect()->route('banks.index')->with('success', 'Cartão e suas faturas foram excluídos com sucesso.');
    }
}
