<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OperateurModel;
use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeModel;
use App\Models\ClientModel;

class OperateurController extends BaseController
{
    protected OperateurModel      $operateurModel;
    protected PrefixeModel        $prefixeModel;
    protected TypeOperationModel  $typeOpModel;
    protected BaremeModel         $baremeModel;
    protected ClientModel         $clientModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
        $this->prefixeModel   = new PrefixeModel();
        $this->typeOpModel    = new TypeOperationModel();
        $this->baremeModel    = new BaremeModel();
        $this->clientModel    = new ClientModel();
    }


    public function index(): string
    {
        return view('operateur/dashboard', [
            'total_gains'  => $this->operateurModel->getTotalGains(),
            'nb_clients'   => (new \App\Models\ClientModel())->countAll(),
        ]);
    }

    public function prefixes(): string
    {
        $prefixes = $this->prefixeModel
            ->select('prefixe.*, operateur.nom AS nom_operateur')
            ->join('operateur', 'operateur.id = prefixe.id_operateur')
            ->findAll();

        return view('operateur/prefixes/index', [
            'prefixes'   => $prefixes,
            'operateurs' => $this->operateurModel->findAll(),
        ]);
    }


    public function ajouterPrefixe(): \CodeIgniter\HTTP\RedirectResponse
    {
        $debut      = trim($this->request->getPost('debut_numero'));
        $idOperateur = (int) $this->request->getPost('id_operateur');

        if (!preg_match('/^\d{3}$/', $debut)) {
            return redirect()->back()->with('error', 'Un préfixe doit contenir exactement 3 chiffres.');
        }

        if ($this->prefixeModel->where('debut_numero', $debut)->first()) {
            return redirect()->back()->with('error', "Le préfixe {$debut} existe déjà.");
        }

        if (!$this->prefixeModel->insert(['debut_numero' => $debut, 'id_operateur' => $idOperateur])) {
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout du préfixe.');
        }

        return redirect()->to('/operateur/prefixes')->with('success', "Préfixe {$debut} ajouté.");
    }

    public function supprimerPrefixe(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->prefixeModel->delete($id);
        return redirect()->to('/operateur/prefixes')->with('success', 'Préfixe supprimé.');
    }

    public function typesOperations(): string
    {
        return view('operateur/types/index', [
            'types' => $this->typeOpModel->getAllWithBaremes(),
        ]);
    }


    public function baremes(int $idType): string
    {
        $type = $this->typeOpModel->find($idType);
        if (!$type) {
            return redirect()->to('/operateur/types')->with('error', 'Type d\'opération introuvable.');
        }

        return view('operateur/baremes/index', [
            'type'    => $type,
            'baremes' => $this->baremeModel->getByTypeOperation($idType),
        ]);
    }


    public function ajouterBareme(): \CodeIgniter\HTTP\RedirectResponse
    {
        $idType      = (int) $this->request->getPost('id_type_operation');
        $description = trim($this->request->getPost('description'));
        $min         = (float) $this->request->getPost('min');
        $max         = (float) $this->request->getPost('max');
        $frais       = (float) $this->request->getPost('frais');

        if ($min <= 0 || $max <= 0 || $min >= $max) {
            return redirect()->back()->with('error', 'Les montants doivent être positifs et min < max.');
        }

        if ($frais < 0) {
            return redirect()->back()->with('error', 'Les frais ne peuvent pas être négatifs.');
        }

        if (!$this->baremeModel->estSansChevauchemnt($idType, $min, $max)) {
            return redirect()->back()->with('error', 'Cette tranche chevauche une tranche existante.');
        }

        $this->baremeModel->insert([
            'description'        => $description,
            'min'                => $min,
            'max'                => $max,
            'frais'              => $frais,
            'id_type_operation'  => $idType,
        ]);

        return redirect()->to("/operateur/baremes/{$idType}")->with('success', 'Tranche ajoutée.');
    }

    public function modifierBareme(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $bareme = $this->baremeModel->find($id);
        if (!$bareme) {
            return redirect()->back()->with('error', 'Barème introuvable.');
        }

        $description = trim($this->request->getPost('description'));
        $min         = (float) $this->request->getPost('min');
        $max         = (float) $this->request->getPost('max');
        $frais       = (float) $this->request->getPost('frais');

        if ($min <= 0 || $max <= 0 || $min >= $max) {
            return redirect()->back()->with('error', 'Les montants doivent être positifs et min < max.');
        }

        if ($frais < 0) {
            return redirect()->back()->with('error', 'Les frais ne peuvent pas être négatifs.');
        }

        if (!$this->baremeModel->estSansChevauchemnt((int) $bareme['id_type_operation'], $min, $max, $id)) {
            return redirect()->back()->with('error', 'Cette tranche chevauche une tranche existante.');
        }

        $this->baremeModel->update($id, [
            'description' => $description,
            'min'         => $min,
            'max'         => $max,
            'frais'       => $frais,
        ]);

        return redirect()->to("/operateur/baremes/{$bareme['id_type_operation']}")->with('success', 'Tranche modifiée.');
    }

    public function supprimerBareme(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $bareme = $this->baremeModel->find($id);
        $this->baremeModel->delete($id);
        return redirect()->to("/operateur/baremes/{$bareme['id_type_operation']}")->with('success', 'Tranche supprimée.');
    }


    public function situationComptes(): string
    {
        return view('operateur/comptes', [
            'comptes' => $this->clientModel->getSituationComptes(),
        ]);
    }


    public function situationGains(): string
    {
        $gainsData = $this->operateurModel->getSituationGains(); // tableau avec retrait et transfert

        $retrait_total   = 0;
        $retrait_nb      = 0;
        $transfert_total = 0;
        $transfert_nb    = 0;

        foreach ($gainsData as $g) {
            if ($g['type_operation'] === 'retrait') {
                $retrait_total = (float) $g['total_frais'];
                $retrait_nb    = (int)   $g['nb_transactions'];
            } elseif ($g['type_operation'] === 'transfert') {
                $transfert_total = (float) $g['total_frais'];
                $transfert_nb    = (int)   $g['nb_transactions'];
            }
        }

        return view('operateur/gains', [
            'gains'          => $gainsData,
            'retrait_total'  => $retrait_total,
            'retrait_nb'     => $retrait_nb,
            'transfert_total'=> $transfert_total,
            'transfert_nb'   => $transfert_nb,
            'total_gains'    => $retrait_total + $transfert_total,
            'historique'     => $this->operateurModel->getHistoriqueGainsFiltree(),
        ]);
    }
    
    public function filtrerGains(): \CodeIgniter\HTTP\ResponseInterface
    {
        $filtres = [
            'type'         => $this->request->getPost('type'),
            'date_debut'   => $this->request->getPost('date_debut'),
            'date_fin'     => $this->request->getPost('date_fin'),
            'client'       => $this->request->getPost('client'),
            'montant_min'  => $this->request->getPost('montant_min'),
            'montant_max'  => $this->request->getPost('montant_max'),
        ];

        $filtres = array_filter($filtres, fn($v) => $v !== null && $v !== '');

        $historique = $this->operateurModel->getHistoriqueGainsFiltree($filtres);

        $retrait_total = 0;
        $retrait_nb    = 0;
        $transfert_total = 0;
        $transfert_nb  = 0;

        foreach ($historique as $t) {
            if ($t['type_operation'] === 'retrait') {
                $retrait_total += (float) $t['frais'];
                $retrait_nb++;
            } else {
                $transfert_total += (float) $t['frais'];
                $transfert_nb++;
            }
        }

        return $this->response->setJSON([
            'historique' => $historique,
            'stats' => [
                'retrait_total'   => $retrait_total,
                'retrait_nb'      => $retrait_nb,
                'transfert_total' => $transfert_total,
                'transfert_nb'    => $transfert_nb,
                'total_gains'     => $retrait_total + $transfert_total,
                'total_nb'        => $retrait_nb + $transfert_nb,
            ],
        ]);
    }
    
    public function modifierPrefixe(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $prefixe = $this->prefixeModel->find($id);
        if (!$prefixe) {
            return redirect()->back()->with('error', 'Préfixe introuvable.');
        }
     
        $debut       = trim($this->request->getPost('debut_numero'));
        $idOperateur = (int) $this->request->getPost('id_operateur');
     
        if (!preg_match('/^\d{3}$/', $debut)) {
            return redirect()->back()->with('error', 'Un préfixe doit contenir exactement 3 chiffres.');
        }
     
        $existing = $this->prefixeModel->where('debut_numero', $debut)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', "Le préfixe {$debut} est déjà utilisé.");
        }
     
        $this->prefixeModel->update($id, [
            'debut_numero' => $debut,
            'id_operateur' => $idOperateur,
        ]);
     
        return redirect()->to('/operateur/prefixes')->with('success', "Préfixe modifié en {$debut}.");
    }
    

}