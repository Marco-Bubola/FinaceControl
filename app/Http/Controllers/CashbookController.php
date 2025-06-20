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
use App\Models\Cofrinho;

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
      $categories = Category::where('user_id', auth()->id())->where('type', 'transaction')->get();
$clients = Client::where('user_id', auth()->id())->get();
        $types = Type::all();
        $segments = Segment::all();
        $cofrinhos = Cofrinho::where('user_id', auth()->id())->get();

        return view('cashbook.index', compact('currentMonth', 'monthName', 'transactions', 'totals', 'clients', 'categories', 'types', 'segments', 'cofrinhos'));
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
            'cofrinho_id' => 'nullable|exists:cofrinhos,id',
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

    public function edit($id)
    {
        $cashbook = Cashbook::findOrFail($id);
        $cofrinhos = Cofrinho::where('user_id', auth()->id())->get();
        return response()->json([
            'cashbook' => [
                'id' => $cashbook->id,
                'value' => $cashbook->value,
                'description' => $cashbook->description,
                'date' => $cashbook->date,
                'is_pending' => $cashbook->is_pending,
                'category_id' => $cashbook->category_id,
                'type_id' => $cashbook->type_id,
                'note' => $cashbook->note,
                'segment_id' => $cashbook->segment_id,
                'client_id' => $cashbook->client_id,
                'cofrinho_id' => $cashbook->cofrinho_id,
            ],
            'cofrinhos' => $cofrinhos,
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
            'cofrinho_id' => 'nullable|exists:cofrinhos,id',
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
        $transactions = Cashbook::with('category')
            ->where('user_id', Auth::id())
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->orderBy('date', 'desc')
            ->get();

        // Calcular totais do mês selecionado
        $totals = [
            'income' => $transactions->where('type_id', 1)->sum('value'),
            'expense' => $transactions->where('type_id', 2)->sum('value'),
            'balance' => $transactions->where('type_id', 1)->sum('value') - $transactions->where('type_id', 2)->sum('value'),
        ];

        // Agrupar transações por categoria
        $transactionsByCategory = $transactions->groupBy(function ($transaction) {
            return $transaction->category_id ?: 'sem_categoria';
        })->map(function ($group, $catId) {
            $category = $group->first()->category;
            $total_receita = $group->where('type_id', 1)->sum('value');
            $total_despesa = $group->where('type_id', 2)->sum('value');
            return [
                'category_id' => $catId,
                'category_name' => $category->name ?? 'Sem Categoria',
                'category_hexcolor_category' => $category->hexcolor_category ?? '#cccccc',
                'category_icone' => $category->icone ?? 'fas fa-question',
                'total_receita' => $total_receita,
                'total_despesa' => $total_despesa,
                'transactions' => $group->map(function ($transaction) use ($category) {
                    return [
                        'id' => $transaction->id,
                        'description' => $transaction->description,
                        'value' => $transaction->value,
                        'type_id' => $transaction->type_id,
                        'time' => \Carbon\Carbon::parse($transaction->date)->format('d/m'),
                        'category_id' => $transaction->category_id,
                        'category_name' => $category->name ?? 'Sem Categoria',
                        'category_hexcolor_category' => $category->hexcolor_category ?? '#cccccc',
                        'category_icone' => $category->icone ?? 'fas fa-question',
                        'note' => $transaction->note,
                        'client_id' => $transaction->client_id,
                    ];
                })->values(),
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

        // Calcular dados do mês anterior
        $prevDate = (clone $date)->subMonth();
        $prevMonthName = $prevDate->format('F Y');
        foreach ($monthTranslations as $english => $portuguese) {
            $prevMonthName = str_replace($english, $portuguese, $prevMonthName);
        }
        $prevTransactions = Cashbook::where('user_id', Auth::id())
            ->whereYear('date', $prevDate->year)
            ->whereMonth('date', $prevDate->month)
            ->get();
        $prevMonthBalance = $prevTransactions->where('type_id', 1)->sum('value') - $prevTransactions->where('type_id', 2)->sum('value');

        // Calcular dados do próximo mês
        $nextDate = (clone $date)->addMonth();
        $nextMonthName = $nextDate->format('F Y');
        foreach ($monthTranslations as $english => $portuguese) {
            $nextMonthName = str_replace($english, $portuguese, $nextMonthName);
        }
        $nextTransactions = Cashbook::where('user_id', Auth::id())
            ->whereYear('date', $nextDate->year)
            ->whereMonth('date', $nextDate->month)
            ->get();
        $nextMonthBalance = $nextTransactions->where('type_id', 1)->sum('value') - $nextTransactions->where('type_id', 2)->sum('value');

        return response()->json([
            'currentMonth' => $date->format('Y-m'),
            'monthName' => $monthName,
            'transactionsByCategory' => $transactionsByCategory, // <-- agrupado por categoria
            'totals' => $totals,
            'categories' => [
                'income' => $transactions->where('type_id', 1)
                    ->groupBy('category_id')
                    ->map(function ($group) {
                        $category = $group->first()->category;
                        return [
                            'name' => $category->name ?? 'Sem Categoria',
                            'total' => $group->sum('value'),
                            'hexcolor_category' => $category->hexcolor_category ?? '#cccccc',
                        ];
                    })->values(),
                'expense' => $transactions->where('type_id', 2)
                    ->groupBy('category_id')
                    ->map(function ($group) {
                        $category = $group->first()->category;
                        return [
                            'name' => $category->name ?? 'Sem Categoria',
                            'total' => $group->sum('value'),
                            'hexcolor_category' => $category->hexcolor_category ?? '#cccccc',
                        ];
                    })->values(),
            ],
            'prevMonth' => [
                'name' => $prevMonthName,
                'balance' => $prevMonthBalance,
            ],
            'nextMonth' => [
                'name' => $nextMonthName,
                'balance' => $nextMonthBalance,
            ],
        ]);
    }
}
