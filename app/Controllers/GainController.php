<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\TransactionsModel;
use App\Models\OperateurModel;

class GainController extends BaseController
{
    protected ClientModel $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    public function index(): string
    {
        if (!session()->get('operateur_id')) {
            return redirect()->to('/operateur/login');
        }

        $idOperateur = session()->get('operateur_id');
        $transactionModel = new TransactionsModel();

        $data = [
            'gains'      => $transactionModel->getGains($idOperateur),
            'historique' => $transactionModel->getHistoriqueOperateur($idOperateur),
        ];

        return view('operateur/gains', $data);
    }

}
