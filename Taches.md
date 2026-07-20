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
- [ ] design
    - [ ] carte récapitulative : total retrait / total transfert / total général
    - [ ] tableau historique : date, type, client, montant, frais

## 4 - Situation des comptes clients

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
- [ok] base
    - transaction
- [] fonction
    - transactionModel.faireDepot(id,montant)
- [] integration
    - routes `client/depot , clientController::depot`
    - ClientController.depot()
- [] design
    - Appeler cette fonciton depuis un Ajax
## 8 - Faire un retrait pour le client

## 9 - Faire un transfert pour le client

## 10 - Client peut voir son historique de transactions (depot, retrait, transfert)


