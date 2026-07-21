<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigEpargneModel extends Model
{
    protected $table      = 'config_epargne';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['taux_epargne',
     'date_modification',
     'id_client'];

    protected $validationRules = [
        'taux_epargne' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
    ];

    /**
     * Retourne le taux de commission inter-opérateur courant (le plus récent).
     * 0.0 par défaut si la table est vide.
     */
    public function getPourcentageActuelle(int $idClient): float
    {
        $row = $this->where('id_client',$idClient)
                    ->orderBy('date_modification', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->first();

        return $row ? (float) $row['taux_epargne'] : 0.0;
    }

    /**
     * Historise un nouveau taux de commission (insert, pas update).
     */
    public function setPourcentage(float $taux, int $idClient): bool
    {
        return (bool) $this->insert([
            'taux_epargne'  => $taux,
            'date_modification' => date('Y-m-d H:i:s'),
            'id_client' => $idClient,
        ]);
    }
}