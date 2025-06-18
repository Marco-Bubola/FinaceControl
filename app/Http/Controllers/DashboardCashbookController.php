<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cashbook;
use Illuminate\Support\Facades\Auth;

class DashboardCashbookController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $totalReceitas = Cashbook::where('user_id', $userId)->where('type_id', 1)->sum('value');
        $totalDespesas = Cashbook::where('user_id', $userId)->where('type_id', 2)->sum('value');
        $saldoTotal = $totalReceitas - $totalDespesas;

        $ano = $request->input('ano', date('Y'));
        $meses = [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ago', 9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
        ];
        $dadosReceita = [];
        $dadosDespesa = [];
        $saldosMes = [];
        foreach ($meses as $num => $nome) {
            $receita = (float) Cashbook::where('user_id', $userId)
                ->where('type_id', 1)
                ->whereYear('date', $ano)
                ->whereMonth('date', $num)
                ->sum('value');
            $despesa = (float) Cashbook::where('user_id', $userId)
                ->where('type_id', 2)
                ->whereYear('date', $ano)
                ->whereMonth('date', $num)
                ->sum('value');
            $dadosReceita[] = $receita;
            $dadosDespesa[] = $despesa;
            $saldosMes[] = $receita - $despesa;
        }

        $ultimoMes = null;
        foreach (array_reverse(array_keys($meses)) as $i) {
            if ($dadosReceita[$i-1] != 0 || $dadosDespesa[$i-1] != 0) {
                $ultimoMes = $i;
                break;
            }
        }
        $nomeUltimoMes = $ultimoMes ? $meses[$ultimoMes] : '-';
        $receitaUltimoMes = $ultimoMes ? $dadosReceita[$ultimoMes-1] : 0;
        $despesaUltimoMes = $ultimoMes ? $dadosDespesa[$ultimoMes-1] : 0;
        $saldoUltimoMes = $ultimoMes ? $saldosMes[$ultimoMes-1] : 0;

        // Bancos do usuário
        $bancos = \App\Models\Bank::where('user_id', $userId)->get();

        // Informações detalhadas de bancos e invoices (apenas saídas)
        $bancosInfo = $bancos->map(function($bank) use ($userId) {
            $invoices = \App\Models\Invoice::where('id_bank', $bank->id_bank)->where('user_id', $userId)->get();
            $totalInvoices = $invoices->sum('value');
            $qtdInvoices = $invoices->count();
            $mediaInvoices = $qtdInvoices > 0 ? $totalInvoices / $qtdInvoices : 0;
            $maiorInvoice = $invoices->sortByDesc('value')->first();
            $menorInvoice = $invoices->sortBy('value')->first();

            // Como invoices são apenas saídas, saldo é sempre negativo ou zero
            $saldoBanco = -$totalInvoices;

            return [
                'id_bank' => $bank->id_bank,
                'nome' => $bank->name,
                'descricao' => $bank->description,
                'total_invoices' => $totalInvoices,
                'qtd_invoices' => $qtdInvoices,
                'media_invoices' => $mediaInvoices,
                'maior_invoice' => $maiorInvoice,
                'menor_invoice' => $menorInvoice,
                'saldo' => $saldoBanco,
                'saidas' => $totalInvoices,
            ];
        });

        // Totais gerais de bancos
        $totalBancos = $bancos->count();
        $totalInvoicesBancos = $bancosInfo->sum('total_invoices');
        $saldoTotalBancos = $bancosInfo->sum('saldo');
        $totalSaidasBancos = $bancosInfo->sum('saidas');

        // Evolução do saldo total dos bancos nos últimos 12 meses (apenas saídas)
        $bancosEvolucaoMeses = [];
        $bancosEvolucaoSaldos = [];
        $saldoAcumulado = 0;
        $mesRef = now()->copy()->subMonths(11)->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $mesLabel = $mesRef->format('m/Y');
            $invoicesMes = \App\Models\Invoice::where('user_id', $userId)
                ->whereBetween('invoice_date', [$mesRef->copy()->startOfMonth(), $mesRef->copy()->endOfMonth()])
                ->get();
            $saidasMes = $invoicesMes->sum('value');
            $saldoAcumulado -= $saidasMes;
            $bancosEvolucaoMeses[] = $mesLabel;
            $bancosEvolucaoSaldos[] = $saldoAcumulado;
            $mesRef->addMonth();
        }

        // Mês e ano selecionados para o gráfico diário de invoices (padrão: mês atual)
        $mesInvoices = $request->input('mes_invoices', now()->month);
        $anoInvoices = $request->input('ano_invoices', now()->year);

        // Gera labels e valores para os dias do mês selecionado
        $diasInvoices = [];
        $valoresInvoices = [];
        $diasNoMes = \Carbon\Carbon::create($anoInvoices, $mesInvoices, 1)->daysInMonth;
        for ($i = 1; $i <= $diasNoMes; $i++) {
            $data = \Carbon\Carbon::create($anoInvoices, $mesInvoices, $i);
            $diasInvoices[] = $data->format('d/m');
            $valorDia = \App\Models\Invoice::where('user_id', $userId)
                ->whereDate('invoice_date', $data)
                ->sum('value');
            $valoresInvoices[] = (float)$valorDia;
        }

        // Dias do mês atual com receitas/despesas/invoices
        $mesAtual = now()->month;
        $anoAtual = now()->year;
        $diasNoMes = \Carbon\Carbon::create($anoAtual, $mesAtual, 1)->daysInMonth;
        $cashbookDays = [];
        $invoiceDays = [];

        for ($i = 1; $i <= $diasNoMes; $i++) {
            $data = \Carbon\Carbon::create($anoAtual, $mesAtual, $i)->format('Y-m-d');
            $receita = Cashbook::where('user_id', $userId)
                ->where('type_id', 1)
                ->whereDate('date', $data)
                ->exists();
            $despesa = Cashbook::where('user_id', $userId)
                ->where('type_id', 2)
                ->whereDate('date', $data)
                ->exists();
            if ($receita || $despesa) {
                $cashbookDays[$data] = [
                    'receita' => $receita,
                    'despesa' => $despesa
                ];
            }
            $hasInvoice = \App\Models\Invoice::where('user_id', $userId)
                ->whereDate('invoice_date', $data)
                ->exists();
            if ($hasInvoice) {
                $invoiceDays[] = $data;
            }
        }

        return view('dashboard.cashbook.index', compact(
            'totalReceitas',
            'totalDespesas',
            'saldoTotal',
            'ano',
            'meses',
            'dadosReceita',
            'dadosDespesa',
            'saldosMes',
            'saldoUltimoMes',
            'nomeUltimoMes',
            'receitaUltimoMes',
            'despesaUltimoMes',
            'bancos',
            'bancosInfo',
            'totalBancos',
            'totalInvoicesBancos',
            'saldoTotalBancos',
            'totalSaidasBancos',
            'bancosEvolucaoMeses',
            'bancosEvolucaoSaldos',
            'diasInvoices',
            'valoresInvoices',
            'mesInvoices',
            'anoInvoices',
            'cashbookDays',
            'invoiceDays'
        ));
    }

    public function cashbookChartData(Request $request)
    {
        $userId = Auth::id();
        $ano = $request->input('ano', date('Y'));
        $meses = [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ago', 9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
        ];
        $dadosReceita = [];
        $dadosDespesa = [];
        $saldosMes = [];
        foreach ($meses as $num => $nome) {
            $receita = (float) Cashbook::where('user_id', $userId)
                ->where('type_id', 1)
                ->whereYear('date', $ano)
                ->whereMonth('date', $num)
                ->sum('value');
            $despesa = (float) Cashbook::where('user_id', $userId)
                ->where('type_id', 2)
                ->whereYear('date', $ano)
                ->whereMonth('date', $num)
                ->sum('value');
            $dadosReceita[] = $receita;
            $dadosDespesa[] = $despesa;
            $saldosMes[] = $receita - $despesa;
        }
        $ultimoMes = null;
        foreach (array_reverse(array_keys($meses)) as $i) {
            if ($dadosReceita[$i-1] != 0 || $dadosDespesa[$i-1] != 0) {
                $ultimoMes = $i;
                break;
            }
        }
        $nomeUltimoMes = $ultimoMes ? $meses[$ultimoMes] : '-';
        $receitaUltimoMes = $ultimoMes ? $dadosReceita[$ultimoMes-1] : 0;
        $despesaUltimoMes = $ultimoMes ? $dadosDespesa[$ultimoMes-1] : 0;
        $saldoUltimoMes = $ultimoMes ? $saldosMes[$ultimoMes-1] : 0;

        return response()->json([
            'dadosReceita' => $dadosReceita,
            'dadosDespesa' => $dadosDespesa,
            'saldosMes' => $saldosMes,
            'saldoUltimoMes' => $saldoUltimoMes,
            'nomeUltimoMes' => $nomeUltimoMes,
            'receitaUltimoMes' => $receitaUltimoMes,
            'despesaUltimoMes' => $despesaUltimoMes,
        ]);
    }

    public function invoicesDailyChartData(Request $request)
    {
        $userId = \Auth::id();
        $mes = $request->input('mes', now()->month);
        $ano = $request->input('ano', now()->year);

        $diasInvoices = [];
        $valoresInvoices = [];
        $diasNoMes = \Carbon\Carbon::create($ano, $mes, 1)->daysInMonth;
        for ($i = 1; $i <= $diasNoMes; $i++) {
            $data = \Carbon\Carbon::create($ano, $mes, $i);
            $diasInvoices[] = $data->format('d/m');
            $valorDia = \App\Models\Invoice::where('user_id', $userId)
                ->whereDate('invoice_date', $data)
                ->sum('value');
            $valoresInvoices[] = (float)$valorDia;
        }

        return response()->json([
            'diasInvoices' => $diasInvoices,
            'valoresInvoices' => $valoresInvoices,
        ]);
    }
/**
     * Retorna os dias do mês/ano com receitas, despesas e invoices para o calendário (AJAX)
     */
    public function getCalendarMarkers(Request $request)
    {
        $userId = Auth::id();
        $mes = $request->input('mes', now()->month);
        $ano = $request->input('ano', now()->year);
        $diasNoMes = \Carbon\Carbon::create($ano, $mes, 1)->daysInMonth;
        $cashbookDays = [];
        $invoiceDays = [];
        for ($i = 1; $i <= $diasNoMes; $i++) {
            $data = \Carbon\Carbon::create($ano, $mes, $i)->format('Y-m-d');
            $receita = \App\Models\Cashbook::where('user_id', $userId)
                ->where('type_id', 1)
                ->whereDate('date', $data)
                ->exists();
            $despesa = \App\Models\Cashbook::where('user_id', $userId)
                ->where('type_id', 2)
                ->whereDate('date', $data)
                ->exists();
            if ($receita || $despesa) {
                $cashbookDays[$data] = [
                    'receita' => $receita,
                    'despesa' => $despesa
                ];
            }
            $hasInvoice = \App\Models\Invoice::where('user_id', $userId)
                ->whereDate('invoice_date', $data)
                ->exists();
            if ($hasInvoice) {
                $invoiceDays[] = $data;
            }
        }
        return response()->json([
            'cashbookDays' => $cashbookDays,
            'invoiceDays' => $invoiceDays
        ]);
    }
    
    /**
     * Retorna receitas, despesas e invoices de um dia específico (AJAX)
     */
    public function getDayDetails(Request $request)
    {
        $userId = Auth::id();
        $date = $request->input('date'); // formato Y-m-d
        if (!$date) {
            return response()->json(['error' => 'Data não informada'], 400);
        }
        // Receitas
        $receitas = \App\Models\Cashbook::where('user_id', $userId)
            ->where('type_id', 1)
            ->whereDate('date', $date)
            ->with('category')
            ->get()
            ->map(function($item) {
                return [
                    'value' => $item->value,
                    'description' => $item->description,
                    'category' => $item->category ? $item->category->name : null,
                    'category_color' => $item->category ? $item->category->hexcolor_category : null,
                    'category_icon' => $item->category ? $item->category->icone : null,
                ];
            });
        // Despesas
        $despesas = \App\Models\Cashbook::where('user_id', $userId)
            ->where('type_id', 2)
            ->whereDate('date', $date)
            ->with('category')
            ->get()
            ->map(function($item) {
                return [
                    'value' => $item->value,
                    'description' => $item->description,
                    'category' => $item->category ? $item->category->name : null,
                    'category_color' => $item->category ? $item->category->hexcolor_category : null,
                    'category_icon' => $item->category ? $item->category->icone : null,
                ];
            });
        // Invoices
        $invoices = \App\Models\Invoice::where('user_id', $userId)
            ->whereDate('invoice_date', $date)
            ->with('category')
            ->get()
            ->map(function($item) {
                return [
                    'value' => $item->value,
                    'description' => $item->description,
                    'category' => $item->category ? $item->category->name : null,
                    'category_color' => $item->category ? $item->category->hexcolor_category : null,
                    'category_icon' => $item->category ? $item->category->icone : null,
                ];
            });
        return response()->json([
            'receitas' => $receitas,
            'despesas' => $despesas,
            'invoices' => $invoices
        ]);
    }
}

// Todos os métodos presentes (index, cashbookChartData, invoicesDailyChartData) são utilizados pelos gráficos e pela view.
// Nenhuma função extra para remover.
