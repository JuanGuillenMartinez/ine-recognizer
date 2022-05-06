<?php

namespace App\Models\FaceApi;

use Illuminate\Support\Facades\Http;

class Person
{
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

    public function create($personGroupId, $name, $userData = '')
    {
        $endpoint = "{$this->baseUrl}/{$personGroupId}/persons";
        $response = Http::withHeaders($this->headers)->post($endpoint, [
            'name' => $name,
            'userData' => $userData,
        ]);
        return json_decode($response);
    }

    public function addFace($personGroupId, $personId, $imageUrl, $userData = '')
    {
        $endpoint = "{$this->baseUrl}/{$personGroupId}/persons/{$personId}/persistedFaces?userData={$userData}&detectionModel={$this->detectionModel}";
        $response = Http::withHeaders($this->headers)->post($endpoint, [
            'url' => $imageUrl,
        ]);
        return json_decode($response);
    }

    public function list($personGroupId, $start = '', $top = 1000)
    {
        $endpoint = "{$this->baseUrl}/{$personGroupId}/persons?top={$top}";
        if (strcmp($start, '') !== 0) {
            $endpoint = $endpoint . "&start={$start}";
        }
        $response = Http::withHeaders($this->headers)->get($endpoint);
        return json_decode($response);
    }
}
