<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FaceApi\PersonGroupPerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FaceapiPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'faceapi_person_group_id',
        'faceapi_person_id',
        'name',
    ];

    public function faceapiPersonGroup()
    {
        return $this->belongsTo(FaceapiPersonGroup::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function azureVerifyResults()
    {
        return $this->hasMany(FaceapiVerifyResult::class);
    }

    public function personGroupId()
    {
        return $this->faceapiPersonGroup->person_group_id;
    }

    public function addFace($detectFaceResults, $urlPhoto)
    {
        $targetFace = "{$detectFaceResults->left},{$detectFaceResults->top},{$detectFaceResults->width},{$detectFaceResults->height}";
        $faceapiPersonId = $this->faceapi_person_id;
        $personGroupPerson = new PersonGroupPerson($this->personGroupId());
        $response = $personGroupPerson->addFace($faceapiPersonId, $urlPhoto, $targetFace);
        if (isset($response->persistedFaceId)) {
            $this->persistPersonFaceOnDb($response->persistedFaceId, $urlPhoto);
        }
        return $response;
    }

    public function persistPersonFaceOnDb($persistedFaceId, $imageUrl)
    {
        $faceapiFace = FaceapiFace::create([
            'faceapi_person_id' => $this->id,
            'url_image' => $imageUrl,
            'persisted_face_id' => $persistedFaceId,
        ]);
        return isset($faceapiFace);
    }
}
