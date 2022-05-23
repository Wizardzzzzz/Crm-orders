<?php

namespace Database\Seeders;

use App\Enum\Permissions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Orchid\Support\Facades\Dashboard;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'slug' => 'admin',
                'permissions' => Dashboard::getAllowAllPermission(),
            ],
            [
                'name' => 'manager',
                'slug' => 'manager',
                'permissions' => [
                    'platform.index' => 1,
                    'platform.systems.roles' => 0,
                    'platform.systems.users' => 0,
                    'platform.systems.attachment' => 0,
                ]
            ],
            [
                'name' => 'client',
                'slug' => 'client',
                'permissions' => [
                    'platform.index' => 0,
                    'platform.systems.roles' => 0,
                    'platform.systems.users' => 0,
                    'platform.systems.attachment' => 0,
                ]
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'slug' => $role['slug'],
                'permissions' => json_encode($role['permissions']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
