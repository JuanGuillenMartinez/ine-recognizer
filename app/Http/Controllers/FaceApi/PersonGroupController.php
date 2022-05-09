<?php

namespace App\Http\Controllers\FaceApi;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\FaceApi\PersonGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PersonGroupController extends Controller
{
    public function create(Request $request, $personGroupId)
    {
        $name = $request->name;
        $userData = isset($request->user_data) ? $request->user_data : '';

        $personGroup = new PersonGroup($personGroupId);
        $personGroup->save($name, $userData);
        $response = $personGroup->get();
        return JsonResponse::sendResponse($response);
    }

    public function train(Request $request, $personGroupId)
    {
        $personGroup = new PersonGroup($personGroupId);
        $personGroup->train();
        $response = $personGroup->getTrainingStatus();
        return JsonResponse::sendResponse($response);
    }

    public function trainingStatus($personGroupId)
    {
        $personGroup = new PersonGroup($personGroupId);
        $response = $personGroup->getTrainingStatus();
        return JsonResponse::sendResponse($response);
    }

    public function delete($personGroupId)
    {
        $personGroup = new PersonGroup($personGroupId);
        $response = $personGroup->delete();
        return JsonResponse::sendResponse($response);
    }

    public function list()
    {
        $endpoint = env('URL_BASE_FACEAPI') . "/persongroups";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => env('FACEAPI_SUBSCRIPTION_KEY'),
        ])->get($endpoint);
        return JsonResponse::sendResponse(json_decode($response));
    }
}
