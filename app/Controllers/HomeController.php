<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use CodeIgniter\HTTP\ResponseInterface;

class HomeController extends BaseController
{
    protected ClientModel $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    // GET /login
    // Affiche le formulaire de saisie du numéro
    public function choice(): string|ResponseInterface
    {
        return view('index');
    }

}
