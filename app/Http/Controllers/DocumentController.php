<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Helpers\ResponseFormatter;
use App\Helpers\AzureRecognitionRequest;
use App\Http\Requests\IneRequest;

class DocumentController extends Controller
{
    public function analyzeFrontIne(IneRequest $request)
    {
        $rawResponse = $this->analyzeDocument($request->document_url);
        $formatter = new ResponseFormatter();
        $formattedResponse = $formatter->formattedResponse($rawResponse['analyzeResult']['documents']);
        return JsonResponse::sendResponse($formattedResponse);
    }

    public function analyzeBackIne(IneRequest $request)
    {
        $rawResponse = $this->analyzeDocument($request->document_url);
        $formatter = new ResponseFormatter();
        $formattedResponse = $formatter->formattedResponse($rawResponse['analyzeResult']['documents']);
        return JsonResponse::sendResponse($formattedResponse);
    }

    protected function analyzeDocument(string $documentUrl): array
    {
        $azureHandler = new AzureRecognitionRequest('test-ine');
        $responseId = $azureHandler->sendRequest($documentUrl);
        $rawResponse = $azureHandler->getResults($responseId);
        return $rawResponse;
    }
}
