<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    public function current() {
        return response()->json(Auth::user(), 200);
    }

    public function listOtherUsers() {
        $users = User::where('id', '<>', Auth::user()->id)->get();
        return response()->json($users, 200);
    }

    public function deleteUser($id) {
        $user = User::find($id);
        if($user) {
            if ($user->delete()) {
                return response()->json(['message' => 'Usuário deletado!']);
            } else {
                return response()->json(['error' => 'Ocorreu um erro ao deletar o usuário!'], 400);
            }
        }
        return response()->json(['error' => 'Usuário não encontrado!'], 404);
    }
}
