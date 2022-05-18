<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = $this->createUser();
        $this->assignRole($user);
    }

    private function createUser() {
        return User::create([
            'name' => env('ADMIN_USER'),
            'email' => env('ADMIN_EMAIL'),
            'password' => Hash::make(env('ADMIN_PASS'))
        ]);
    }

    private function assignRole($user) {
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $user->assignRole($superAdminRole);
        return $user;
    }
}
