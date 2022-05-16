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
}
