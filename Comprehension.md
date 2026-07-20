## Côté opérateur

* Gérer les préfixes valides de l'opérateur (ex. 033, 037).

* Gérer les types d'opérations :

  * dépôt
  * retrait
  * transfert

* Pour chaque type d'opération, gérer des **barèmes de frais par tranche de montant**, et ces barèmes doivent être **modifiables**. Ce n'est pas juste "un frais".

  Par exemple :

  | Montant         | Frais retrait |
  | --------------- | ------------: |
  | 0 - 10 000      |        100 Ar |
  | 10 001 - 50 000 |        500 Ar |
  | > 50 000        |      1 000 Ar |

* Voir combien l'opérateur a gagné grâce aux frais de retrait et de transfert.

* Voir la situation des comptes clients (solde de chaque client).

---

## Côté client

* Login automatique avec le numéro de téléphone.
* Il n'y a **aucune inscription**. Le sujet dit simplement que le login se fait avec le numéro. Cela signifie probablement que si le numéro n'existe pas encore, le compte est créé automatiquement.

Le client peut :

* Consulter son solde.
* Effectuer un dépôt.

  * Pas de frais.
  * L'argent est ajouté automatiquement.
* Effectuer un retrait.

  * Des frais sont appliqués.
* Effectuer un transfert.

  * Des frais sont appliqués.
* Consulter son historique des opérations.

---

## Ce que le sujet laisse comprendre

Pour qu'un transfert fonctionne, il faudra probablement :

* vérifier que le numéro destinataire possède un préfixe valide ;
* vérifier que le client possède suffisamment d'argent pour payer :

  * le montant transféré ;
  * les frais de transfert.

Pour un retrait :

* vérifier que le solde couvre le montant **+ les frais**.

---

## Les principales tables que j'imagine déjà

* `operateur`
* `prefixe`
* `client`
* `type_operation`
* `bareme_frais`
* `transaction`

---

1. les clients et leurs soldes ;
2. les transactions (dépôt, retrait, transfert) ;
3. les frais calculés selon des barèmes.
