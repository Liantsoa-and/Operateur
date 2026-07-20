# To-do binome 

Jedidia (B1)
Liantsoa (B2)

## 1 - Initialisation du projet 
- [ok] mise en place de l'architecture ci4 (B2)
- [ok] mise en place de l'arborescence du projet (models, controllers, views, helpers) (B2)
- [ok] mise en place de git (B2)
- [ok] mise en plase de la base de données initiales (B2)
    - [ok] mise en place sqlite3
    - [ok] analyse de la demande
    - [ok] base dans codeigniter en tant que migration
    - [ok] base en sql

## 2 - Creation des fichiers initiales selon la base
- [ok] models (B2)
- [ok] controllers (B2)

## 3 - Situation des gains de l'opérateur (B2)
Objectif : un opérateur consulte le total des frais collectés
(retraits + transferts) et l'historique des transactions associées.
- [ok] base
    - [ok] vérifier comment relier transactions → opérateur
    - [ok] écrire la requête SQL de gain (SUM frais WHERE type IN retrait, transfert)
    - [ok] écrire la requête SQL d'historique global (toutes transactions)
- [ok] fonction
    - [ok] ajouter getGains(int $idOperateur) dans TransactionsModel
    - [ok] ajouter getHistoriqueOperateur(int $idOperateur) dans TransactionsModel
- [ok] intégration
    - [ok] créer GainsController avec méthode index()
    - [ok] récupérer l'opérateur connecté depuis la session
    - [ok] passer gains + historique à la vue
- [ok] design
    - [ok] carte récapitulative : total retrait / total transfert / total général
    - [ok] tableau historique : date, type, client, montant, frais
    - [ok] filtre multi-criteres

## 4 - Situation des comptes clients
- [ok] ajouter un tableau de liste des clients
- [ok] ajouter des kpi de resumer en haut de page

## 5 - Login client automatique (si le numero n'existe pas, il est cree automatiquement) (B1)
- [ok] vérification du préfixe
    - [ok] PrefixeModel.estNumerovalide()
- [ok] fonctionnalité login/création
    - [ok] ClientModel.loginOuCreer()
    - [ok] AuthController.login()
- [ok] intégration
    - [ok] routes GET/POST /login configurées
    - [ok] session client_id et client_numero
- [ok] design
    - [ok] vue auth/login avec formulaire de saisie

## 6 - Voir Solde pour le client
Objectif : Le client peut consulter son solde sur son compte 
- [ok] base (B1)
    - appeler la view v_solde
- [ok] fonction (B1)
    - ClientModel.getSolde()
- [ok] integration (B1)
    - Creer routes: `client/solde,  clientController::solde`
    - ClientController.clientModel.getSolde()
    - Mettre dans un  attribut "solde" puis l'anvoyer dans le view
- [ok] design (B1)
    - Utiliser la variable solde envoyer depuis le Controller

## 7 - Faire un depot pour le client
- Objectif : un client peux deposer
- [ok] base (B1)
    - transaction
- [ok] fonction (B1)
    - transactionModel.faireDepot(id,montant)
- [ok] integration (B1)
    - routes `client/depot , clientController::depot`
    - ClientController.depot()
- [ok] design (B1)
    - Appeler cette fonction depuis un Ajax
    
## 8 - Faire un retrait pour le client
Objectif : un client peut retirer de l'argent (frais selon barème, solde suffisant)
- [ok] base (B1)
    - [ok] vérifier la tranche de barème applicable (type = retrait)
    - [ok] vérifier solde suffisant (montant + frais)
- [ok] fonction (B1)
    - [ok] TransactionsModel.faireRetrait(idClient, montant)
- [ok] intégration (B1)
    - [ok] routes `client/retrait, ClientController::retrait` (GET + POST)
    - [ok] ClientController.retrait()
- [ok] design (B1)
    - [ok] formulaire de saisie du montant
    - [ok] affichage du message (frais appliqués, total débité, ou erreur solde insuffisant)


## 9 - Faire un transfert pour le client
## 9 - Faire un transfert pour le client
Objectif : un client peut transférer de l'argent à un autre client (frais selon barème, solde suffisant)
- [ok] base (B1)
    - [ok] vérifier que le numéro destinataire est valide (préfixe reconnu)
    - [ok] vérifier que le destinataire existe et n'est pas le client lui-même
    - [ok] vérifier la tranche de barème applicable (type = transfert)
    - [ok] vérifier solde suffisant (montant + frais)
- [ok] fonction (B1)
    - [ok] TransactionsModel.faireTransfert(idClient, numeroDestinataire, montant)
- [ok] intégration (B1)
    - [ok] routes `client/transfert, ClientController::transfert` (GET + POST)
    - [ok] ClientController.transfert()
- [ok] design (B1)
    - [ok] formulaire (numéro destinataire + montant)
    - [ok] affichage du message (frais, destinataire, ou erreur)


## 10 - Client peut voir son historique de transactions (depot, retrait, transfert)
Objectif : le client consulte l'ensemble de ses opérations (dépôts, retraits, transferts envoyés/reçus)
- [ok] base (B1)
    - [ok] écrire la requête SQL d'historique (impact solde selon type + numéro correspondant pour transfert)
- [ok] fonction (B1)
    - [ok] TransactionsModel.getHistoriqueClient(idClient)
- [ok] intégration (B1)
    - [ok] route `client/historique, ClientController::historique`
    - [ok] ClientController.historique()
- [ok] design (B1)
    - [ok] tableau : date, type, montant, frais, impact sur le solde, numéro correspondant (transfert)

