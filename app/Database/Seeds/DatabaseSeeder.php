<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('OperateurSeeder');
        $this->call('PrefixeSeeder');
        $this->call('ClientSeeder');
        $this->call('TypeOperationSeeder');
        $this->call('BaremeSeeder');
    }
}