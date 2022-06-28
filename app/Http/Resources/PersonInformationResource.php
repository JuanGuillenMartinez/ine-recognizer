<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonInformationResource extends JsonResource
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
            'primer_nombre' => $this->names()->first_name,
            'segundo_nombre' => isset($this->names()->other_names) ? $this->names()->other_names : "",
            'apellido_paterno' => $this->father_lastname,
            'apellido_materno' => $this->mother_lastname,
            'clave_elector' => $this->clave_elector,
            'curp' => $this->curp,
            'sexo' => $this->gender,
            'nacimiento' => $this->birthdate,
            'domicilio' => $this->address,
        ];
    }
}
