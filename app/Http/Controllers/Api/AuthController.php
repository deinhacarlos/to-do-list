<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function cadastrar(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'senha' => 'required|string|min:8|confirmed',
        ]);

        $usuario = Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
        ]);

        $token = $usuario->createToken('token_autenticacao')->plainTextToken;

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Usuário cadastrado com sucesso',
            'dados' => [
                'usuario' => $usuario,
                'token' => $token,
            ],
        ], 201);
    }

    public function entrar(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->senha, $usuario->senha)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        $token = $usuario->createToken('token_autenticacao')->plainTextToken;

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Login realizado com sucesso',
            'dados' => [
                'usuario' => $usuario,
                'token' => $token,
            ],
        ]);
    }

    public function sair(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Logout realizado com sucesso',
        ]);
    }

    public function perfil(Request $request)
    {
        return response()->json([
            'sucesso' => true,
            'dados' => $request->user(),
        ]);
    }
}