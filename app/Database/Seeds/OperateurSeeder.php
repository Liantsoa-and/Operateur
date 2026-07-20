<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nom' => 'Telma'],
            ['nom' => 'Orange'],
            ['nom' => 'Airtel'],
        ];

        $this->db->table('operateur')->insertBatch($data);
    }
}