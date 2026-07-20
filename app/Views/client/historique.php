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

    <!-- Formulaire de filtres multi-critères -->
    <form action="/client/historique" method="get">
        <fieldset>
            <legend>Filtres</legend>

            <p>
                <label>Date min : </label>
                <input type="date" name="date_min" value="<?= esc($filters['date_min'] ?? '') ?>">
                <label>Date max : </label>
                <input type="date" name="date_max" value="<?= esc($filters['date_max'] ?? '') ?>">
            </p>

            <p>
                <label>N° transaction : </label>
                <input type="text" name="numero_transaction" value="<?= esc($filters['numero_transaction'] ?? '') ?>">
            </p>

            <p>
                <label>Type d'opération : </label>
                <select name="type_operation">
                    <option value="">-- Tous --</option>
                    <option value="depot"     <?= ($filters['type_operation'] ?? '') === 'depot'     ? 'selected' : '' ?>>Dépôt</option>
                    <option value="retrait"   <?= ($filters['type_operation'] ?? '') === 'retrait'   ? 'selected' : '' ?>>Retrait</option>
                    <option value="transfert" <?= ($filters['type_operation'] ?? '') === 'transfert' ? 'selected' : '' ?>>Transfert</option>
                </select>
            </p>

            <p>
                <label>Montant min : </label>
                <input type="number" step="0.01" name="montant_min" value="<?= esc($filters['montant_min'] ?? '') ?>">
                <label>Montant max : </label>
                <input type="number" step="0.01" name="montant_max" value="<?= esc($filters['montant_max'] ?? '') ?>">
            </p>

            <p>
                <label>Frais min : </label>
                <input type="number" step="0.01" name="frais_min" value="<?= esc($filters['frais_min'] ?? '') ?>">
                <label>Frais max : </label>
                <input type="number" step="0.01" name="frais_max" value="<?= esc($filters['frais_max'] ?? '') ?>">
            </p>

            <p>
                <label>Correspondant (numéro) : </label>
                <input type="text" name="correspondant" value="<?= esc($filters['correspondant'] ?? '') ?>">
            </p>

            <button type="submit">Filtrer</button>
            <a href="/client/historique">Réinitialiser</a>
        </fieldset>
    </form>

    <?php if (empty($transactions)): ?>
        <p>Aucune transaction ne correspond aux critères.</p>
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
