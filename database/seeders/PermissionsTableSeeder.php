<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissionsData = [
            [
               'permission_name' =>'Threats',
               'description' =>'Only admin can create and update the threats'
            ],
            [
                'permission_name' =>'New partner',
                'description' =>'Only admin can create and update new partner'
            ],
            [
                'permission_name' =>'Impersonate',
                'description' =>'Admin can impersonate its users'
            ],
            [
                'permission_name' =>' Industries',
                'description' =>'Only admin can create and upate Industries'
            ],
            [
                'permission_name' =>'Create News',
                'description' =>'Only admin can create news'
            ],

        ];
        $now = Carbon::now('utc')->toDateTimeString();
        $date = [
            'created_at' => $now,
            'updated_at' => $now,
        ];

      $permissionsData = array_map(static fn($permission)=>array_merge($permission, $date), $permissionsData);

      Permission::insert($permissionsData);

    }
}
