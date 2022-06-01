<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Resources\User\UserLimitsResource;
use App\Models\RequestLimit;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
}
