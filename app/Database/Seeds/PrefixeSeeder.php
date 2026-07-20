<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixeSeeder extends Seeder
{
    public function run()
    {
        // Récupère les id des opérateurs déjà insérés
        $telma  = $this->db->table('operateur')->where('nom', 'Telma')->get()->getRow('id');
        $orange = $this->db->table('operateur')->where('nom', 'Orange')->get()->getRow('id');
        $airtel = $this->db->table('operateur')->where('nom', 'Airtel')->get()->getRow('id');

        $data = [
            ['debut_numero' => '034', 'id_operateur' => $telma],
            ['debut_numero' => '038', 'id_operateur' => $telma],
            ['debut_numero' => '032', 'id_operateur' => $orange],
            ['debut_numero' => '037', 'id_operateur' => $orange],
            ['debut_numero' => '033', 'id_operateur' => $airtel],
        ];

        $this->db->table('prefixe')->insertBatch($data);
    }
}