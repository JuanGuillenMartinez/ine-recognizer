<?php

namespace App\Jobs;

use App\Models\FaceApi\PersonGroup;
use App\Models\FaceApi\PersonGroupPerson;
use App\Models\FaceapiFace;
use App\Models\FaceapiPerson;
use App\Models\FaceapiVerifyResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TrainPersonWithPhotoSended implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $azurePerson;
    public $verifyResults;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FaceapiPerson $azurePerson, FaceapiVerifyResult $verifyResponse)
    {
        $this->azurePerson = $azurePerson;
        $this->verifyResults = $verifyResponse;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $personGroupId = $this->azurePerson->personGroupId();
        $faceapiPerson = new PersonGroupPerson($personGroupId);
        $azurePersonGroup = new PersonGroup($personGroupId);
        $response = $faceapiPerson->addFace($this->azurePerson->faceapi_person_id, $this->verifyResults->url_image);
        $personFace = FaceapiFace::create([
            'faceapi_person_id' => $this->azurePerson->id,
            'url_image' => $this->verifyResults->url_image,
            'persisted_face_id' => $response->persistedFaceId,
        ]);
        $azurePersonGroup->train();
    }
}
