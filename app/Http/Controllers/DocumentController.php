<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JsonResponse;
use App\Helpers\ResponseFormatter;
use App\Helpers\AzureRecognitionRequest;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    //
    // public function analyze(Request $request)
    // {
    //     // $documentUrl = $request->get('document_url');
    //     $azureHandler = new AzureRecognitionRequest('ine-model-test');
    //     // $responseId = $azureHandler->sendRequest($documentUrl);
    //     $rawResponse = $azureHandler->getResults('aced5162-9696-437f-98cf-4b7bc7f67ade');
    //     $formatter = new ResponseFormatter();
    //     $formattedResponse = $formatter->formattedResponse($rawResponse['analyzeResult']['documents']);
    //     return JsonResponse::sendResponse($formattedResponse);
    // }

    public function analyzeFrontIne(Request $request)
    {
        $rawResponse = $this->analyzeDocument($request->front_document_url);
        $formatter = new ResponseFormatter();
        $formattedResponse = $formatter->formattedResponse($rawResponse['analyzeResult']['documents']);
        return JsonResponse::sendResponse($formattedResponse);
    }

    public function analyzeBackIne(Request $request)
    {
        $rawResponse = $this->analyzeDocument($request->back_document_url);
        $formatter = new ResponseFormatter();
        $formattedResponse = $formatter->formattedResponse($rawResponse['analyzeResult']['documents']);
        return JsonResponse::sendResponse($formattedResponse);
    }

    protected function analyzeDocument(string $documentUrl) : array {
        $azureHandler = new AzureRecognitionRequest('test-ine');
        $responseId = $azureHandler->sendRequest($documentUrl);
        Log::info($responseId);
        $rawResponse = $azureHandler->getResults($responseId);
        return $rawResponse;
    }
}
