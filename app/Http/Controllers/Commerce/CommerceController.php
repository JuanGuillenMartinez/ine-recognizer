<?php

namespace App\Http\Controllers\Commerce;

use App\Models\User;
use App\Models\Person;
use App\Models\Commerce;
use App\Models\IneResult;
use Illuminate\Http\Request;
use App\Helpers\JsonResponse;
use App\Helpers\FaceApiRequest;
use App\Helpers\AnalyzeDocument;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressInformationResource;
use App\Jobs\AnalyzeBackIneJob;
use App\Models\Address;
use App\Models\FaceapiPerson;
use App\Models\IneInformation;
use Illuminate\Support\Facades\Hash;

class CommerceController extends Controller
{
    public function create(Request $request)
    {
        $user = User::where(['email' => $request->email])->first();
        if (isset($user)) {
            return JsonResponse::sendError('Ya se encuentra registrado un comercio con el correo proporcionado');
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'default_pass' => $request->password,
        ]);
        $user->assignRole('user');
        $attributes = [
            'user_id' => $user->id,
            'name' => $request->name,
        ];
        $commerce = Commerce::where('user_id', $user->id)->first();
        if (isset($commerce)) {
            return JsonResponse::sendError('Ya se encuentra registrado un comercio con el correo proporcionado');
        }
        $commerce = new Commerce($attributes);
        if ($commerce->save()) {
            return JsonResponse::sendResponse([
                'commerce_id' => $commerce->id,
                'name' => $commerce->name,
                'email' => $commerce->user->email,
            ], 'Comercio registrado correctamente');
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
        $personGroup = $commerce->faceapiPersonGroup;
        $urlIne = $request->photo_url;
        $backIne = $request->back_photo_url;
        if (!isset($commerce)) {
            return JsonResponse::sendError('El ID proporcionado es incorrecto');
        }
        $results = AnalyzeDocument::analyzeDocument($urlIne);
        $dataExtracted = $results[0];
        $person = Person::where('clave_elector', $dataExtracted['clave_elector'])->first();
        if (!isset($person)) {
            $person = $this->registerPerson($dataExtracted, $urlIne);
            AnalyzeBackIneJob::dispatch($person, $backIne);
        }
        $faceapiPerson = FaceapiPerson::where(['person_id' => $person->id, 'faceapi_person_group_id' => $personGroup->id])->first();
        if (!isset($faceapiPerson)) {
            $detectFaceResults = $this->detectFacesOnIne($person, $urlIne);
            $faceapiPerson = $commerce->addToPersonGroup($person);
            $persistedFaceResponse = $faceapiPerson->addFace($detectFaceResults, $urlIne);
            if (isset($persistedFaceResponse->error)) {
                $error = $persistedFaceResponse->error;
                return JsonResponse::sendError($error->code, 400, $error->message);
            }
            $commerce->train();
        }
        $data = $this->formatResponseData($faceapiPerson, $dataExtracted, $person);
        $data = $this->unsetAddressFromResponse($data);
        return JsonResponse::sendResponse($data);
    }

    private function registerPerson($dataExtracted, $ineUrl)
    {
        $searchParams = [
            'clave_elector' => $dataExtracted['clave_elector']
        ];
        $address = $this->extractAddressInformation($dataExtracted);
        $attributes = [
            'name' => $dataExtracted['nombre'],
            'father_lastname' => $dataExtracted['apellido_paterno'],
            'mother_lastname' => $dataExtracted['apellido_materno'],
            'curp' => $dataExtracted['curp'],
            'gender' => $dataExtracted['sexo'],
            'birthdate' => $dataExtracted['nacimiento'],
            'address' => $this->formatAddress($address),
            'ine_url' => $ineUrl,
        ];
        $person = Person::firstOrCreate($searchParams, $attributes);
        $address['person_id'] = $person->id;
        Address::create($address);
        return $person;
    }

    private function formatResponseData($faceapiPerson, $dataExtracted, $person)
    {
        $personInformation['person'] = $dataExtracted;
        $personInformation['person'] = $dataExtracted;
        $personInformation['person']['domicilio'] = $person->address;
        $personInformation['faceapi_person_id'] = $faceapiPerson->faceapi_person_id;
        $personInformation['informacion_domicilio'] = new AddressInformationResource($person->addressInformation);
        return $personInformation;
    }

    private function detectFacesOnIne($person, $urlImage)
    {
        $personId = $person->id;
        $faceapiRequest = new FaceApiRequest();
        $results = $faceapiRequest->detect($urlImage);
        $results = $this->persistFaceIneResults($personId, $urlImage, $results);
        return $results;
    }

    protected function persistFaceIneResults($personId, $urlImage, $results)
    {
        $faceRectangle = $results->faceRectangle;
        $ineResult = IneResult::create([
            'person_id' => $personId,
            'url_image' => $urlImage,
            'faceId' => $results->faceId,
            'top' => $faceRectangle->top,
            'left' => $faceRectangle->left,
            'width' => $faceRectangle->width,
            'height' => $faceRectangle->height,
        ]);
        return $ineResult;
    }

    protected function extractAddressInformation($dataExtracted)
    {
        $address['first_address'] = isset($dataExtracted['primer_direccion']) ? $dataExtracted['primer_direccion'] : "";
        $address['second_address'] = isset($dataExtracted['segunda_direccion']) ? $dataExtracted['segunda_direccion'] : "";
        $address['exterior_number'] = isset($dataExtracted['numero_exterior']) ? $dataExtracted['numero_exterior'] : "";
        $address['state'] = isset($dataExtracted['nombre_estado']) ? $dataExtracted['nombre_estado'] : "";
        $address['city'] = isset($dataExtracted['nombre_municipio']) ? $dataExtracted['nombre_municipio'] : "";
        $address['zip_code'] = isset($dataExtracted['codigo_postal']) ? $dataExtracted['codigo_postal'] : "";
        return $address;
    }

    protected function formatAddress($addressInformation)
    {
        $address = "{$addressInformation['first_address']} {$addressInformation['exterior_number']} {$addressInformation['second_address']} {$addressInformation['zip_code']} {$addressInformation['city']} {$addressInformation['state']}";
        return trim($address);
    }

    protected function unsetAddressFromResponse($data) {
        unset($data['person']['primer_direccion']);
        unset($data['person']['segunda_direccion']);
        unset($data['person']['numero_exterior']);
        unset($data['person']['nombre_estado']);
        unset($data['person']['nombre_municipio']);
        unset($data['person']['codigo_postal']);
        return $data;
    }
}
