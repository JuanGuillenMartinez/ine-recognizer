<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaceApiPersonResource extends JsonResource
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
            'person_id' => $this->id,
            'person' => new PersonInformationResource($this->person),
            'faceapi_person_id' => $this->faceapi_person_id,
            'informacion_domicilio' => new AddressInformationResource($this->person->addressInformation),
            'informacion_ine' => new BackIneDetailResource($this->backIneResult),
        ];
    }
}
