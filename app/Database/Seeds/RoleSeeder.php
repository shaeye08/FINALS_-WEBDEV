<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'role_name' => 'SuperAdmin'],
            ['id' => 2, 'role_name' => 'Manager'],
            ['id' => 3, 'role_name' => 'Staff'],
        ];

        $this->db->table('roles')->insertBatch($data);
    }
}