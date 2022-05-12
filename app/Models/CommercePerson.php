<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommercePerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'commerce_id',
        'person_id',
    ];

    public function commerce()
    {
        return $this->belongsTo(Commerce::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
