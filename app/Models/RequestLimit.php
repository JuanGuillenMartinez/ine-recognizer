<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'limit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}