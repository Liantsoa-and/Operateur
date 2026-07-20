<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des transactions</title>
</head>
<body>
    <h1>Historique des transactions</h1>

    <p><strong>Numéro :</strong> <?= esc($numero) ?></p>
    <p><strong>Solde actuel :</strong> <?= number_format($solde, 0, ',', ' ') ?> Ar</p>

    <p>
        <a href="/client/solde">Retour au solde</a>
    </p>

    <?php if (session()->has('error')): ?>
        <p><strong>Erreur :</strong> <?= esc(session('error')) ?></p>
    <?php endif; ?>

    <?php if (session()->has('success')): ?>
        <p><strong>Succès :</strong> <?= esc(session('success')) ?></p>
    <?php endif; ?>

    <?php if (empty($transactions)): ?>
        <p>Aucune transaction pour le moment.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>N° transaction</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Frais</th>
                    <th>Impact sur le solde</th>
                    <th>Correspondant</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= esc($t['date_transaction']) ?></td>
                        <td><?= esc($t['numero_transaction']) ?></td>
                        <td><?= esc(ucfirst($t['type_operation'])) ?></td>
                        <td><?= number_format($t['montant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</td>
                        <td>
                            <?php if ($t['impact_solde'] >= 0): ?>
                                +<?= number_format($t['impact_solde'], 0, ',', ' ') ?> Ar
                            <?php else: ?>
                                <?= number_format($t['impact_solde'], 0, ',', ' ') ?> Ar
                            <?php endif; ?>
                        </td>
                        <td><?= $t['numero_correspondant'] ? esc($t['numero_correspondant']) : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
