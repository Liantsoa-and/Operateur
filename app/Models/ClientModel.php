<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table      = 'client';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['numero'];

    protected $validationRules = [
        'numero' => 'required|max_length[12]|is_unique[client.numero]',
    ];

    // Login automatique : connecte ou crée le compte si le numéro est valide
    // Retourne ['success' => bool, 'client' => array|null, 'created' => bool, 'error' => string]
    public function loginOuCreer(string $numero): array
    {
        $prefixeModel = new PrefixeModel();

        if (!$prefixeModel->estNumerovalide($numero)) {
            return [
                'success' => false,
                'client'  => null,
                'created' => false,
                'error'   => "Numéro invalide : le préfixe n'est pas reconnu.",
            ];
        }

        $client  = $this->where('numero', $numero)->first();
        $created = false;

        if (!$client) {
            // Création automatique du compte (solde initial = 0 via la vue)
            $this->insert(['numero' => $numero]);
            $client  = $this->where('numero', $numero)->first();
            $created = true;
        }

        return [
            'success' => true,
            'client'  => $client,
            'created' => $created,
            'error'   => null,
        ];
    }

    // Retourne le solde du client via la vue v_solde
    public function getSolde(int $idClient): float
    {
        $row = $this->db->query(
            "SELECT solde FROM v_solde WHERE id_client = ?",
            [$idClient]
        )->getRowArray();

        return (float) ($row['solde'] ?? 0);
    }

    // Vérifie que le client a un solde suffisant
    public function aSoldeSuffisant(int $idClient, float $montantTotal): bool
    {
        return $this->getSolde($idClient) >= $montantTotal;
    }

    // Situation de tous les comptes (pour l'opérateur)
    public function getSituationComptes(): array
    {
        return $this->db->query("
            SELECT
                c.id,
                c.numero,
                vs.solde,
                p.debut_numero AS prefixe,
                o.nom AS operateur
            FROM client c
            JOIN v_solde vs ON vs.id_client = c.id
            LEFT JOIN prefixe p ON p.debut_numero = SUBSTR(c.numero, 1, 3)
            LEFT JOIN operateur o ON o.id = p.id_operateur
            ORDER BY c.numero
        ")->getResultArray();
    }

    // Trouve un client par son numéro
    public function findByNumero(string $numero): array|null
    {
        return $this->where('numero', $numero)->first();
    }
}
