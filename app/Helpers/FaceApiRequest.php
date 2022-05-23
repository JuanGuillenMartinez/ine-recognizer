<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class FaceApiRequest
{

    protected $baseUrl;
    protected $detectUrl;
    protected $headers;

    public function __construct()
    {
        $this->baseUrl = 'https://test-faceapi-fymsa.cognitiveservices.azure.com/face/v1.0';
        $this->detectUrl = $this->baseUrl . "/detect?returnFaceId=true&recognitionModel=recognition_04";
        $this->headers = [
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => env('FACEAPI_SUBSCRIPTION_KEY')
        ];
    }

    public function detect($urlImage)
    {
        $response = Http::withHeaders($this->headers)->post($this->detectUrl, [
            'url' => $urlImage,
        ]);
        $jsonResponse = json_decode($response->body());
        if (isset($jsonResponse->error)) {
            return JsonResponse::sendError($jsonResponse);
        }
        if(!isset($jsonResponse[0])) {
            return JsonResponse::sendError('Ha ocurrido un error al procesar la imagen. Asegúrese de enviar la imagen correctamente de acuerdo a la documentación proporcionada.', 500, $jsonResponse);
        }
        return $jsonResponse[0];
    }

    public function verifyFaceToFace($image1, $image2)
    {
        $verifyUrl = "{$this->baseUrl}/verify";
        $faceResult = $this->detect($image1);
        $faceResult2 = $this->detect($image2);
        $response = Http::withHeaders($this->headers)->post($verifyUrl, [
            'faceId1' => $faceResult->faceId,
            'faceId2' => $faceResult2->faceId
        ]);
        $jsonResponse = json_decode($response->body());
        return $jsonResponse;
    }

    public function verifyFaceToPerson($urlImage, $personGroupId, $personId)
    {
        $verifyUrl = "{$this->baseUrl}/verify";
        $response = $this->detect($urlImage);
        if (isset($response->error)) {
            return (object) $response;
        }
        $faceId = $response->faceId;
        $response = Http::withHeaders($this->headers)->post($verifyUrl, [
            'faceId' => $faceId,
            'personGroupId' => $personGroupId,
            'personId' => $personId
        ]);
        return json_decode($response);
    }
}
