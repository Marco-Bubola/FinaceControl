<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UploadInvoiceController extends Controller
{
    public function __construct()
    {
        // Garantir que o usuário esteja autenticado para acessar os métodos
        $this->middleware('auth');
    }

    public function upload(Request $request)
    {
        // Validação do arquivo PDF ou CSV
        $request->validate([
            'file' => 'required|file|mimes:pdf,csv|',
        ]);

        // Armazenar o arquivo
        $file = $request->file('file');
        $filePath = $file->store('uploads');
        $fileExtension = $file->getClientOriginalExtension();
        $transactions = [];

        // Verificar o tipo do arquivo e processá-lo
        if (strtolower($fileExtension) === 'pdf') {
            $transactions = $this->extractTransactionsFromPdf(Storage::path($filePath));
        } elseif (strtolower($fileExtension) === 'csv') {
            $transactions = $this->extractTransactionsFromCsv(Storage::path($filePath));
        } else {
            return response()->json(['success' => false, 'message' => 'Formato de arquivo não suportado.']);
        }


        // Recuperar categorias ativas do usuário
        $categories = Category::where('is_active', 1)
            ->where('user_id', auth()->id())
            ->get(['id_category as id', 'name']);

        // Recuperar todos os clientes
        $clients = Client::all(['id', 'name']);

        // Retornar a resposta como JSON
        return response()->json([
            'success' => true,
            'transactions' => $transactions,
            'categories' => $categories,
            'clients' => $clients,
        ]);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'transactions' => 'required|array',
            'transactions.*.date' => 'required|date',
            'transactions.*.value' => 'required|numeric',
            'transactions.*.description' => 'required|string|max:255',
            'transactions.*.installments' => 'nullable|string|max:255',
            'transactions.*.category_id' => 'required|exists:category,id_category',
            'transactions.*.client_id' => 'nullable|exists:clients,id',
        ]);

        foreach ($request->transactions as $transaction) {
            Invoice::create([
                'id_bank' => $request->id_bank,
                'invoice_date' => $transaction['date'],
                'value' => $transaction['value'],
                'description' => $transaction['description'],
                'installments' => $transaction['installments'] ?? null,
                'category_id' => $transaction['category_id'],
                'client_id' => $transaction['client_id'],
                'user_id' => auth()->id(),
            ]);
        }

        // Redireciona de volta para a página de invoices com a variável 'bank_id'
        return redirect()->route('invoices.index', ['bank_id' => $request->id_bank])
                         ->with('success', 'Transações confirmadas com sucesso.');
    }

    protected function extractTransactionsFromCsv($csvPath)
    {
        $transactions = [];
        $categoryMapping = [
            'BOTICARIO' => '1019',
            'Eudora' => '1019',
            'NATURA' => '1019',
            'COSMETIC' => '1019',
            'BEER' => '1018',
            'BURGER' => '1018',
            'Bar' => '1018',
            'RESTAURANTE' => '1018',
            '1A99' => '1021',
            'SUPERMERCADO' => '1021',
            'ATACADAO' => '1021',
            'ROFATTO' => '1021',
            'PENHA' => '1021',
            'mix' => '1021',
            'DAVID BARBOZA' => '1021',
            'PANDULANCHES' => '1021',
            'CASARAO' => '1021',
            'SUCOS' => '1021',
            'PIZZARIA' => '1021',
            'POSTO' => '1022',
            'Posto' => '1022',
            'Shell' => '1022',
            'ARENA' => '1022',
            'N. R.' => '1022',
            'ITAPIRENSE' => '1022',
            'PANORAMA' => '1022',
            'PHARMA' => '1023',
            'DROGARIA' => '1023',
            'CLARO' => '1029',
            'AUTO CENTER' => '1023',
            'AIRBNB' => '1027',
            'BYMA' => '1027',
            'MERCADOLIVRE' => '1024',
            'SHOPEE' => '1024',
            'Pagamentos' => '1025',
            'MP*JOSE' => '1025',
            'FABIOLUIZDAGNONI' => '1026',
            'CLEUSA' => '1018',
            'JOSE ROBERTO' => '1026',
            'LUCIANO DE ANDRADE' => '1026',
            'Mega Motos' => '1030',
            'FACEBK' => '1031',
            'SHOPIFY' => '1031',
            'Skyfit' => '1028',
            'ANTONELLI' => '1021',
            'Agro' => '1021',
            'Amazon Prime' => '1029',
            'Ton Central' => '1018',
            'Bear' => '1018',
            'Zeferinoltda' => '1023',
            'Tabacaria' => '1018',
            'Spotify' => '1029',
            'Cubatao' => '1021',
            'Fabioluizdagnoni' => '1026',
            'Supermercado' => '1021',
            'Lojaehcases' => '1024',
            'Melimais' => '1025',
            'Pg' => '1018',
            'Tabacaria Jb' => '1018',
            'Supermercado Jardim P' => '1021',
            'Auto Posto' => '1022',
            'sem Parar' => '1025',
            'Pandulanches' => '1021',
        ];

        if (($handle = fopen($csvPath, 'r')) !== false) {
            $headers = fgetcsv($handle, 1000, ',');

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Limpeza de dados
                $data = array_map(function ($item) {
                    return trim(str_replace('"', '', $item)); // Remover aspas e espaços extras
                }, $data);


                if (count($data) >= 5) {
                    // Adaptar para os campos necessários
                    $transaction = [
                        'invoice_date' => $data[0] ?? null,
                        'description' => $data[1] ?? null,
                        'category' => $data[2] ?? null,
                        'installments' => $data[3] ?? '-',
                        'value' => isset($data[4]) ? $this->processValue($data[4]) : '0.00', // Usando a função processValue para processar o valor
                        'category_id' => null,
                        'client_id' => null,
                    ];

                    // Converter a data no formato correto
                    if ($transaction['invoice_date']) {
                        $dateParts = explode('/', $transaction['invoice_date']);
                        if (count($dateParts) == 3) {
                            $day = $dateParts[0];
                            $month = $dateParts[1];
                            $year = $dateParts[2];

                            $transaction['invoice_date'] = $year . '-' . $month . '-' . $day;
                        }
                    }

                    // Verificação de categoria mapeada
                    if ($transaction['description']) {
                        foreach ($categoryMapping as $keyword => $categoryId) {
                            if (stripos($transaction['description'], $keyword) !== false) {
                                $transaction['category_id'] = $categoryId;
                                break;
                            }
                        }
                    }

                    // Se a categoria não for mapeada, atribua uma categoria padrão
                    if (!$transaction['category_id']) {
                        $transaction['category_id'] = '1026';  // Categoria padrão
                    }

                    // Verificação e formatação da descrição
                    $transaction['description'] = $transaction['description'] ?? 'Descrição não disponível';

                    // Verificação e formatação das parcelas
                    $transaction['installments'] = $transaction['installments'] ?? 'Compra à vista';


                    // Adicionar transação à lista se o valor for maior que 0, e a data e descrição estiverem presentes
                    if ($transaction['value'] > 0 && !empty($transaction['invoice_date']) && !empty($transaction['description'])) {
                        $transactions[] = $transaction;
                    } else {
                        Log::warning('Transação ignorada devido a valores inválidos:', ['transaction' => $transaction]);
                    }
                } else {
                    Log::warning('Linha CSV com dados incompletos:', ['data' => $data]);
                }
            }

            fclose($handle);
        } else {
            Log::error('Erro ao abrir o arquivo CSV:', ['csv_path' => $csvPath]);
        }

         // Garante que todas as transações tenham os campos necessários
         foreach ($transactions as &$transaction) {
            $transaction['date'] = $transaction['invoice_date'] ?? ''; // Corrigir para "date"
            $transaction['valor'] = $transaction['value'] ?? '';
            $transaction['descricao'] = $transaction['description'] ?? '';
            $transaction['parcelas'] = $transaction['installments'] ?? '-';
        }

        return $transactions;
    }


    private function processValue($value)
    {
        // Remover "R$", espaços extras e pontos de milhar antes de processar
        $value = str_replace(['R$', ' '], ['', ''], $value); // Remove 'R$', espaços e pontos de milhar
        $value = str_replace(',', '.', $value); // Substitui vírgula por ponto

        // Remover caracteres invisíveis ou estranhos
        $value = trim($value); // Remove espaços extras no início e fim
        $value = preg_replace('/[^\x20-\x7E]/', '', $value); // Remove caracteres não imprimíveis

        // Verificar se a string não está vazia antes de tentar convertê-la
        if (is_numeric($value)) {
            // Retornar o valor como string, com 2 casas decimais
            return number_format((float) $value, 2, '.', '');
        } else {
            // Se o valor não for válido, retornar "0.00" como string
            return "0.00";
        }
    }
    // Método para extrair transações do PDF
    protected function extractTransactionsFromPdf($pdfPath)
    {
        $transactions = [];
        $pdf = new Parser();
        $document = $pdf->parseFile($pdfPath);
        $text = $document->getText();
        // Verificar se o texto foi extraído corretamente
        if (empty($text)) {
            Log::error('Erro: Nenhum texto extraído do PDF.');
            return $transactions;
        }

        // Explodir o texto em linhas
        $lines = explode("\n", $text);

        $currentTransaction = [
            'id_bank' => null,
            'description' => '',
            'installments' => '-',
            'value' => null,
            'category_id' => null,
            'invoice_date' => null,
            'user_id' => auth()->id(),
            'client_id' => null,
        ];

        // Mapeamento de meses em português para números
        $monthMapping = [
            'jan' => '01',
            'fev' => '02',
            'mar' => '03',
            'abr' => '04',
            'mai' => '05',
            'jun' => '06',
            'jul' => '07',
            'ago' => '08',
            'set' => '09',
            'out' => '10',
            'nov' => '11',
            'dez' => '12',
            'JAN' => '01',
            'FEV' => '02',
            'MAR' => '03',
            'ABR' => '04',
            'MAI' => '05',
            'JUN' => '06',
            'JUL' => '07',
            'AGO' => '08',
            'SET' => '09',
            'OUT' => '10',
            'NOV' => '11',
            'DEZ' => '12',
        ];

        $categoryMapping = [
            'BOTICARIO' => '1019',
            'Eudora' => '1019',
            'NATURA' => '1019',
            'COSMETIC' => '1019',
            'BEER' => '1018',
            'BURGER' => '1018',
            'Bar' => '1018',
            'RESTAURANTE' => '1018',
            '1A99' => '1021',
            'SUPERMERCADO' => '1021',
            'ATACADAO' => '1021',
            'ROFATTO' => '1021',
            'PENHA' => '1021',
            'mix' => '1021',
            'DAVID BARBOZA' => '1021',
            'PANDULANCHES' => '1021',
            'CASARAO' => '1021',
            'SUCOS' => '1021',
            'PIZZARIA' => '1021',
            'POSTO' => '1022',
            'Posto' => '1022',
            'Shell' => '1022',
            'ARENA' => '1022',
            'N. R.' => '1022',
            'ITAPIRENSE' => '1022',
            'PANORAMA' => '1022',
            'PHARMA' => '1023',
            'DROGARIA' => '1023',
            'CLARO' => '1029',
            'AUTO CENTER' => '1023',
            'AIRBNB' => '1027',
            'BYMA' => '1027',

            'MERCADOLIVRE' => '1024',
            'SHOPEE' => '1024',
            'Pagamentos' => '1025',
            'MP*JOSE' => '1025',
            'FABIOLUIZDAGNONI' => '1026',
            'CLEUSA' => '1018',
            'JOSE ROBERTO' => '1026',
            'LUCIANO DE ANDRADE' => '1026',
            'Mega Motos' => '1030',
            'FACEBK' => '1031',
            'SHOPIFY' => '1031',

            // Novas adições para garantir maior cobertura:
            'Skyfit' => '1028',  // Se o nome aparecer em transações de supermercado

            'ANTONELLI' => '1021',
            'Agro' => '1021',     // Agro pode ser relacionado a supermercados ou mercados
            'Amazon Prime' => '1029', // Se for referente ao serviço de streaming
            'Ton Central' => '1018',
            'Bear' => '1018', // Pode ser uma referência a bares ou restaurantes
            'Zeferinoltda' => '1023', // Farmácia (considerando o nome da empresa)
            'Tabacaria' => '1018', // Categoria de farmácias ou tabacarias
            'Spotify' => '1029',   // Pagamentos relacionados a serviços de streaming
            'Cubatao' => '1021',   // Pode se referir a supermercado ou mercado
            'Fabioluizdagnoni' => '1026', // Nome específico
            'Supermercado' => '1021', // Generalização para supermercados
            'Lojaehcases' => '1024',  // Considerando como uma loja online (Shopee)
            'Melimais' => '1025', // Pode ser considerado pagamento
            'Pg' => '1018',         // Pode se referir a restaurante ou bar (Ton Central Beer)
            'Tabacaria Jb' => '1018',  // Farmácia ou loja de tabaco
            'Supermercado Jardim P' => '1021', // Supermercado
            'Auto Posto' => '1022', // Auto posto (N. R. e Arena)
            'sem Parar' => '1025', // Pagamentos (serviços recorrentes)
            'Pandulanches' => '1021',  // Supermercado ou alimentação
        ];

        // Função para determinar a categoria com base na descrição
        $determineCategoryId = function ($description, $categoryMapping) {

            foreach ($categoryMapping as $keyword => $categoryId) {
                if (stripos($description, $keyword) !== false) {
                    return $categoryId;
                }
            }

           return '1026'; // Categoria padrão caso não encontre nenhuma correspondência
        };

        // Processa cada linha do texto extraído do PDF
        foreach ($lines as $line) {
            $trimmedLine = trim($line);


            if (empty($trimmedLine)) {
                continue;
            }

            // Verifica se a linha contém uma data no formato (1) 4 de out. 2024 ou (2) 06 SET
            if (preg_match('/(\d{1,2})\sde\s([a-záàâãäéèêíóòôõöúç]{3})\.\s(\d{4})/', $trimmedLine, $dateMatches) || preg_match('/(\d{2})\s([A-Z]{3})/', $trimmedLine, $dateMatches)) {
                $day = $dateMatches[1];
                $month = strtolower($dateMatches[2]);
                $year = isset($dateMatches[3]) ? $dateMatches[3] : date('Y'); // Assume o ano atual se não houver

                if (isset($monthMapping[$month])) {
                    $currentTransaction['invoice_date'] = $year . '-' . $monthMapping[$month] . '-' . $day;

                    // Se já tivermos todos os dados necessários, salvamos a transação
                    if (!empty($currentTransaction['invoice_date']) && !empty($currentTransaction['value']) && !empty($currentTransaction['description'])) {
                        $currentTransaction['category_id'] = $determineCategoryId($currentTransaction['description'], $categoryMapping);
                        $transactions[] = $currentTransaction;
                    }

                    // Reseta a transação atual
                    $currentTransaction = [
                        'id_bank' => null,
                        'description' => '',
                        'installments' => '-',
                        'value' => null,
                        'category_id' => null,
                        'invoice_date' => $currentTransaction['invoice_date'],
                        'user_id' => auth()->id(),
                        'client_id' => null,
                    ];

                    $trimmedLine = str_replace($dateMatches[0], '', $trimmedLine); // Remove a data processada
                }
            }

            // Extrai o valor se a linha contiver "R$"
            if (strpos($trimmedLine, 'R$') !== false) {
                if (preg_match('/R\$\s*([-]?\d{1,3}(?:\.\d{3})*(?:,\d{2})?)/', $trimmedLine, $valueMatches)) {
                    $currentTransaction['value'] = str_replace(',', '.', str_replace('.', '', $valueMatches[1]));
                    $currentTransaction['value'] = $currentTransaction['value'] !== null ? abs($currentTransaction['value']) : 0;
                    $trimmedLine = str_replace($valueMatches[0], '', $trimmedLine); // Remove o valor processado
                }
            }

            // Extrai as parcelas se a linha contiver "Parcela"
            if (preg_match('/\(?\s*(\d+)\s*(?:de|\/)\s*(\d+)\s*\)?/', $trimmedLine, $parcelMatches)) {
                $currentTransaction['installments'] = "{$parcelMatches[1]} de {$parcelMatches[2]}";
                $trimmedLine = str_replace($parcelMatches[0], '', $trimmedLine); // Remove as parcelas processadas
            }

            // Adiciona a descrição de forma mais refinada
            if (!empty($trimmedLine)) {
                // Lista de palavras-chave que indicam que a linha não deve ser incluída na descrição
                $excludedKeywords = ['fatura', 'Período', 'Olá', 'LIMITES', 'crédito', 'aplicativo', 'marco', 'juros', 'bloqueado', '0800', 'meses', 'IOF', 'ENCARGOS', 'PARCIAL', '2306', 'agendamento', 'rotativo', 'Descritivo', 'CARTÃO', 'Vencimento', 'Sempre', 'nem', 'podendo', 'simulações', 'São Paulo/SP', 'Válido', 'Parcelar', 'capitais', 'quiser', 'SAC', 'pagar', 'cabe', 'CNPJ', 'Ideal', 'rua', 'parcelamento', 'valido', 'ainda', 'termos', 'Disponível', 'R$', 'valor', 'total', 'desconto', 'Pagamento', 'saldo'];

                // Verifica se a linha contém qualquer palavra-chave que deve excluir a linha inteira
                $shouldExcludeLine = false;
                foreach ($excludedKeywords as $keyword) {
                    if (stripos($trimmedLine, $keyword) !== false) {
                        $shouldExcludeLine = true;
                        break; // Se encontrar qualquer palavra-chave, a linha será excluída
                    }
                }

                // Se a linha não for excluída, remove palavras específicas e adiciona à descrição
                if (!$shouldExcludeLine) {
                    // Lista de palavras e caracteres a serem removidos da linha (mesmo que não exclua a linha inteira)
                    $removeKeywords = ['parcela', 'desconto', 'taxa', '-', '(', ')'];

                    // Remove as palavras e caracteres específicos da linha
                    foreach ($removeKeywords as $removeKeyword) {
                        $trimmedLine = preg_replace('/' . preg_quote($removeKeyword, '/') . '/i', '', $trimmedLine);
                    }

                    // Remove espaços extras após a remoção das palavras
                    $trimmedLine = preg_replace('/\s+/', ' ', $trimmedLine);
                    $trimmedLine = trim($trimmedLine); // Remove espaços extras no começo e fim

                    // Agora, adiciona a linha limpa à descrição
                    $currentTransaction['description'] .= empty($currentTransaction['description']) ? $trimmedLine : ' ' . $trimmedLine;
                }

            }


            // Se a transação tiver todos os dados, adiciona à lista
            if (!empty($currentTransaction['invoice_date']) && !empty($currentTransaction['value']) && !empty($currentTransaction['description'])) {
                $currentTransaction['category_id'] = $determineCategoryId($currentTransaction['description'], $categoryMapping);
                $transactions[] = $currentTransaction;
                // Reinicia a transação para a próxima
                $currentTransaction = [
                    'id_bank' => null,
                    'description' => '',
                    'installments' => '-',
                    'value' => null,
                    'category_id' => null,
                    'invoice_date' => null,
                    'user_id' => auth()->id(),
                    'client_id' => null,
                ];
            }
        }

        // Garante que todas as transações tenham os campos necessários
        foreach ($transactions as &$transaction) {
            $transaction['date'] = $transaction['invoice_date'] ?? ''; // Corrigir para "date"
            $transaction['valor'] = $transaction['value'] ?? '';
            $transaction['descricao'] = $transaction['description'] ?? '';
            $transaction['parcelas'] = $transaction['installments'] ?? '-';
        }
        return $transactions;
    }


}
