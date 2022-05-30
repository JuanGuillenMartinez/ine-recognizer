<?php

namespace App\Models;

use App\Models\Request as UserRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_id',
        'limit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requestLimited()
    {
        return $this->belongsTo(UserRequest::class, 'request_id');
    }
}
