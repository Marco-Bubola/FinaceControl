<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Cashbook;
use Illuminate\Http\Request;

class ClienteResumoController extends Controller
{
    public function index($clienteId)
    {
        // Buscar o cliente pelo ID
        $cliente = Client::findOrFail($clienteId);

        // Calcular totais
        $totalFaturas = Invoice::where('client_id', $clienteId)->sum('value');
        $totalRecebido = Cashbook::where('client_id', $clienteId)->where('type_id', 1)->sum('value');
        $totalEnviado = Cashbook::where('client_id', $clienteId)->where('type_id', 2)->sum('value');
        $saldoAtual = $totalRecebido - $totalEnviado - $totalFaturas;

        // Listas detalhadas
        $faturas = Invoice::where('client_id', $clienteId)
            ->select('invoice_date', 'description', 'value')
            ->orderBy('invoice_date', 'desc')
            ->get();

        $transferencias = Cashbook::where('client_id', $clienteId)
            ->select('type_id', 'value', 'date', 'description')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($transferencia) {
                $transferencia->tipo = $transferencia->type_id == 1 ? 'Recebido' : 'Enviado';
                return $transferencia;
            });

        // Retornar para a view
        return view('teste.index', compact(
            'cliente',
            'totalFaturas',
            'totalRecebido',
            'totalEnviado',
            'saldoAtual',
            'faturas',
            'transferencias'
        ));
    }
}
