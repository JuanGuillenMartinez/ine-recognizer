<?php

namespace Database\Seeders;

use App\Models\Request as UserRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LimitedRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRequest::create([
            'name' => 'register-person',
            'url' => env('APP_URL') . '/api/commerces/{commerceId}/persons',
            'limit_default' => 10,
        ]);
        UserRequest::create([
            'name' => 'verify_identity',
            'url' => env('APP_URL') . '/api/commerces/{commerceId}/persons/{personId}/verify',
            'limit_default' => 5,
        ]);
    }
}
