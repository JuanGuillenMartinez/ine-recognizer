<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceapiCredential extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'endpoint',
        'subscription_key',
        'recognition_model',
        'detection_model',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
