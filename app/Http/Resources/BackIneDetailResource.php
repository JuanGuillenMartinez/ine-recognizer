<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BackIneDetailResource extends JsonResource
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
            'tipo_identificacion' => $this->model,
            'cic' => $this->when(isset($this->cic), $this->cic),
            'ocr' => $this->when(isset($this->ocr), $this->ocr),
            'identificador_del_ciudadano' => $this->when(isset($this->citizen_identifier), $this->citizen_identifier),
            'emision' => $this->when(isset($this->emision), $this->emision),
        ];
    }
}
