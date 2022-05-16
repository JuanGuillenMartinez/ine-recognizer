<?php

namespace App\Models;

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

    public function addToPersonGroup($name)
    {
        $personGroupId = $this->faceapiPersonGroup->person_group_id;
        $personGroupPerson = new PersonGroupPerson($personGroupId);
        $results = $personGroupPerson->save($name);
        return $results;
    }
}
