<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\TransactionsModel;

class ClientController extends BaseController
{
    protected ClientModel      $clientModel;
    protected TransactionsModel $transactionsModel;

    public function __construct()
    {
        $this->clientModel       = new ClientModel();
        $this->transactionsModel = new TransactionsModel();
    }

    // ----------------------------------------------------------------
    // Garde de session : redirige si non connecté
    // ----------------------------------------------------------------
    private function getClientId(): int|null
    {
        $id = session()->get('client_id');
        return $id ? (int) $id : null;
    }

    private function requireAuth(): \CodeIgniter\HTTP\RedirectResponse|null
    {
        if (!$this->getClientId()) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter.');
        }
        return null;
    }

    // ----------------------------------------------------------------
    // GET /client/solde
    // Affiche le solde du client connecté
    // ----------------------------------------------------------------
    public function solde(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($redir = $this->requireAuth()) return $redir;

        $idClient = $this->getClientId();
        $solde    = $this->clientModel->getSolde($idClient);

        return view('client/solde', [
            'numero' => session()->get('client_numero'),
            'solde'  => $solde,
        ]);
    }

    // ----------------------------------------------------------------
    // GET  /client/depot
    // POST /client/depot
    // Dépôt : pas de frais, montant > 0, validé automatiquement
    // ----------------------------------------------------------------
    public function depot(): string|\CodeIgniter\HTTP\RedirectResponse
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
            $numeroDestinataire = trim($this->request->getPost('numero_destinataire'));
            $montant            = (float) $this->request->getPost('montant');

            $result = $this->transactionsModel->faireTransfert(
                $this->getClientId(),
                $numeroDestinataire,
                $montant
            );

            if (!$result['success']) {
                return redirect()->back()->with('error', $result['error']);
            }

            $msg = sprintf(
                'Transfert de %s Ar vers %s effectué. Frais : %s Ar.',
                number_format($montant, 2),
                $result['destinataire'],
                number_format($result['frais'], 2)
            );

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

        $idClient     = $this->getClientId();
        $transactions = $this->transactionsModel->getHistoriqueClient($idClient);

        return view('client/historique', [
            'numero'       => session()->get('client_numero'),
            'solde'        => $this->clientModel->getSolde($idClient),
            'transactions' => $transactions,
        ]);
    }
}
