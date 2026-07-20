<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-clock-history me-2"></i>Historique des transactions</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>N° Transaction</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Frais</th>
                            <th>Impact Solde</th>
                            <th>Correspondant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    Aucune transaction pour le moment.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $t): ?>
                            <?php 
                                $montant = (float)($t['montant'] ?? 0);
                                $frais   = (float)($t['frais'] ?? 0);
                                $impact  = $t['impact'] ?? ($t['type_operation'] === 'depot' ? $montant : -$montant - $frais);
                            ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($t['date_transaction'] ?? '')) ?></td>
                                <td><code><?= esc($t['numero_transaction'] ?? '-') ?></code></td>
                                <td>
                                    <span class="badge bg-<?= $t['type_operation'] === 'retrait' ? 'warning' : ($t['type_operation'] === 'transfert' ? 'primary' : 'success') ?>">
                                        <?= esc(ucfirst($t['type_operation'] ?? '')) ?>
                                    </span>
                                </td>
                                <td class="fw-bold"><?= number_format($montant, 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($frais, 0, ',', ' ') ?> Ar</td>
                                <td class="<?= $impact >= 0 ? 'text-success' : 'text-danger' ?> fw-medium">
                                    <?= $impact >= 0 ? '+' : '' ?><?= number_format($impact, 0, ',', ' ') ?> Ar
                                </td>
                                <td><?= esc($t['correspondant'] ?? $t['numero_destinataire'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>