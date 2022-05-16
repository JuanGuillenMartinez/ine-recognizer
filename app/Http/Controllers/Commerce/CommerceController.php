<?php

namespace App\Http\Controllers\Commerce;

use App\Helpers\AnalyzeDocument;
use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Commerce;
use App\Models\Person;
use Illuminate\Http\Request;

class CommerceController extends Controller
{
    public function create(Request $request)
    {
        $attributes = [
            'user_id' => $request->input('user_id'),
            'name' => $request->input('name'),
        ];
        $commerce = new Commerce($attributes);
        if ($commerce->save()) {
            return JsonResponse::sendResponse($commerce, 'Comercio registrado correctamente');
        }
        return JsonResponse::sendError('Ha ocurrido un error al registrar el comercio');
    }

    public function addPerson(Request $request, $commerceId)
    {
        $commerce = Commerce::find($commerceId);
        $urlIne = $request->photo_url;
        if (!isset($commerce)) {
            return JsonResponse::sendError('El ID proporcionado es incorrecto');
        }
        $results = AnalyzeDocument::analyzeDocument($urlIne);
        $dataExtracted = $results[0];
        $person = $this->registerPerson($dataExtracted, $urlIne);
        $wasAssigned = $person->assignToCommerce($commerceId);
        if ($wasAssigned) {
            $personGroupPerson = $person->saveOnAzureFaceApi($commerceId);
        }
        return JsonResponse::sendResponse($person);
    }

    private function registerPerson($dataExtracted, $ineUrl)
    {
        $searchParams = [
            'clave_elector' => $dataExtracted['clave_elector']
        ];
        $attributes = [
            'name' => $dataExtracted['nombre'],
            'father_lastname' => $dataExtracted['apellido_paterno'],
            'mother_lastname' => $dataExtracted['apellido_materno'],
            'curp' => $dataExtracted['curp'],
            'gender' => $dataExtracted['sexo'],
            'birthdate' => $dataExtracted['nacimiento'],
            'address' => $dataExtracted['domicilio'],
            'ine_url' => $ineUrl,
        ];
        $person = Person::firstOrCreate($searchParams, $attributes);
        return $person;
    }
}
