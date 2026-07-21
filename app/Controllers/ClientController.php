<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\TransactionsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RedirectResponse;

class ClientController extends BaseController
{
    protected ClientModel      $clientModel;
    protected TransactionsModel $transactionsModel;

    public function __construct(){
        $this->clientModel       = new ClientModel();
        $this->transactionsModel = new TransactionsModel();
    }

    // Garde de session 
    private function getClientId(): int|null{
        $id = session()->get('client_id');
        return $id ? (int) $id : null;
    }

    // Vérifie si le client est connecté, sinon redirige vers la page de connexion
    private function requireAuth(): RedirectResponse|null{
        if (!$this->getClientId()) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter.');
        }
        return null;
    }


    // Affiche le solde du client connecté
    public function solde(): string|RedirectResponse{
        if ($redir = $this->requireAuth()) return $redir;

        $idClient = $this->getClientId();
        $solde    = $this->clientModel->getSolde($idClient);

        return view('client/solde', [
            'numero' => session()->get('client_numero'),
            'solde'  => $solde,
        ]);
    }


    // Dépôt : pas de frais, montant > 0, validé automatiquement
    public function depot(): string|RedirectResponse
    {
        if ($redir = $this->requireAuth()) return $redir;

        if ($this->request->getMethod() === 'POST') {
            $montant = (float) $this->request->getPost('montant');

            $result = $this->transactionsModel->faireDepot($this->getClientId(), $montant);

            if (!$result['success']) {
                return redirect()->back()->with('error', $result['error']);
            }

            return redirect()->to('/client/solde')
                ->with('success', "Dépôt de {$montant} Ar effectué avec succès.");
        }

        return view('client/solde', [
            'numero' => session()->get('client_numero'),
            'solde'  => $this->clientModel->getSolde($this->getClientId()),
        ]);
    }

    // ----------------------------------------------------------------
    // GET  /client/retrait
    // POST /client/retrait
    // Retrait : frais selon barème, solde suffisant (montant + frais)
    // ----------------------------------------------------------------
    public function retrait(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($redir = $this->requireAuth()) return $redir;

        if ($this->request->getMethod() === 'POST') {
            $montant = (float) $this->request->getPost('montant');

            $result = $this->transactionsModel->faireRetrait($this->getClientId(), $montant);

            if (!$result['success']) {
                return redirect()->back()->with('error', $result['error']);
            }

            $msg = sprintf(
                'Retrait de %s Ar effectué. Frais : %s Ar. Total débité : %s Ar.',
                number_format($montant, 2),
                number_format($result['frais'], 2),
                number_format($result['total_debite'], 2)
            );

            return redirect()->to('/client/solde')->with('success', $msg);
        }

        return view('client/retrait', [
            'numero' => session()->get('client_numero'),
            'solde'  => $this->clientModel->getSolde($this->getClientId()),
        ]);
    }

    // ----------------------------------------------------------------
    // GET  /client/transfert
    // POST /client/transfert
    // Transfert : vérifie préfixe destinataire, frais selon barème, solde suffisant
    // ----------------------------------------------------------------
public function transfert(): string|\CodeIgniter\HTTP\RedirectResponse
{
    if ($redir = $this->requireAuth()) return $redir;

    if ($this->request->getMethod() === 'POST') {
        $numeroDestinataire = trim($this->request->getPost('destinataire'));
        $montant            = (float) $this->request->getPost('montant');
        $inclureFrais       = (bool) $this->request->getPost('inclure_frais');

        $result = $this->transactionsModel->faireTransfert(
            $this->getClientId(),
            $numeroDestinataire,
            $montant,
            $inclureFrais
        );

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['error']);
        }

        $msg = sprintf(
            'Transfert effectué vers %s. Débité : %s Ar. Reçu par le destinataire : %s Ar. Frais : %s Ar.',
            $result['destinataire'],
            number_format($result['total_debite'], 2),
            number_format($result['montant_net_recu'], 2),
            number_format($result['frais'], 2)
        );

        if ($result['commission_appliquee'] > 0) {
            $msg .= sprintf(' Commission inter-opérateur : %s Ar.', number_format($result['commission_appliquee'], 2));
        }

        return redirect()->to('/client/solde')->with('success', $msg);
    }

    return view('client/transfert', [
        'numero' => session()->get('client_numero'),
        'solde'  => $this->clientModel->getSolde($this->getClientId()),
    ]);
}

    // ----------------------------------------------------------------
    // GET /client/historique
    // Historique des opérations du client connecté
    // ----------------------------------------------------------------

public function historique(): string|\CodeIgniter\HTTP\RedirectResponse
{
    if ($redir = $this->requireAuth()) return $redir;

    $idClient = $this->getClientId();

    $filters = [
        'date_min'            => $this->request->getGet('date_min'),
        'date_max'            => $this->request->getGet('date_max'),
        'numero_transaction'  => $this->request->getGet('numero_transaction'),
        'type_operation'      => $this->request->getGet('type_operation'),
        'montant_min'         => $this->request->getGet('montant_min'),
        'montant_max'         => $this->request->getGet('montant_max'),
        'frais_min'           => $this->request->getGet('frais_min'),
        'frais_max'           => $this->request->getGet('frais_max'),
        'correspondant'       => $this->request->getGet('correspondant'),
    ];

    $transactions = $this->transactionsModel->getHistoriqueClient($idClient, $filters);

    return view('client/historique', [
        'numero'       => session()->get('client_numero'),
        'solde'        => $this->clientModel->getSolde($idClient),
        'transactions' => $transactions,
        'filters'      => $filters,
    ]);
}

public function verifierCommission(): \CodeIgniter\HTTP\ResponseInterface
{
    if (!$this->getClientId()) {
        return $this->response->setStatusCode(401)->setJSON(['error' => 'Non authentifié.']);
    }

    $numeroDestinataire = trim($this->request->getPost('destinataire'));
    $montant            = (float) $this->request->getPost('montant');
    $inclureFrais       = (bool) $this->request->getPost('inclure_frais');

    $prefixeModel = new \App\Models\PrefixeModel();
    $configModel  = new \App\Models\ConfigOperateurModel();
    $baremeModel  = new \App\Models\BaremeModel();
    $typeOpModel  = new \App\Models\TypeOperationModel();

    if ($numeroDestinataire === '' || !$prefixeModel->estNumerovalide($numeroDestinataire)) {
        return $this->response->setJSON(['valide' => false]);
    }

    $numeroExpediteur = session()->get('client_numero');
    $interOperateur   = $prefixeModel->estInterOperateur($numeroExpediteur, $numeroDestinataire);

    $frais = 0.0;
    $tauxCommission = 0.0;
    $commission     = 0.0;
    $montantNet     = $montant;
    $totalDebit     = $montant;

    if ($montant > 0) {
        $idTypeTransfert = $typeOpModel->getIdByType(\App\Models\TypeOperationModel::TRANSFERT);
        $tranche         = $baremeModel->getTranche($idTypeTransfert, $montant);
        $frais           = $tranche ? (float) $tranche['frais'] : 0.0;

        if ($interOperateur) {
            $tauxCommission = $configModel->getCommissionActuelle();
            $commission     = round($montant * $tauxCommission / 100, 2);
        }

        $fraisTotal = $frais + $commission;

        if ($inclureFrais) {
            $montantNet = $montant - $fraisTotal;
            $totalDebit = $montant;
        } else {
            $montantNet = $montant;
            $totalDebit = $montant + $fraisTotal;
        }
    }

    return $this->response->setJSON([
        'valide'          => true,
        'inter_operateur' => $interOperateur,
        'taux_commission' => $tauxCommission,
        'commission'      => $commission,
        'frais'           => $frais,
        'montant_net'     => $montantNet,
        'total_debit'     => $totalDebit,
    ]);
}
public function transfertMultiple(): string|\CodeIgniter\HTTP\RedirectResponse
{
    if ($redir = $this->requireAuth()) return $redir;

    if ($this->request->getMethod() === 'POST') {
        $numeros = array_filter(array_map('trim', $this->request->getPost('destinataires') ?? []));
        $montant = (float) $this->request->getPost('montant');

        $result = $this->transactionsModel->faireTransfertMultiple(
            $this->getClientId(),
            $numeros,
            $montant
        );

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['error'])->withInput();
        }

        $msg = sprintf(
            'Envoi multiple effectué vers %d destinataires. %s Ar chacun (frais : %s Ar chacun). Total débité : %s Ar.',
            $result['nb_destinataires'],
            number_format($result['montant_par_dest'], 2),
            number_format($result['frais_par_dest'], 2),
            number_format($result['total_debite'], 2)
        );

        return redirect()->to('/client/solde')->with('success', $msg);
    }

    return view('client/transfert_multiple', [
        'numero' => session()->get('client_numero'),
        'solde'  => $this->clientModel->getSolde($this->getClientId()),
    ]);
}
}
