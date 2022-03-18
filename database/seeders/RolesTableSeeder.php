<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now('utc')->toDateTimeString();
        Role::insert([
            [
                'role' => 'Admin',
                'description' => 'Admin has access to every information.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'role'=>'Customer',
                'description'=>'This is a normal customer that uses the dashboard. He only has access to his own data.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'role'=>'Partner',
                'description'=>'The partner are companies, that provide services, that implement the recommendation that the platform gives.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'role'=>'IT-Security Consultant',
                'description'=>'The IT-Security consultant has access to his own data and to the data of the customers he is assigned to. The assigned will be done by the admin or by a customer request.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'role'=>'Employee',
                'description'=>'This is a normal employee that uses the dashboard. He only has access to his own data.',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
