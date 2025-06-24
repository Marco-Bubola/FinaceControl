<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cofrinho;
use Illuminate\Support\Facades\Auth;

class CofrinhoController extends Controller
{
    public function index()
    {
        $frases = [
            'Meta atingida!' => 'Parabéns! Você conquistou seu objetivo! 🎉',
            'Falta pouco!' => 'Continue firme, está quase lá!',
            'Continue assim!' => 'Ótimo progresso, mantenha o ritmo!',
            'Comece já!' => 'Todo sonho começa com o primeiro passo!'
        ];
        $badgeClasses = [
            'Meta atingida!' => 'bg-success',
            'Falta pouco!' => 'bg-warning text-dark',
            'Continue assim!' => 'bg-info text-dark',
            'Comece já!' => 'bg-secondary',
        ];
        $iconList = [
            'fa-piggy-bank', 'fa-coins', 'fa-gem', 'fa-star', 'fa-heart', 'fa-rocket', 'fa-gift', 'fa-trophy', 'fa-crown', 'fa-seedling'
        ];
        $cofrinhos = Cofrinho::where('user_id', Auth::id())
            ->with(['cashbooks' => function($q) {
                $q->select('id', 'cofrinho_id', 'value', 'type_id', 'created_at');
            }])
            ->withCount('cashbooks')
            ->get()
            ->map(function($cofrinho, $i) use ($frases, $badgeClasses, $iconList) {
                $valorAcumulado = 0;
                foreach ($cofrinho->cashbooks as $cb) {
                    if ($cb->type_id == 1) $valorAcumulado += $cb->value;
                    elseif ($cb->type_id == 2) $valorAcumulado -= $cb->value;
                }
                $meta = $cofrinho->meta_valor;
                $falta = max(0, $meta - $valorAcumulado);
                $percent = $meta > 0 ? min(100, round(($valorAcumulado/$meta)*100)) : 0;
                $isComplete = $percent >= 100;
                $created = $cofrinho->created_at ? $cofrinho->created_at->format('d/m/Y') : null;
                $status = $isComplete ? 'Concluído' : 'Ativo';
                $ultimasTransacoes = $cofrinho->cashbooks->sortByDesc('created_at')->take(3)->values();
                $progressoMensal = $cofrinho->cashbooks
                    ->where('type_id', 1)
                    ->filter(function($cb) {
                        return $cb->created_at && $cb->created_at->format('Y-m') === now()->format('Y-m');
                    })
                    ->sum('value');
                $badgeMotivacao = $isComplete ? 'Meta atingida!' : ($percent >= 80 ? 'Falta pouco!' : ($percent >= 50 ? 'Continue assim!' : 'Comece já!'));
                $badgeClass = $badgeClasses[$badgeMotivacao];
                $fraseMotivacional = $frases[$badgeMotivacao];
                $evolucao = [];
                $saldo = 0;
                foreach ($cofrinho->cashbooks->sortBy('created_at') as $cb) {
                    if ($cb->type_id == 1) $saldo += $cb->value;
                    if ($cb->type_id == 2) $saldo -= $cb->value;
                    $evolucao[] = $saldo;
                }
                // Ícone e cor para cada cofrinho (pode ser random ou baseado no índice)
                $icon = $iconList[$i % count($iconList)];
                $colorClass = ['bg-warning','bg-primary','bg-success','bg-danger','bg-info','bg-secondary','bg-dark','bg-light'][$i % 8];
                return [
                    'id' => $cofrinho->id,
                    'nome' => $cofrinho->nome,
                    'meta' => $meta,
                    'falta' => $falta,
                    'percent' => $percent,
                    'isComplete' => $isComplete,
                    'created' => $created,
                    'status' => $status,
                    'ultimasTransacoes' => $ultimasTransacoes,
                    'progressoMensal' => $progressoMensal,
                    'badgeMotivacao' => $badgeMotivacao,
                    'badgeClass' => $badgeClass,
                    'fraseMotivacional' => $fraseMotivacional,
                    'valorAcumulado' => $valorAcumulado,
                    'qtdTransacoes' => $cofrinho->cashbooks_count,
                    'evolucao' => $evolucao,
                    'icon' => $icon,
                    'colorClass' => $colorClass,
                ];
            });
        $total = $cofrinhos->sum('meta');
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
            'icone' => 'nullable|string|max:50',
        ]);
        Cofrinho::create([
            'user_id' => Auth::id(),
            'nome' => $request->nome,
            'meta_valor' => $request->meta_valor,
            'icone' => $request->icone ?? 'fa-piggy-bank',
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
            'icone' => 'nullable|string|max:50',
        ]);
        $cofrinho = Cofrinho::findOrFail($id);
        $cofrinho->update([
            'nome' => $request->nome,
            'meta_valor' => $request->meta_valor,
            'icone' => $request->icone ?? 'fa-piggy-bank',
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