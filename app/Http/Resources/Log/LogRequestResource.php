<?php

namespace App\Http\Resources\Log;

use Illuminate\Http\Resources\Json\JsonResource;

class LogRequestResource extends JsonResource
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
            'user' => $this->user->name,
            'token' => $this->token_used,
            'ip' => $this->ip_address,
            'url_requested' => $this->url_requested,
            'created_at' => $this->created_at,
        ];
    }
}
