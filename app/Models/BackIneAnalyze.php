<?php

namespace App\Models;

use App\Exceptions\WrongOCRLecture;
use App\Models\Person;
use App\Helpers\AnalyzeDocument;
use App\Helpers\IneValidator;
use App\Models\BackIneDetail;
use App\Models\IneDetail;
use App\Models\IneModel;
use Illuminate\Support\Facades\Log;

class BackIneAnalyze
{

    public $person;
    public $urlIne;
    public $dataExtracted;
    public $faceApiPerson;
    public $fieldsRequired = ['identificador_documento', 'identificador_ciudadano', 'identificador_fecha', 'identificador_titular'];

    public function __construct(Person $person, $urlIne, $dataExtracted, $faceapiPerson)
    {
        $this->person = $person;
        $this->urlIne = $urlIne;
        $this->dataExtracted = $dataExtracted;
        $this->faceApiPerson = $faceapiPerson;
    }

    public function run()
    {
        $backResults = AnalyzeDocument::analyzeDocument($this->urlIne);
        $results = $backResults[0];
        $ineModel = $this->determineIneModel($this->dataExtracted);
        if(strcmp($ineModel, 'C') === 0) $this->fieldsRequired = ['ocr'];
        $validateBackResults = new IneValidator($results);
        $validateBackResults->allFieldsRequiredExist($results, $this->fieldsRequired);
        $this->persistIneInformation($this->person->id, $results);
        $backIneResults = $this->formatBackIneInformation($ineModel, $results, $this->dataExtracted);
        $this->persistBackIneInformation($this->faceApiPerson, $backIneResults);
    }

    private function persistIneInformation($personId, $ineInformation)
    {
        Log::info($ineInformation);
        $ineInformation = IneDetail::create([
            'person_id' => $personId,
            'date_identifier' => isset($ineInformation['identificador_fecha']) ? $ineInformation['identificador_fecha'] : null,
            'owner_identifier' => isset($ineInformation['identificador_titular']) ? $ineInformation['identificador_titular'] : null,
            'credential_identifier' => isset($ineInformation['identificador_documento']) ? $ineInformation['identificador_documento'] : null,
            'vertical_number' => isset($ineInformation['ocr']) ? $ineInformation['ocr'] : null,
        ]);
        return $ineInformation;
    }

    private function determineIneModel($ineInformation)
    {
        $keyWord = str_replace(' ', '', 'INSTITUTO FEDERAL ELECTORAL');
        $issuedBy = str_replace(' ', '', $ineInformation['emitido_por']);
        if (isset($ineInformation['folio'])) {
            return 'C';
        }
        if (str_contains($issuedBy, $keyWord)) {
            return 'D';
        }
        if (!isset($ineInformation['emision'])) {
            return 'G';
        } else {
            return 'E';
        }
        throw new WrongOCRLecture('{"field":"identificador_ciudadano"}', 406);
    }

    private function formatBackIneInformation($ineModel, $backIneInformation, $frontIneInformation)
    {
        $ineModel = new IneModel($ineModel, $backIneInformation, $frontIneInformation);
        return $ineModel->getBackInformation();
    }

    private function persistBackIneInformation($faceApiPerson, $backIneResults)
    {
        $backIneDetail = BackIneDetail::firstOrCreate(
            [
                'faceapi_person_id' => $faceApiPerson->id,
            ],
            [
                'citizen_identifier' => isset($backIneResults['identificador_ciudadano']) ? $backIneResults['identificador_ciudadano'] : null,
                'cic' => isset($backIneResults['cic']) ? $backIneResults['cic'] : null,
                'ocr' => isset($backIneResults['ocr']) ? $backIneResults['ocr'] : null,
                'model' => isset($backIneResults['modelo']) ? $backIneResults['modelo'] : null,
                'emision' => isset($backIneResults['emision']) ? $backIneResults['emision'] : null,
            ]
        );
        return isset($backIneDetail);
    }
}
