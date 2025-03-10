<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'role' => 'Admin',
                'permission' => 3
            ],
            [
                'id' => 2,
                'role' => 'Supervisor',
                'permission' => 2
            ],
            [
                'id' => 3,
                'role' => 'Student',
                'permission' => 1
            ],
            [
                'id' => 4,
                'role' => 'Guest',
                'permission' => 0
            ],
        ];

        if (Role::count() === 0) {
            Role::factory()->createMany($data);
        }
    }
}
