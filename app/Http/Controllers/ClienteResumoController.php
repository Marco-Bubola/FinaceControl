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

        // Buscar categorias e somar valores considerando 'dividida'
        $categories = \DB::table('category')
            ->join('invoice', 'category.id_category', '=', 'invoice.category_id')
            ->select(
                'category.name as label',
                \DB::raw('SUM(CASE WHEN invoice.dividida = 1 THEN invoice.value / 2 ELSE invoice.value END) as value')
            )
            ->where('invoice.client_id', $clienteId)
            ->groupBy('category.name')
            ->havingRaw('SUM(CASE WHEN invoice.dividida = 1 THEN invoice.value / 2 ELSE invoice.value END) > 0')
            ->get();

        // Calcular total de faturas considerando 'dividida'
        $totalFaturas = Invoice::where('client_id', $clienteId)
            ->selectRaw('SUM(CASE WHEN dividida = 1 THEN value / 2 ELSE value END) as total')
            ->value('total');

        $totalRecebido = Cashbook::where('client_id', $clienteId)->where('type_id', 1)->sum('value');
        $totalEnviado = Cashbook::where('client_id', $clienteId)->where('type_id', 2)->sum('value');
        $saldoAtual = $totalRecebido - $totalEnviado - $totalFaturas;

        // Dados para o gráfico de pizza
        $totals = [
            'income' => $totalRecebido,
            'expense' => $totalEnviado + $totalFaturas,
            'balance' => $saldoAtual,
        ];

        // Listas detalhadas (mantém o valor original para exibição individual)
        $faturas = Invoice::where('client_id', $clienteId)
            ->select('id_invoice as id', 'invoice_date', 'description', 'value', 'category_id', 'dividida')
            ->with('category') // Carrega o relacionamento category
            ->orderBy('invoice_date', 'desc')
            ->paginate(6); // <-- Adiciona paginação

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
            'transferencias',
            'clienteId' // <-- Adicione aqui
        ));
    }

    public function faturasAjax(Request $request, $clienteId)
    {
        // Otimize o with e o select
        $faturas = Invoice::where('client_id', $clienteId)
            ->select('id_invoice as id', 'invoice_date', 'description', 'value', 'category_id', 'dividida')
            ->with(['category:id_category,name,icone'])
            ->orderBy('invoice_date', 'desc')
            ->paginate(6);

        if ($request->ajax()) {
            return response()->json([
                'faturas' => view('resumo.partials.faturas', [
                    'faturas' => $faturas,
                    'clienteId' => $clienteId,
                    'onlyFaturas' => true
                ])->render(),
                'pagination' => view('resumo.partials.faturas-pagination', [
                    'faturas' => $faturas,
                    'clienteId' => $clienteId
                ])->render(),
            ]);
        }

        return redirect()->route('clientes.resumo', ['cliente' => $clienteId]);
    }

    public function transferenciasEnviadasAjax(Request $request, $clienteId)
    {
        $enviadas = \App\Models\Cashbook::where('client_id', $clienteId)
            ->where('type_id', 2)
            ->with(['category:id_category,name,icone,hexcolor_category'])
            ->orderBy('date', 'desc')
            ->paginate(6);

        if ($request->ajax()) {
            return response()->json([
                'enviadas' => view('resumo.partials.transferencias-enviadas', [
                    'enviadas' => $enviadas,
                    'clienteId' => $clienteId
                ])->render(),
                'pagination' => view('resumo.partials.transferencias-enviadas-pagination', [
                    'enviadas' => $enviadas,
                    'clienteId' => $clienteId
                ])->render(),
            ]);
        }

        return redirect()->route('clientes.resumo', ['cliente' => $clienteId]);
    }

    public function transferenciasRecebidasAjax(Request $request, $clienteId)
    {
        $recebidas = \App\Models\Cashbook::where('client_id', $clienteId)
            ->where('type_id', 1)
            ->with(['category:id_category,name,icone,hexcolor_category'])
            ->orderBy('date', 'desc')
            ->paginate(6);

        if ($request->ajax()) {
            return response()->json([
                'recebidas' => view('resumo.partials.transferencias-recebidas', [
                    'recebidas' => $recebidas,
                    'clienteId' => $clienteId
                ])->render(),
                'pagination' => view('resumo.partials.transferencias-recebidas-pagination', [
                    'recebidas' => $recebidas,
                    'clienteId' => $clienteId
                ])->render(),
            ]);
        }

        return redirect()->route('clientes.resumo', ['cliente' => $clienteId]);
    }

   
    public function toggleDividida(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $dividida = $request->dividida === true || $request->dividida === 'true' || $request->dividida == 1 ? 1 : 0;
        $invoice->dividida = $dividida;
        $invoice->save();

        $valor = $invoice->dividida ? $invoice->value / 2 : $invoice->value;

        // Atualiza totais e categorias do cliente
        $clienteId = $invoice->client_id;
        if ($clienteId) {
            $categories = \DB::table('category')
                ->join('invoice', 'category.id_category', '=', 'invoice.category_id')
                ->select(
                    'category.name as label',
                    \DB::raw('SUM(CASE WHEN invoice.dividida = 1 THEN invoice.value / 2 ELSE invoice.value END) as value')
                )
                ->where('invoice.client_id', $clienteId)
                ->groupBy('category.name')
                ->havingRaw('SUM(CASE WHEN invoice.dividida = 1 THEN invoice.value / 2 ELSE invoice.value END) > 0')
                ->get();

            $totalFaturas = \App\Models\Invoice::where('client_id', $clienteId)
                ->selectRaw('SUM(CASE WHEN dividida = 1 THEN value / 2 ELSE value END) as total')
                ->value('total');

            $totalRecebido = \App\Models\Cashbook::where('client_id', $clienteId)->where('type_id', 1)->sum('value');
            $totalEnviado = \App\Models\Cashbook::where('client_id', $clienteId)->where('type_id', 2)->sum('value');
            $saldoAtual = $totalRecebido - $totalEnviado - $totalFaturas;

            $totals = [
                'income' => $totalRecebido,
                'expense' => $totalEnviado + $totalFaturas,
                'balance' => $saldoAtual,
            ];
        } else {
            $categories = [];
            $totalFaturas = 0;
            $totals = [];
            $saldoAtual = 0;
            $totalRecebido = 0;
            $totalEnviado = 0;
        }

        return response()->json([
            'success' => true,
            'valor' => number_format($valor, 2, ',', '.'),
            'categories' => $categories,
            'totalFaturas' => $totalFaturas,
            'totals' => $totals,
            'saldoAtual' => $saldoAtual,
            'totalRecebido' => $totalRecebido,
            'totalEnviado' => $totalEnviado,
        ]);
    }
}
