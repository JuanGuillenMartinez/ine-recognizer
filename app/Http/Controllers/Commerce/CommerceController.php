<?php

namespace App\Http\Controllers\Commerce;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Commerce;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\JsonMatchesErrorMessageProvider;

class CommerceController extends Controller
{
    public function create(Request $request)
    {
        $attributes = [
            'user_id' => $request->input('user_id'),
            'name' => $request->input('name'),
        ];
        $commerce = new Commerce($attributes);
        if ($commerce->save()) {
            return JsonResponse::sendResponse($commerce, 'Comercio registrado correctamente');
        }
        return JsonResponse::sendError('Ha ocurrido un error al registrar el comercio');
    }
}
