<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Category;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $bank = Bank::findOrFail($request->bank_id);
        $categories = Category::all();
    
        // Obtém o mês atual da paginação (se não fornecido, usa o mês inicial do banco)
        $currentMonth = $request->query('month', \Carbon\Carbon::parse($bank->start_date)->format('Y-m-d'));
    
        // Define o intervalo de datas para o mês atual com base no $bank->start_date
        $currentStartDate = \Carbon\Carbon::parse($currentMonth)
            ->setDay(\Carbon\Carbon::parse($bank->start_date)->day)
            ->startOfDay();
        $currentEndDate = $currentStartDate->copy()->addMonth()->subDay()->endOfDay(); // Termina no mesmo dia do próximo mês, menos um dia
    
        // Filtra as faturas dentro do intervalo de datas
        $invoices = Invoice::where('id_bank', $bank->id_bank)
            ->whereBetween('invoice_date', [$currentStartDate, $currentEndDate])
            ->orderBy('invoice_date', 'asc') // Ordena por data
            ->get();
    
        // Agrupa as faturas por mês com base no campo invoice_date
        $eventsGroupedByMonth = $invoices->groupBy(function ($invoice) {
            return \Carbon\Carbon::parse($invoice->invoice_date)->format('d'); // Agrupa por ano e mês
        });
    
        // Para ser usado no FullCalendar (detalhes das faturas)
        $eventsDetailed = $invoices->map(function ($invoice) {
            return [
                'id_invoice' => $invoice->id,
                'title' => $invoice->description,
                'start' => $invoice->invoice_date,
                'category' => $invoice->category->name,
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
        });
    
        // Define os links de navegação para os meses anterior e próximo
        $previousMonth = $currentStartDate->copy()->subMonth()->format('Y-m-d');
        $nextMonth = $currentStartDate->copy()->addMonth()->format('Y-m-d');
    
        // Nome do mês atual traduzido para português
        Carbon::setLocale('pt_BR');
        $currentMonthName = ucfirst($currentStartDate->translatedFormat('F Y'));
    
        // Calcula o preço total do mês
        $totalInvoices = $invoices->sum('value');
    
        // Obtém a maior fatura
        $highestInvoice = $invoices->sortByDesc('value')->first();
    
        // Obtém a menor fatura
        $lowestInvoice = $invoices->sortBy('value')->first();
    
        // Conta o total de transações no mês
        $totalTransactions = $invoices->count();
    
        if ($request->ajax()) {
            try {
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
        });

                \Log::info('Dados de categorias enviados para o gráfico:', $categoriesData->toArray()); // Log para depuração

                return response()->json([
                    'transactionsHtml' => view('invoice.transactions', compact('eventsGroupedByMonth', 'categories'))->render(),
                    'eventsDetailed' => $eventsDetailed,
                    'totalInvoices' => $totalInvoices,
                    'highestInvoice' => $highestInvoice ? number_format($highestInvoice->value, 2) : '0,00',
                    'lowestInvoice' => $lowestInvoice ? number_format($lowestInvoice->value, 2) : '0,00',
                    'totalTransactions' => $totalTransactions,
                    'previousMonth' => $previousMonth,
                    'nextMonth' => $nextMonth,
                    'currentMonthTitle' => "$currentMonthName ({$currentStartDate->format('d/m/Y')} - {$currentEndDate->format('d/m/Y')})",
                    'categories' => $categoriesData,
                    'categoriesWithTransactions' => $categoriesWithTransactions
                ]);
            } catch (\Exception $e) {
                \Log::error('Erro ao carregar os dados do mês:', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Erro ao carregar os dados do mês.'], 500);
            }
        }

        \Log::info('Dados de categorias enviados para a view:', $categoriesData->toArray()); // Log para depuração
    
        return view('invoice.index', compact(
            'bank',
            'eventsDetailed', // Detalhes das faturas
            'eventsGroupedByMonth', // Agrupamento por mês
            'categoriesWithTransactions', // Apenas categorias com transações
            'invoices',
            'categories',
            'currentStartDate',
            'currentEndDate',
            'previousMonth',
            'nextMonth',
            'currentMonthName',
            'totalInvoices', // Total do mês
            'highestInvoice', // Maior fatura
            'lowestInvoice', // Menor fatura
            'totalTransactions', // Total de transações
            'categoriesData' // Passando as categorias para a view
        ));
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_bank' => 'required|exists:banks,id_bank',
            'description' => 'required|string|max:255',
            'value' => 'required|numeric',
            'installments' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:category,id_category',
            'invoice_date' => 'required|date',
        ]);

        Invoice::create($validated);

        return redirect()->route('invoices.index', ['bank_id' => $request->id_bank])
            ->with('success', 'Transferência adicionada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'value' => 'required|numeric',
            'installments' => 'required|integer|min:1',
            'category_id' => 'required|exists:category,id_category',
            'invoice_date' => 'required|date',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update($validated);

        // Redireciona para a página correta com o id_bank
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

    // Outras funções, como 'store', 'edit', 'update', 'destroy', podem ser adicionadas conforme necessário.
}
