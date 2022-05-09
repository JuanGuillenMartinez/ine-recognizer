<?php

namespace App\Models\FaceApi;

use Illuminate\Support\Facades\Http;

class Person
{
    protected $baseUrl;
    protected $detectionModel;
    protected $headers;
    protected $personGroupId;

    public function __construct($personGroupId)
    {
        $this->baseUrl = env('URL_BASE_FACEAPI') . "/persongroups";
        $this->detectionModel = 'detection_03';
        $this->personGroupId = $personGroupId;
        $this->headers = [
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => env('FACEAPI_SUBSCRIPTION_KEY'),
        ];
    }

    public function save($name, $userData = '')
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/persons";
        $response = Http::withHeaders($this->headers)->post($endpoint, [
            'name' => $name,
            'userData' => $userData,
        ]);
        return json_decode($response);
    }

    public function addFace($personId, $imageUrl, $userData = '')
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/persons/{$personId}/persistedFaces?userData={$userData}&detectionModel={$this->detectionModel}";
        $response = Http::withHeaders($this->headers)->post($endpoint, [
            'url' => $imageUrl,
        ]);
        return json_decode($response);
    }

    public function list($start = '', $top = 1000)
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/persons?top={$top}";
        if (strcmp($start, '') !== 0) {
            $endpoint = $endpoint . "&start={$start}";
        }
        $response = Http::withHeaders($this->headers)->get($endpoint);
        return json_decode($response);
    }

    public function get($personId)
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/persons/{$personId}";
        $response = Http::withHeaders($this->headers)->get($endpoint);
        return json_decode($response);
    }
}
