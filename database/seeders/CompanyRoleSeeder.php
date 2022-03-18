<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyRole;

class CompanyRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyRole::insert([
            ['name' => 'CEO', 'slug' => 'ceo'],
            ['name' => 'CTO', 'slug' => 'cto'],
            ['name' => 'IT Admin', 'slug' => 'it-admin'],
            ['name' => 'CISO', 'slug' => 'ciso'],
            ['name' => 'Other', 'slug' => 'other']
        ]);
    }
}
