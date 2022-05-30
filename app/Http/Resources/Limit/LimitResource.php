<?php

namespace App\Http\Resources\Limit;

use Illuminate\Http\Resources\Json\JsonResource;

class LimitResource extends JsonResource
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
            'request' => $this->requestLimited->name,
            'limit' => $this->limit,
        ];
    }
}
