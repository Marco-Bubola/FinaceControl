<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                // Verificar se o usuário está autenticado
                if (!auth()->check()) {
                    return response()->json(['error' => 'Usuário não autenticado.'], 401);
                }

                // Buscar clientes com base no termo de pesquisa
                $search = $request->get('search', '');
                $clients = Client::where('user_id', auth()->id())
                    ->where('name', 'like', "%{$search}%")
                    ->with('sales')
                    ->get();

                return response()->json(['clients' => $clients]); // Retornar JSON
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500); // Retornar erro em JSON
            }
        }

        $userId = auth()->id();  // Obter o ID do usuário logado

        // Inicializa a consulta para clientes, filtrando pelo user_id
        $clients = Client::where('user_id', $userId);

        // Filtro de pesquisa por nome
        if ($request->has('search') && $request->search != '') {
            // Remove os pontos do termo de pesquisa para garantir que ele seja encontrado independentemente do formato
            $searchTerm = str_replace('.', '', $request->search);

            $clients->where(function ($q) use ($searchTerm) {
                // Busca pelo nome do cliente, considerando o termo de pesquisa ajustado
                $q->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtro por data de criação, atualização ou outros
        if ($request->has('filter') && $request->filter != '') {
            switch ($request->filter) {
                case 'created_at':
                    $clients->orderBy('created_at', 'desc');
                    break;
                case 'updated_at':
                    $clients->orderBy('updated_at', 'desc');
                    break;
                case 'name_asc':
                    $clients->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $clients->orderBy('name', 'desc');
                    break;
            }
        }

        // Controle do número de itens por página (valor padrão é 10)
        $perPage = $request->input('per_page', 18);  // Pega o valor de 'per_page' ou usa 10 como padrão

        // Paginando os resultados com a quantidade definida
        $clients = $clients->with('sales')->paginate($perPage); // Paginação com a quantidade definida

        // Passando os clientes e parâmetros para a view
        return view('clients.index', compact('clients', 'perPage'));
    }

    // Método para exibir o formulário de criação de cliente
    public function create()
    {
        return view('clients.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:15',
            'address' => 'nullable',
            'avatar_cliente' => 'required|url',
        ]);

        Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_id' => Auth::id(),
            'caminho_foto' => $request->avatar_cliente,
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente criado com sucesso!');
    }


    // Método para editar um cliente
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    // Método para atualizar o cliente
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:15',
            'address' => 'nullable',
            'avatar_cliente' => 'required|url',
        ]);

        $client = Client::findOrFail($id);
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'caminho_foto' => $request->avatar_cliente,
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    // Método para excluir um cliente
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente deletado com sucesso!');
    }

    // Retornar histórico de compras
    public function getPurchaseHistory($id)
    {
        $client = Client::with('sales.saleItems.product')->findOrFail($id);
        return view('sales.show', compact('client'));
    }
}
