<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JsonResponse;
use App\Helpers\ResponseFormatter;
use App\Helpers\AzureRecognitionRequest;

class DocumentController extends Controller
{
    //
    public function analyze(Request $request)
    {
        // $documentUrl = $request->get('document_url');
        $azureHandler = new AzureRecognitionRequest('ine-model-test');
        // $responseId = $azureHandler->sendRequest($documentUrl);
        $rawResponse = $azureHandler->getResults('aced5162-9696-437f-98cf-4b7bc7f67ade');
        $formatter = new ResponseFormatter();
        $formattedResponse = $formatter->formattedResponse($rawResponse['analyzeResult']['documents']);
        return JsonResponse::sendResponse($formattedResponse);
    }

    public function analyzeIne(Request $request)
    {
        $frontDocumentUrl = $request->get('front_document_url');
        $backDocumentUrl = $request->get('back_document_url');

        $azureHandler = new AzureRecognitionRequest('test-ine');
        $responseId = $azureHandler->sendRequest($frontDocumentUrl);
        $responseId = $azureHandler->sendRequest($backDocumentUrl);
        $rawResponse = $azureHandler->getResults('aced5162-9696-437f-98cf-4b7bc7f67ade');
        $formatter = new ResponseFormatter();
        $formattedResponse = $formatter->formattedResponse($rawResponse['analyzeResult']['documents']);
        return JsonResponse::sendResponse($formattedResponse);
    }
}
