<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table      = 'transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'numero_transaction',
        'montant',
        'frais',
        'date_transaction',
        'id_client',
        'id_destinataire',
        'id_bareme',
    ];

    // ----------------------------------------------------------------
    // DEPOT
    // Règles : montant > 0, pas de frais, validé automatiquement
    // ----------------------------------------------------------------
    public function faireDepot(int $idClient, float $montant): array
    {
        if ($montant <= 0) {
            return ['success' => false, 'error' => 'Le montant doit être strictement positif.'];
        }

        $typeOpModel = new TypeOperationModel();
        $baremeModel = new BaremeModel();

        $idTypeDepot = $typeOpModel->getIdByType(TypeOperationModel::DEPOT);
        $tranche     = $baremeModel->getTranche($idTypeDepot, $montant);

        // Pour le dépôt : frais = 0 même si une tranche existe
        $idBareme = $tranche ? (int) $tranche['id'] : $this->_getBaremeParDefaut($idTypeDepot);

        if (!$idBareme) {
            return ['success' => false, 'error' => 'Aucun barème configuré pour le dépôt.'];
        }

        $data = [
            'numero_transaction' => $this->_genererNumero(),
            'montant'            => $montant,
            'frais'              => 0,
            'date_transaction'   => date('Y-m-d H:i:s'),
            'id_client'          => $idClient,
            'id_destinataire'    => null,
            'id_bareme'          => $idBareme,
        ];

        $this->insert($data);

        return ['success' => true, 'transaction' => $data];
    }

    // ----------------------------------------------------------------
    // RETRAIT
    // Règles : montant > 0, solde suffisant (montant + frais), frais selon barème
    // ----------------------------------------------------------------
    public function faireRetrait(int $idClient, float $montant): array
    {
        if ($montant <= 0) {
            return ['success' => false, 'error' => 'Le montant doit être strictement positif.'];
        }

        $typeOpModel  = new TypeOperationModel();
        $baremeModel  = new BaremeModel();
        $clientModel  = new ClientModel();

        $idTypeRetrait = $typeOpModel->getIdByType(TypeOperationModel::RETRAIT);
        $tranche       = $baremeModel->getTranche($idTypeRetrait, $montant);

        if (!$tranche) {
            return ['success' => false, 'error' => 'Montant hors barème : aucune tranche de frais applicable.'];
        }

        $frais       = (float) $tranche['frais'];
        $totalDebit  = $montant + $frais;

        if (!$clientModel->aSoldeSuffisant($idClient, $totalDebit)) {
            $solde = $clientModel->getSolde($idClient);
            return [
                'success' => false,
                'error'   => "Solde insuffisant. Solde disponible : {$solde} Ar, montant total requis : {$totalDebit} Ar.",
            ];
        }

        $data = [
            'numero_transaction' => $this->_genererNumero(),
            'montant'            => $montant,
            'frais'              => $frais,
            'date_transaction'   => date('Y-m-d H:i:s'),
            'id_client'          => $idClient,
            'id_destinataire'    => null,
            'id_bareme'          => (int) $tranche['id'],
        ];

        $this->insert($data);

        return ['success' => true, 'transaction' => $data, 'frais' => $frais, 'total_debite' => $totalDebit];
    }

    // ----------------------------------------------------------------
    // TRANSFERT
    // Règles : destinataire valide, montant > 0, solde suffisant (montant + frais)
    //          destinataire reçoit uniquement le montant, frais conservés opérateur
    // ----------------------------------------------------------------
    public function faireTransfert(int $idClient, string $numeroDestinataire, float $montant): array
    {
        if ($montant <= 0) {
            return ['success' => false, 'error' => 'Le montant doit être strictement positif.'];
        }

        $clientModel  = new ClientModel();
        $prefixeModel = new PrefixeModel();
        $typeOpModel  = new TypeOperationModel();
        $baremeModel  = new BaremeModel();

        // Vérifier que le destinataire a un numéro valide (préfixe reconnu)
        if (!$prefixeModel->estNumerovalide($numeroDestinataire)) {
            return ['success' => false, 'error' => "Le numéro destinataire n'est pas valide."];
        }

        $destinataire = $clientModel->findByNumero($numeroDestinataire);
        if (!$destinataire) {
            return ['success' => false, 'error' => "Aucun compte trouvé pour le numéro {$numeroDestinataire}."];
        }

        if ($destinataire['id'] === $idClient) {
            return ['success' => false, 'error' => 'Vous ne pouvez pas vous transférer à vous-même.'];
        }

        $idTypeTransfert = $typeOpModel->getIdByType(TypeOperationModel::TRANSFERT);
        $tranche         = $baremeModel->getTranche($idTypeTransfert, $montant);

        if (!$tranche) {
            return ['success' => false, 'error' => 'Montant hors barème : aucune tranche de frais applicable.'];
        }

        $frais      = (float) $tranche['frais'];
        $totalDebit = $montant + $frais;

        if (!$clientModel->aSoldeSuffisant($idClient, $totalDebit)) {
            $solde = $clientModel->getSolde($idClient);
            return [
                'success' => false,
                'error'   => "Solde insuffisant. Solde disponible : {$solde} Ar, montant total requis : {$totalDebit} Ar.",
            ];
        }

        $data = [
            'numero_transaction' => $this->_genererNumero(),
            'montant'            => $montant,
            'frais'              => $frais,
            'date_transaction'   => date('Y-m-d H:i:s'),
            'id_client'          => $idClient,
            'id_destinataire'    => (int) $destinataire['id'],
            'id_bareme'          => (int) $tranche['id'],
        ];

        $this->insert($data);

        return [
            'success'      => true,
            'transaction'  => $data,
            'frais'        => $frais,
            'total_debite' => $totalDebit,
            'destinataire' => $numeroDestinataire,
        ];
    }

    // ----------------------------------------------------------------
    // HISTORIQUE d'un client
    // Règle : date, type, montant, frais, solde après opération + numéro correspondant pour transfert
    // ----------------------------------------------------------------
    public function getHistoriqueClient(int $idClient): array
    {
        return $this->db->query("
            SELECT
                t.id,
                t.numero_transaction,
                t.date_transaction,
                to_.type AS type_operation,
                t.montant,
                t.frais,
                CASE
                    WHEN to_.type = 'depot'     THEN t.montant
                    WHEN to_.type IN ('retrait', 'transfert') AND t.id_client = :id: THEN -(t.montant + t.frais)
                    WHEN to_.type = 'transfert' AND t.id_destinataire = :id: THEN t.montant
                    ELSE 0
                END AS impact_solde,
                CASE
                    WHEN to_.type = 'transfert' AND t.id_client = :id:          THEN dest.numero
                    WHEN to_.type = 'transfert' AND t.id_destinataire = :id:    THEN src.numero
                    ELSE NULL
                END AS numero_correspondant
            FROM transactions t
            JOIN bareme b ON t.id_bareme = b.id
            JOIN type_operation to_ ON b.id_type_operation = to_.id
            LEFT JOIN client dest ON dest.id = t.id_destinataire
            LEFT JOIN client src  ON src.id  = t.id_client
            WHERE t.id_client = :id:
               OR t.id_destinataire = :id:
            ORDER BY t.date_transaction DESC
        ", ['id' => $idClient])->getResultArray();
    }

    // ----------------------------------------------------------------
    // Helpers privés
    // ----------------------------------------------------------------

    // Génère un numéro de transaction unique (format : TXN-YYYYMMDD-microtime)
    private function _genererNumero(): string
    {
        return 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    // Fallback : premier barème du type (utilisé pour le dépôt si tranche non obligatoire)
    private function _getBaremeParDefaut(int $idTypeOperation): int|null
    {
        $baremeModel = new BaremeModel();
        $row = $baremeModel->where('id_type_operation', $idTypeOperation)->first();
        return $row ? (int) $row['id'] : null;
    }
}
