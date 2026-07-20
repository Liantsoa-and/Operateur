<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigOperateurModel extends Model
{
    protected $table      = 'config_operateur';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['commission_inter', 'date_modification'];

    protected $validationRules = [
        'commission_inter' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
    ];

    /**
     * Retourne le taux de commission inter-opérateur courant (le plus récent).
     * 0.0 par défaut si la table est vide.
     */
    public function getCommissionActuelle(): float
    {
        $row = $this->orderBy('date_modification', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->first();

        return $row ? (float) $row['commission_inter'] : 0.0;
    }

    /**
     * Historise un nouveau taux de commission (insert, pas update).
     */
    public function setCommission(float $taux): bool
    {
        return (bool) $this->insert([
            'commission_inter'  => $taux,
            'date_modification' => date('Y-m-d H:i:s'),
        ]);
    }
}