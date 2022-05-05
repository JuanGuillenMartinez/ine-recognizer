<?php
namespace App\Models\FaceApi;

use Illuminate\Support\Facades\Http;

class FaceApi {
    protected $baseUrl;
    protected $detectionModel;
    protected $headers;

    public function __construct()
    {
        $this->baseUrl = "https://test-faceapi-fymsa.cognitiveservices.azure.com/face/v1.0/persongroups";
        $this->detectionModel = 'detection_03';
        $this->headers = [
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => env('FACEAPI_SUBSCRIPTION_KEY'),
        ];
    }

    public function create($personGroupId, $name, $userData = '') {
        $endpoint = "{$this->baseUrl}/{$personGroupId}/persons";
        $response = Http::withHeaders($this->headers)->post($endpoint, [
            'name' => $name,
            'userData' => $userData,
        ]);
        return json_decode($response);
    }
}