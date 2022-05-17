<?php

namespace App\Models;

use App\Models\FaceApi\PersonGroupPerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'father_lastname',
        'mother_lastname',
        'clave_elector',
        'curp',
        'gender',
        'birthdate',
        'ine_url',
        'address',
    ];

    public function commerces()
    {
        return $this->belongsToMany(Commerce::class);
    }

    public function faceapiPerson()
    {
        return $this->hasOne(FaceapiPerson::class);
    }

    public function azurePersonId()
    {
        return $this->faceapiPerson->faceapi_person_id;
    }

    public function assignToCommerce($commerceId)
    {
        $commerce = Commerce::find($commerceId);
        $commercePerson = CommercePerson::where(['commerce_id' => $commerceId, 'person_id' => $this->id])->first();
        if (!isset($commercePerson)) {
            $commerce->people()->attach($this->id);
            return true;
        }
        return false;
    }

    public function saveOnAzureFaceApi($commerceId)
    {
        $commerce = Commerce::find($commerceId);
        $response = $commerce->addToPersonGroup($this->name);
        $personId = $response->personId;
        $personGroupId = $commerce->faceapiPersonGroup->id;
        $this->saveOnFaceapiPeopleTable($personId, $personGroupId);
        return $response;
    }

    public function saveOnFaceapiPeopleTable($faceapiPersonId, $faceapiPersonGroupId)
    {
        $faceapiPerson = FaceapiPerson::create([
            'person_id' => $this->id,
            'faceapi_person_group_id' => $faceapiPersonGroupId,
            'faceapi_person_id' => $faceapiPersonId,
            'name' => $this->name,
        ]);
        return $faceapiPerson;
    }

    public function addFaceToPersonOnAzure($detectFaceResults, $commerceId, $urlPhoto)
    {
        $targetFace = "{$detectFaceResults->left},{$detectFaceResults->top},{$detectFaceResults->width},{$detectFaceResults->height}";
        $commerce = Commerce::find($commerceId);
        $personId = $this->azurePersonId();
        $personGroupId = $commerce->personGroupId();
        $personGroupPerson = new PersonGroupPerson($personGroupId);
        $response = $personGroupPerson->addFace($personId, $urlPhoto, $targetFace);
        if (isset($response->persistedFaceId)) {
            $this->persistPersonFaceOnDb($response->persistedFaceId, $this->id, $urlPhoto);
        }
        return $response;
    }

    public function persistPersonFaceOnDb($persistedFaceId, $personId, $imageUrl)
    {
        $faceapiFace = FaceapiFace::create([
            'faceapi_person_id' => $personId,
            'url_image' => $imageUrl,
            'persisted_face_id' => $persistedFaceId,
        ]);
        return isset($faceapiFace);
    }
}
