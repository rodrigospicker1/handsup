<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);
        
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return back()->withErrors(['error' => 'Credenciais inválidas']);
        }

        if ($user && Hash::check($request->password, $user->password)) {
            // Autentica manualmente
            session(['user' => $user->id]); // Armazena o ID na sessão

            return redirect('/'); // Redireciona para área protegida
        } else {
            return back()->withErrors(['email' => 'Credenciais inválidas']);
        }
    }

    public function register(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // email único
            'password' => 'required|min:6|confirmed', // precisa do campo password_confirmation
        ]);

        // Criar o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => 1,
        ]);

        // Logar automaticamente (opcional)
        session(['user' => $user->id]);

        return back()->with(['system_success' => 'Usuário logado no sistema']);
        // Redireciona para a dashboard
        return redirect('/');
    }

    public function logout()
	{
		session()->forget('user');
        return redirect('/');
	}

}
