<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Limit\LimitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLimitsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'limit' => LimitResource::collection($this->requestLimit),
            'is_banned' => isset($this->banned_at),
        ];
    }
}
