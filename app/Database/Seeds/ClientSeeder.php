<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Numéros respectant les préfixes valides insérés (034, 038, 032, 037, 033)
        // Un seul numéro par client, unicité garantie
        $data = [
            ['numero' => '0341234567'], // Telma
            ['numero' => '0381234567'], // Telma
            ['numero' => '0321234567'], // Orange
            ['numero' => '0371234567'], // Orange
            ['numero' => '0331234567'], // Airtel
            ['numero' => '0340001122'], // Telma
            ['numero' => '0320001122'], // Orange
            ['numero' => '0330001122'], // Airtel
        ];

        $this->db->table('client')->insertBatch($data);
    }
}