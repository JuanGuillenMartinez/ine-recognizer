<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');
        $commerceId = $request->get('commerce_id');

        $user = User::where('email', $email)->first();

        if (isset($user)) {
            return JsonResponse::sendError('Ya se encuentra registrado el correo electrónico proporcionado');
        }

        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'default_pass' => $password,
        ]);

        if ($user->save()) {
            $role = Role::where('name', 'user')->first();
            $user->assignRole($role);
            return JsonResponse::sendResponse([
                'email' => $user->email,
            ], 'El usuario ha sido registrado correctamente');
        }
        return JsonResponse::sendError('Ha ocurrido un error al registrar el usuario');
    }

    public function login(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $userIsValid = Auth::attempt([
            'email' => $email,
            'password' => $password,
        ]);

        if ($userIsValid) {
            $user = User::where('email', $email)->first();
            $user->tokens()->delete();
            $token = $user->createToken('auth-token');
            return JsonResponse::sendResponse([
                'token' => $token->plainTextToken
            ]);
        }

        return JsonResponse::sendError('Las credenciales son incorrectas', 401);
    }
}
