<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class AdminAuthController extends Controller
{
    public function create(Request $request)
    {
        return view('auth.login');
    } // create()

    public function store(Request $request)
    {
        // Validações
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        // Tentar autenticar usando o email e a senha
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // Autenticação bem-sucedida
            return redirect()->intended(route('dashboard'));
        }

        // Autenticação falhou
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    } // store()

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        /*$request->session()->invalidate();
        $request->session()->regenerateToken();*/

        return redirect()->route('admin.login');
    } // LOGOUT()
}
