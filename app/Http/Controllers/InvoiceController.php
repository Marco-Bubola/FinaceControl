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
                'id' => $invoice->id,
                'title' => $invoice->description,
                'start' => $invoice->invoice_date,
                'category' => $invoice->category->name,
                'installments' => $invoice->installments,
                'value' => $invoice->value,
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
            return response()->json([
                'transactionsHtml' => view('invoice.transactions', compact('eventsGroupedByMonth'))->render(),
                'eventsDetailed' => $eventsDetailed,
                'totalInvoices' => $totalInvoices,
                'highestInvoice' => $highestInvoice ? number_format($highestInvoice->value, 2) : '0,00',
                'lowestInvoice' => $lowestInvoice ? number_format($lowestInvoice->value, 2) : '0,00',
                'totalTransactions' => $totalTransactions,
                'previousMonth' => $previousMonth,
                'nextMonth' => $nextMonth,
                'currentMonthTitle' => "$currentMonthName ({$currentStartDate->format('d/m/Y')} - {$currentEndDate->format('d/m/Y')})"
            ]);
        }

        return view('invoice.index', compact(
            'bank',
            'eventsDetailed', // Detalhes das faturas
            'eventsGroupedByMonth', // Agrupamento por mês
            'categories',
            'invoices',
            'currentStartDate',
            'currentEndDate',
            'previousMonth',
            'nextMonth',
            'currentMonthName',
            'totalInvoices', // Total do mês
            'highestInvoice', // Maior fatura
            'lowestInvoice', // Menor fatura
            'totalTransactions' // Total de transações
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

    // Outras funções, como 'store', 'edit', 'update', 'destroy', podem ser adicionadas conforme necessário.
}
