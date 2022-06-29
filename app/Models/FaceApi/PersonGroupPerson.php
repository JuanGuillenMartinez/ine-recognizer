<?php

namespace App\Models\FaceApi;

use App\Exceptions\AzureFaceApiException;
use Illuminate\Support\Facades\Http;

class PersonGroupPerson
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
        $jsonResponse = json_decode($response);
        $this->existErrorOnResponse($jsonResponse);
        return $jsonResponse;
    }

    public function addFace($personId, $imageUrl, $targetFace = null, $userData = '')
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/persons/{$personId}/persistedFaces?userData={$userData}&detectionModel={$this->detectionModel}";
        if (isset($targetFace)) {
            $endpoint = $endpoint . '&targetFace=' . $targetFace;
        }
        $response = Http::withHeaders($this->headers)->post($endpoint, [
            'url' => $imageUrl,
        ]);
        $jsonResponse = json_decode($response);
        $this->existErrorOnResponse($jsonResponse);
        return $jsonResponse;
    }

    public function list($start = '', $top = 1000)
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/persons?top={$top}";
        if (strcmp($start, '') !== 0) {
            $endpoint = $endpoint . "&start={$start}";
        }
        $response = Http::withHeaders($this->headers)->get($endpoint);
        $jsonResponse = json_decode($response);
        $this->existErrorOnResponse($jsonResponse);
        return $jsonResponse;
    }

    public function get($personId)
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/persons/{$personId}";
        $response = Http::withHeaders($this->headers)->get($endpoint);
        $jsonResponse = json_decode($response);
        $this->existErrorOnResponse($jsonResponse);
        return $jsonResponse;
    }

    public function delete($personId)
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/persons/{$personId}";
        $response = Http::withHeaders($this->headers)->delete($endpoint);
        $jsonResponse = json_decode($response);
        $this->existErrorOnResponse($jsonResponse);
        return $jsonResponse;
    }

    public function deleteFace($personId, $persistedFaceId)
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/persons/{$personId}/persistedFaces/{$persistedFaceId}";
        $response = Http::withHeaders($this->headers)->delete($endpoint);
        $jsonResponse = json_decode($response);
        $this->existErrorOnResponse($jsonResponse);
        return $jsonResponse;
    }

    protected function existErrorOnResponse($response)
    {
        if (isset($response->error)) {
            throw new AzureFaceApiException(json_encode($response->error), 415);
        }
    }
}
