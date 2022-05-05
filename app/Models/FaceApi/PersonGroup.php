<?php

namespace App\Models\FaceApi;

use Illuminate\Support\Facades\Http;

class PersonGroup
{
    protected $baseUrl;
    protected $recognitionModel;
    protected $headers;

    public function __construct()
    {
        $this->baseUrl = "https://test-faceapi-fymsa.cognitiveservices.azure.com/face/v1.0/persongroups";
        $this->recognitionModel = 'recognition_04';
        $this->headers = [
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => env('FACEAPI_SUBSCRIPTION_KEY'),
        ];
    }

    public function create(string $personGroupId, array $properties)
    {
        $endpoint = "{$this->baseUrl}/{$personGroupId}";
        $response = Http::withHeaders($this->headers)->put($endpoint, [
            'name' => $properties['name'],
            'userData' => $properties['userData'],
            'recognitionModel' => $this->recognitionModel,
        ]);
        if (is_null($response)) {
            return (object) array('success' => true);
        }
        return json_decode($response);
    }

    public function get(string $personGroupId, bool $returnRecognitionModel = false)
    {
        $endpoint = "{$this->baseUrl}/{$personGroupId}";
        $response = Http::withHeaders($this->headers)->get($endpoint, ['returnRecognitionModel' => $returnRecognitionModel]);
        return json_decode($response);
    }

    public function train(string $personGroupId)
    {
        $endpoint = "{$this->baseUrl}/{$personGroupId}/train";
        $response = Http::withHeaders($this->headers)->post($endpoint);
        return json_decode($response);
    }

    public function getTrainingStatus(string $personGroupId) {
        $endpoint = "{$this->baseUrl}/{$personGroupId}/training";
        $response = Http::withHeaders($this->headers)->get($endpoint);
        return json_decode($response);
    }
}
