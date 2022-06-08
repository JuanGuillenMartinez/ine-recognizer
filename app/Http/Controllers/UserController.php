<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RequestLimit;
use Illuminate\Http\Request;
use App\Helpers\JsonResponse;
use App\Http\Resources\User\UserLimitsResource;
use App\Http\Resources\User\UserCredentialResource;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::paginate(10);
        // return JsonResponse::sendPaginateResponse(UserLimitsResource::collection($users));
        $users = User::all();
        return JsonResponse::sendResponse(UserLimitsResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateLimits($userId, Request $request)
    {
        $requestLimit = RequestLimit::find($request->user_limit_id);
        if (!isset($requestLimit)) {
            return JsonResponse::sendError('El usuario no tiene asignado el permiso');
        }
        $requestLimit->limit = $request->limit;
        if ($requestLimit->save()) {
            return JsonResponse::sendResponse($requestLimit, 'El limite de peticiones ha sido actualizado correctamente');
        }
        return JsonResponse::sendError('El limite de peticiones ha sido actualizado correctamente');
    }

    public function credentials($userId)
    {
        $user = User::find($userId);
        return JsonResponse::sendResponse(new UserCredentialResource($user));
    }

    public function generateToken($userId)
    {
        $user = User::find($userId);
        $token = $user->createToken('auth-token');
        return JsonResponse::sendResponse([
            'token' => $token->plainTextToken
        ]);
    }

    public function banUser($userId)
    {
        $user = User::find($userId);
        if (!isset($user)) {
            return JsonResponse::sendError('El usuario no se encuentra registrado');
        }
        $user->banned_at = Carbon::now();
        if ($user->save()) {
            return JsonResponse::sendResponse($user, 'El usuario ha sido dado de baja del sistema.');
        }
        return JsonResponse::sendError('Ha ocurrido un error');
    }

    public function unbanUser($userId)
    {
        $user = User::find($userId);
        if (!isset($user)) {
            return JsonResponse::sendError('El usuario no se encuentra registrado');
        }
        if (!isset($user->banned_at)) {
            return JsonResponse::sendError('El usuario se encuentra activo.');
        }
        $user->banned_at = null;
        if ($user->save()) {
            return JsonResponse::sendResponse($user, 'Se ha removido el bloqueo del usuario.');
        }
        return JsonResponse::sendError('Ha ocurrido un error');
    }
}
