<?php

namespace App\Http\Controllers\FaceApi;

use Illuminate\Http\Request;
use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\FaceApi\PersonGroupPerson;

class PersonController extends Controller
{
    public function create(Request $request, $personGroupId)
    {
        $userData = $request->input('user_data', '');
        $person = new PersonGroupPerson($personGroupId);
        $persistedPerson = $person->save($request->name, $userData);
        $personResponse = $person->get($persistedPerson->personId);
        return JsonResponse::sendResponse($personResponse);
    }

    public function listAll(Request $request, $personGroupId)
    {
        $start = $request->query('start', '');
        $top = $request->query('top', 500);
        $persons = new PersonGroupPerson($personGroupId);
        $response = $persons->list($start, $top);
        return JsonResponse::sendResponse($response);
    }

    public function addFace(Request $request, $personGroupId, $personId)
    {
        $imageUrl = $request->url_image;
        $userData = $request->input('user_data', '');
        $person = new PersonGroupPerson($personGroupId);
        $response = $person->addFace($personId, $imageUrl, $userData);
        return JsonResponse::sendResponse($response);
    }

    public function delete($personGroupId, $personId)
    {
        $person = new PersonGroupPerson($personGroupId);
        $response = $person->delete($personId);
        return JsonResponse::sendResponse($response);
    }

    public function deleteFace($personGroupId, $personId, $persistedFaceId)
    {
        $person = new PersonGroupPerson($personGroupId);
        $response = $person->deleteFace($personId, $persistedFaceId);
        return JsonResponse::sendResponse($response);
    }
}
