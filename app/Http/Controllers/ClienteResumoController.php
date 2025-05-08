<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Cashbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteResumoController extends Controller
{
    public function index($clienteId)
    {
        // Buscar o cliente pelo ID
        $cliente = Client::findOrFail($clienteId);
        $categories = DB::table('category')
            ->join('invoice', 'category.id_category', '=', 'invoice.category_id') // Certifique-se de que os nomes das tabelas estão corretos
            ->select('category.name as label', DB::raw('SUM(invoice.value) as value'))
            ->where('invoice.client_id', $clienteId) // Filtra pelas faturas do cliente
            ->groupBy('category.name')
            ->havingRaw('SUM(invoice.value) > 0') // Garante que apenas categorias com faturas sejam retornadas
            ->get();
        // Calcular totais
        $totalFaturas = Invoice::where('client_id', $clienteId)->sum('value');
        $totalRecebido = Cashbook::where('client_id', $clienteId)->where('type_id', 1)->sum('value');
        $totalEnviado = Cashbook::where('client_id', $clienteId)->where('type_id', 2)->sum('value');
        $saldoAtual = $totalRecebido - $totalEnviado - $totalFaturas;

        // Dados para o gráfico de pizza
        $totals = [
            'income' => $totalRecebido,
            'expense' => $totalEnviado + $totalFaturas,
            'balance' => $saldoAtual,
        ];

        // Listas detalhadas
        $faturas = Invoice::where('client_id', $clienteId)
            ->select('invoice_date', 'description', 'value','category_id')
            ->orderBy('invoice_date', 'desc')
            ->get();

        $transferencias = Cashbook::where('client_id', $clienteId)
            ->select('type_id', 'value', 'date', 'description', 'category_id')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($transferencia) {
                $transferencia->tipo = $transferencia->type_id == 1 ? 'Recebido' : 'Enviado';
                return $transferencia;
            });

        // Retornar para a view
        return view('resumo.index', compact(
            'cliente',
            'totalFaturas',
            'categories',
            'totals',
            'totalRecebido',
            'totalEnviado',
            'saldoAtual',
            'faturas',
            'transferencias'
        ));
    }
}
