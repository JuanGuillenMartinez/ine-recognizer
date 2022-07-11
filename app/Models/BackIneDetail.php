<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackIneDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'faceapi_person_id',
        'citizen_identifier',
        'cic',
        'ocr',
        'model',
        'emision',
    ];

    public function azurePerson() {
        return $this->belongsTo(FaceapiPerson::class);
    }
}
