<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LegalRole;

class LegalRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       LegalRole::insert([
        ['title' => 'Gesellschaft mit beschränkter Haftung (GmbH)'],
        ['title' => 'Einzelunternehmen'],
        ['title' => 'Gesellschaft bürgerlichen Rechts (GbR)'],
        ['title' => 'Offene Handelsgesellschaft (OHG)'],
        ['title' => 'Kommanditgesellschaft (KG)'],
        ['title' => 'Partnerschaft Gesellschaft (PartnG)'],
        ['title' => 'Stille Gesellschaft'],
        ['title' => 'GmbH &amp; Co. KG'],
        ['title' => 'Betriebsaufspaltung'],
        ['title' => 'Aktiengesellschaft (AG)'],
        ['title' => 'Kommanditgesellschaft auf Aktien (KGaA)'],
        ['title' => 'Ein-Personen-GmbH'],
        ['title' => 'Unternehmergesellschaft (UG haftungsbeschränkt)'],
        ['title' => 'Eingetragene Genossenschaft (eG)'],
        ['title' => 'Versicherungsverein auf Gegenseitigkeit (VVaG)'],
        ['title' => 'Stiftung'],
        ['title' => 'Öffentliche Einrichtung'],
        ['title' => 'sonstige']
       ]); 
    }
}
