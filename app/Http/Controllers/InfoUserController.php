<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InfoUserController extends Controller
{
    // Método para exibir todos os usuários
    public function index()
    {
        $users = User::all();
        $roles = Role::all();  // Busca as funções (roles)

        // Passa os usuários e as funções para a view
        return view('profile/user-management', compact('users', 'roles'));
    }


    // Método para exibir o formulário de criação de um novo usuário
    public function create()
    {
        return view('profile/user-management');  // Rendeiriza a página de criação de um novo usuário
    }

    // Método para exibir o formulário de edição
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Aqui você busca as funções disponíveis

        // Retorna a view com o usuário e as funções
        return view('profile/user-management', compact('user', 'roles')); // Como seu formulário está em 'user-management'
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);  // Encontre o usuário pelo ID

        // Validação dos campos
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|max:15',
            'location' => 'nullable|max:255',
            'about_me' => 'nullable|max:500',
            'role_id' => 'required|exists:role,id',  // Validação para garantir que o role existe
        ]);

        // Atualiza os dados do usuário
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->location,
            'about_me' => $request->about_me,
            'role_id' => $request->role_id,  // Atualiza o role do usuário
        ]);

        // Retorna com uma mensagem de sucesso
        return redirect()->route('user-management')->with('success', 'Usuário atualizado com sucesso!');
    }

    // Método para excluir o usuário
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();  // Exclui o usuário
        return redirect()->route('user-management')->with('success', 'User deleted successfully');
    }
    public function store(Request $request)
    {
        // Validação dos campos
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            'phone' => ['nullable', 'max:50'],
            'location' => ['nullable', 'max:70'],
            'about_me' => ['nullable', 'max:150'],
            'profile_picture' => ['nullable', 'image', 'max:1024'], // Validação da imagem de perfil
        ]);

        // Lógica de alteração de email na versão demo
        if ($request->get('email') != Auth::user()->email) {
            if (env('IS_DEMO') && Auth::user()->id == 1) {
                return redirect()->back()->withErrors(['msg2' => 'Você está na versão demo, não pode alterar o endereço de e-mail.']);
            }
        } else {
            $attributes['email'] = request()->validate([
                'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            ])['email'];
        }

        // Verifica se foi enviada uma imagem cortada
        if ($request->has('profile_picture_crop')) {
            $base64Image = $request->input('profile_picture_crop');
            $imageData = explode(',', $base64Image)[1]; // Remove o prefixo de dados (data:image/png;base64,...)
            $image = base64_decode($imageData);

            // Gerar nome para o arquivo e salvar
            $imageName = 'profile_' . time() . '.png';  // Nome do arquivo
            Storage::disk('public')->put('profile_pictures/' . $imageName, $image);  // Salva no diretório 'profile_pictures'

            // Atualiza o caminho da imagem no atributo
            $attributes['profile_picture'] = 'profile_pictures/' . $imageName;
        } elseif ($request->hasFile('profile_picture')) {
            // Se não foi cortada, mas foi carregada uma nova imagem
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $attributes['profile_picture'] = $imagePath;
        }

        // Verificação de origem da requisição
        if ($request->is('user-profile*')) {
            // Atualizar o usuário existente (caso seja um perfil de usuário)
            User::where('id', Auth::user()->id)
                ->update([
                    'name' => $attributes['name'],
                    'email' => $attributes['email'],
                    'phone' => $attributes['phone'],
                    'location' => $attributes['location'],
                    'about_me' => $attributes["about_me"],
                    'profile_picture' => $attributes["profile_picture"] ?? null, // Atualiza a foto de perfil
                ]);

            return redirect('/user-profile')->with('success', 'Perfil atualizado com sucesso');
        } else {
            // Definindo a senha fixa para novos usuários
            $attributes['password'] = bcrypt('admin123');  // Senha fixa para novos usuários
            $attributes['role_id'] = 2;  // Definindo o role_id diretamente como 2

            // Criar o usuário com o role_id = 2
            $user = User::create([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'phone' => $attributes['phone'],
                'location' => $attributes['location'],
                'about_me' => $attributes['about_me'],
                'profile_picture' => $attributes['profile_picture'] ?? null,
                'password' => $attributes['password'],  // Adiciona a senha no momento da criação
                'role_id' => $attributes['role_id'],  // Atribui o role_id
            ]);
            return redirect()->route('user-management')->with('success', 'Usuário criado com sucesso');
        }
    }
}
