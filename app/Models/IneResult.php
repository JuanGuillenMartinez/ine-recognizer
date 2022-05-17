<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IneResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'url_image',
        'faceId',
        'top',
        'left',
        'width',
        'height',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
