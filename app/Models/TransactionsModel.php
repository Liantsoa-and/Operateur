<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'numero_transaction',
        'montant',
        'frais',
        'commission_appliquee',
        'date_transaction',
        'id_client',
        'id_destinataire',
        'id_bareme',
    ];

    public function faireDepot(int $idClient, float $montant): array
    {
        if ($montant <= 0) {
            return ['success' => false, 'error' => 'Le montant doit être positif.'];
        }

        $typeOpModel = new TypeOperationModel();
        $baremeModel = new BaremeModel();

        $idTypeDepot = $typeOpModel->getIdByType(TypeOperationModel::DEPOT);
        $tranche = $baremeModel->getTranche($idTypeDepot, $montant);

        $frais = $tranche ? (float) $tranche['frais'] : 0.0;

        $data = [
            'numero_transaction' => $this->_genererNumero(),
            'montant' => $montant,
            'frais' => $frais,
            'date_transaction' => date('Y-m-d H:i:s'),
            'id_client' => $idClient,
            'id_destinataire' => null,
            'id_bareme' => $tranche ? (int) $tranche['id'] : null,
        ];

        $this->insert($data);

        return [
            'success' => true,
            'frais' => $frais,
            'message' => $frais > 0
                ? "Dépôt de {$montant} Ar effectué (frais : {$frais} Ar)."
                : "Dépôt de {$montant} Ar effectué avec succès."
        ];
    }

    public function faireRetrait(int $idClient, float $montant): array
    {
        if ($montant <= 0) {
            return ['success' => false, 'error' => 'Le montant doit être strictement positif.'];
        }

        $typeOpModel = new TypeOperationModel();
        $baremeModel = new BaremeModel();
        $clientModel = new ClientModel();

        $idTypeRetrait = $typeOpModel->getIdByType(TypeOperationModel::RETRAIT);
        $tranche = $baremeModel->getTranche($idTypeRetrait, $montant);

        if (!$tranche) {
            return ['success' => false, 'error' => 'Montant hors barème : aucune tranche de frais applicable.'];
        }

        $frais = (float) $tranche['frais'];
        $totalDebit = $montant + $frais;

        if (!$clientModel->aSoldeSuffisant($idClient, $totalDebit)) {
            $solde = $clientModel->getSolde($idClient);
            return [
                'success' => false,
                'error' => "Solde insuffisant. Solde disponible : {$solde} Ar, montant total requis : {$totalDebit} Ar.",
            ];
        }

        $data = [
            'numero_transaction' => $this->_genererNumero(),
            'montant' => $montant,
            'frais' => $frais,
            'date_transaction' => date('Y-m-d H:i:s'),
            'id_client' => $idClient,
            'id_destinataire' => null,
            'id_bareme' => (int) $tranche['id'],
        ];

        $this->insert($data);

        return ['success' => true, 'transaction' => $data, 'frais' => $frais, 'total_debite' => $totalDebit];
    }

    public function faireTransfert(int $idClient, string $numeroDestinataire, float $montant, bool $inclureFrais = false): array
    {
        if ($montant <= 0) {
            return ['success' => false, 'error' => 'Le montant doit être strictement positif.'];
        }

        $clientModel = new ClientModel();
        $prefixeModel = new PrefixeModel();
        $typeOpModel = new TypeOperationModel();
        $baremeModel = new BaremeModel();
        $configModel = new ConfigOperateurModel();

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

        $expediteur = $clientModel->find($idClient);
        if (!$expediteur) {
            return ['success' => false, 'error' => 'Client expéditeur introuvable.'];
        }

        $idTypeTransfert = $typeOpModel->getIdByType(TypeOperationModel::TRANSFERT);
        $tranche = $baremeModel->getTranche($idTypeTransfert, $montant);

        if (!$tranche) {
            return ['success' => false, 'error' => 'Montant hors barème : aucune tranche de frais applicable.'];
        }

        $frais = (float) $tranche['frais'];

        // --- Détection inter-opérateur + commission ---
        $interOperateur = $prefixeModel->estInterOperateur($expediteur['numero'], $numeroDestinataire);
        $commissionAppliquee = 0.0;

        if ($interOperateur) {
            $tauxCommission = $configModel->getCommissionActuelle();
            $commissionAppliquee = round($montant * $tauxCommission / 100, 2);
        }

        $fraisTotal = $frais + $commissionAppliquee;

        // --- Calcul selon inclure_frais ---
        if ($inclureFrais) {
            // Le montant saisi est débité tel quel ; les frais sont prélevés dessus
            $montantNet = $montant - $fraisTotal;

            if ($montantNet <= 0) {
                return ['success' => false, 'error' => "Le montant est insuffisant pour couvrir les frais ({$fraisTotal} Ar)."];
            }

            $totalDebit = $montant;
        } else {
            // Comportement actuel : le destinataire reçoit le montant plein, frais en plus
            $montantNet = $montant;
            $totalDebit = $montant + $fraisTotal;
        }

        if (!$clientModel->aSoldeSuffisant($idClient, $totalDebit)) {
            $solde = $clientModel->getSolde($idClient);
            return [
                'success' => false,
                'error' => "Solde insuffisant. Solde disponible : {$solde} Ar, montant total requis : {$totalDebit} Ar.",
            ];
        }

        $data = [
            'numero_transaction' => $this->_genererNumero(),
            'montant' => $montantNet, // ce que reçoit réellement le destinataire
            'frais' => $frais,
            'commission_appliquee' => $interOperateur ? $commissionAppliquee : null,
            'date_transaction' => date('Y-m-d H:i:s'),
            'id_client' => $idClient,
            'id_destinataire' => (int) $destinataire['id'],
            'id_bareme' => (int) $tranche['id'],
        ];

        $this->insert($data);

        return [
            'success' => true,
            'transaction' => $data,
            'frais' => $frais,
            'commission_appliquee' => $commissionAppliquee,
            'montant_net_recu' => $montantNet,
            'total_debite' => $totalDebit,
            'destinataire' => $numeroDestinataire,
        ];
    }

    public function getHistoriqueClient(int $idClient, array $filters = []): array
    {
        $sql = "
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
            WHERE (t.id_client = :id: OR t.id_destinataire = :id:)
        ";

        $params = ['id' => $idClient];

        // --- Filtre Date (min / max) ---
        if (!empty($filters['date_min'])) {
            $sql .= " AND t.date_transaction >= :date_min: ";
            $params['date_min'] = $filters['date_min'] . ' 00:00:00';
        }

        if (!empty($filters['date_max'])) {
            $sql .= " AND t.date_transaction <= :date_max: ";
            $params['date_max'] = $filters['date_max'] . ' 23:59:59';
        }

        // --- Filtre Numéro de transaction (texte, un seul champ) ---
        if (!empty($filters['numero_transaction'])) {
            $sql .= " AND t.numero_transaction LIKE :numero_transaction: ";
            $params['numero_transaction'] = '%' . $filters['numero_transaction'] . '%';
        }

        // --- Filtre Type d'opération (texte, un seul champ) ---
        if (!empty($filters['type_operation'])) {
            $sql .= " AND to_.type = :type_operation: ";
            $params['type_operation'] = $filters['type_operation'];
        }

        // --- Filtre Montant (min / max) ---
        if (!empty($filters['montant_min']) || $filters['montant_min'] === '0') {
            $sql .= " AND t.montant >= :montant_min: ";
            $params['montant_min'] = (float) $filters['montant_min'];
        }

        if (!empty($filters['montant_max'])) {
            $sql .= " AND t.montant <= :montant_max: ";
            $params['montant_max'] = (float) $filters['montant_max'];
        }

        // --- Filtre Frais (min / max) ---
        if (!empty($filters['frais_min']) || $filters['frais_min'] === '0') {
            $sql .= " AND t.frais >= :frais_min: ";
            $params['frais_min'] = (float) $filters['frais_min'];
        }

        if (!empty($filters['frais_max'])) {
            $sql .= " AND t.frais <= :frais_max: ";
            $params['frais_max'] = (float) $filters['frais_max'];
        }

        // --- Filtre Correspondant (texte, un seul champ ; source ou destinataire) ---
        if (!empty($filters['correspondant'])) {
            $sql .= " AND (dest.numero LIKE :correspondant: OR src.numero LIKE :correspondant:) ";
            $params['correspondant'] = '%' . $filters['correspondant'] . '%';
        }

        $sql .= " ORDER BY t.date_transaction DESC ";

        return $this->db->query($sql, $params)->getResultArray();
    }

    // Historique des transactions pour un operateur
    public function getHistoriqueOperateur(int $idOperateur): array
    {
        return $this->db->query("
            SELECT
                t.id,
                t.numero_transaction,
                t.date_transaction,
                to_.type AS type_operation,
                t.montant,
                t.frais,
                t.commission_appliquee,
                c_src.numero  AS numero_expediteur,
                c_dest.numero AS numero_destinataire,
                
                -- Impact sur le solde global de l'opérateur (approximation)
                CASE
                    WHEN to_.type = 'depot' THEN t.montant
                    WHEN to_.type = 'retrait' THEN -(t.montant + t.frais)
                    WHEN to_.type = 'transfert' AND p_src.id_operateur = :idOperateur 
                        AND p_dest.id_operateur = :idOperateur THEN 0          -- intra-opérateur
                    WHEN to_.type = 'transfert' AND p_src.id_operateur = :idOperateur 
                        THEN -(t.montant + t.frais + COALESCE(t.commission_appliquee, 0)) -- sortie
                    WHEN to_.type = 'transfert' AND p_dest.id_operateur = :idOperateur 
                        THEN t.montant                                          -- entrée (net)
                    ELSE 0
                END AS impact_solde_operateur,

                CASE 
                    WHEN to_.type = 'transfert' THEN 
                        CONCAT(c_src.numero, ' → ', COALESCE(c_dest.numero, 'Inconnu'))
                    ELSE NULL 
                END AS correspondants

            FROM transactions t
            JOIN bareme b ON t.id_bareme = b.id
            JOIN type_operation to_ ON b.id_type_operation = to_.id
            
            -- Jointures pour récupérer les opérateurs des numéros
            LEFT JOIN client c_src  ON c_src.id  = t.id_client
            LEFT JOIN prefixe p_src ON p_src.debut_numero = LEFT(c_src.numero, 3)
            
            LEFT JOIN client c_dest ON c_dest.id = t.id_destinataire
            LEFT JOIN prefixe p_dest ON p_dest.debut_numero = LEFT(c_dest.numero, 3)
            
            WHERE (p_src.id_operateur = :idOperateur 
                OR p_dest.id_operateur = :idOperateur)
            
            ORDER BY t.date_transaction DESC
        ", ['idOperateur' => $idOperateur])->getResultArray();
    }

    public function getGains(int $idOperateurPropre): array
    {
        // Gains intra : frais hors transferts inter-opérateurs (dépôt, retrait, transfert même opérateur)
        $intra = $this->db->query("
            SELECT COALESCE(SUM(t.frais), 0) AS total_frais, COUNT(*) AS nb
            FROM transactions t
            WHERE t.commission_appliquee IS NULL
        ")->getRowArray();

        // Gains inter : commissions sur transferts vers autres opérateurs
        $inter = $this->db->query("
            SELECT COALESCE(SUM(t.commission_appliquee), 0) AS total_commission, COUNT(*) AS nb
            FROM transactions t
            WHERE t.commission_appliquee IS NOT NULL
        ")->getRowArray();

        // Montants à reverser par opérateur externe (principal transféré, hors frais/commission)
        $reversements = $this->db->query("
            SELECT
                o.id  AS id_operateur,
                o.nom AS nom_operateur,
                COALESCE(SUM(t.montant), 0) AS montant_a_reverser,
                COUNT(*) AS nb_transactions
            FROM transactions t
            JOIN client   dest ON dest.id = t.id_destinataire
            JOIN prefixe  p    ON SUBSTR(dest.numero, 1, 3) = p.debut_numero
            JOIN operateur o   ON o.id = p.id_operateur
            WHERE t.commission_appliquee IS NOT NULL
              AND o.id != :idOperateurPropre:
            GROUP BY o.id, o.nom
            ORDER BY montant_a_reverser DESC
        ", ['idOperateurPropre' => $idOperateurPropre])->getResultArray();

        return [
            'intra' => [
                'total_frais' => (float) ($intra['total_frais'] ?? 0),
                'nb' => (int) ($intra['nb'] ?? 0),
            ],
            'inter' => [
                'total_commission' => (float) ($inter['total_commission'] ?? 0),
                'nb' => (int) ($inter['nb'] ?? 0),
            ],
            'reversements' => $reversements,
        ];
    }

    private function _genererNumero(): string
    {
        return 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    private function _getBaremeParDefaut(int $idTypeOperation): int|null
    {
        $baremeModel = new BaremeModel();
        $row = $baremeModel->where('id_type_operation', $idTypeOperation)->first();
        return $row ? (int) $row['id'] : null;
    }
    public function faireTransfertMultiple(int $idClient, array $numeros, float $montant): array
    {
        $numeros = array_values(array_unique(array_map('trim', $numeros)));

        if (count($numeros) < 2) {
            return ['success' => false, 'error' => 'Il faut au moins 2 destinataires pour un envoi multiple.'];
        }

        if ($montant <= 0) {
            return ['success' => false, 'error' => 'Le montant total doit être strictement positif.'];
        }

        $clientModel = new ClientModel();
        $prefixeModel = new PrefixeModel();
        $typeOpModel = new TypeOperationModel();
        $baremeModel = new BaremeModel();

        $expediteur = $clientModel->find($idClient);
        if (!$expediteur) {
            return ['success' => false, 'error' => 'Client expéditeur introuvable.'];
        }

        $nbDestinataires = count($numeros);
        $montantParDest = round($montant / $nbDestinataires, 2);

        if ($montantParDest <= 0) {
            return ['success' => false, 'error' => 'Montant par destinataire trop faible.'];
        }

        $idTypeTransfert = $typeOpModel->getIdByType(TypeOperationModel::TRANSFERT);
        $tranche = $baremeModel->getTranche($idTypeTransfert, $montantParDest);

        if (!$tranche) {
            return ['success' => false, 'error' => 'Montant par destinataire hors barème.'];
        }

        $fraisParDest = (float) $tranche['frais'];
        $destinatairesInfo = [];

        foreach ($numeros as $numero) {
            if (!$prefixeModel->estNumerovalide($numero)) {
                return ['success' => false, 'error' => "Numéro invalide : {$numero}."];
            }

            $dest = $clientModel->findByNumero($numero);
            if (!$dest) {
                return ['success' => false, 'error' => "Aucun compte trouvé pour {$numero}."];
            }

            if ((int) $dest['id'] === $idClient) {
                return ['success' => false, 'error' => 'Vous ne pouvez pas vous transférer à vous-même.'];
            }

            if ($prefixeModel->estInterOperateur($expediteur['numero'], $numero)) {
                return ['success' => false, 'error' => "L'envoi multiple n'est autorisé que vers le même opérateur ({$numero} est inter-opérateur)."];
            }

            $destinatairesInfo[] = $dest;
        }

        $totalDebit = ($montantParDest + $fraisParDest) * $nbDestinataires;

        if (!$clientModel->aSoldeSuffisant($idClient, $totalDebit)) {
            $solde = $clientModel->getSolde($idClient);
            return [
                'success' => false,
                'error' => "Solde insuffisant. Solde disponible : {$solde} Ar, montant total requis : {$totalDebit} Ar.",
            ];
        }

        $this->db->transStart();

        $transactionsCreees = [];
        foreach ($destinatairesInfo as $index => $dest) {
            $data = [
                'numero_transaction' => $this->_genererNumero() . '-' . ($index + 1),
                'montant' => $montantParDest,
                'frais' => $fraisParDest,
                'commission_appliquee' => null,
                'date_transaction' => date('Y-m-d H:i:s'),
                'id_client' => $idClient,
                'id_destinataire' => (int) $dest['id'],
                'id_bareme' => (int) $tranche['id'],
            ];
            $this->insert($data);
            $transactionsCreees[] = $data + ['destinataire' => $numeros[$index]];
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return ['success' => false, 'error' => 'Erreur lors de l\'enregistrement des transactions.'];
        }

        return [
            'success' => true,
            'nb_destinataires' => $nbDestinataires,
            'montant_par_dest' => $montantParDest,
            'frais_par_dest' => $fraisParDest,
            'total_debite' => $totalDebit,
            'transactions' => $transactionsCreees,
        ];
    }
}
