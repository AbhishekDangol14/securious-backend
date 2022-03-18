<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(LegalRoleSeeder::class);
        $this->call(CompanyRoleSeeder::class);
        $this->call(OptionSeeder::class);
    }
}
