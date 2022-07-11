<?php

namespace App\Http\Controllers\Commerce;

use App\Models\User;
use App\Models\Person;
use App\Models\Address;
use App\Models\Commerce;
use App\Models\IneResult;
use Illuminate\Http\Request;
use App\Helpers\IneValidator;
use App\Helpers\JsonResponse;
use App\Models\FaceapiPerson;
use App\Models\BackIneAnalyze;
use App\Models\IneInformation;
use App\Helpers\FaceApiRequest;
use App\Jobs\AnalyzeBackIneJob;
use App\Helpers\AnalyzeDocument;
use App\Exceptions\WrongImageUrl;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\FaceApiPersonResource;
use App\Http\Resources\AddressInformationResource;

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
        $request->validate([
            'photo_url' => 'string|required',
            'back_photo_url' => 'string|required',
        ]);

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

        //* Valida que la información recibida mediante OCR sea correcta
        $dataExtracted = $this->validateInformationExtracted($dataExtracted);

        //* Búsqueda de la persona a la que pertenece la fotografia del INE recibida mediante su clave de elector
        $person = Person::where('clave_elector', $dataExtracted['clave_elector'])->first();
        if (!isset($person)) {
            //* Si no se encuentra registrada, créala
            $person = $this->registerPerson($dataExtracted, $urlIne);
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

        if (!isset($faceapiPerson->backIneResult)) {
            $backAnalyzeHandler = new BackIneAnalyze($person, $backIne, $dataExtracted, $faceapiPerson);
            $backAnalyzeHandler->run();
        }

        if (!isset($person->addressInformation)) {
            $addressInformation = $this->extractAddressInformation($dataExtracted);
            $person->setAddressInformation($addressInformation);
        }
        $faceapiPerson->refresh();
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
            'name' => isset($dataExtracted['nombre']) ? $dataExtracted['nombre'] : null,
            'father_lastname' => isset($dataExtracted['apellido_paterno']) ? $dataExtracted['apellido_paterno'] : null,
            'mother_lastname' => isset($dataExtracted['apellido_materno']) ? $dataExtracted['apellido_materno'] : null,
            'curp' => isset($dataExtracted['curp']) ? $dataExtracted['curp'] : null,
            'gender' => isset($dataExtracted['sexo']) ? $dataExtracted['sexo'] : null,
            'birthdate' => isset($dataExtracted['nacimiento']) ? $dataExtracted['nacimiento'] : "",
            'address' => $this->formatFullAddress($address),
            'ine_url' => $ineUrl,
        ];
        $person = Person::firstOrCreate($searchParams, $attributes);
        $person->setAddressInformation($address);
        return $person;
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
        $address = "{$addressInformation['first_address']} {$addressInformation['second_address']} {$addressInformation['city']} {$addressInformation['state']}";
        return trim($address);
    }

    private function extractLastIndexFromText($textLine)
    {
        $textArray = explode(' ', $textLine);
        return end($textArray);
    }

    private function extractStateInformation($stateInformation)
    {
        $stateInformation = str_replace('.', ',', $stateInformation);
        $stateInformation = trim($stateInformation);
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

    private function validateInformationExtracted($dataExtracted) {
        $validator = new IneValidator($dataExtracted);
        return $validator->validate();
    }
}
