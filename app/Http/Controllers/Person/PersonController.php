<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Models\CommercePerson;
use App\Models\FaceApi\PersonGroupPerson;
use App\Models\FaceapiFace;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function addFace(Request $request, $commerceId, $personId)
    {
        $imageUrl = $request->url_photo;
        $commercePerson = CommercePerson::where(['commerce_id' => $commerceId, 'person_id' => $personId])->first();
        $commerce = $commercePerson->commerce;
        $person = $commercePerson->person;
        $results = $this->addFaceOnAzure($commerce, $person, $imageUrl);
        return $results;
    }

    private function addFaceOnAzure($commerce, $person, $imageUrl)
    {
        $personId = $person->faceapiPerson->faceapi_person_id;
        $personGroupId = $commerce->faceapiPersonGroup->person_group_id;
        $faceapiPerson = new PersonGroupPerson($personGroupId);
        $response = $faceapiPerson->addFace($personId, $imageUrl);
        $this->persistFaceResults($response, $person->faceapiPerson->id, $imageUrl);
        return $response;
    }

    private function persistFaceResults($response, $personId, $imageUrl)
    {
        $persistedFaceId = $response->persistedFaceId;
        if (!isset($persistedFaceId)) {
            return false;
        }
        $faceapiFace = FaceapiFace::create([
            'faceapi_person_id' => $personId,
            'url_image' => $imageUrl,
            'persisted_face_id' => $persistedFaceId,
        ]);
        return isset($faceapiFace);
    }
}
