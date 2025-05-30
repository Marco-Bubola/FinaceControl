<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }
    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($attributes)) {
            session()->regenerate();
            return redirect('dashboard')->with(['success' => 'Você está logado.']);
        } else {
            return back()->withErrors(['email' => 'Email ou senha inválidos.']);
        }
    }
    public function destroy()
    {
        Auth::logout();
        return redirect('/login')->with(['success' => 'Você foi desconectado.']);
    }
}
