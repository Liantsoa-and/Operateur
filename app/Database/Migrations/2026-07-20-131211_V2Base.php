<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class V2Base extends Migration
{
    public function up()
    {
        // TABLE config_operateur : taux de commission inter-opérateur configurable
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'commission_inter' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'comment'    => 'Pourcentage de commission pour transfert inter-opérateur',
            ],
            'date_modification' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('config_operateur');

        // COLONNE commission_appliquee dans transactions
        // Null si intra-opérateur, valeur réelle si inter-opérateur
        $this->forge->addColumn('transactions', [
            'commission_appliquee' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'default'    => null,
                'after'      => 'frais',
            ],
        ]);

        // Insérer un taux par défaut
        $this->db->query("INSERT INTO config_operateur (commission_inter, date_modification) VALUES (5.00, datetime('now'))");
    }

    public function down()
    {
        $this->forge->dropTable('config_operateur');
        $this->forge->dropColumn('transactions', 'commission_appliquee');
    }
}