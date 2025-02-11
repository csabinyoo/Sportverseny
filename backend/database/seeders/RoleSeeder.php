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
                'role' => 'test',
                'permission' => 3
            ],
        ];

        if (Role::count() === 0) {
            Role::factory()->createMany($data);
        }
    }
}
