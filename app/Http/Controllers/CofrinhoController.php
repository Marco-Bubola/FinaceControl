<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cofrinho;
use Illuminate\Support\Facades\Auth;

class CofrinhoController extends Controller
{
    public function index()
    {
        $cofrinhos = Cofrinho::where('user_id', Auth::id())
            ->with(['cashbooks' => function($q) {
                $q->select('id', 'cofrinho_id', 'value', 'type_id');
            }])
            ->withCount('cashbooks')
            ->get();
        // Calcular valor acumulado considerando receitas e despesas
        foreach ($cofrinhos as $cofrinho) {
            $valor = 0;
            foreach ($cofrinho->cashbooks as $cb) {
                if ($cb->type_id == 1) { // Receita
                    $valor += $cb->value;
                } elseif ($cb->type_id == 2) { // Despesa
                    $valor -= $cb->value;
                }
            }
            $cofrinho->valor_acumulado = $valor;
        }
        $total = $cofrinhos->sum('meta_valor');
        return view('cofrinho.index', compact('cofrinhos', 'total'));
    }

    public function create()
    {
        return view('cofrinho.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'meta_valor' => 'required|numeric|min:0',
        ]);
        Cofrinho::create([
            'user_id' => Auth::id(),
            'nome' => $request->nome,
            'meta_valor' => $request->meta_valor,
            'status' => 'ativo',
        ]);
        return redirect()->route('cofrinho.index')->with('success', 'Cofrinho criado com sucesso!');
    }

    public function edit($id)
    {
        $cofrinho = Cofrinho::withCount('cashbooks')->findOrFail($id);
        // Calcular valor acumulado
        $valor = 0;
        foreach ($cofrinho->cashbooks as $cb) {
            if ($cb->type_id == 1) {
                $valor += $cb->value;
            } elseif ($cb->type_id == 2) {
                $valor -= $cb->value;
            }
        }
        $cofrinho->valor_acumulado = $valor;
        return response()->json($cofrinho);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'meta_valor' => 'required|numeric|min:0',
        ]);
        $cofrinho = Cofrinho::findOrFail($id);
        $cofrinho->update([
            'nome' => $request->nome,
            'meta_valor' => $request->meta_valor,
        ]);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $cofrinho = Cofrinho::findOrFail($id);
        $cofrinho->delete();
        return response()->json(['success' => true]);
    }
} 