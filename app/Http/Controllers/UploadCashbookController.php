<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

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

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
            'categories' => $categories,
            'segments' => $segments,
            'clients' => $clients, // Inclua os clientes na resposta
        ]);
    }

    public function confirm(Request $request)
    {
        $transactions = $request->input('transactions');
        $success = true;

        foreach ($transactions as $trans) {
            $cashbook = new \App\Models\Cashbook();
            $cashbook->user_id = auth()->id();
            $cashbook->client_id = $trans['client_id'] ?? null; // Salvar client_id como opcional

            // Verificar e corrigir o formato da data
            $dateFormats = ['d-m-Y', 'Y-m-d']; // Suporte para múltiplos formatos
            $validDate = false;

            foreach ($dateFormats as $format) {
                if (\Carbon\Carbon::hasFormat($trans['date'], $format)) {
                    $cashbook->date = \Carbon\Carbon::createFromFormat($format, $trans['date'])->format('Y-m-d');
                    $validDate = true;
                    break;
                }
            }

            if (!$validDate) {
                \Log::error("Data inválida ou ausente: " . ($trans['date'] ?? 'null'));
                return redirect()->route('cashbook.index')->with('error', 'Erro: Data inválida ou ausente em uma das transações.');
            }

            // Validar campos obrigatórios
            if (empty($trans['value']) || empty($trans['category_id']) || empty($trans['type_id'])) {
                \Log::error("Campos obrigatórios ausentes: " . json_encode($trans));
                return redirect()->route('cashbook.index')->with('error', 'Erro: Campos obrigatórios ausentes em uma das transações.');
            }

            $cashbook->value = $trans['value'];
            $cashbook->description = $trans['description'] ?? null;
            $cashbook->category_id = $trans['category_id'];
            $cashbook->type_id = $trans['type_id'];
            $cashbook->segment_id = $trans['segment_id'] ?? null;
            $cashbook->is_pending = $trans['is_pending'] ?? 0;
            $cashbook->note = $trans['note'] ?? null;

            // Log dos dados antes de salvar
            \Log::info("Tentando salvar transação: " . json_encode($cashbook->toArray()));

            // Salvar e registrar logs de erro, se necessário
            if (!$cashbook->save()) {
                $success = false;
                \Log::error("Erro ao salvar a transação: " . json_encode($cashbook->getErrors()));
            } else {
                \Log::info("Transação salva com sucesso: " . json_encode($cashbook->toArray()));
            }
        }

        if ($success) {
            return redirect()->route('cashbook.index')->with('success', 'Transações salvas com sucesso.');
        } else {
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

        \Log::info("Texto extraído do PDF:\n" . $text); // Log do texto completo extraído do PDF

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            if (empty($trimmedLine)) {
                continue;
            }

            \Log::info("Processando linha: " . $trimmedLine); // Log de cada linha processada

            $skipLine = false;
            foreach ($irrelevantKeywords as $keyword) {
                if (stripos($trimmedLine, $keyword) !== false) {
                    \Log::info("Linha ignorada devido à palavra-chave: " . $keyword); // Log de linhas ignoradas
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

                    \Log::info("Transação adicionada: " . json_encode($currentTransaction)); // Log da transação adicionada
                    $transactions[] = $currentTransaction;
                }

                $currentTransaction = [
                    'date' => $dateMatches[1],
                    'description' => '',
                    'value' => null,
                    'category_id' => null,
                    'type_id' => null,
                ];

                \Log::info("Nova transação iniciada com data: " . $dateMatches[1]); // Log da nova transação iniciada

                $trimmedLine = trim(str_replace($dateMatches[0], '', $trimmedLine));
            }

            if (strpos($trimmedLine, 'R$') !== false) {
                if (preg_match('/R\$\s*([-]?\d{1,3}(?:\.\d{3})*(?:,\d{2})?)/', $trimmedLine, $valueMatches)) {
                    $currentTransaction['value'] = str_replace(',', '.', str_replace('.', '', $valueMatches[1]));
                    $currentTransaction['value'] = abs($currentTransaction['value']);
                    $currentTransaction['type_id'] = (strpos($valueMatches[1], '-') === 0) ? '2' : '1';

                    \Log::info("Valor extraído: " . $currentTransaction['value'] . ", Tipo: " . $currentTransaction['type_id']); // Log do valor extraído

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

                \Log::info("Descrição atualizada: " . $currentTransaction['description']); // Log da descrição atualizada
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

            \Log::info("Última transação adicionada: " . json_encode($currentTransaction)); // Log da última transação adicionada
            $transactions[] = $currentTransaction;
        }

        \Log::info("Transações extraídas: " . json_encode($transactions)); // Log de todas as transações extraídas

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
