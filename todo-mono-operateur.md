# Urgence : simuler une seule opérateur

Jedidia (B1)
Liantsoa (B2)

## Situation actuelle du système
- On peut déjà envoyer de l'argent vers un numéro d'un autre opérateur (aucune restriction).
- Le système ne simule pas une seule opérateur : toutes les fonctions de reporting (`index`, `prefixes`, `situationComptes`, `situationGains`) traitent tous les opérateurs mélangés, sans distinguer "notre opérateur" des autres.
- Le login client reste ouvert à tout numéro, quel que soit son opérateur (comportement voulu, à conserver) — un client peut avoir un compte chez nous même si son numéro appartient à un autre réseau.
- ⚠️ Il faut choisir une seule opérateur ("notre opérateur") pour tout le côté gestion/reporting. C'est urgent et bloquant pour la suite.

## 1 - Page de choix à la racine
Objectif : séparer clairement l'entrée opérateur de l'entrée client.
- [ ] fonction (B1)
    - [ ] HomeController.choice() → retourne la vue `choice.php`
- [ ] intégration (B1)
    - [ ] route `/` → `HomeController::choice`
    - [ ] route `/operateur` → `OperateurController::index` (déjà fait)
    - [ ] route `/client` → `AuthController::index`
- [ ] design (B1)
    - [ ] `choice.php` : deux cartes avec chacune un `<a href="">`
        - [ ] carte 1 → `/operateur`
        - [ ] carte 2 → `/client`

## 2 - Définir "notre opérateur"
Objectif : fixer une bonne fois pour toutes quel opérateur est simulé par le système.
- [ ] intégration (B2)
    - [ ] dans `OperateurController`, initialiser une session `operateur_id` par défaut sur "Telma" si elle n'existe pas encore
    - [ ] ⚠️ point à trancher avec B1 : une session doit être régénérée à chaque requête si absente — une alternative plus robuste est de fixer "notre opérateur" en config (`app/Config/App.php` ou `.env`) plutôt qu'en session, puisque cette valeur ne doit jamais changer. À décider avant de coder.

## 3 - Nombre de clients par opérateur
Objectif : sur le dashboard opérateur, n'afficher que les clients dont le numéro appartient à notre opérateur.
- [ ] fonction (B2)
    - [ ] `ClientModel.getNbClientByOperateur($idOperateur)` (jointure numero → prefixe → operateur)
- [ ] intégration (B2)
    - [ ] `OperateurController::index()` appelle `getNbClientByOperateur($idOperateur)` (notre opérateur, via session/config)
    - [ ] `OperateurController::index()` appelle aussi `getTotalGainsByOperateur($idOperateur)`

## 4 - Page des préfixes : afficher les nôtres ET ceux des autres, bien différenciés
Objectif : la page doit lister tous les préfixes existants, mais visuellement séparer "notre opérateur" des opérateurs externes.
- [ ] fonction (B2)
    - [ ] `PrefixeModel.findByOperateur($idOperateur)` → nos préfixes uniquement
    - [ ] `PrefixeModel.findAutresOperateurs($idOperateur)` → tous les préfixes n'appartenant PAS à `$idOperateur`
- [ ] intégration (B2)
    - [ ] `OperateurController::prefixes()` appelle les deux fonctions et envoie les deux listes séparément à la vue (ex: `mesPrefixes` et `autresPrefixes`)
- [ ] design (B2)
    - [ ] `prefixes.php` : deux tableaux ou deux sections distinctes clairement labellisées ("Nos préfixes" / "Préfixes des autres opérateurs"), avec le nom de l'opérateur affiché pour la seconde liste

## 5 - Ajout de préfixe : limité à notre opérateur
Objectif : le formulaire d'ajout de préfixe ne doit permettre d'ajouter un préfixe qu'à notre propre opérateur (pas aux concurrents).
- [ ] intégration (B2)
    - [ ] dans la vue "ajouter préfixe", la liste déroulante n'affiche qu'un seul opérateur (le nôtre) — pas de `findAll()` sur les opérateurs ici

## 6 - Situation des comptes clients par opérateur
- [ ] fonction (B2)
    - [ ] `ClientModel.getSituationComptesByOperateur($idOperateur)`
- [ ] intégration (B2)
    - [ ] `OperateurController::situationComptes()` appelle `getSituationComptesByOperateur($idOperateur)` (notre opérateur)

## 7 - Détection même opérateur lors d'un transfert
Objectif : savoir si l'expéditeur et le destinataire sont sur le même réseau téléphonique (indépendamment de qui gère le compte mobile money).
- [ ] fonction (B1)
    - [ ] `TransactionsModel.estMemeOperateur(string $numEnvoyeur, string $numDestinataire): bool`
- [ ] intégration (B1)
    - [ ] appeler cette fonction dans `TransactionsModel.faireTransfert()` pour préparer la logique de commission (V2, pas encore appliquée ici)

## 8 - Situation des gains par opérateur
- [ ] fonction (B2)
    - [ ] `OperateurModel.getSituationGainsByOperateur($idOperateur)`
    - [ ] `OperateurModel.getTotalGainsByOperateur($idOperateur)`
    - [ ] `OperateurModel.getHistoriqueGainsFiltreeByOperateur($idOperateur)`
- [ ] intégration (B2)
    - [ ] `OperateurController::situationGains()` : remplacer les trois anciennes fonctions par leurs versions `ByOperateur`
    - [ ] `OperateurController::filtrerGains()` appelle `getHistoriqueGainsFiltreeByOperateur($idOperateur)`

## 9 - Tests
- [ ] vérifier que `/operateur` n'affiche que les données liées à notre opérateur (clients, comptes, gains)
- [ ] vérifier que la page préfixes distingue bien visuellement nos préfixes des préfixes externes
- [ ] vérifier qu'un client avec un numéro d'un autre opérateur peut toujours se connecter normalement via `/client`
- [ ] vérifier que l'ajout de préfixe ne propose que notre opérateur dans la liste déroulante
