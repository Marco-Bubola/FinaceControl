<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        // Obter as transações (filtro por usuário logado)
        $invoices = Invoice::where('user_id', auth()->id())
            ->with(['bank', 'category']) // Relacionando com o banco e a categoria
            ->orderBy('created_at', 'desc')
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    // Outras funções, como 'store', 'edit', 'update', 'destroy', podem ser adicionadas conforme necessário.
}
