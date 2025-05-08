<?php

namespace App\Http\Controllers;

use App\Models\Cashbook;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Type;
use App\Models\Segment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashbookController extends Controller
{
    public function index()
    {
        // Obter o mês atual
        $currentMonth = now()->format('Y-m');
        $monthName = now()->translatedFormat('F Y');

        // Obter transações do mês atual para o usuário logado
        $transactions = Cashbook::where('user_id', Auth::id())
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->orderBy('date', 'desc')
            ->get();

        // Calcular totais do mês atual
        $totals = [
            'income' => $transactions->where('type_id', 1)->sum('value'), // Receitas
            'expense' => $transactions->where('type_id', 2)->sum('value'), // Despesas
            'balance' => $transactions->where('type_id', 1)->sum('value') - $transactions->where('type_id', 2)->sum('value'), // Saldo
        ];
        $clients = Client::all();
        $categories = Category::all();
        $types = Type::all();
        $segments = Segment::all();

        return view('cashbook.index', compact('currentMonth', 'monthName', 'transactions', 'totals', 'clients', 'categories', 'types', 'segments'));
    }

    public function store(Request $request)
    {
       $request->validate([
            'value' => 'required|numeric',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
            'is_pending' => 'required|boolean',
            'attachment' => 'nullable|file|max:2048',
            'category_id' => 'required|exists:category,id_category',
            'type_id' => 'required|exists:type,id_type',
            'client_id' => 'nullable|exists:clients,id', // Permitir client_id como opcional
            'note' => 'nullable|string|max:255',
            'segment_id' => 'nullable|exists:segment,id',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Salvar o usuário logado
        $data['inc_datetime'] = now();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        Cashbook::create($data);

        return redirect()->route('cashbook.index')->with('success', 'Transação adicionada com sucesso!');
    }

    public function edit(Cashbook $cashbook)
    {
        $categories = Category::all();
        $types = Type::all();
        $segments = Segment::all();

        return response()->json([
            'cashbook' => $cashbook,
            'categories' => $categories,
            'types' => $types,
            'segments' => $segments,
        ]);
    }

    public function update(Request $request, Cashbook $cashbook)
    {
        $validated = $request->validate([
            'value' => 'required|numeric',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
            'is_pending' => 'required|boolean',
            'attachment' => 'nullable|file|max:2048',
            'category_id' => 'required|exists:category,id_category',
            'type_id' => 'required|exists:type,id_type',
            'client_id' => 'nullable|exists:clients,id', // Permitir client_id como opcional
            'note' => 'nullable|string|max:255',
            'segment_id' => 'nullable|exists:segment,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $data['edit_datetime'] = now();

        $cashbook->update($data);

        return redirect()->route('cashbook.index')->with('success', 'Transação atualizada com sucesso!');
    }

    public function destroy(Cashbook $cashbook)
    {
        $cashbook->delete();

        return redirect()->route('cashbook.index')->with('success', 'Transação excluída com sucesso!');
    }

    public function getMonth(Request $request, $direction)
    {
        $currentMonth = $request->query('currentMonth', now()->format('Y-m'));
        $date = \Carbon\Carbon::parse($currentMonth);

        if ($direction === 'previous') {
            $date->subMonth();
        } elseif ($direction === 'next') {
            $date->addMonth();
        }

        // Obter transações do mês selecionado para o usuário logado
        $transactions = Cashbook::where('user_id', Auth::id())
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->orderBy('date', 'desc')
            ->get();

        // Calcular totais do mês selecionado
        $totals = [
            'income' => $transactions->where('type_id', 1)->sum('value'), // Apenas receitas (type_id = 1)
            'expense' => $transactions->where('type_id', 2)->sum('value'), // Apenas despesas (type_id = 2)
            'balance' => $transactions->where('type_id', 1)->sum('value') - $transactions->where('type_id', 2)->sum('value'), // Saldo
        ];

        // Agrupar transações por dia
        $transactionsByDay = $transactions->groupBy(function ($transaction) {
            return \Carbon\Carbon::parse($transaction->date)->format('d');
        })->map(function ($dayTransactions) {
            return $dayTransactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'description' => $transaction->description,
                    'value' => $transaction->value,
                    'type_id' => $transaction->type_id,
                    'time' => \Carbon\Carbon::parse($transaction->date)->format('d/m'),
                    'category_name' => $transaction->category->name ?? null,
                    'category_hexcolor_category' => $transaction->category->hexcolor_category ?? '#cccccc',
                    'category_icone' => $transaction->category->icone ?? 'fas fa-question',
                    'note' => $transaction->note,
                ];
            });
        });

        // Obter dados de categorias para receitas e despesas
        $incomeCategories = $transactions->where('type_id', 1)
            ->groupBy('category_id')
            ->map(function ($group) {
                $category = $group->first()->category;
                return [
                    'name' => $category->name ?? 'Sem Categoria',
                    'total' => $group->sum('value'),
                    'hexcolor_category' => $category->hexcolor_category ?? '#cccccc',
                ];
            })->values();

        $expenseCategories = $transactions->where('type_id', 2)
            ->groupBy('category_id')
            ->map(function ($group) {
                $category = $group->first()->category;
                return [
                    'name' => $category->name ?? 'Sem Categoria',
                    'total' => $group->sum('value'),
                    'hexcolor_category' => $category->hexcolor_category ?? '#cccccc', // Garantir que a cor seja retornada
                ];
            })->values();

        // Tradução manual dos meses
        $monthTranslations = [
            'January' => 'Janeiro',
            'February' => 'Fevereiro',
            'March' => 'Março',
            'April' => 'Abril',
            'May' => 'Maio',
            'June' => 'Junho',
            'July' => 'Julho',
            'August' => 'Agosto',
            'September' => 'Setembro',
            'October' => 'Outubro',
            'November' => 'Novembro',
            'December' => 'Dezembro',
        ];

        $monthName = $date->format('F Y');
        foreach ($monthTranslations as $english => $portuguese) {
            $monthName = str_replace($english, $portuguese, $monthName);
        }

        return response()->json([
            'currentMonth' => $date->format('Y-m'),
            'monthName' => $monthName,
            'transactionsByDay' => $transactionsByDay,
            'totals' => $totals,
            'categories' => [
                'income' => $incomeCategories,
                'expense' => $expenseCategories,
            ],
        ]);
    }
}
