<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeModel extends Model
{
    protected $table      = 'bareme';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'description',
        'min',
        'max',
        'frais',
        'id_type_operation',
    ];

    protected $validationRules = [
        'min'               => 'required|decimal|greater_than[0]',
        'max'               => 'required|decimal',
        'frais'             => 'required|decimal|greater_than_equal_to[0]',
        'id_type_operation' => 'required|is_natural_no_zero',
    ];

    // Retourne le barème applicable pour un montant et un type d'opération
    public function getTranche(int $idTypeOperation, float $montant): array|null
    {
        return $this->where('id_type_operation', $idTypeOperation)
                    ->where('min <=', $montant)
                    ->where('max >=', $montant)
                    ->first();
    }

    // Calcule les frais pour un montant et un type d'opération
    public function calculerFrais(int $idTypeOperation, float $montant): float|null
    {
        $tranche = $this->getTranche($idTypeOperation, $montant);
        if (!$tranche) return null;

        return (float) $tranche['frais'];
    }

    // Vérifie qu'une nouvelle tranche ne chevauche pas les tranches existantes
    public function estSansChevauchemnt(int $idTypeOperation, float $min, float $max, int|null $excludeId = null): bool
    {
        $builder = $this->where('id_type_operation', $idTypeOperation)
                        ->where('min <', $max)
                        ->where('max >', $min);

        if ($excludeId !== null) {
            $builder = $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() === 0;
    }

    // Tous les barèmes d'un type d'opération, triés par min
    public function getByTypeOperation(int $idTypeOperation): array
    {
        return $this->where('id_type_operation', $idTypeOperation)
                    ->orderBy('min', 'ASC')
                    ->findAll();
    }

    // Avoir les tranches de dépôt pour un montant donné
    public function getTrancheDepot(float $montant): array|null
    {
        $idTypeDepot = (new TypeOperationModel())->getIdByType(TypeOperationModel::DEPOT);
        return $this->getTranche($idTypeDepot, $montant);
    }
}
