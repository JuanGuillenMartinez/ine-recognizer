<?php

namespace App\Jobs;

use App\Models\FaceapiPerson;
use App\Models\FaceapiVerifyResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        echo '<pre>';
        var_dump($this->azurePerson);
        var_dump($this->verifyResults);
        echo '</pre>';
        die;
    }
}
