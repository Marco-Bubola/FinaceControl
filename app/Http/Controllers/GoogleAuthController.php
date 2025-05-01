<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{
    function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    function callback()
    {
        try {
            $google_user = Socialite::driver('google')->user();

            // Verifica se já existe um usuário com o mesmo google_id
            $user = User::where('google_id', $google_user->getId())->first();

            // Se o usuário não existir, cria um novo
            if (!$user) {
                // Cria o novo usuário com role_id setado como 2
                $new_user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),
                    'role_id' => 2,
                ]);

                // Loga o novo usuário
                auth()->login($new_user);

                // Mensagem de sucesso
                session()->flash('success', 'Sua conta foi criada e você está logado.');
                return redirect()->intended('dashboard');
            } else {
                // Se o usuário já existir, apenas faz login
                auth()->login($user);

                // Mensagem de sucesso
                session()->flash('success', 'Você está logado.');
                return redirect()->intended('dashboard');
            }
        } catch (\Throwable $th) {
            // Caso ocorra algum erro, mostra a mensagem de erro
            session()->flash('error', 'Ocorreu um erro ao tentar realizar o login.');
            return redirect()->intended('dashboard');
        }
    }



}
