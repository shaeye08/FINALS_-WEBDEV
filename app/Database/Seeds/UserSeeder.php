<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'     => 'System Admin',
                'email'    => 'admin@assetflow.com',
                'password' => password_hash('Admin123!', PASSWORD_DEFAULT),
                'role_id'  => 1, // SuperAdmin
            ],
            [
                'name'     => 'Department Manager',
                'email'    => 'manager@assetflow.com',
                'password' => password_hash('Manager123!', PASSWORD_DEFAULT),
                'role_id'  => 2, // Manager
            ],
            [
                'name'     => 'Operations Staff',
                'email'    => 'staff@assetflow.com',
                'password' => password_hash('Staff123!', PASSWORD_DEFAULT),
                'role_id'  => 3, // Staff
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}