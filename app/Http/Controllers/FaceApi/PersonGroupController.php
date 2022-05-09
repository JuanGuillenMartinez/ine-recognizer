<?php

namespace App\Http\Controllers\FaceApi;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\FaceApi\PersonGroup;
use Illuminate\Http\Request;

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
}
