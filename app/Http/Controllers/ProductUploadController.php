<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Smalot\PdfParser\Parser;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductUploadController extends Controller
{
    public function store(Request $request)
{
    // Validação
    $request->validate([
        'products.*.name' => 'required|string|max:255',
        'products.*.description' => 'nullable|string|max:1000',
        'products.*.price' => 'required|numeric',
        'products.*.price_sale' => 'required|numeric',
        'products.*.quantity' => 'required|integer',
        'products.*.product_code' => 'required|string|max:255',
        'products.*.status' => 'required|in:ativo,inativo,descontinuado',
        'products.*.image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048',
    ]);


    // Processar cada produto
    foreach ($request->products as $index => $product) {
        $imageName = null;

        // Se o produto tiver uma imagem enviada via input file, faz o upload normalmente
        if ($request->hasFile("products.{$index}.image")) {
            $imagePath = $request->file("products.{$index}.image")->store('products', 'public');
            $imageName = basename($imagePath);
        }
        // Se não tiver arquivo, mas tiver base64, salva o base64 como arquivo
        elseif (!empty($product['image_base64'])) {
            $imageData = $product['image_base64'];
            $type = 'png'; // padrão caso não consiga detectar
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $type = strtolower($matches[1]);
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }
            $imageData = base64_decode($imageData);
            if ($imageData !== false) {
                $imageName = 'product_' . uniqid() . '.' . $type;
                Storage::disk('public')->put('products/' . $imageName, $imageData);
            }
        }

        $imageName = $imageName ?? 'product-placeholder.png'; // Se a imagem não for fornecida, usa a imagem padrão.

        try {

            $existingProduct = Product::where('product_code', $product['product_code'])
                ->where('price', $product['price'])
                ->where('price_sale', $product['price_sale'])
                ->where('user_id', Auth::id())
                ->first();

            if ($existingProduct) {

                // Se o preço de venda for o mesmo, apenas atualiza a quantidade
                if ($existingProduct->price_sale == $product['price_sale']) {
                    $existingProduct->stock_quantity += $product['quantity']; // Aumenta a quantidade
                    $existingProduct->save(); // Salva as alterações
                } else {
                    // Caso o preço de venda seja diferente, cria um novo produto com o mesmo código
                    Product::create([
                        'name' => $product['name'],
                        'description' => $product['description'] ?? null,
                        'price' => $product['price'],
                        'price_sale' => $product['price_sale'],
                        'stock_quantity' => $product['quantity'],
                        'product_code' => $product['product_code'],
                        'status' => $product['status'] ?? 'ativo',
                        'tipo' => $product['tipo'] ?? 'simples',
                        'custos_adicionais' => $product['custos_adicionais'] ?? 0,
                        'image' => $imageName,
                        'user_id' => Auth::check() ? Auth::id() : null,
                        'category_id' => $product['category_id'] ?? 1,
                    ]);
                }
            } else {

                // Se o produto não existe, cria um novo produto
                Product::create([
                    'name' => $product['name'],
                    'description' => $product['description'] ?? null,
                    'price' => $product['price'],
                    'price_sale' => $product['price_sale'],
                    'stock_quantity' => $product['quantity'],
                    'product_code' => $product['product_code'],
                    'status' => $product['status'] ?? 'ativo',
                    'tipo' => $product['tipo'] ?? 'simples',
                    'custos_adicionais' => $product['custos_adicionais'] ?? 0,
                    'image' => $imageName,
                    'user_id' => Auth::check() ? Auth::id() : null,
                    'category_id' => $product['category_id'] ?? 1,
                ]);
            }
        } catch (\Exception $e) {
            // Log: Erro durante o processamento
            \Log::error('Erro ao salvar o produto', [
                'error' => $e->getMessage(),
                'product' => $product
            ]);

            // Captura erros e retorna para o formulário de upload com a mensagem de erro
            return redirect()->route('products.index')->withErrors(['message' => 'Erro ao salvar os produtos: ' . $e->getMessage()]);
        }
    }


    // Redireciona para a lista de produtos após o sucesso
    return redirect()->route('products.index')->with('success', 'Produtos salvos com sucesso!');
}


    // Método para mostrar o formulário de upload
    public function showUploadForm()
    {
        return view('products.upload', ['productsUpload' => null]);
    }

    // Método para processar o arquivo PDF ou CSV
    public function upload(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf,csv|max:2048',
        ]);

        $path = $request->file('pdf_file')->store('uploads');
        $extension = $request->file('pdf_file')->extension();

        $productsUpload = [];

        if ($extension === 'pdf') {
            // Processa o PDF
            $parser = new Parser();
            $pdf = $parser->parseFile(storage_path('app/' . $path));
            $text = $pdf->getText();

            // Filtra o texto para pegar apenas os dados entre "OPERAÇÃO" e "PEDIDO Nº"
            $filteredText = $this->filterText($text);

            // Limpa o texto de tabulações e múltiplos espaços
            $cleanText = preg_replace('/\s+/', ' ', $filteredText);

            // Organize o texto limpo em produtos individuais
            $productsText = $this->separateProducts($cleanText);

            // Extraí os produtos do texto separado
            $productsUpload = $this->extractProductsFromText($productsText);
        } elseif ($extension === 'csv') {
            // Processa o CSV
            $productsUpload = $this->extractProductsFromCsv(storage_path('app/' . $path));
        }

        // Verifica se algum produto foi extraído
        if (empty($productsUpload)) {
            return response()->json(['message' => 'Nenhum produto encontrado no arquivo.'], 400);
        }

        // Agora, vamos dividir o price e price_sale pela quantidade de cada produto
        foreach ($productsUpload as $key => $product) {
            $productsUpload[$key]['price'] = $product['price'] / $product['stock_quantity'];
            $productsUpload[$key]['price_sale'] = $product['price_sale'] / $product['stock_quantity'];
        }

        // Armazena os produtos extraídos na sessão
        session(['productsUpload' => $productsUpload]);

        // Retorna os produtos extraídos como resposta JSON
        return response()->json(['products' => $productsUpload]);
    }

    private function filterText($text)
    {
        $startPos = strpos($text, 'OPERAÇÃO');
        $endPos = strpos($text, 'PEDIDO Nº');

        if ($startPos !== false && $endPos !== false) {
            $filteredText = substr($text, $startPos + strlen('OPERAÇÃO'), $endPos - ($startPos + strlen('OPERAÇÃO')));
            $filteredText = preg_replace('/\s+/', ' ', $filteredText);
            $filteredText = preg_replace('/\bOPERAÇÃO\b/', '', $filteredText);
            return $filteredText;
        }

        return '';
    }
    private function separateProducts($text)
    {
        if (!is_string($text)) {
            // Se for um array, transforma-o em uma string
            $text = implode(' ', $text); // converte array para string com separação por espaço
        }
        // A regex para separação dos produtos, com modificação para aceitar parênteses nos nomes dos produtos
        $pattern = '/(\d{1,5}\.\d{3})\s+           # Código do produto (CÓD.)
                (\d+)\s+                       # Quantidade (QTD.)
                ([A-Za-z0-9À-ÿ\s&\-\/,()]+(?:[A-Za-z0-9À-ÿ\s&\-\/,()]*))\s+   # Nome do produto
                ([\d,\.]+)\s+                  # Preço Tabela (R$ TABELA)
                ([\d,\.]+)\s+                  # Preço Praticado (R$ PRATICADO)
                ([\d,\.]+)\s+                  # Preço Revenda (R$ REVENDA)
                ([\d,\.]+)\s+                  # Preço a Pagar (R$ A PAGAR)
                ([\d,\.]+)\s+                  # Lucro (R$ LUCRO)
                (Venda)?                       # Operação (opcional)
            /x';

        preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);

        $productsText = [];

        if ($matches) {
            foreach ($matches[0] as $key => $match) {
                $productsText[] = [
                    'product_code' => (string) $matches[1][$key][0],
                    'stock_quantity' => (string) $matches[2][$key][0],
                    'name' => trim((string) $matches[3][$key][0]),
                    'price_resell' => $this->formatPrice((string) $matches[4][$key][0]),
                    'price_to_pay' => $this->formatPrice((string) $matches[5][$key][0]),
                    'price_sale' => $this->formatPrice((string) $matches[6][$key][0]),
                    'price' => $this->formatPrice((string) $matches[7][$key][0]),
                    'profit' => $this->formatPrice((string) $matches[8][$key][0]),
                    'category_id' => 1,
                    'user_id' => 1,
                    'image' => 'product-placeholder.png',
                    'status' => 'active',
                ];
            }
        }


        return ['products' => $productsText];
    }


    private function extractProductsFromText($productsText)
    {
        $products = [];
        if (isset($productsText['products'])) {
            foreach ($productsText['products'] as $item) {
                $products[] = [
                    'product_code' => $item['product_code'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'price_sale' => $item['price_sale'],
                    'stock_quantity' => $item['stock_quantity'],
                ];
            }
        }
        return $products;
    }


    private function formatPrice($price)
    {
        $price = str_replace([' ', ','], ['', '.'], trim($price));
        return (float) $price;
    }

    private function extractProductsFromCsv($path)
    {
        $productsUpload = [];
        $csv = array_map('str_getcsv', file($path));

        foreach ($csv as $row) {
            $productsUpload[] = [
                'name' => $row[0],
                'price' => $this->formatPrice($row[1]),
                'price_sale' => $this->formatPrice($row[2]),
                'quantity' => (int) $row[3],
            ];
        }

        return $productsUpload;
    }
}
