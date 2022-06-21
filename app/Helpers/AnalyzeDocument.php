<?php

namespace App\Helpers;

class AnalyzeDocument
{
    public static function analyzeDocument(string $documentUrl): array
    {
        $modelRecognition = env('FORM_RECOGNIZER_MODEL');
        $azureHandler = new AzureRecognitionRequest($modelRecognition);
        $responseId = $azureHandler->sendRequest($documentUrl);
        $rawResponse = $azureHandler->getResults($responseId);
        $formattedResponse = AnalyzeDocument::formatResults($rawResponse);
        return $formattedResponse;
    }

    public static function formatResults($rawResponse)
    {
        $formatter = new ResponseFormatter();
        $formattedResponse = $formatter->formattedResponse($rawResponse['analyzeResult']['documents']);
        return $formattedResponse;
    }
}
