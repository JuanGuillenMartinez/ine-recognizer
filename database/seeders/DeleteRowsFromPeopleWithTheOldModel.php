<?php

namespace Database\Seeders;

use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeleteRowsFromPeopleWithTheOldModel extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $persons = Person::all();
        $dateNow = new Carbon('2022-07-01 12:40:00');
        foreach($persons as $person) {
            $dateCreated = new Carbon($person->created_at);
            if($dateCreated->lessThan($dateNow)) {
                $person->delete();
            }
        }
    }
}
