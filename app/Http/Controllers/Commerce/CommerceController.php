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

    public function faceapiPersonGroupId($commerceId)
    {
        $commerce = Commerce::find($commerceId);
        if (!isset($commerce)) {
            return JsonResponse::sendError('El ID proporcionado es incorrecto');
        }
        $personGroupId = [
            'person_group_id' => $commerce->faceapiPersonGroup->person_group_id,
        ];
        return JsonResponse::sendResponse($personGroupId);
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
            $person->saveOnAzureFaceApi($commerceId);
        }
        $data = $this->formatResponseData($person, $dataExtracted);
        return JsonResponse::sendResponse($data);
    }

    public function train(Request $request, $commerceId)
    {
        // $urlArray = $request->images;
        // echo '<pre>';
        // var_dump($urlArray);
        // echo '</pre>';
        // die;
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

    private function formatResponseData($person, $dataExtracted)
    {
        $dataExtracted['faceapi_person_id'] = $person->faceapiPerson->faceapi_person_id;
        return $dataExtracted;
    }
}
