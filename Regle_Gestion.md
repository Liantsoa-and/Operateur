| Fonctionnalité              | Règles de gestion                                                                                                                                                                                                                                                                                            |
| --------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **Préfixes des opérateurs** | - Un opérateur peut posséder plusieurs préfixes.<br>- Un préfixe ne peut appartenir qu'à un seul opérateur.<br>- Un préfixe est composé de 3 chiffres (ex : 033, 034, 037...).<br>- Un client ne peut utiliser qu'un numéro commençant par un préfixe valide.                                                |
| **Numéro de téléphone**     | - Un numéro est unique.<br>- Le numéro sert d'identifiant de connexion.<br>- Aucun mot de passe n'est demandé.                                                                                                                                                                                               |
| **Connexion**               | - Si le numéro existe, le client est connecté.<br>- Si le numéro n'existe pas, un compte est créé automatiquement (hypothèse probable).                                                                                                                                                                      |
| **Compte client**           | - Chaque client possède un seul compte.<br>- Chaque compte possède un solde.<br>- Le solde initial est généralement de 0 Ar.                                                                                                                                                                                 |
| **Types d'opérations**      | - Les opérations autorisées sont : dépôt, retrait et transfert.<br>- Chaque opération possède ses propres règles de frais.                                                                                                                                                                                   |
| **Barèmes de frais**        | - Un type d'opération peut posséder plusieurs tranches de frais.<br>- Chaque tranche correspond à un intervalle de montants.<br>- Les barèmes sont modifiables par l'opérateur.<br>- Une seule tranche est applicable pour une opération donnée.                                                             |
| **Dépôt**                   | - Le dépôt augmente le solde du client.<br>- Aucun frais n'est appliqué.<br>- Le dépôt est validé automatiquement.                                                                                                                                                                                           |
| **Retrait**                 | - Le client doit disposer d'un solde suffisant.<br>- Les frais sont calculés selon le barème.<br>- Le montant débité est : montant + frais.<br>- Le retrait est validé automatiquement.                                                                                                                      |
| **Transfert**               | - Le destinataire doit posséder un numéro valide.<br>- Le client doit disposer d'un solde suffisant.<br>- Les frais sont calculés selon le barème.<br>- Le montant débité est : montant + frais.<br>- Le destinataire reçoit uniquement le montant transféré.<br>- Les frais sont conservés par l'opérateur. |
| **Historique**              | - Chaque opération est enregistrée.<br>- L'historique contient au minimum : date, type, montant, frais, solde après opération.<br>- Pour un transfert, le numéro correspondant (expéditeur ou destinataire) est enregistré.                                                                                  |
| **Situation des comptes**   | - L'opérateur peut consulter le solde de tous les comptes.<br>- Les informations des comptes sont mises à jour après chaque opération.                                                                                                                                                                       |
| **Situation des gains**     | - Les gains de l'opérateur correspondent à la somme des frais de retrait et de transfert.<br>- Les dépôts ne génèrent aucun gain.                                                                                                                                                                            |

## Autres regles de gestion :

| Fonctionnalité | Règle                                                           |
| -------------- | --------------------------------------------------------------- |
| Montant        | Le montant d'une opération doit être strictement positif (> 0). |
| Solde          | Le solde d'un compte ne peut jamais devenir négatif.            |
| Barème         | Les tranches de montants ne doivent pas se chevaucher.          |
| Numéro         | Un même numéro ne peut exister qu'une seule fois.               |
| Transaction    | Une transaction validée ne peut plus être modifiée.             |
| Date           | Chaque transaction possède une date et une heure.               |
| Frais          | Les frais sont calculés avant la validation de l'opération.     |

## Pour la conception base : 

| Entité            | Rôle                                                           |
| ----------------- | -------------------------------------------------------------- |
| **Operateur**     | Représente l'opérateur mobile.                                 |
| **Prefixe**       | Les préfixes appartenant à un opérateur.                       |
| **Client**        | Les utilisateurs du service.                                   |
| **Compte**        | Le portefeuille mobile et son solde.                           |
| **TypeOperation** | Dépôt, retrait, transfert.                                     |
| **BaremeFrais**   | Les frais par tranche de montant pour chaque type d'opération. |
| **Transaction**   | Toutes les opérations effectuées par les clients.              |

