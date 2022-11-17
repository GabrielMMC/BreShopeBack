<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Exception;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', '=', $request['email'])->first();
        $error = [];
        if ($user == null) {
            $error = ["Usuario nao encontrado."];
        }


        if (!password_verify($request->password, $user->password)) {
            $error = ['Email ou senha incorretos!'];
        }


        if (!$error) {
            $token = $user->createToken('token')->accessToken;
            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'errors' => $error,
            ]);
        }
    }

    public function register(UserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = new User();
            $user->fill($data)->save();

            // if ($request->file('file_path')) {
            //     $img = $request->file('file_path');
            //     $name = uniqid('foto_') . '.' . $img->getClientOriginalExtension();
            //     $user->file_path = $img->storeAs('fotos', $name, ['disk' => 'public']);
            //     $user->file_path = $img->storeAs('fotos', $name, ['disk' => 'public']);
            // }
            return response()->json([
                'status' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e,
            ]);
        }
    }

    public function get_users(Request $request)
    {
        $users = User::orderBy('id')->where(function ($q) use ($request) {
            $q->whereRaw('lower(name) LIKE lower(?)', ['%' . $request->search . '%']);
        })->paginate(10);

        return response()->json([
            'users' => UserResource::collection($users),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total_pages' => $users->total(),
                'per_page' => $users->perPage(),
            ],
        ]);
    }

    public function get_user($id)
    {
        $user = User::where('id', '=', $id)->first();
        return response()->json(['user' => $user]);
    }

    public function update_user(Request $request)
    {
        try {
            $user = User::where('id', '=', $request['id'])->first();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = $request->input('password');

            if ($request->file('file_path')) {
                $img = $request->file('file_path');
                $name = uniqid('foto_') . '.' . $img->getClientOriginalExtension();
                $user->file_path = $img->storeAs('fotos', $name, ['disk' => 'public']);
                $user->file_path = $img->storeAs('fotos', $name, ['disk' => 'public']);
            }
            $user->save();
            return response()->json([
                'status' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e
            ]);
        }
    }

    public function delete_user($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'status' => true
        ]);
    }

    // public function login(Request $request)
    // {
    //     $user = User::where('email', '=', $request['email'])->first();
    //     $error = [];
    //     if ($user == null) {
    //         $error = ["Usuario nao encontrado."];
    //         return $error;
    //     }

    //     // !password_verify($request->password, $user->password)
    //     if ($request->password != $user->password) {
    //         return response()->json(['errors' => ['error' => 'Email ou senha incorretos!']]);
    //     }


    //     if (!$error) {
    //         $token = $user->createToken('token')->accessToken;
    //         return response()->json([
    //             'user' => $user,
    //             'access_token' => $token,
    //             'errors' => $error,
    //         ]);
    //     }
    // }
}
