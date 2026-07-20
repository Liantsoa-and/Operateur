<?php
$retrait_total  = 0;
$retrait_nb     = 0;
$transfert_total = 0;
$transfert_nb   = 0;

foreach ($gains as $g) {
    if ($g['type_operation'] === 'retrait') {
        $retrait_total = (float) $g['total_frais'];
        $retrait_nb    = (int)   $g['nb_transactions'];
    } elseif ($g['type_operation'] === 'transfert') {
        $transfert_total = (float) $g['total_frais'];
        $transfert_nb    = (int)   $g['nb_transactions'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des gains</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #333; }

        .header { background: #1a73e8; color: #fff; padding: 20px 30px; display: flex; align-items: center; gap: 20px; }
        .header h1 { font-size: 1.4rem; font-weight: 600; }
        .header a { color: rgba(255,255,255,.8); text-decoration: none; font-size: .85rem; }
        .header a:hover { color: #fff; }

        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }

        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .card { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        .card-label { font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; color: #888; margin-bottom: 8px; }
        .card-value { font-size: 1.8rem; font-weight: 700; }
        .card-sub { font-size: .8rem; color: #999; margin-top: 4px; }
        .card--retrait   .card-value { color: #e53935; }
        .card--transfert .card-value { color: #1a73e8; }
        .card--total     .card-value { color: #2e7d32; }

        .section-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 16px; }

        .table-wrapper { background: #fff; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.08); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #fafafa; text-align: left; padding: 12px 16px; font-size: .78rem;
             text-transform: uppercase; letter-spacing: .5px; color: #666; border-bottom: 1px solid #eee; }
        td { padding: 12px 16px; border-bottom: 1px solid #f5f5f5; font-size: .9rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8f9fa; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: .75rem; font-weight: 600; }
        .badge--retrait   { background: #fdecea; color: #e53935; }
        .badge--transfert { background: #e8f0fe; color: #1a73e8; }

        .text-right { text-align: right; }
        .empty-msg { padding: 40px; text-align: center; color: #aaa; }
    </style>
</head>
<body>

<div class="header">
    <h1>Situation des gains</h1>
    <a href="/operateur">← Retour au tableau de bord</a>
</div>

<div class="container">

    <!-- Cartes récapitulatives -->
    <div class="cards">
        <div class="card card--retrait">
            <div class="card-label">Total retraits</div>
            <div class="card-value"><?= number_format($retrait_total, 2, ',', ' ') ?> Ar</div>
            <div class="card-sub"><?= $retrait_nb ?> transaction<?= $retrait_nb > 1 ? 's' : '' ?></div>
        </div>
        <div class="card card--transfert">
            <div class="card-label">Total transferts</div>
            <div class="card-value"><?= number_format($transfert_total, 2, ',', ' ') ?> Ar</div>
            <div class="card-sub"><?= $transfert_nb ?> transaction<?= $transfert_nb > 1 ? 's' : '' ?></div>
        </div>
        <div class="card card--total">
            <div class="card-label">Total général</div>
            <div class="card-value"><?= number_format($total_gains, 2, ',', ' ') ?> Ar</div>
            <div class="card-sub"><?= $retrait_nb + $transfert_nb ?> transaction<?= ($retrait_nb + $transfert_nb) > 1 ? 's' : '' ?> au total</div>
        </div>
    </div>

    <!-- Historique -->
    <div class="section-title">Historique des transactions</div>

    <?php if (empty($historique)): ?>
        <div class="table-wrapper">
            <div class="empty-msg">Aucune transaction enregistrée.</div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>N° Transaction</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th class="text-right">Frais</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($historique as $t): ?>
                    <tr>
                        <td><?= esc($t['numero_transaction']) ?></td>
                        <td><?= esc(date('d/m/Y H:i', strtotime($t['date_transaction']))) ?></td>
                        <td>
                            <span class="badge badge--<?= esc($t['type_operation']) ?>">
                                <?= esc(ucfirst($t['type_operation'])) ?>
                            </span>
                        </td>
                        <td><?= esc($t['numero_client']) ?></td>
                        <td><?= number_format((float)$t['montant'], 2, ',', ' ') ?> Ar</td>
                        <td class="text-right"><?= number_format((float)$t['frais'], 2, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
</body>
</html>