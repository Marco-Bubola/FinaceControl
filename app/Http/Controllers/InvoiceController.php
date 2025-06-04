<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Category;
use App\Models\Client; // Importação do modelo Client
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $bank = Bank::findOrFail($request->bank_id);

            $categories = Category::all();
            $banks = Bank::all();
            $clients = Client::all();

            $currentMonth = $request->query('month', now()->format('Y-m-d'));

            $currentStartDate = \Carbon\Carbon::parse($currentMonth)
                ->setDay(\Carbon\Carbon::parse($bank->start_date)->day)
                ->startOfDay();

            $currentEndDate = $currentStartDate->copy()->addMonth()->subDay()->endOfDay();

            $previousMonth = $currentStartDate->copy()->subMonth()->startOfMonth()->format('Y-m-d');
            $nextMonth = $currentStartDate->copy()->addMonth()->startOfMonth()->format('Y-m-d');
            $previousMonthName = \Carbon\Carbon::parse($previousMonth)->locale('pt_BR')->isoFormat('MMMM ');
            $nextMonthName = \Carbon\Carbon::parse($nextMonth)->locale('pt_BR')->isoFormat('MMMM');

            $invoices = Invoice::with('category')
                ->where('id_bank', $bank->id_bank)
                ->whereBetween('invoice_date', [$currentStartDate, $currentEndDate])
                ->orderBy('invoice_date', 'asc')
                ->get();

            // Gera os dados diários para o gráfico de linhas
            $dailyData = $invoices->groupBy(function ($invoice) {
                return Carbon::parse($invoice->invoice_date)->day;
            })->map(function ($dayInvoices) {
                return $dayInvoices->sum('value');
            });

            $dailyLabels = $dailyData->keys()->toArray();
            $dailyValues = $dailyData->values()->toArray();

            // Agrupa as faturas apenas por categoria, em um grupo único 'current'
            $eventsGroupedByMonthAndCategory = [
                'current' => []
            ];
            foreach ($invoices->groupBy('category_id') as $categoryId => $categoryInvoices) {
                $eventsGroupedByMonthAndCategory['current'][$categoryId] = $categoryInvoices->values();
            }

            // Para ser usado no FullCalendar (detalhes das faturas)
            $eventsDetailed = $invoices->map(function ($invoice) {
                return [
                    'id_invoice' => $invoice->id_invoice ?? $invoice->id ?? null,
                    'title' => $invoice->description,
                    'start' => $invoice->invoice_date,
                    'category' => optional($invoice->category)->name ?? 'Sem Categoria',
                    'installments' => $invoice->installments,
                    'value' => $invoice->value,
                ];
            });

            // Filtra as categorias com base nas transações do mês
            $categoriesWithTransactions = $categories->filter(function ($category) use ($invoices) {
                return $invoices->where('category_id', $category->id_category)->isNotEmpty();
            });

            // Calculando as categorias e os valores totais por categoria
            $categoriesData = $categoriesWithTransactions->map(function ($category) use ($invoices) {
                $categoryTotal = $invoices->where('category_id', $category->id_category)->sum('value');
                return [
                    'label' => $category->name,
                    'value' => $categoryTotal,
                ];
            })->values();

            Carbon::setLocale('pt_BR');
            $currentMonthName = ucfirst($currentStartDate->translatedFormat('F'));
            $totalInvoices = $invoices->sum('value');
            $highestInvoice = $invoices->sortByDesc('value')->first();
            $lowestInvoice = $invoices->sortBy('value')->first();
            $totalTransactions = $invoices->count();

            if ($request->ajax()) {
                try {
                    $transactionsHtml = view('invoice.transactions', [
                        'eventsGroupedByMonthAndCategory' => $eventsGroupedByMonthAndCategory,
                        'categories' => $categories,
                        'clients' => $clients,
                        'banks' => $banks,
                        'currentStartDate' => $currentStartDate,
                        'currentEndDate' => $currentEndDate
                    ])->render();

                    return response()->json([
                        'transactionsHtml' => $transactionsHtml,
                        'eventsDetailed' => $eventsDetailed,
                        'totalInvoices' => $totalInvoices,
                        'highestInvoice' => $highestInvoice ? number_format($highestInvoice->value, 2) : '0,00',
                        'lowestInvoice' => $lowestInvoice ? number_format($lowestInvoice->value, 2) : '0,00',
                        'totalTransactions' => $totalTransactions,
                        'previousMonth' => $previousMonth,
                        'nextMonth' => $nextMonth,
                        'previousMonthName' => $previousMonthName,
                        'nextMonthName' => $nextMonthName,
                        'currentMonthTitle' => "$currentMonthName",
                        'currentMonthRange' => "({$currentStartDate->format('d/m')} - {$currentEndDate->format('d/m')})",
                        'categories' => $categoriesData,
                        'categoriesWithTransactions' => $categoriesWithTransactions,
                        'dailyLabels' => $dailyLabels,
                        'dailyValues' => $dailyValues,
                        'clients' => $clients,
                        'eventsGroupedByMonthAndCategory' => $eventsGroupedByMonthAndCategory,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Erro AJAX InvoiceController@index: ' . $e->getMessage(), ['exception' => $e]);
                    // Retorna o erro real em ambiente de desenvolvimento
                    if (config('app.debug')) {
                        return response()->json([
                            'error' => 'Erro ao carregar os dados do mês: ' . $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ], 500);
                    }
                    return response()->json(['error' => 'Erro ao carregar os dados do mês.'], 500);
                }
            }

            return view('invoice.index', [
                'bank' => $bank,
                'banks' => $banks,
                'clients' => $clients,
                'eventsDetailed' => $eventsDetailed,
                'eventsGroupedByMonthAndCategory' => $eventsGroupedByMonthAndCategory,
                   'categoriesWithTransactions' => $categoriesWithTransactions,
                'invoices' => $invoices,
                'categories' => $categories,
                'previousMonthName' => $previousMonthName,
                'nextMonthName' => $nextMonthName,  'currentStartDate' => $currentStartDate,
                'currentEndDate' => $currentEndDate,
                'previousMonth' => $previousMonth,
                'nextMonth' => $nextMonth,
                'currentMonthName' => $currentMonthName,
                'totalInvoices' => $totalInvoices,
                'highestInvoice' => $highestInvoice,
                'lowestInvoice' => $lowestInvoice,
                'totalTransactions' => $totalTransactions,
                'categoriesData' => $categoriesData
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro InvoiceController@index: ' . $e->getMessage(), ['exception' => $e]);
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Erro ao carregar os dados do mês: ' . $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
            return response()->json(['error' => 'Erro ao carregar os dados do mês.'], 500);
        }
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'id_bank' => 'required|exists:banks,id_bank',
        'description' => 'required|string|max:255',
        'value' => 'required|string|max:255',
        'installments' => 'nullable|string|max:255',
        'user_id' => 'required|exists:users,id',
        'category_id' => 'required|exists:category,id_category',
        'invoice_date' => 'required|date',
        'client_id' => 'nullable|exists:clients,id',
    ]);

    // Converter vírgula para ponto no valor
    $validated['value'] = str_replace(',', '.', $validated['value']);

    Invoice::create($validated);

    return redirect()->route('invoices.index', ['bank_id' => $request->id_bank])
        ->with('success', 'Transferência adicionada com sucesso!');
}


public function update(Request $request, $id)
{
    $validated = $request->validate([
        'description' => 'required|string|max:255',
        'value' => 'required|string|max:255',
        'installments' => 'nullable|string|max:255',
        'category_id' => 'required|exists:category,id_category',
        'invoice_date' => 'required|date',
        'client_id' => 'nullable|exists:clients,id',
    ]);

    // Converter vírgula para ponto no valor
    $validated['value'] = str_replace(',', '.', $validated['value']);

    $invoice = Invoice::findOrFail($id);
    $invoice->update($validated);

    return redirect()->route('invoices.index', ['bank_id' => $invoice->id_bank])
        ->with('success', 'Transação atualizada com sucesso!');
}

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        // Redireciona para a página correta com o id_bank
        return redirect()->route('invoices.index', ['bank_id' => $invoice->id_bank])
            ->with('success', 'Transação excluída com sucesso!');
    }

public function copy(Request $request, $id)
{
    $validated = $request->validate([
        'id_bank' => 'required|exists:banks,id_bank',
        'description' => 'required|string|max:255',
        'value' => 'required|string|max:255', // Alterado de numeric para string
        'installments' => 'required|string|max:255',
        'category_id' => 'required|exists:category,id_category',
        'invoice_date' => 'required|date',
        'divisions' => 'required|integer|min:1',
    ]);

    // Corrigir formato decimal
    $validated['value'] = str_replace(',', '.', $validated['value']);

    $originalInvoice = Invoice::findOrFail($id);

    Invoice::create([
        'id_bank' => $validated['id_bank'],
        'description' => $validated['description'],
        'value' => $validated['value'], // já corrigido
        'installments' => $validated['installments'],
        'category_id' => $validated['category_id'],
        'invoice_date' => $validated['invoice_date'],
        'user_id' => $originalInvoice->user_id,
    ]);

    return redirect()->route('invoices.index', ['bank_id' => $validated['id_bank']])
        ->with('success', 'Transação copiada com sucesso!');
}


    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $categories = Category::all();
        $clients = Client::all(); // Adiciona a variável $clients

        return view('invoice.edit', compact('invoice', 'categories', 'clients'));
    }

}
