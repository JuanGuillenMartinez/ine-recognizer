<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceapiVerifyResult extends Model
{
    use HasFactory;
    protected $fillable = [
        'faceapi_person_id',
        'url_image',
        'confidence',
        'isIdentical',
    ];

    public function azurePerson()
    {
        return $this->belongsTo(FaceapiPerson::class);
    }
}
