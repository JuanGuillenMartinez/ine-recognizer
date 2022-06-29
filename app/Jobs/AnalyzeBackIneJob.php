<?php

namespace App\Jobs;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use App\Models\IneInformation;
use App\Helpers\AnalyzeDocument;
use App\Models\IneDetail;
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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Person $person, $urlIne, $dataExtracted)
    {
        $this->person = $person;
        $this->urlIne = $urlIne;
        $this->dataExtracted = $dataExtracted;
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
}
