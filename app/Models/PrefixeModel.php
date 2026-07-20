<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table      = 'prefixe';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['debut_numero', 'id_operateur'];

    protected $validationRules = [
        'debut_numero' => 'required|exact_length[3]|is_natural',
        'id_operateur' => 'required|is_natural_no_zero',
    ];

    // Vérifie si un numéro commence par un préfixe valide
    // Retourne le préfixe correspondant ou null
    public function trouverPrefixe(string $numero): array|null
    {
        $prefixe = substr($numero, 0, 3);
        return $this->where('debut_numero', $prefixe)->first();
    }

    // Retourne true si le numéro possède un préfixe valide
    public function estNumerovalide(string $numero): bool
    {
        return $this->trouverPrefixe($numero) !== null;
    }

    // Tous les préfixes d'un opérateur
    public function getByOperateur(int $idOperateur): array
    {
        return $this->where('id_operateur', $idOperateur)->findAll();
    }
}
