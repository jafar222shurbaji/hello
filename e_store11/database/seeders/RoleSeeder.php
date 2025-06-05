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
        $Roles = [
            'data entry',
            'order checker',
            'support team'
        ];
        foreach ($Roles as $role) {
            Role::create(['role_name' => $role]);
        }

    }
}
