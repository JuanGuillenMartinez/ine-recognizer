<?php

namespace App\Models;

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

    public function assignToCommerce($commerceId)
    {
        $commerce = Commerce::find($commerceId);
        $commercePerson = CommercePerson::where(['commerce_id' => $commerceId, 'person_id' => $this->id])->first();
        if(!isset($commercePerson)) {
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
}
