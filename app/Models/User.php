<?php

namespace App\Models;

use App\Models\Request as UserRequest;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasEvents;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'default_pass'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function credential()
    {
        return $this->hasOne(FaceapiCredential::class);
    }

    public function commerce()
    {
        return $this->hasOne(Commerce::class);
    }

    public function requestLimit()
    {
        return $this->hasMany(RequestLimit::class);
    }

    public function registerAllLimits($limit = null)
    {
        $requests = UserRequest::all();
        $limitIsSetted = isset($limit);

        if(!isset($requests)) {
            return false;
        }

        foreach ($requests as $request) {
            $limitAssigned = $limitIsSetted ? $limit : $request->limit_default;
            $this->assignLimitToRequest($request->id, $limitAssigned);
        }
    }

    public function assignLimitToRequest(int $requestId, int $limit)
    {
        $requestLimit = RequestLimit::create([
            'user_id' => $this->id,
            'request_id' => $requestId,
            'limit' => $limit,
        ]);
        return isset($requestLimit);
    }

    public function registerRequestMade(UserRequest $request) {
        $requestLimit = RequestLimit::where(['user_id' => $this->id, 'request_id' => $request->id])->first();
        $limitForUser = $requestLimit->limit;
        if($limitForUser > 0) {
            $requestLimit->limit = $limitForUser - 1;
            return $requestLimit->save();
        }
        return false;
    }
}
