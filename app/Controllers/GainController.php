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
        if (session()->get('operateur_id')) {
            return redirect()->to('/operateur/gains');
        }

        return view('auth/login');
    }

}
