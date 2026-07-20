<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ConfigOperateurSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'commission_inter'   => 5.00,
            'date_modification'  => date('Y-m-d H:i:s'),
        ];

        $this->db->table('config_operateur')->insert($data);
    }
}
