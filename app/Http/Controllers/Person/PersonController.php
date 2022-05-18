<?php

namespace App\Http\Controllers\Person;

use App\Helpers\FaceApiRequest;
use App\Helpers\JsonResponse;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Models\FaceapiPerson;
use App\Http\Controllers\Controller;
use App\Jobs\TrainPersonWithPhotoSended;
use App\Models\Commerce;
use App\Models\FaceapiVerifyResult;

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
        $verifyResults = $this->persistVerifyResults($faceapiPerson, $response, $urlImage);
        if ($response->isIdentical) {
            TrainPersonWithPhotoSended::dispatch($faceapiPerson, $verifyResults);
        }
        return JsonResponse::sendResponse($response);
    }

    public function personInformation(Request $request, $commerceId)
    {
        $curp = $request->curp;
        $person = Person::where('curp', $curp)->first();
        if (!isset($person)) return JsonResponse::sendError('La CURP proporcionada es incorrecta');
        $commerce = Commerce::find($commerceId);
        if (!isset($person)) return JsonResponse::sendError('El comercio no es encuentra registrado');
        $personGroup = $commerce->faceapiPersonGroup;
        $facePerson = FaceapiPerson::where([
            'person_id' => $person->id,
            'faceapi_person_group_id' => $personGroup->id,
        ])->first();
        if (!isset($facePerson)) return JsonResponse::sendError('El usuario no es encuentra registrado');
        return JsonResponse::sendResponse([
            'person_id' => $facePerson->id,
        ]);
    }

    private function persistVerifyResults($azurePerson, $response, $urlImage)
    {
        $verifyResults = FaceapiVerifyResult::create([
            'faceapi_person_id' => $azurePerson->id,
            'url_image' => $urlImage,
            'confidence' => $response->confidence,
            'isIdentical' => $response->isIdentical,
        ]);
        return $verifyResults;
    }
}
