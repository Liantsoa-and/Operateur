<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les IDs des barèmes
        $baremeRetrait   = $this->db->table('bareme')
            ->where('description', 'Retrait petite tranche')->get()->getRow('id');
        $baremeTransfert = $this->db->table('bareme')
            ->where('description', 'Transfert petite tranche')->get()->getRow('id');
        $baremeDepot     = $this->db->table('bareme')
            ->where('description', 'Dépôt sans frais')->get()->getRow('id');

        // Récupérer quelques clients
        $client1 = $this->db->table('client')->where('numero', '0341234567')->get()->getRow('id');
        $client2 = $this->db->table('client')->where('numero', '0321234567')->get()->getRow('id');
        $client3 = $this->db->table('client')->where('numero', '0331234567')->get()->getRow('id');

        $data = [
            // Dépôts
            [
                'numero_transaction' => 'TXN-20260720-AA0001',
                'montant'            => 100000,
                'frais'              => 0,
                'date_transaction'   => '2026-07-18 08:00:00',
                'id_client'          => $client1,
                'id_destinataire'    => null,
                'id_bareme'          => $baremeDepot,
            ],
            [
                'numero_transaction' => 'TXN-20260720-AA0002',
                'montant'            => 50000,
                'frais'              => 0,
                'date_transaction'   => '2026-07-18 09:00:00',
                'id_client'          => $client2,
                'id_destinataire'    => null,
                'id_bareme'          => $baremeDepot,
            ],
            // Retraits (frais = 500)
            [
                'numero_transaction' => 'TXN-20260720-AA0003',
                'montant'            => 20000,
                'frais'              => 500,
                'date_transaction'   => '2026-07-19 10:00:00',
                'id_client'          => $client1,
                'id_destinataire'    => null,
                'id_bareme'          => $baremeRetrait,
            ],
            [
                'numero_transaction' => 'TXN-20260720-AA0004',
                'montant'            => 30000,
                'frais'              => 500,
                'date_transaction'   => '2026-07-19 11:00:00',
                'id_client'          => $client2,
                'id_destinataire'    => null,
                'id_bareme'          => $baremeRetrait,
            ],
            // Transferts (frais = 300)
            [
                'numero_transaction' => 'TXN-20260720-AA0005',
                'montant'            => 10000,
                'frais'              => 300,
                'date_transaction'   => '2026-07-20 08:30:00',
                'id_client'          => $client1,
                'id_destinataire'    => $client2,
                'id_bareme'          => $baremeTransfert,
            ],
            [
                'numero_transaction' => 'TXN-20260720-AA0006',
                'montant'            => 5000,
                'frais'              => 300,
                'date_transaction'   => '2026-07-20 09:00:00',
                'id_client'          => $client3,
                'id_destinataire'    => $client1,
                'id_bareme'          => $baremeTransfert,
            ],
        ];

        $this->db->table('transactions')->insertBatch($data);
    }
}