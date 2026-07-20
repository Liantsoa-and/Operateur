<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BaremeSeeder extends Seeder
{
    public function run()
    {
        $depot     = $this->db->table('type_operation')->where('type', 'depot')->get()->getRow('id');
        $retrait   = $this->db->table('type_operation')->where('type', 'retrait')->get()->getRow('id');
        $transfert = $this->db->table('type_operation')->where('type', 'transfert')->get()->getRow('id');

        $data = [
            // Dépôt : aucun frais, quelle que soit la tranche
            [
                'description'       => 'Dépôt sans frais',
                'min'               => 0,
                'max'               => 99999999,
                'frais'             => 0,
                'id_type_operation' => $depot,
            ],

            // Retrait : tranches sans chevauchement
            [
                'description'       => 'Retrait petite tranche',
                'min'               => 0,
                'max'               => 50000,
                'frais'             => 500,
                'id_type_operation' => $retrait,
            ],
            [
                'description'       => 'Retrait tranche moyenne',
                'min'               => 50000.01,
                'max'               => 200000,
                'frais'             => 1500,
                'id_type_operation' => $retrait,
            ],
            [
                'description'       => 'Retrait grande tranche',
                'min'               => 200000.01,
                'max'               => 99999999,
                'frais'             => 3000,
                'id_type_operation' => $retrait,
            ],

            // Transfert : tranches sans chevauchement
            [
                'description'       => 'Transfert petite tranche',
                'min'               => 0,
                'max'               => 50000,
                'frais'             => 300,
                'id_type_operation' => $transfert,
            ],
            [
                'description'       => 'Transfert tranche moyenne',
                'min'               => 50000.01,
                'max'               => 200000,
                'frais'             => 1000,
                'id_type_operation' => $transfert,
            ],
            [
                'description'       => 'Transfert grande tranche',
                'min'               => 200000.01,
                'max'               => 99999999,
                'frais'             => 2000,
                'id_type_operation' => $transfert,
            ],
        ];

        $this->db->table('bareme')->insertBatch($data);
    }
}