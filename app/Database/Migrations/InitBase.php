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

        // TRANSACTIONS (inclut directement commission_appliquee, fusionné depuis V2Base)
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
            'commission_appliquee' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'default'    => null,
                'comment'    => 'Null si intra-opérateur, valeur réelle si inter-opérateur',
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

        // CONFIG_OPERATEUR : taux de commission inter-opérateur configurable (fusionné depuis V2Base)
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

        // Insérer un taux de commission par défaut
        $this->db->query("INSERT INTO config_operateur (commission_inter, date_modification) VALUES (5.00, datetime('now'))");

        // VUE v_solde (mise à jour : prend en compte commission_appliquee)
        $this->db->query("
            CREATE VIEW v_solde AS
            SELECT
                c.id     AS id_client,
                c.numero AS numero_client,
                COALESCE(SUM(
                    CASE
                        WHEN to_.type = 'depot'     THEN t.montant
                        WHEN to_.type IN ('retrait', 'transfert') AND c.id = t.id_client      THEN -(t.montant + t.frais + COALESCE(t.commission_appliquee, 0))
                        WHEN to_.type = 'transfert' AND c.id = t.id_destinataire              THEN t.montant
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

        $this->forge->dropTable('config_operateur');
        $this->forge->dropTable('transactions');
        $this->forge->dropTable('bareme');
        $this->forge->dropTable('type_operation');
        $this->forge->dropTable('client');
        $this->forge->dropTable('prefixe');
        $this->forge->dropTable('operateur');
    }
}
