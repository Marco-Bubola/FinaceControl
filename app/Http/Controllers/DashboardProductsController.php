<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale;

class DashboardProductsController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ticket médio
        $totalVendas = SaleItem::sum(DB::raw('price_sale * quantity'));
        $totalVendasCount = \App\Models\Sale::where('user_id', $userId)->count();
        $ticketMedio = $totalVendasCount > 0 ? $totalVendas / $totalVendasCount : 0;

        // Produtos mais vendidos (top 10)
        $produtosMaisVendidos = SaleItem::select('products.product_code', DB::raw('SUM(quantity) as total_vendido'))
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('products.product_code')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        // Produto com maior receita (top 5)
        $produtosMaiorReceita = SaleItem::select('products.product_code', DB::raw('SUM(sale_items.price_sale * sale_items.quantity) as receita_total'))
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('products.product_code')
            ->orderByDesc('receita_total')
            ->limit(5)
            ->get();

        // Produto com maior lucro (top 5)
        $produtoMaiorLucro = SaleItem::select(
                'products.product_code',
                DB::raw('SUM((sale_items.price_sale - products.price) * sale_items.quantity) as lucro_total')
            )
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('products.product_code')
            ->orderByDesc('lucro_total')
            ->limit(5)
            ->get();

        // Gráfico de vendas por categoria
        $dadosGraficoPizza = SaleItem::select(
            DB::raw('category.name as category_name'),
            DB::raw('SUM(sale_items.quantity) as total_sold')
        )
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('category', 'products.category_id', '=', 'category.id_category')
            ->groupBy('category.name')
            ->get();

        $ultimosProdutos = Product::where('user_id', $userId)
            ->where('stock_quantity', '>', 0)
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        $produtosTodos = Product::where('user_id', $userId)
            ->where('stock_quantity', '>', 0)
            ->get(['price', 'price_sale']);

        $totalDespesasProdutos = $produtosTodos->sum('price');
        $totalReceitasProdutos = $produtosTodos->sum('price_sale');
        $totalSaldoProdutos = $totalReceitasProdutos - $totalDespesasProdutos;

        // Produtos parados (sem venda nos últimos 60 dias)
        $produtosVendidosIds = \App\Models\SaleItem::whereHas('sale', function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->where('created_at', '>=', now()->subDays(60));
            })
            ->pluck('product_id')
            ->unique();
        $produtosParados = Product::where('user_id', $userId)
            ->whereNotIn('id', $produtosVendidosIds)
            ->get();

        // Produtos com estoque baixo e alta demanda (top 10)
        $produtosEstoqueBaixoAltaDemanda = \App\Models\SaleItem::select('products.product_code', DB::raw('SUM(sale_items.quantity) as total_vendido'), 'products.stock_quantity')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('products.product_code', 'products.stock_quantity')
            ->having('products.stock_quantity', '<', 10)
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        // Total de produtos do usuário
        $totalProdutos = Product::where('user_id', $userId)->count();

        // Total de produtos em estoque (>0)
        $totalProdutosEstoque = Product::where('user_id', $userId)
            ->where('stock_quantity', '>', 0)
            ->sum('stock_quantity');

        // Produto com maior estoque
        $produtoMaiorEstoque = Product::where('user_id', $userId)
            ->orderByDesc('stock_quantity')
            ->first();

        // Produto mais vendido (nome)
        $produtoMaisVendido = null;
        if ($produtosMaisVendidos->count() > 0) {
            $produtoMaisVendido = Product::where('product_code', $produtosMaisVendidos->first()->product_code)->first();
        }

        // Produtos sem estoque
        $produtosSemEstoque = Product::where('user_id', $userId)
            ->where('stock_quantity', 0)
            ->count();

        return view('dashboard.products.index', compact(
            'ultimosProdutos',
            'totalDespesasProdutos',
            'totalReceitasProdutos',
            'totalSaldoProdutos',
            'ticketMedio',
            'produtosMaisVendidos',
            'produtosMaiorReceita',
            'produtoMaiorLucro',
            'dadosGraficoPizza',
            'produtosParados',
            'produtosEstoqueBaixoAltaDemanda',
            'totalProdutos',
            'totalProdutosEstoque',
            'produtoMaiorEstoque',
            'produtoMaisVendido',
            'produtosSemEstoque'
        ));
    }
}
