<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Orchid\Support\Facades\Dashboard;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = DB::table('users')->insertGetId([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'created_at' => now(),
            'updated_at' => now(),
            'permissions' => Dashboard::getAllowAllPermission()
        ]);

        DB::table('role_users')->insert([
            'user_id' => $user_id,
            'role_id' => DB::table('roles')->where('slug', 'admin')->first('id')->id
        ]);
    }
}
