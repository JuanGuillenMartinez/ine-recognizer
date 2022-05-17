<?php

namespace App\Models;

use App\Models\FaceApi\PersonGroup;
use App\Models\FaceApi\PersonGroupPerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Commerce extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function people()
    {
        return $this->belongsToMany(Person::class);
    }

    public function faceapiPersonGroup()
    {
        return $this->hasOne(FaceapiPersonGroup::class);
    }

    public function personGroupId()
    {
        return $this->faceapiPersonGroup->person_group_id;
    }

    public function addToPersonGroup($person)
    {
        $name = $person->name;
        $personGroupId = $this->personGroupId();
        $personGroupPerson = new PersonGroupPerson($personGroupId);
        $results = $personGroupPerson->save($name);
        $azurePersonId = $results->personId;
        $faceapiPerson = $this->persistPersonOnTable($person, $azurePersonId);
        return $faceapiPerson;
    }

    public function train() {
        $personGroupId = $this->personGroupId();
        $personGroup = new PersonGroup($personGroupId);
        $personGroup->train();
    }

    protected function persistPersonOnTable($person, $azurePersonId) {
        $faceapiPersonGroupId = $this->faceapiPersonGroup->id;
        $faceapiPerson = FaceapiPerson::create([
            'person_id' => $person->id,
            'faceapi_person_group_id' => $faceapiPersonGroupId,
            'faceapi_person_id' => $azurePersonId,
            'name' => $person->name,
        ]);
        return $faceapiPerson;
    }

    public function addFaceToPerson($detectFaceResults, $faceapiPerson, $urlPhoto) {
        $targetFace = "{$detectFaceResults->left},{$detectFaceResults->top},{$detectFaceResults->width},{$detectFaceResults->height}";
        $personGroupId = $this->personGroupId();
        $faceapiPersonId = $faceapiPerson->faceapi_person_id;
        $personGroupPerson = new PersonGroupPerson($personGroupId);
        $response = $personGroupPerson->addFace($faceapiPersonId, $urlPhoto, $targetFace);
        if (isset($response->persistedFaceId)) {
            $this->persistPersonFaceOnDb($response->persistedFaceId, $faceapiPerson, $urlPhoto);
        }
        return $response;
    }

    public function persistPersonFaceOnDb($persistedFaceId, $faceapiPerson, $imageUrl)
    {
        $faceapiFace = FaceapiFace::create([
            'faceapi_person_id' => $faceapiPerson->id,
            'url_image' => $imageUrl,
            'persisted_face_id' => $persistedFaceId,
        ]);
        return isset($faceapiFace);
    }
}
