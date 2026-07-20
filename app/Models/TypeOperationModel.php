<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table      = 'type_operation';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['type'];

    protected $validationRules = [
        'type' => 'required|in_list[depot,retrait,transfert]|is_unique[type_operation.type]',
    ];

    // Types autorisés
    public const DEPOT     = 'depot';
    public const RETRAIT   = 'retrait';
    public const TRANSFERT = 'transfert';

    // Retourne l'id d'un type par son libellé
    public function getIdByType(string $type): int|null
    {
        $row = $this->where('type', $type)->first();
        return $row ? (int) $row['id'] : null;
    }

    // Retourne un type avec ses barèmes
    public function getWithBaremes(int $id): array|null
    {
        $typeOp = $this->find($id);
        if (!$typeOp) return null;

        $baremeModel          = new BaremeModel();
        $typeOp['baremes']    = $baremeModel->getByTypeOperation($id);
        return $typeOp;
    }

    // Tous les types avec leurs barèmes
    public function getAllWithBaremes(): array
    {
        $types = $this->findAll();
        $baremeModel = new BaremeModel();

        foreach ($types as &$type) {
            $type['baremes'] = $baremeModel->getByTypeOperation($type['id']);
        }

        return $types;
    }
}
