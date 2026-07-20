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

## 3 - Situation gain via les differents frais
Objectif : chaque operateur peut voir sa caisse, total des frais payes par les clients lors de la transaction retrait et transfert, en fonction des barèmes respectifs. On peut aussi voir l'historique des transactions.
- [] base
    - 
- [] fonction
    -
- [] integration
    - comment on implemente dans la view
- [] design
    - comment on va afficher les resultats

## 4 - Situation des comptes clients

## 5 - Login client automatique (si le numero n'existe pas, il est cree automatiquement) (B1)

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

## 8 - Faire un retrait pour le client

## 9 - Faire un transfert pour le client

## 10 - Client peut voir son historique de transactions (depot, retrait, transfert)


