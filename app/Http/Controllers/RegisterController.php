<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request) {
        $input = $request->all();
        $mensagensErro = [
            'login.required' => 'Informe o login.',
            'login.unique' => 'O login informado já está em uso.',
            'login.alpha_num' => 'O login deve conter apenas números e letras.',
            'login.min' => 'O login deve ter no mínimo 6 caracteres.',
            'password.required' => 'Informe a senha.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'password.confirmed' => 'As senhas informadas não conferem.',
            'name.required' => 'Informe o nome.',
        ];

        $validator = Validator::make($request->all(), [
            'login' => 'required|min:6|alpha_num|unique:users|bail',
            'password' => 'required|min:6|confirmed|bail',
            'name' => 'required|bail'
        ], $mensagensErro);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()],400);
        }
        $input['password'] = app('hash')->make($input['password']);
        User::create($input);

        return response()->json(['message' => 'Usuário criado com sucesso.'], 200);
    }
}
