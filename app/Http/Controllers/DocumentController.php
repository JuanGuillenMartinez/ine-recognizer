<?php

namespace App\Http\Controllers;

use App\Helpers\AnalyzeDocument;
use Illuminate\Http\Request;
use App\Helpers\JsonResponse;

class DocumentController extends Controller
{
    public function analyzeFrontIne(Request $request)
    {
        $response = AnalyzeDocument::analyzeDocument($request->document_url);
        return JsonResponse::sendResponse($response);
    }

    public function analyzeBackIne(Request $request)
    {
        $response = AnalyzeDocument::analyzeDocument($request->document_url);
        return JsonResponse::sendResponse($response);
    }
}
