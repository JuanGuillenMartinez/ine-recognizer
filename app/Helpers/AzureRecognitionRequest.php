<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class AzureRecognitionRequest
{
    static $response;
    protected $azureModelId;
    protected $baseUrl;

    public function __construct(string $azureModelId)
    {
        $this->azureModelId = $azureModelId;
        $this->baseUrl = "https://eastus2.api.cognitive.microsoft.com/formrecognizer/documentModels/{$azureModelId}";
    }

    public function sendRequest($documentUrl): string
    {
        $analizeUrl = "{$this->baseUrl}:analyze?api-version=2022-01-30-preview";
        $curl = curl_init($analizeUrl);
        curl_setopt($curl, CURLOPT_URL, $analizeUrl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt(
            $curl,
            CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$headers) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                    return $len;
                $headers[strtolower(trim($header[0]))][] = trim($header[1]);
                return $len;
            }
        );

        $headers = array(
            "Content-Type: application/json",
            "Ocp-Apim-Subscription-Key: " . env("SUBSCRIPTION_KEY"),
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = "{'urlSource': '" . $documentUrl . "' }";

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($curl);
        curl_close($curl);
        $keyResponse = strval($headers['apim-request-id'][0]);
        return $keyResponse;
    }

    public function getResults($responseId)
    {
        Log::info($responseId);
        $response = $this->getCurlResults($responseId);
        if (!isset($response['status'])) {
            return $response;
        }
        $isSuccess = strcmp($response['status'], 'succeeded') === 0;
        while (!$isSuccess) {
            sleep(1);
            $response = $this->getCurlResults($responseId);
            $isSuccess = strcmp($response['status'], 'succeeded') === 0;
        }
        return $response;
    }

    protected function getCurlResults($responseId)
    {
        $url = "{$this->baseUrl}/analyzeResults/{$responseId}?api-version=2022-01-30-preview";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Ocp-Apim-Subscription-Key: " . env('SUBSCRIPTION_KEY'),
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $jsonResponse = json_decode($resp, true);
        return $jsonResponse;
    }
}
