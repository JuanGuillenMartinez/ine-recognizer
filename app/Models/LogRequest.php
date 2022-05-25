<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token_used',
        'ip_address',
        'url_requested',
        'headers',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}