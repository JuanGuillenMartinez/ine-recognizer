<?php

namespace App\Http\Controllers;

use App\Helpers\FaceApiRequest;
use App\Helpers\JsonResponse;
use Illuminate\Http\Request;

class FaceApiController extends Controller
{
    public function detectFace(Request $request) {
        $urlImage = $request->url_image;
        $results = FaceApiRequest::detect($urlImage);
        return JsonResponse::sendResponse($results, 'El analisis ha finalizado correctamente');
    }
}
