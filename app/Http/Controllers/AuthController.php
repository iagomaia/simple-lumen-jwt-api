<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Validator;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    private $request;
    public function __construct(Request $request) {
        $this->request = $request;
    }
    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60*24 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }


    public function login(Request $request) {
        $this->validate($this->request, [
            'login'     => 'required',
            'password'  => 'required'
        ]);
        // Find the user by email
        $user = User::where('login', $this->request->input('login'))->first();
        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the
            // below respose for now.
            return response()->json([
                'error' => 'Usuário não encontrado.'
            ], 404);
        }
        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json(['token' => $this->jwt($user)], 200);
        }
        // Bad Request response
        return response()->json([
            'error' => 'Login/Senha inválido(s).'
        ], 400);
    }

}