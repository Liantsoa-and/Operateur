<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigPromotionModel extends Model
{
    protected $table      = 'config_promotion';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['pourcentage', 'date_modification'];

    protected $validationRules = [
        'pourcentage' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
    ];

    /**
     * Retourne le taux de commission inter-opérateur courant (le plus récent).
     * 0.0 par défaut si la table est vide.
     */
    public function getPourcentageActuelle(): float
    {
        $row = $this->orderBy('date_modification', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->first();

        return $row ? (float) $row['pourcentage'] : 0.0;
    }

    /**
     * Historise un nouveau taux de commission (insert, pas update).
     */
    public function setPourcentage(float $taux): bool
    {
        return (bool) $this->insert([
            'pourcentage'  => $taux,
            'date_modification' => date('Y-m-d H:i:s'),
        ]);
    }
}