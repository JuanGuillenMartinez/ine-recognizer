<?php

namespace App\Jobs;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use App\Models\IneInformation;
use App\Helpers\AnalyzeDocument;
use App\Models\BackIneDetail;
use App\Models\IneDetail;
use App\Models\IneModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class AnalyzeBackIneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $person;
    public $urlIne;
    public $dataExtracted;
    public $faceApiPerson;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Person $person, $urlIne, $dataExtracted, $faceapiPerson)
    {
        $this->person = $person;
        $this->urlIne = $urlIne;
        $this->dataExtracted = $dataExtracted;
        $this->faceApiPerson = $faceapiPerson;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $backResults = AnalyzeDocument::analyzeDocument($this->urlIne);
        $this->persistIneInformation($this->person->id, $backResults[0]);
        $ineModel = $this->determineIneModel($this->dataExtracted);
        $backIneResults = $this->formatBackIneInformation($ineModel, $backResults[0]);
        $this->persistBackIneInformation($this->faceApiPerson, $backIneResults);
    }

    private function persistIneInformation($personId, $ineInformation)
    {
        Log::info($ineInformation);
        $ineInformation = IneDetail::create([
            'person_id' => $personId,
            'date_identifier' => $ineInformation['identificador_fecha'],
            'owner_identifier' => $ineInformation['identificador_titular'],
            'credential_identifier' => $ineInformation['identificador_documento'],
        ]);
        return $ineInformation;
    }

    private function determineIneModel($ineInformation)
    {
        $keyWord = str_replace(' ', '', 'INSTITUTO FEDERAL ELECTORAL');
        $issuedBy = str_replace(' ', '', $ineInformation['emitido_por']);
        if (strcmp($keyWord, $issuedBy) === 0) {
            return 'D';
        }
        if (!isset($ineInformation['emision'])) {
            return 'G';
        }
        return 'E';
    }

    private function formatBackIneInformation($ineModel, $backIneInformation)
    {
        $ineModel = new IneModel($ineModel, $backIneInformation);
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
            ]
        );
        return isset($backIneDetail);
    }
}
