<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController {
    protected ClientModel $clientModel;

    public function __construct(){
        $this->clientModel = new ClientModel();
    }

    // affiche le formulaire de connexion
    public function index(): string|ResponseInterface{
        if (session()->get('client_id')) {
            return redirect()->to('/client/solde');
        }

        return view('auth/login');
    }

    // Login automatique : crée le compte si le numéro est valide et inconnu
    public function login(): \CodeIgniter\HTTP\RedirectResponse{

        $numero = trim($this->request->getPost('numero'));

        if (empty($numero)) {
            return redirect()->back()->with('error', 'Veuillez saisir un numéro de téléphone.');
        }

        $result = $this->clientModel->loginOuCreer($numero);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['error']);
        }

        // Stocker le client en session
        session()->set([
            'client_id'     => $result['client']['id'],
            'client_numero' => $result['client']['numero'],
        ]);

        $message = $result['created']
            ? 'Compte créé et connexion réussie.'
            : 'Connexion réussie.';

        return redirect()->to('/client/solde')->with('success', $message);
    }

    // deconnexion : détruit la session et redirige vers la page de connexion
    public function logout(): \CodeIgniter\HTTP\RedirectResponse {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Déconnexion réussie.');
    }
}
