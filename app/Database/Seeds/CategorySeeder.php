<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id' => 1, 'category_name' => 'Laptop'],
            ['id' => 2, 'category_name' => 'Monitor'],
            ['id' => 3, 'category_name' => 'Router'],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}