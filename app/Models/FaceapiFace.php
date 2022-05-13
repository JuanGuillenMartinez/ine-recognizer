<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceapiFace extends Model
{
    use HasFactory;

    protected $fillable = [
        'faceapi_person_id',
        'url_image',
        'persisted_face_id',
    ];

    public function faceapiPerson()
    {
        return $this->belongsTo(FaceapiPerson::class);
    }
}
