<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
