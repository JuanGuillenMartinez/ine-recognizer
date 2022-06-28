<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressInformationResource extends JsonResource
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
            'primer_direccion' => $this->first_address,
            'segunda_direccion' => $this->second_address,
            'numero_exterior' => $this->exterior_number,
            'estado' => $this->state,
            'municipio' => $this->city,
            'codigo_postal' => $this->zip_code,
        ];
    }
    // https://blobstorage1999.blob.core.windows.net/container-test/training/ine-david.png
}
