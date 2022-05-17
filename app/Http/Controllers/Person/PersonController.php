<?php

namespace App\Http\Controllers\Person;

use App\Helpers\FaceApiRequest;
use App\Helpers\JsonResponse;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Models\FaceapiPerson;
use App\Http\Controllers\Controller;

class PersonController extends Controller
{
    public function analyzeFaceToPerson(Request $request, $commerceId, $personId)
    {
        $faceapiPerson = FaceapiPerson::find($personId);
        $urlImage = $request->url_image;
        if (!isset($faceapiPerson)) {
            return JsonResponse::sendError('El ID proporcionado es incorrecto');
        }
        $personGroup = $faceapiPerson->faceapiPersonGroup;
        $faceapiRequest = new FaceApiRequest();
        $response = $faceapiRequest->verifyFaceToPerson($urlImage, $personGroup->person_group_id, $faceapiPerson->faceapi_person_id);
        return JsonResponse::sendResponse($response);
    }
}
