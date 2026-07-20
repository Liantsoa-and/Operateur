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

---

# V2 - Fonctionnalités inter-opérateurs (B2)

## 1 - Table config_operateur + migration (B2)

- [ok] base
    - [ok] créer une nouvelle migration (ne pas modifier InitBase)
    - [ok] ajouter table `config_operateur (id, commission_inter DECIMAL, date_modification)`
    - [ok] ajouter colonne `commission_appliquee DECIMAL null` dans `transactions`
    - [ok] mettre à jour `base.sql`

## 2 - Configuration des préfixes externes (opérateurs tiers) (B2)

- [ok] base
    - [ok] vérifier que Airtel, Orange etc. existent dans `operateur`
    - [ok] vérifier que leurs préfixes existent dans `prefixe`
- [ok] intégration
    - [ok] adapter la vue `prefixes/index.php` : distinguer visuellement
          les préfixes de notre opérateur vs les autres
    - [ok] adapter `ajouterPrefixe()` : permettre de choisir l'opérateur cible

## 3 - Configuration de la commission inter-opérateur (B2)

- [ok] modèle
    - [ok] créer `ConfigOperateurModel` avec :
        - [ok] `getCommissionActuelle()` : retourne le taux courant
        - [ok] `setCommission(float $taux)` : insère une nouvelle ligne
- [ok] intégration
    - [ok] ajouter une section dans le dashboard opérateur
    - [ok] formulaire pour modifier le %
    - [ok] ajouter routes GET/POST dans `OperateurController`

## 4 - Transfert inter-opérateur avec commission (B2)

- [ ] modèle
    - [ ] ajouter `estInterOperateur(string $numeroExp, string $numeroDest): bool`
          dans `PrefixeModel` (comparer id_operateur des deux préfixes)
    - [ ] modifier `faireTransfert()` dans `TransactionsModel` :
        - [ ] détecter si inter-opérateur
        - [ ] lire `commission_inter` depuis `config_operateur`
        - [ ] calculer `commission_appliquee = % × montant`
        - [ ] total frais = frais barème + commission_appliquee
        - [ ] sauvegarder `commission_appliquee` dans la transaction
- [ ] intégration
    - [ ] adapter vue transfert : afficher commission si inter-opérateur (AJAX)

## 5 - Situation des gains séparée

- [ ] modèle
    - [ ] modifier `getGains()` :
        - [ ] gains intra (frais sur clients même opérateur)
        - [ ] gains inter (commission_appliquee par opérateur externe)
        - [ ] montants à reverser à chaque opérateur externe
- [ ] intégration
    - [ ] adapter `gains.php` : deux sections distinctes
    - [ ] tableau "Montants à reverser par opérateur"

## 6 - Option "inclure frais" dans le transfert

- [ ] modèle
    - [ ] modifier `faireTransfert()` : paramètre `inclure_frais`
    - [ ] si true : montant_net = montant - frais_total, destinataire reçoit montant_net
    - [ ] vérifier montant_net > 0
- [ ] intégration
    - [ ] ajouter case à cocher dans vue transfert
    - [ ] afficher récapitulatif avant confirmation

## 7 - Envoi multiple

- [ ] modèle
    - [ ] créer `faireTransfertMultiple(int $idClient, array $numeros, float $montant)`
    - [ ] montant_par_destinataire = montant / count(numeros)
    - [ ] valider chaque numéro
    - [ ] vérifier solde suffisant pour le total
    - [ ] insérer une transaction par destinataire
- [ ] intégration
    - [ ] champ dynamique pour ajouter des numéros dans vue transfert
    - [ ] afficher récapitulatif avant envoi

---
# (B1)

## Situation actuel de notre systeme : 
- On peut envoyer de l'argent vers d'autre operateur
- et notre systeme ne simule pas une seule operateur
- IL FAUT CHOISIR UNE SEULE OPERATEUR (urgent)
### Todo : 
- pour routes "/" il faut avoir deux cards pour dirigedr vers operateur ou connexion client

- fonction : 
    - HomeController.choice() doit rediriger vers une view choice.php
    - Dans OperateurController.index , le nb_client doit etre by Operateur
        - creer une fonction ClientModel.getNbClientByOperateur($idOperateur) 
    - OperateurController.prefixes() 
        - PrefixModel ajouter findByOperateur
        - Dans le view : afficher juste les prefixes des autres controller
    - Dans "ajouter prefixes" n'envoyer qu'une seul operateur(le notre vers la view) pour la liste deroulante
    - ClientModel.getSituationComptesByOperateur($idOperateur)
    - TransctionModel.estMemeOperateur(string $numEnvoyeur, string $numDestinataire) qui retourne boolean
    -  operateurModel :
        - ajouter trois autres fonction
            - getSituationGainsByOperateur($idOperateur)
            - getTotalGainsByOperateur($idOperateur)
            - getHistoriqueGainsFiltree($idOperateur)

- integration :
    - /  qui doit vers HomeController::choice
    - /operateur => OperateurController::index (deja fait)
    - /client => AthController::index
    - Dans le OperateurController creer une session d'un operateur par defaut "Telma"
    - tout les fonction doivent appeler une version "ByOperateur"
        - index :
            - getTotalGainsByOperateur
            - getNbClientsByOperateur($idOperateur)
        - prefixes 
            - changer le 1er findAll par findByOperateur($idOperateur)
        - ajouterPrefixes : dans la liste deroulante , nafficher que notre operateur
        - situationComptes
            - getSituationComptesByOperateur($idOperateur)
        - situationGains
            - changer les trois fonctions par des By Operateur
        - filtrerGains
            - getHistoriqueGainsFiltreeByOperateur
- design :
    - choice.php : deux a href 
        - vers /operateur
        - vers /client

# Version  2

- Coté opérateur
    - Configuration des préfixes valable pour les autres opérateurs (ex: 032 et 031, …)
    - Configuration % en plus de commissions pour les transferts vers les autres opérateurs 
    - Sur la page “Situation gain via les différents frais” , séparer opérateur et autres opérateurs
    - Situation des montants à envoyer à chaque opérateur

- Coté client
    - Option inclure frais de retrait lors de l’envoi
    il n’y a pas de frais de retrait pour les autres opérateurs
    - Envoi multiple vers plusieurs numéros ( divisé le montant pour chaque numéro)
même opérateur uniquement
