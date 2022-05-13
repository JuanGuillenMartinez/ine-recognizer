<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceapiPersonGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'commerce_id',
        'person_group_id',
        'name',
        'user_data',
    ];

    public function commerce()
    {
        return $this->belongsTo(Commerce::class);
    }

    public function faceapiPersons()
    {
        return $this->hasMany(FaceapiPerson::class);
    }
}
