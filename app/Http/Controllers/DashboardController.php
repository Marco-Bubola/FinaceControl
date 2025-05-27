<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cashbook;
use App\Models\Product;
use App\Models\Client;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Soma de receitas e despesas do cashbook do usuário
        $totalReceitas = Cashbook::where('user_id', $userId)->where('type_id', 1)->sum('value');
        $totalDespesas = Cashbook::where('user_id', $userId)->where('type_id', 2)->sum('value');
        $totalCashbook = $totalReceitas - $totalDespesas;

        // Produtos do usuário
        $totalProdutos = Product::where('user_id', $userId)->count();
        $totalProdutosEstoque = Product::where('user_id', $userId)->sum('stock_quantity');

        // Clientes do usuário
        $totalClientes = Client::where('user_id', $userId)->count();
        $clientesComSalesPendentes = Client::where('user_id', $userId)
            ->whereHas('sales', function($q) use ($userId) {
                $q->where('status', 'pendente')->where('user_id', $userId);
            })->count();

        // Faturamento do usuário
        $totalFaturamento = Sale::where('user_id', $userId)->sum('total_price');

        // Valor faltante: soma do valor_total das vendas pendentes - soma dos pagamentos feitos para essas vendas
        $salesPendentes = Sale::where('user_id', $userId)->where('status', 'pendente')->get(['id', 'total_price']);
        $idsPendentes = $salesPendentes->pluck('id');
        $totalPendentes = $salesPendentes->sum('total_price');
        $totalPagamentos = DB::table('sale_payments')
            ->whereIn('sale_id', $idsPendentes)
            
            ->sum('amount_paid');
        $totalFaltante = $totalPendentes - $totalPagamentos;

        // Totais de receitas e despesas do usuário
        $totalReceitas = Cashbook::where('user_id', $userId)->where('type_id', 1)->sum('value');
        $totalDespesas = Cashbook::where('user_id', $userId)->where('type_id', 2)->sum('value');
        $saldoTotal = $totalReceitas - $totalDespesas;

        // Ano selecionado (padrão: ano atual)
        $ano = $request->input('ano', date('Y'));

        // Dados mensais para o gráfico
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

        // Descobrir o último mês com movimentação
        $ultimoMes = null;
        foreach (array_reverse(array_keys($meses)) as $i) {
            if ($dadosReceita[$i-1] != 0 || $dadosDespesa[$i-1] != 0) {
                $ultimoMes = $i;
                break;
            }
        }
        $nomeUltimoMes = $ultimoMes ? $meses[$ultimoMes] : '-';

        // Receitas, despesas e saldo apenas do último mês com movimentação
        $receitaUltimoMes = $ultimoMes ? $dadosReceita[$ultimoMes-1] : 0;
        $despesaUltimoMes = $ultimoMes ? $dadosDespesa[$ultimoMes-1] : 0;
        $saldoUltimoMes = $ultimoMes ? $saldosMes[$ultimoMes-1] : 0;

        // Últimos 8 produtos adicionados com estoque > 0
        $ultimosProdutos = \App\Models\Product::where('user_id', $userId)
            ->where('stock_quantity', '>', 0)
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        $produtosTodos = \App\Models\Product::where('user_id', $userId)
            ->where('stock_quantity', '>', 0)
            ->get(['price', 'price_sale']);

        // Soma de todas as despesas (price) e receitas (price_sale) dos produtos com estoque > 0
        $totalDespesasProdutos = $produtosTodos->sum('price');
        $totalReceitasProdutos = $produtosTodos->sum('price_sale');
        $totalSaldoProdutos = $totalReceitasProdutos - $totalDespesasProdutos;

        // Últimos clientes com sales pendentes (agora todos)
        $clientesPendentes = \App\Models\Client::where('user_id', $userId)
            ->whereHas('sales', function($q) {
                $q->where('status', 'pendente');
            })
            ->orderByDesc('created_at')
            ->with(['sales' => function($q) {
                $q->where('status', 'pendente');
            }])
            ->get();

        // Para cada venda pendente, calcule o valor restante a receber
        foreach ($clientesPendentes as $cliente) {
            foreach ($cliente->sales as $sale) {
                $pagamentos = \DB::table('sale_payments')
                    ->where('sale_id', $sale->id)
                    ->sum('amount_paid');
                $sale->valor_restante = $sale->total_price - $pagamentos;
            }
        }

        return view('dashboard.index', compact(
            'totalCashbook',
            'totalProdutos',
            'totalProdutosEstoque',
            'totalClientes',
            'clientesComSalesPendentes',
            'totalFaturamento',
            'totalFaltante',
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
            'ultimosProdutos',
            'clientesPendentes',
            'totalDespesasProdutos',
            'totalReceitasProdutos',
            'totalSaldoProdutos'
        ));
    }

    // AJAX para gráfico dinâmico
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
}
