<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use App\Models\Cofrinho;

class UploadCashbookController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,csv|max:2048',
        ]);

        $file = $request->file('file');
        $transactions = [];

        if ($file->getClientOriginalExtension() === 'pdf') {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($file->getPathname());
            $text = $pdf->getText();

            // Extrair transações do PDF
            $transactions = $this->extractTransactionsFromPdf($text);
        } elseif ($file->getClientOriginalExtension() === 'csv') {
            $transactions = $this->parseCsvFile($file);
        }

        // Obter categorias ativas do usuário
        $categories = \App\Models\Category::where('is_active', 1)
            ->where('user_id', auth()->id())
            ->select(['name as name', 'id_category as id'])
            ->get();

        // Obter segmentos ativos do usuário
        $segments = \App\Models\Segment::where('user_id', auth()->id())
            ->select(['name', 'id'])
            ->get();

        // Obter todos os clientes
        $clients = \App\Models\Client::all(['id', 'name']);

        $cofrinhos = Cofrinho::where('user_id', auth()->id())->get();

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
            'categories' => $categories,
            'segments' => $segments,
            'clients' => $clients,
            'cofrinhos' => $cofrinhos,
        ]);
    }

    public function confirm(Request $request)
    {
        $transactions = $request->input('transactions');
        $cofrinho_id = $request->input('cofrinho_id');
        $success = true;
        $duplicated = [];
        $inserted = [];

        if (!$transactions || !is_array($transactions)) {
            return redirect()->route('cashbook.index')->with('error', 'Nenhuma transação recebida.');
        }

        foreach ($transactions as $idx => $trans) {
            try {
                // Verificar e corrigir o formato da data
                $dateFormats = ['d-m-Y', 'Y-m-d'];
                $validDate = false;
                $dateFormatted = null;

                foreach ($dateFormats as $format) {
                    if (\Carbon\Carbon::hasFormat($trans['date'], $format)) {
                        $dateFormatted = \Carbon\Carbon::createFromFormat($format, $trans['date'])->format('Y-m-d');
                        $validDate = true;
                        break;
                    }
                }

                if (!$validDate) {
                    return redirect()->route('cashbook.index')->with('error', 'Erro: Data inválida ou ausente em uma das transações.');
                }

                // Validar campos obrigatórios
                if (empty($trans['value']) || empty($trans['category_id']) || empty($trans['type_id'])) {
                    return redirect()->route('cashbook.index')->with('error', 'Erro: Campos obrigatórios ausentes em uma das transações.');
                }

                // Verificar duplicidade
                $exists = \App\Models\Cashbook::where('user_id', auth()->id())
                    ->where('date', $dateFormatted)
                    ->where('value', $trans['value'])
                    ->where('description', $trans['description'] ?? null)
                    ->where('category_id', $trans['category_id'])
                    ->where('type_id', $trans['type_id'])
                    ->where(function($q) use ($trans) {
                        if (isset($trans['client_id'])) {
                            $q->where('client_id', $trans['client_id']);
                        } else {
                            $q->whereNull('client_id');
                        }
                    })
                    ->exists();

                if ($exists) {
                    $duplicated[] = [
                        'date' => $trans['date'],
                        'value' => $trans['value'],
                        'description' => $trans['description'] ?? '',
                    ];
                    continue;
                }

                // Salvar normalmente
                $cashbook = new \App\Models\Cashbook();
                $cashbook->user_id = auth()->id();
                $cashbook->client_id = $trans['client_id'] ?? null;
                $cashbook->date = $dateFormatted;
                $cashbook->value = $trans['value'];
                $cashbook->description = $trans['description'] ?? null;
                $cashbook->category_id = $trans['category_id'];
                $cashbook->type_id = $trans['type_id'];
                $cashbook->is_pending = $trans['is_pending'] ?? 0;
                $cashbook->cofrinho_id = $cofrinho_id ?? null;

                if ($cashbook->save()) {
                    $inserted[] = [
                        'date' => $dateFormatted,
                        'value' => $trans['value'],
                        'description' => $trans['description'] ?? '',
                    ];
                } else {
                    $success = false;
                }
            } catch (\Exception $e) {
                \Log::error('Exceção ao salvar transação', [
                    'index' => $idx,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $success = false;
            }
        }

        if ($success) {
            $msg = 'Transações salvas com sucesso.';
            if (count($duplicated) > 0) {
                // Mensagem resumida + detalhes para modal
                return redirect()->route('cashbook.index')->with([
                    'success' => $msg,
                    'warning' => 'Algumas transações não foram inseridas pois já existiam.',
                    'warning_details' => [
                        'inserted' => $inserted,
                        'duplicated' => $duplicated
                    ]
                ]);
            }
            return redirect()->route('cashbook.index')->with('success', $msg);
        } else {
            \Log::error('Houve um erro ao salvar uma ou mais transações');
            return redirect()->route('cashbook.index')->with('error', 'Houve um erro ao salvar as transações.');
        }
    }

    protected function processTransactions()
    {
        $transactions = \App\Models\Cashbook::where('value', '<', 0)
            ->where('type_id', 1)
            ->get();

        foreach ($transactions as $transaction) {
            $transaction->value = abs($transaction->value);

            if (!$transaction->save()) {
                \Log::error('Erro ao processar a transação ID ' . $transaction->id);
            }
        }
    }

    protected function extractTransactionsFromPdf($text)
    {
        $transactions = [];
        $lines = explode("\n", $text);
        $currentTransaction = [
            'date' => null,
            'description' => '',
            'value' => null,
            'category_id' => null,
            'type_id' => null,
        ];

        $categoryMapping = [
            'PIX' => '1013',
            'Rendimentos' => '1016',
            'Santander' => '1014',
            'Inter' => '1015',
        ];

        $irrelevantKeywords = [
            'Saldo final',
            'ID da operação',
            'Valor',
            'Saldo',
            'Página',
            '2/',
            '3/',
        ];


        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            if (empty($trimmedLine)) {
                continue;
            }


            $skipLine = false;
            foreach ($irrelevantKeywords as $keyword) {
                if (stripos($trimmedLine, $keyword) !== false) {
                    $skipLine = true;
                    break;
                }
            }
            if ($skipLine) {
                continue;
            }

            if (preg_match('/(\d{2}-\d{2}-\d{4})/', $trimmedLine, $dateMatches)) {
                if (!empty($currentTransaction['date']) && !is_null($currentTransaction['value'])) {
                    $currentTransaction['category_id'] = $this->determineCategoryId($currentTransaction['description'], $categoryMapping);

                    if ($currentTransaction['value'] < 0) {
                        $currentTransaction['value'] = abs($currentTransaction['value']);
                    }

                    $transactions[] = $currentTransaction;
                }

                $currentTransaction = [
                    'date' => $dateMatches[1],
                    'description' => '',
                    'value' => null,
                    'category_id' => null,
                    'type_id' => null,
                ];
                $trimmedLine = trim(str_replace($dateMatches[0], '', $trimmedLine));
            }

            if (strpos($trimmedLine, 'R$') !== false) {
                if (preg_match('/R\$\s*([-]?\d{1,3}(?:\.\d{3})*(?:,\d{2})?)/', $trimmedLine, $valueMatches)) {
                    $currentTransaction['value'] = str_replace(',', '.', str_replace('.', '', $valueMatches[1]));
                    $currentTransaction['value'] = abs($currentTransaction['value']);
                    $currentTransaction['type_id'] = (strpos($valueMatches[1], '-') === 0) ? '2' : '1';
                    $trimmedLine = trim(str_replace($valueMatches[0], '', $trimmedLine));
                }
            }

            if (preg_match('/(\D+)(\d{8,})/', $trimmedLine, $descriptionMatches)) {
                $trimmedLine = $descriptionMatches[1];
            }

            $trimmedLine = preg_replace('/\b\d{8,}\b/', '', $trimmedLine);
            $trimmedLine = preg_replace('/R\$\s*[-]?\d{1,3}(?:\.\d{3})*(?:,\d{2})?/', '', $trimmedLine);

            if (!empty($trimmedLine)) {
                if (!empty($currentTransaction['description'])) {
                    $currentTransaction['description'] .= ' ';
                }
                $currentTransaction['description'] .= trim($trimmedLine);

            }
        }

        if (!empty($currentTransaction['date']) && !is_null($currentTransaction['value'])) {
            if (empty($currentTransaction['description'])) {
                $currentTransaction['description'] = 'Rendimentos';
            }
            $currentTransaction['category_id'] = $this->determineCategoryId($currentTransaction['description'], $categoryMapping);

            if ($currentTransaction['value'] < 0) {
                $currentTransaction['value'] = abs($currentTransaction['value']);
            }

            $transactions[] = $currentTransaction;
        }


        return array_filter($transactions, function ($transaction) {
            return !empty($transaction['date']) && !is_null($transaction['value']);
        });
    }

    private function determineCategoryId($description, $categoryMapping)
    {
        foreach ($categoryMapping as $keyword => $categoryId) {
            if (stripos($description, $keyword) !== false) {
                return $categoryId;
            }
        }
        return null;
    }

    private function parseCsvFile($file)
    {
        $transactions = [];
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $transactions[] = [
                    'date' => $data[0],
                    'value' => $data[1],
                    'description' => $data[2],
                    'category_id' => null,
                    'type_id' => null,
                    'note' => null,
                    'segment_id' => null,
                ];
            }
            fclose($handle);
        }
        return $transactions;
    }
}
