<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'first_address',
        'second_address',
        'exterior_number',
        'zip_code',
        'city',
        'state',
    ];

    public function person() {
        return $this->belongsTo(Person::class);
    }
}
