<?php

namespace App\Http\Controllers\Commerce;

use App\Exceptions\WrongImageUrl;
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
use App\Http\Resources\FaceApiPersonResource;
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
        //* Verificación de la existencia del comercio
        if (!isset($commerce)) {
            return JsonResponse::sendError('El ID proporcionado es incorrecto');
        }
        //* Verificación de la existencia de grupos de personas
        if (!isset($commerce->faceapiPersonGroup)) {
            return JsonResponse::sendError('No se encuentra un comercio registrado con el ID proporcionado');
        }
        $personGroup = $commerce->faceapiPersonGroup;

        $urlIne = $request->photo_url;
        $backIne = $request->back_photo_url;
        //* Análisis de la fotografía del INE enviada por el usuario
        $results = AnalyzeDocument::analyzeDocument($urlIne);
        $dataExtracted = $results[0];

        //* Validación de existencia de la clave de elector
        if (!isset($dataExtracted['clave_elector'])) {
            throw new WrongImageUrl('No se ha podido reconocer la clave de elector del documento. Asegúrese que la calidad del documento sea buena.', 400);
        }

        //* Búsqueda de la persona a la que pertenece la fotografia del INE recibida mediante su clave de elector
        $person = Person::where('clave_elector', $dataExtracted['clave_elector'])->first();
        if (!isset($person)) {
            //* Si no se encuentra registrada, créala
            $person = $this->registerPerson($dataExtracted, $urlIne);
            AnalyzeBackIneJob::dispatch($person, $backIne);
        }

        //* Busqueda de la persona de acuerdo al comercio al que se encuentra registrado
        $faceapiPerson = FaceapiPerson::where(['person_id' => $person->id, 'faceapi_person_group_id' => $personGroup->id])->first();
        if (!isset($faceapiPerson)) {

            //* Si no se encuentra registrada en el comercio, detecta la ubicación el rostro contenido en el INE
            $detectFaceResults = $this->detectFacesOnIne($person, $urlIne);

            //* Añade la persona al grupo
            $faceapiPerson = $commerce->addToPersonGroup($person);
            $persistedFaceResponse = $faceapiPerson->addFace($detectFaceResults, $urlIne);
            if (isset($persistedFaceResponse->error)) {
                $error = $persistedFaceResponse->error;
                return JsonResponse::sendError($error->code, 400, $error->message);
            }
            //* Realiza el entrenamiento
            $commerce->train();
        }
        if (!isset($person->addressInformation)) {
            $addressInformation = $this->extractAddressInformation($dataExtracted);
            $person->setAddressInformation($addressInformation);
        }
        // $data = $this->formatResponseData($faceapiPerson, $dataExtracted, $person);
        // $data['person'] = $this->formatPersonName($data);
        // $data = $this->unsetAddressFromResponse($data);
        $personInformation = new FaceApiPersonResource($faceapiPerson);
        return JsonResponse::sendResponse($personInformation);
    }

    private function registerPerson($dataExtracted, $ineUrl)
    {
        $address = $this->extractAddressInformation($dataExtracted);
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
            'address' => $this->formatFullAddress($address),
            'ine_url' => $ineUrl,
        ];
        $person = Person::firstOrCreate($searchParams, $attributes);
        $person->setAddressInformation($address);
        return $person;
    }

    private function formatResponseData($faceapiPerson, $dataExtracted, $person)
    {
        $personInformation['person'] = $dataExtracted;
        $personInformation['person']['domicilio'] = $person->address;
        $personInformation['person']['id'] = $faceapiPerson->id;
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
        $firstAddress = "";
        if (isset($dataExtracted['domicilio_linea_uno'])) {
            $firstAddress = $dataExtracted['domicilio_linea_uno'];
            $address['exterior_number'] = $this->extractLastIndexFromText($firstAddress);
        }
        $address['first_address'] = $firstAddress;
        $secondAddress = "";
        if (isset($dataExtracted['domicilio_linea_dos'])) {
            $secondAddress = $dataExtracted['domicilio_linea_dos'];
            $address['zip_code'] = $this->extractLastIndexFromText($secondAddress);
        }
        $address['second_address'] = $secondAddress;
        $thirdAddress = "";
        if (isset($dataExtracted['domicilio_linea_tres'])) {
            $thirdAddress = $dataExtracted['domicilio_linea_tres'];
            $informationFormatted = $this->extractStateInformation($thirdAddress);
            $address['city'] = $informationFormatted[0];
            $address['state'] = $informationFormatted[1];
        }
        return $address;
    }

    protected function formatFullAddress($addressInformation)
    {
        $address = "{$addressInformation['first_address']} {$addressInformation['exterior_number']} {$addressInformation['second_address']} {$addressInformation['zip_code']} {$addressInformation['city']} {$addressInformation['state']}";
        return trim($address);
    }

    protected function unsetAddressFromResponse($data)
    {
        unset($data['person']['primer_direccion']);
        unset($data['person']['segunda_direccion']);
        unset($data['person']['numero_exterior']);
        unset($data['person']['nombre_estado']);
        unset($data['person']['nombre_municipio']);
        unset($data['person']['codigo_postal']);
        return $data;
    }

    protected function formatPersonName($data)
    {
        if (isset($data['person']['nombre'])) {
            $personInformation = $data['person'];
            $personName = trim($personInformation['nombre']);
            $nameExploded = explode(' ', $personName);
            $personInformation['primer_nombre'] = array_shift($nameExploded);
            if (count($nameExploded) > 0) {
                $personInformation['segundo_nombre'] = implode(' ', $nameExploded);
            }
            return $personInformation;
        }
        return $data['person'];
    }

    private function extractLastIndexFromText($textLine)
    {
        $textArray = explode(' ', $textLine);
        return end($textArray);
    }

    private function extractStateInformation($stateInformation)
    {
        $stateArray = explode(',', $stateInformation);
        foreach ($stateArray as $key => $word) {
            if (strcmp($word, '') === 0) {
                unset($stateArray[$key]);
            } else {
                $stateArray[$key] = trim($word);
            }
        }
        return $stateArray;
    }
}
