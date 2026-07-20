<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeOperationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['type' => 'depot'],
            ['type' => 'retrait'],
            ['type' => 'transfert'],
        ];

        $this->db->table('type_operation')->insertBatch($data);
    }
}