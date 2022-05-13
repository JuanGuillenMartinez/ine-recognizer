<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commerce extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function people()
    {
        return $this->belongsToMany(Person::class);
    }
}
