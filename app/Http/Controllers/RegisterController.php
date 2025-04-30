<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('session.register');
    }

    public function store()
    {
        // Validação dos campos
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'min:5', 'max:20'],
            'agreement' => ['accepted']
        ]);

        // Adicionando o role_id ao array de atributos
        $attributes['password'] = bcrypt($attributes['password']);
        $attributes['role_id'] = 2; // Atribuindo o role_id como 2

        // Armazenando o usuário no banco de dados
        session()->flash('success', 'Sua conta foi criada.');
        $user = User::create($attributes); // Criando o usuário com o role_id

        // Realizando o login do usuário
        Auth::login($user);

        // Redirecionando para o dashboard
        return redirect('/dashboard');
    }
}
