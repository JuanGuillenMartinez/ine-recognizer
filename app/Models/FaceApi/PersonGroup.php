<?php

namespace App\Models\FaceApi;

use Illuminate\Support\Facades\Http;

class PersonGroup
{
    protected $baseUrl;
    protected $recognitionModel;
    protected $headers;
    protected $personGroupId;

    public function __construct($personGroupId)
    {
        $this->baseUrl = env('URL_BASE_FACEAPI') . "/persongroups";
        $this->recognitionModel = 'recognition_04';
        $this->personGroupId = $personGroupId;
        $this->headers = [
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => env('FACEAPI_SUBSCRIPTION_KEY'),
        ];
    }

    public function save(array $properties)
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}";
        $response = Http::withHeaders($this->headers)->put($endpoint, [
            'name' => $properties['name'],
            'userData' => $properties['userData'],
            'recognitionModel' => $this->recognitionModel,
        ]);
        echo '<pre>';
        var_dump($response);
        echo '</pre>';
        die;
        if (is_null($response)) {
            return (object) array('success' => true);
        }
        return json_decode($response);
    }

    public function get(bool $returnRecognitionModel = false)
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}";
        $response = Http::withHeaders($this->headers)->get($endpoint, ['returnRecognitionModel' => $returnRecognitionModel]);
        return json_decode($response);
    }

    public function train()
    {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/train";
        $response = Http::withHeaders($this->headers)->post($endpoint);
        return json_decode($response);
    }

    public function getTrainingStatus() {
        $endpoint = "{$this->baseUrl}/{$this->personGroupId}/training";
        $response = Http::withHeaders($this->headers)->get($endpoint);
        return json_decode($response);
    }
}
