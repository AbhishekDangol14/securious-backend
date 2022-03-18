<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::insert([
            [
                'language_name'=>'English',
                'language_identifier'=>'EN'
            ],
            [
                'language_name'=>'Deutsch',
                'language_identifier'=>'DE'
            ]
        ]);
    }
}
