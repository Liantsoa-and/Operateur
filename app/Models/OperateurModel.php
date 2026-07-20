<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table      = 'operateur';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['nom'];

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[255]',
    ];

    // Retourne l'opérateur avec ses préfixes
    public function getWithPrefixes(int $id): array|null
    {
        $prefixeModel = new PrefixeModel();
        $operateur    = $this->find($id);
        if (!$operateur) return null;

        $operateur['prefixes'] = $prefixeModel->where('id_operateur', $id)->findAll();
        return $operateur;
    }

    // Situation des gains : somme des frais de retrait et transfert
    public function getSituationGains(): array
    {
        return $this->db->query("
            SELECT
                to_.type AS type_operation,
                COUNT(t.id) AS nb_transactions,
                SUM(t.frais) AS total_frais
            FROM transactions t
            JOIN bareme b ON t.id_bareme = b.id
            JOIN type_operation to_ ON b.id_type_operation = to_.id
            WHERE to_.type IN ('retrait', 'transfert')
            GROUP BY to_.type
        ")->getResultArray();
    }

    // Total global des gains
    public function getTotalGains(): float
    {
        $result = $this->db->query("
            SELECT COALESCE(SUM(t.frais), 0) AS total
            FROM transactions t
            JOIN bareme b ON t.id_bareme = b.id
            JOIN type_operation to_ ON b.id_type_operation = to_.id
            WHERE to_.type IN ('retrait', 'transfert')
        ")->getRowArray();

        return (float) ($result['total'] ?? 0);
    }
}
