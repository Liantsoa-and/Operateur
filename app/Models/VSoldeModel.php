<?php

namespace App\Models;

use CodeIgniter\Model;

// Modèle en lecture seule sur la vue v_solde
// La vue calcule le solde dynamiquement à partir de toutes les transactions
class VSoldeModel extends Model
{
    protected $table          = 'v_solde';
    protected $primaryKey     = 'id_client';
    protected $returnType     = 'array';
    protected $allowedFields  = [];
    protected $useAutoIncrement = false;

    // Solde d'un client
    public function getSoldeClient(int $idClient): float
    {
        $row = $this->where('id_client', $idClient)->first();
        return (float) ($row['solde'] ?? 0);
    }

    // Tous les soldes (pour la situation des comptes côté opérateur)
    public function getAllSoldes(): array
    {
        return $this->orderBy('numero_client', 'ASC')->findAll();
    }
}
