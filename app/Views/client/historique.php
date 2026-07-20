<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des transactions</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container { max-width: 900px; margin: 0 auto; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { color: white; font-size: 24px; }
        .nav-btn {
            background: rgba(255,255,255,.2); color: #fff; border: 1px solid rgba(255,255,255,.3);
            padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 13px;
            transition: all .3s ease;
        }
        .nav-btn:hover { background: rgba(255,255,255,.3); }

        .card {
            background: #fff; border-radius: 8px; padding: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,.2); margin-bottom: 20px;
        }

        .info-client {
            background: #f0f4ff; border-left: 4px solid #667eea; padding: 15px;
            border-radius: 4px; margin-bottom: 25px; font-size: 14px;
        }
        .info-client p { margin: 5px 0; color: #333; }
        .info-client strong { color: #667eea; }

        .solde-section { text-align: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .solde-label { font-size: 14px; color: #999; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .solde-amount { font-size: 36px; font-weight: bold; color: #667eea; }
        .solde-currency { font-size: 16px; color: #999; }

        .section-title { font-size: 16px; font-weight: 600; color: #333; margin-bottom: 16px; }

        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #f8f9fa; text-align: left; padding: 10px 12px; font-size: 0.78rem;
            text-transform: uppercase; letter-spacing: .5px; color: #666; border-bottom: 2px solid #eee;
        }
        td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; font-size: 0.88rem; }
        tr:hover td { background: #f8f9ff; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
        .badge-depot { background: #e6f9ee; color: #198754; }
        .badge-retrait { background: #fdecea; color: #e53935; }
        .badge-transfert { background: #e8f0fe; color: #1a56db; }

        .impact-pos { color: #198754; font-weight: 600; }
        .impact-neg { color: #e53935; font-weight: 600; }

        .empty-state { text-align: center; padding: 40px; color: #999; }
        .empty-state .icon { font-size: 48px; margin-bottom: 12px; opacity: .4; }

        .alert { padding: 12px 15px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background-color: #fee; color: #c33; border: 1px solid #fcc; }
        .alert-success { background-color: #efe; color: #3c3; border: 1px solid #cfc; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mobile Money</h1>
            <a href="/client/solde" class="nav-btn">← Retour au solde</a>
        </div>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-error">
                <strong>Erreur :</strong> <?= esc(session('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                <?= esc(session('success')) ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="info-client">
                <p><strong>Numéro :</strong> <?= esc($numero) ?></p>
            </div>

            <div class="solde-section">
                <div class="solde-label">Solde actuel</div>
                <div class="solde-amount"><?= number_format($solde, 0, ',', ' ') ?></div>
                <div class="solde-currency">Ariary (Ar)</div>
            </div>

            <div class="section-title">Historique des transactions</div>

            <?php if (empty($transactions)): ?>
                <div class="empty-state">
                    <div class="icon">📋</div>
                    <p>Aucune transaction pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>N° transaction</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Frais</th>
                                <th>Impact solde</th>
                                <th>Correspondant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($t['date_transaction'])) ?></td>
                                <td><code style="font-size:.8rem;"><?= esc($t['numero_transaction']) ?></code></td>
                                <td>
                                    <span class="badge badge-<?= $t['type_operation'] ?>">
                                        <?= ucfirst($t['type_operation']) ?>
                                    </span>
                                </td>
                                <td><?= number_format($t['montant'], 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</td>
                                <td>
                                    <?php if ($t['impact_solde'] >= 0): ?>
                                        <span class="impact-pos">+<?= number_format($t['impact_solde'], 0, ',', ' ') ?> Ar</span>
                                    <?php else: ?>
                                        <span class="impact-neg"><?= number_format($t['impact_solde'], 0, ',', ' ') ?> Ar</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $t['numero_correspondant'] ? esc($t['numero_correspondant']) : '—' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
