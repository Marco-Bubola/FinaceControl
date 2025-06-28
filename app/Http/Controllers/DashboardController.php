<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cashbook;
use App\Models\Product;
use App\Models\Client;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Saldo total do cashbook
        $totalReceitas = Cashbook::where('user_id', $userId)->where('type_id', 1)->sum('value');
        $totalDespesas = Cashbook::where('user_id', $userId)->where('type_id', 2)->sum('value');
        $totalCashbook = $totalReceitas - $totalDespesas;

        // Produtos
        $totalProdutos = Product::where('user_id', $userId)->count();
        $totalProdutosEstoque = Product::where('user_id', $userId)->sum('stock_quantity');
        $totalProdutosSemEstoque = Product::where('user_id', $userId)->where('stock_quantity', 0)->count();

        // Clientes
        $totalClientes = Client::where('user_id', $userId)->count();
        $clientesComSalesPendentes = Client::where('user_id', $userId)
            ->whereHas('sales', function($q) use ($userId) {
                $q->where('status', 'pendente')->where('user_id', $userId);
            })->count();

        // Faturamento
        $totalFaturamento = Sale::where('user_id', $userId)->sum('total_price');

        // Valor faltante (vendas pendentes - pagamentos)
        $salesPendentes = Sale::where('user_id', $userId)->where('status', 'pendente')->get(['id', 'total_price']);
        $idsPendentes = $salesPendentes->pluck('id');
        $totalPendentes = $salesPendentes->sum('total_price');
        $totalPagamentos = DB::table('sale_payments')
            ->whereIn('sale_id', $idsPendentes)
            ->sum('amount_paid');
        $totalFaltante = $totalPendentes - $totalPagamentos;

        // Card Cashbook: última movimentação
        $ultimaMovimentacaoCashbook = Cashbook::where('user_id', $userId)
            ->orderByDesc('date')
            ->first();

        // Média diária do cashbook
        $mediaDiariaCashbook = Cashbook::where('user_id', $userId)
            ->selectRaw('SUM(value)/GREATEST(DATEDIFF(MAX(date), MIN(date)),1) as media')
            ->value('media');

        // Card Produtos: produto com maior estoque
        $produtoMaiorEstoque = Product::where('user_id', $userId)
            ->orderByDesc('stock_quantity')
            ->first();

        // Produto mais vendido (nome)
        $produtoMaisVendido = \App\Models\SaleItem::select('products.name', DB::raw('SUM(quantity) as total_vendido'))
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('products.user_id', $userId)
            ->groupBy('products.name')
            ->orderByDesc('total_vendido')
            ->first();

        // Card Vendas: última venda
        $ultimaVenda = Sale::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->first();

        return view('dashboard.index', compact(
            'totalCashbook',
            'totalReceitas',
            'totalDespesas',
            'totalProdutos',
            'totalProdutosEstoque',
            'totalProdutosSemEstoque',
            'totalClientes',
            'clientesComSalesPendentes',
            'totalFaturamento',
            'totalFaltante',
            'ultimaMovimentacaoCashbook',
            'mediaDiariaCashbook',
            'produtoMaiorEstoque',
            'ultimaVenda',
            'produtoMaisVendido'
        ));
    }
}
