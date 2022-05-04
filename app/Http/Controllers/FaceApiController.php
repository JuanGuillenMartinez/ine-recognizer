<?php

namespace App\Http\Controllers;

use App\Helpers\FaceApiRequest;
use App\Helpers\JsonResponse;
use Illuminate\Http\Request;

class FaceApiController extends Controller
{
    public function detectFace(Request $request) {
        $urlImage = $request->url_image;
        $handler = new FaceApiRequest();
        $results = $handler->detect($urlImage);
        return JsonResponse::sendResponse($results, 'El análisis ha finalizado correctamente');
    }
    
    public function verifyFaceToFace(Request $request) {
        $urlImage1 = $request->url_image_1;
        $urlImage2 = $request->url_image_2;
        $handler = new FaceApiRequest();
        $results = $handler->verifyFaceToFace($urlImage1, $urlImage2);
        return JsonResponse::sendResponse($results, 'El análisis ha finalizado correctamente');
    }
}
