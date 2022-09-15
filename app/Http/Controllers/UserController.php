<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function login(Request $request){
        $user = User::where('email', '=', $request['email'])->first();
        $error = [];
        if ($user == null) {
                $error = ["Usuario nao encontrado."];
                return $error;
        }      
        // if ($user->password != $request->password) {
        //         $error = ["Senha incorreta."];
        //         return $error;
        //     }
            

        if (!password_verify($request->password, $user->password)) { return response()->json(['errors' => ['error' => 'Email ou senha incorretos!']]); }


        if(!$error){
                $token = $user->createToken('token')->accessToken;
                return response()->json([
                    'user' => $user,
                    'access_token' => $token,
                    'errors' => $error,
                ]);
        }      
    }
}
