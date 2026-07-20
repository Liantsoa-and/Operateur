<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitBase extends Migration
{
    public function up()
    {
        // OPERATEUR
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('operateur');

        // PREFIXE
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'debut_numero' => [
                'type'       => 'VARCHAR',
                'constraint' => 3,
            ],
            'id_operateur' => [
                'type' => 'INTEGER',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id');
        $this->forge->createTable('prefixe');

        // CLIENT
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'numero' => [
                'type'       => 'VARCHAR',
                'constraint' => 12,
                'unique'     => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('client');

        // TYPE OPERATION
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('type_operation');

        // BAREME
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'min' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'max' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'frais' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'id_type_operation' => [
                'type' => 'INTEGER',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_type_operation', 'type_operation', 'id');
        $this->forge->createTable('bareme');

        // TRANSACTIONS
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'numero_transaction' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            'montant' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'frais' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'date_transaction' => [
                'type' => 'DATETIME',
            ],
            'id_client' => [
                'type' => 'INTEGER',
            ],
            'id_destinataire' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'id_bareme' => [
                'type' => 'INTEGER',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_client', 'client', 'id');
        $this->forge->addForeignKey('id_destinataire', 'client', 'id');
        $this->forge->addForeignKey('id_bareme', 'bareme', 'id');
        $this->forge->createTable('transactions');

        // VUE
        $this->db->query("
            CREATE VIEW v_solde AS
            SELECT
                c.id AS id_client,
                c.numero AS numero_client,
                COALESCE(SUM(
                    CASE
                        WHEN to_.type = 'depot' THEN t.montant
                        WHEN to_.type = 'retrait' THEN -(t.montant + t.frais)
                        WHEN to_.type = 'transfert' AND c.id = t.id_client THEN -(t.montant + t.frais)
                        WHEN to_.type = 'transfert' AND c.id = t.id_destinataire THEN t.montant
                        ELSE 0
                    END
                ), 0) AS solde
            FROM client c
            LEFT JOIN transactions t
                ON c.id = t.id_client OR c.id = t.id_destinataire
            LEFT JOIN bareme b
                ON t.id_bareme = b.id
            LEFT JOIN type_operation to_
                ON b.id_type_operation = to_.id
            GROUP BY c.id, c.numero
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS v_solde");

        $this->forge->dropTable('transactions');
        $this->forge->dropTable('bareme');
        $this->forge->dropTable('type_operation');
        $this->forge->dropTable('client');
        $this->forge->dropTable('prefixe');
        $this->forge->dropTable('operateur');
    }
}