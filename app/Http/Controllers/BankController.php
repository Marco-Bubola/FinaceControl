<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class BankController extends Controller
{
    // Método para exibir todos os bancos
    public function index(Request $request)
    {
        // Obtendo apenas os bancos do usuário logado
        $banks = Bank::where('user_id', auth()->id())->get();

        // Lista de ícones dos principais bancos brasileiros
        $bankIcons = [
            [
                'name' => 'Nubank',
                'icon' => asset('assets/img/banks/nubank.svg'),
            ],
            [
                'name' => 'Inter',
                'icon' => asset('assets/img/banks/inter.png'),
            ],
            [
                'name' => 'Santander',
                'icon' => asset('assets/img/banks/santander.png'),
            ],
            [
                'name' => 'Itaú',
                'icon' => asset('assets/img/banks/itau.png'),
            ],
            [
                'name' => 'Banco do Brasil',
                'icon' => asset('assets/img/banks/bb.png'),
            ],
            [
                'name' => 'Caixa',
                'icon' => asset('assets/img/banks/caixa.png'),
            ],
            [
                'name' => 'Bradesco',
                'icon' => asset('assets/img/banks/bradesco.png'),
            ],
        ];

        // Obtendo o mês e o ano da requisição ou usando o mês e ano atual como padrão
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Validando os valores de mês e ano
        if (!is_numeric($month) || $month < 1 || $month > 12) {
            $month = now()->month;
        }
        if (!is_numeric($year) || $year < 1900 || $year > now()->year + 1) {
            $year = now()->year;
        }

        // Ajustar o filtro de datas para garantir que o intervalo está correto
        $startOfMonth = \Carbon\Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = \Carbon\Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        // Obtendo as transações do usuário logado para o mês e ano selecionados
        $invoices = Invoice::where('user_id', auth()->id())
            ->whereBetween('invoice_date', [$startOfMonth, $endOfMonth]) // Certifique-se de que o intervalo está correto
            ->with(['bank', 'category'])
            ->orderBy('invoice_date', 'asc') // Ordem crescente
            ->get();

      // Obtém a maior fatura
      $highestInvoice = $invoices->sortByDesc('value')->first();

      // Obtém a menor fatura
      $lowestInvoice = $invoices->sortBy('value')->first();

      // Conta o total de transações no mês
      $totalTransactions = $invoices->count();

        // Calculando o valor total das transações do mês
        $totalMonth = $invoices->sum('value');

        // Agrupar as transações por data (dia)
        $groupedInvoices = $invoices->groupBy(function ($invoice) {
            return \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d');
        });

        // Adicionar dados de categorias para o gráfico de categorias
        $categoryTotals = $invoices->groupBy('category_id')->map(function ($group) {
            $category = $group->first()->category;
            return [
                'label' => $category->name ?? 'Sem Categoria',
                'value' => $group->sum('value'),
                'hexcolor' => $category->hexcolor_category ?? '#cccccc',
                'icone' => $category->icone ?? 'fas fa-question', // Adiciona o ícone da categoria
            ];
        })->values();

        // Adicionar dados diários para o gráfico de linha
        $dailyData = $invoices->groupBy(function ($invoice) {
            return \Carbon\Carbon::parse($invoice->invoice_date)->format('d'); // Agrupa por dia do mês
        })->map(function ($group) {
            return $group->sum('value'); // Soma os valores das faturas por dia
        });

        // Verificar se é uma requisição AJAX
        if ($request->ajax()) {
            return response()->json([
                'groupedInvoices' => $groupedInvoices->map(function ($invoices) {
                    return $invoices->map(function ($invoice) {
                        return [
                            'description' => $invoice->description,
                            'value' => $invoice->value,
                            'type_id' => $invoice->type_id,
                            'invoice_date' => $invoice->invoice_date,
                            'category_hexcolor_category' => $invoice->category->hexcolor_category ?? '#cccccc',
                            'category_icone' => $invoice->category->icone ?? 'fas fa-question',

                        ];
                    });
                }),
                'month' => $month,
                'year' => $year,
                'totalMonth' => $totalMonth,
                'highestInvoice' => $highestInvoice ? number_format($highestInvoice->value, 2) : '0,00',
                'lowestInvoice' => $lowestInvoice ? number_format($lowestInvoice->value, 2) : '0,00',
                'totalTransactions' => $totalTransactions,
                'totals' => $categoryTotals, // Dados para o gráfico de categorias
                'dailyData' => [
                    'labels' => $dailyData->keys()->toArray(), // Dias do mês
                    'values' => $dailyData->values()->toArray(), // Valores das faturas por dia
                ],

            ]);
        }

        // Retornando a view com as transações agrupadas, os dados do mês/ano e o total
        return view('banks.index', compact(
            'banks',
            'groupedInvoices',
            'month',
            'year',
            'totalMonth',
            'highestInvoice',
            'lowestInvoice',
            'totalTransactions',
            'bankIcons'
        ));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        // Lista de ícones dos principais bancos brasileiros
        // Você pode trocar o caminho para SVGs ou classes FontAwesome conforme preferir
        // Exemplo usando FontAwesome e imagens em public/assets/img/
        // 'icon' pode ser uma classe ou caminho de imagem
        
        // Exemplo com imagens (ajuste o caminho conforme necessário)
        // Se preferir FontAwesome, use 'icon' => 'fab fa-cc-mastercard', etc.
        $bankIcons = [
            [
                'name' => 'Nubank',
                'icon' => asset('assets/img/banks/nubank.png'),
            ],
            [
                'name' => 'Inter',
                'icon' => asset('assets/img/banks/inter.png'),
            ],
            [
                'name' => 'Santander',
                'icon' => asset('assets/img/banks/santander.png'),
            ],
            [
                'name' => 'Itaú',
                'icon' => asset('assets/img/banks/itau.png'),
            ],
            [
                'name' => 'Banco do Brasil',
                'icon' => asset('assets/img/banks/bb.png'),
            ],
            [
                'name' => 'Caixa',
                'icon' => asset('assets/img/banks/caixa.png'),
            ],
            [
                'name' => 'Bradesco',
                'icon' => asset('assets/img/banks/bradesco.png'),
            ],
            // Adicione outros bancos se quiser
        ];
        return view('banks.create', compact('bankIcons'));
    }
    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start_date' => 'required|date',  // A data precisa ser válida
            'end_date' => 'required|date',    // A data precisa ser válida
            'caminho_icone' => 'required|string', // Ícone obrigatório
        ]);

        // Criando um novo banco/cartão
        $bank = new Bank();
        $bank->name = $request->name;
        $bank->description = $request->description;

        // Salvar as datas completas, incluindo ano, mês e dia
        $bank->start_date = $request->start_date;  // Salva a data como está
        $bank->end_date = $request->end_date;      // Salva a data como está

        // Atribui o ID do usuário logado
        $bank->user_id = auth()->id();
        $bank->caminho_icone = $request->caminho_icone; // Salva o ícone escolhido

        // Salva os dados no banco
        $bank->save();

        return redirect()->route('banks.index')->with('success', 'Cartão adicionado com sucesso!');
    }


    // Método para editar um banco
    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        $bankIcons = [
            [
                'name' => 'Nubank',
                'icon' => asset('assets/img/banks/nubank.png'),
            ],
            [
                'name' => 'Inter',
                'icon' => asset('assets/img/banks/inter.png'),
            ],
            [
                'name' => 'Santander',
                'icon' => asset('assets/img/banks/santander.png'),
            ],
            [
                'name' => 'Itaú',
                'icon' => asset('assets/img/banks/itau.png'),
            ],
            [
                'name' => 'Banco do Brasil',
                'icon' => asset('assets/img/banks/bb.png'),
            ],
            [
                'name' => 'Caixa',
                'icon' => asset('assets/img/banks/caixa.png'),
            ],
            [
                'name' => 'Bradesco',
                'icon' => asset('assets/img/banks/bradesco.png'),
            ],
        ];
        return view('banks.edit', compact('bank', 'bankIcons'));
    }

    // Método para atualizar um banco
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'caminho_icone' => 'required|string',
        ]);

        $bank = Bank::findOrFail($id);
        $bank->update($request->all());

        return redirect()->route('banks.index')->with('success', 'Cartão atualizado com sucesso!');
    }

    // Método para deletar um banco
    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);

        // Excluir todas as faturas relacionadas ao banco
        Invoice::where('id_bank', $bank->id_bank)->delete();

        // Excluir o banco
        $bank->delete();

        return redirect()->route('banks.index')->with('success', 'Cartão e suas faturas foram excluídos com sucesso.');
    }
}
