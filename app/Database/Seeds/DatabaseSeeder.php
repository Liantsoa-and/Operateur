<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Tronquer dans l'ordre inverse des FK pour éviter les erreurs de contraintes
        $this->db->table('bareme')->truncate();
        $this->db->table('transactions')->truncate();
        $this->db->table('type_operation')->truncate();
        $this->db->table('client')->truncate();
        $this->db->table('prefixe')->truncate();
        $this->db->table('operateur')->truncate();

        $this->call('OperateurSeeder');
        $this->call('PrefixeSeeder');
        $this->call('ClientSeeder');
        $this->call('TypeOperationSeeder');
        $this->call('BaremeSeeder');
        $this->call('TransactionSeeder');
    }
}