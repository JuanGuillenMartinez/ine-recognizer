<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Helpers\ResponseFormatter;
use App\Helpers\AzureRecognitionRequest;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function analyzeFrontIne(Request $request)
    {
        $rawResponse = $this->analyzeDocument($request->document_url);
        $formatter = new ResponseFormatter();
        $formattedResponse = $formatter->formattedResponse($rawResponse['analyzeResult']['documents']);
        return JsonResponse::sendResponse($formattedResponse);
    }

    public function analyzeBackIne(Request $request)
    {
        $rawResponse = $this->analyzeDocument($request->document_url);
        $formatter = new ResponseFormatter();
        $formattedResponse = $formatter->formattedResponse($rawResponse['analyzeResult']['documents']);
        return JsonResponse::sendResponse($formattedResponse);
    }

    protected function analyzeDocument(string $documentUrl): array
    {
        $azureHandler = new AzureRecognitionRequest('composed_ine_model');
        $responseId = $azureHandler->sendRequest($documentUrl);
        $rawResponse = $azureHandler->getResults($responseId);
        return $rawResponse;
    }
}
