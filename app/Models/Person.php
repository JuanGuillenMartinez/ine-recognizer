<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FaceApi\PersonGroupPerson;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->hasMany(FaceapiPerson::class);
    }

    public function ineInformation()
    {
        return $this->hasOne(IneDetail::class);
    }

    public function azurePersonId()
    {
        return $this->faceapiPerson->faceapi_person_id;
    }

    public function addressInformation()
    {
        return $this->hasOne(Address::class);
    }

    public function names()
    {
        $personName = trim($this->name);
        $nameExploded = explode(' ', $personName);
        $names['first_name'] = array_shift($nameExploded);
        if (count($nameExploded) > 0) {
            $names['other_names'] = implode(' ', $nameExploded);
        }
        return (object) $names;
    }

    public function setAddressInformation($attributes)
    {
        $attributes['person_id'] = $this->id;
        Address::create($attributes);
        $this->refresh();
    }
}
