<?php

namespace App\Observers\Commerce;

use App\Models\Commerce;
use App\Models\FaceapiPersonGroup;
use App\Models\FaceApi\PersonGroup;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;
use App\Mail\User\SendCredentialsMail;
use function PHPUnit\Framework\isNull;

class CommerceObserver
{
    /**
     * Handle the Commerce "created" event.
     *
     * @param  \App\Models\Commerce  $commerce
     * @return void
     */
    public function created(Commerce $commerce)
    {
        $uniqid = uniqid($commerce->name);
        $personGroupId = strtolower(str_replace(" ", "", $uniqid));
        $personGroupHelper = new PersonGroup($personGroupId);
        $response = $personGroupHelper->save($commerce->name);
        if (isNull($response)) {
            $personGroup = $this->storePersonGroup($commerce, $personGroupId);
            Mail::to($commerce->user->email)->send(new SendCredentialsMail($commerce->user));
            Log::info($personGroup);
        }
    }

    /**
     * Handle the Commerce "updated" event.
     *
     * @param  \App\Models\Commerce  $commerce
     * @return void
     */
    public function updated(Commerce $commerce)
    {
        //
    }

    /**
     * Handle the Commerce "deleted" event.
     *
     * @param  \App\Models\Commerce  $commerce
     * @return void
     */
    public function deleted(Commerce $commerce)
    {
        //
    }

    /**
     * Handle the Commerce "restored" event.
     *
     * @param  \App\Models\Commerce  $commerce
     * @return void
     */
    public function restored(Commerce $commerce)
    {
        //
    }

    /**
     * Handle the Commerce "force deleted" event.
     *
     * @param  \App\Models\Commerce  $commerce
     * @return void
     */
    public function forceDeleted(Commerce $commerce)
    {
        //
    }

    protected function storePersonGroup($commerce, $personGroupId)
    {
        $personGroup = FaceapiPersonGroup::create([
            'commerce_id' => $commerce->id,
            'person_group_id' => $personGroupId,
            'name' => $commerce->name,
            'user_data' => $commerce->user_data,
        ]);
        return $personGroup;
    }
}
