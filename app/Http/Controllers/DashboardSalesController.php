<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Cashbook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardSalesController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Clientes com vendas pendentes
        $clientesPendentes = Client::where('user_id', $userId)
            ->whereHas('sales', function($q) {
                $q->where('status', 'pendente');
            })
            ->orderByDesc('created_at')
            ->with(['sales' => function($q) {
                $q->where('status', 'pendente');
            }])
            ->get();

        foreach ($clientesPendentes as $cliente) {
            foreach ($cliente->sales as $sale) {
                $pagamentos = DB::table('sale_payments')
                    ->where('sale_id', $sale->id)
                    ->sum('amount_paid');
                $sale->valor_restante = $sale->total_price - $pagamentos;
            }
        }

        // Evolução das vendas por mês (últimos 12 meses)
        $vendasPorMesEvolucao = Sale::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as periodo"),
                DB::raw('SUM(total_price) as total')
            )
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();

        // Vendas por cliente (top 10)
        $vendasPorCliente = Sale::select('client_id', DB::raw('SUM(total_price) as total_vendas'), DB::raw('COUNT(*) as qtd_vendas'))
            ->where('user_id', $userId)
            ->groupBy('client_id')
            ->orderByDesc('total_vendas')
            ->with('client')
            ->limit(10)
            ->get();

        // Clientes inativos (sem compras nos últimos 6 meses)
        $clientesInativos = Client::where('user_id', $userId)
            ->whereDoesntHave('sales', function($q) {
                $q->where('created_at', '>=', now()->subMonths(6));
            })
            ->get();

        // Recorrência de clientes
        $clientesRecorrentes = Sale::select('client_id', DB::raw('COUNT(*) as qtd_vendas'), DB::raw('SUM(total_price) as total'))
            ->where('user_id', $userId)
            ->groupBy('client_id')
            ->having('qtd_vendas', '>', 1)
            ->with('client')
            ->get();
        $ticketMedioRecorrente = $clientesRecorrentes->count() > 0
            ? ($clientesRecorrentes->sum('total') / $clientesRecorrentes->sum('qtd_vendas'))
            : 0;

        // Comparativo de vendas por período
        $mesAtual = date('n');
        $anoAtual = date('Y');
        $mesAnterior = $mesAtual == 1 ? 12 : $mesAtual - 1;
        $anoMesAnterior = $mesAtual == 1 ? $anoAtual - 1 : $anoAtual;

        $vendasMesAtual = Sale::where('user_id', $userId)
            ->whereYear('created_at', $anoAtual)
            ->whereMonth('created_at', $mesAtual)
            ->sum('total_price');
        $vendasMesAnterior = Sale::where('user_id', $userId)
            ->whereYear('created_at', $anoMesAnterior)
            ->whereMonth('created_at', $mesAnterior)
            ->sum('total_price');
        $vendasAnoAtual = Sale::where('user_id', $userId)
            ->whereYear('created_at', $anoAtual)
            ->sum('total_price');
        $vendasAnoAnterior = Sale::where('user_id', $userId)
            ->whereYear('created_at', $anoAtual - 1)
            ->sum('total_price');

        // Vendas por status
        $vendasPorStatus = Sale::select('status', DB::raw('COUNT(*) as total'))
            ->where('user_id', $userId)
            ->groupBy('status')
            ->get();

        // Gráfico de vendas por categoria (dadosGraficoPizza)
        $dadosGraficoPizza = \App\Models\SaleItem::select(
            DB::raw('category.name as category_name'),
            DB::raw('SUM(sale_items.quantity) as total_sold')
        )
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('category', 'products.category_id', '=', 'category.id_category')
            ->groupBy('category.name')
            ->get();

   
        // Faturamento
        $totalFaturamento = Sale::where('user_id', $userId)->sum('total_price');

        // Total faltante (somatório do valor restante das vendas pendentes)
        $totalFaltante = (float) (\App\Models\Sale::where('user_id', $userId)
            ->where('status', 'pendente')
            ->get()
            ->sum(function($sale) {
                $pagamentos = \DB::table('sale_payments')
                    ->where('sale_id', $sale->id)
                    ->sum('amount_paid');
                return max($sale->total_price - $pagamentos, 0);
            }) ?? 0);

        // Última venda
        $ultimaVenda = \App\Models\Sale::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->first();

        // Total de clientes
        $totalClientes = \App\Models\Client::where('user_id', $userId)->count();

        // Clientes com vendas pendentes
        $clientesComSalesPendentes = \App\Models\Client::where('user_id', $userId)
            ->whereHas('sales', function($q) {
                $q->where('status', 'pendente');
            })
            ->count();

        return view('dashboard.sales.index', compact(
            'clientesPendentes',
            'vendasPorMesEvolucao',
            'vendasPorCliente',
            'clientesInativos',
            'clientesRecorrentes',
            'ticketMedioRecorrente',
            'vendasMesAtual',
            'vendasMesAnterior',
            'vendasAnoAtual',
            'vendasAnoAnterior',
            'vendasPorStatus',
            'dadosGraficoPizza',
            'totalFaturamento',
            'totalFaltante',
            'ultimaVenda',
            'totalClientes',
            'clientesComSalesPendentes'
        ));
    }
}
