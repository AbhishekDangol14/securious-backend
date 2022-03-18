<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'email' => 'admin@securious.de',
            'password' => bcrypt('password')
        ]);

        $role = Role::where('role', 'Admin')->first();

        DB::table('user_roles')->insert([
            'role_id' => $role->id,
            'user_id' => $user->id
        ]);

    }
}
