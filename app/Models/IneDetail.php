<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IneDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'person_id',
        'date_identifier',
        'owner_identifier',
        'credential_identifier',
        'vertical_number',
    ];

    public function person() {
        return $this->belongsTo(Person::class);
    }
}
