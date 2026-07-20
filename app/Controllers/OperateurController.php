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

    // ----------------------------------------------------------------
    // Dashboard opérateur
    // GET /operateur
    // ----------------------------------------------------------------
    public function index(): string
    {
        return view('operateur/dashboard', [
            'total_gains'  => $this->operateurModel->getTotalGains(),
            'nb_clients'   => (new \App\Models\ClientModel())->countAll(),
        ]);
    }

    // ================================================================
    // PREFIXES
    // ================================================================

    // GET /operateur/prefixes
    // Liste tous les préfixes avec leur opérateur
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

    // POST /operateur/prefixes/ajouter
    // Règle : préfixe = 3 chiffres, unique, lié à un opérateur
    public function ajouterPrefixe(): \CodeIgniter\HTTP\RedirectResponse
    {
        $debut      = trim($this->request->getPost('debut_numero'));
        $idOperateur = (int) $this->request->getPost('id_operateur');

        if (!preg_match('/^\d{3}$/', $debut)) {
            return redirect()->back()->with('error', 'Un préfixe doit contenir exactement 3 chiffres.');
        }

        // Vérifier unicité
        if ($this->prefixeModel->where('debut_numero', $debut)->first()) {
            return redirect()->back()->with('error', "Le préfixe {$debut} existe déjà.");
        }

        if (!$this->prefixeModel->insert(['debut_numero' => $debut, 'id_operateur' => $idOperateur])) {
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout du préfixe.');
        }

        return redirect()->to('/operateur/prefixes')->with('success', "Préfixe {$debut} ajouté.");
    }

    // POST /operateur/prefixes/supprimer/:id
    public function supprimerPrefixe(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->prefixeModel->delete($id);
        return redirect()->to('/operateur/prefixes')->with('success', 'Préfixe supprimé.');
    }

    // ================================================================
    // TYPES D'OPÉRATIONS
    // ================================================================

    // GET /operateur/types
    // Liste les 3 types avec leurs barèmes
    public function typesOperations(): string
    {
        return view('operateur/types/index', [
            'types' => $this->typeOpModel->getAllWithBaremes(),
        ]);
    }

    // ================================================================
    // BAREMES DE FRAIS
    // ================================================================

    // GET /operateur/baremes/:id_type
    // Affiche les tranches d'un type d'opération
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

    // POST /operateur/baremes/ajouter
    // Règle : montant > 0, tranches sans chevauchement
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

        // Règle : les tranches ne doivent pas se chevaucher
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

    // POST /operateur/baremes/modifier/:id
    // Modification d'une tranche existante (barèmes modifiables)
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

        // Vérifier chevauchement en excluant la tranche actuelle
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

    // POST /operateur/baremes/supprimer/:id
    public function supprimerBareme(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $bareme = $this->baremeModel->find($id);
        $this->baremeModel->delete($id);
        return redirect()->to("/operateur/baremes/{$bareme['id_type_operation']}")->with('success', 'Tranche supprimée.');
    }

    // ================================================================
    // SITUATION DES COMPTES CLIENTS
    // GET /operateur/comptes
    // ================================================================
    public function situationComptes(): string
    {
        return view('operateur/comptes', [
            'comptes' => $this->clientModel->getSituationComptes(),
        ]);
    }

    // ================================================================
    // SITUATION DES GAINS
    // GET /operateur/gains
    // Règle : gains = somme frais retrait + transfert (dépôt exclut)
    // ================================================================
    public function situationGains(): string
    {
        return view('operateur/gains', [
            'gains'       => $this->operateurModel->getSituationGains(),
            'total_gains' => $this->operateurModel->getTotalGains(),
            'historique'  => $this->operateurModel->getHistoriqueGains(),
        ]);
    }
}
