<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clock-history me-2"></i>Historique des transactions</h2>
        <a href="<?= site_url('client/solde') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour au solde
        </a>
    </div>

    <!-- Infos client -->
    <div class="row mb-4">
        <div class="col-md-6">
            <p><strong>Numéro :</strong> <?= esc($numero ?? '') ?></p>
        </div>
        <div class="col-md-6 text-md-end">
            <p><strong>Solde actuel :</strong> 
                <span class="fw-bold text-success"><?= number_format($solde ?? 0, 0, ',', ' ') ?> Ar</span>
            </p>
        </div>
    </div>

    <!-- Filtres Multi-critères -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <strong>Filtres</strong>
        </div>
        <div class="card-body">
            <form action="<?= site_url('client/historique') ?>" method="get" class="row g-3">
                <?= csrf_field() ?>

                <div class="col-md-3">
                    <label class="form-label">Date min</label>
                    <input type="date" name="date_min" value="<?= esc($filters['date_min'] ?? '') ?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date max</label>
                    <input type="date" name="date_max" value="<?= esc($filters['date_max'] ?? '') ?>" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">N° Transaction</label>
                    <input type="text" name="numero_transaction" value="<?= esc($filters['numero_transaction'] ?? '') ?>" 
                           class="form-control" placeholder="Ex: TRX123">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Type d'opération</label>
                    <select name="type_operation" class="form-select">
                        <option value="">-- Tous --</option>
                        <option value="depot"     <?= ($filters['type_operation'] ?? '') === 'depot' ? 'selected' : '' ?>>Dépôt</option>
                        <option value="retrait"   <?= ($filters['type_operation'] ?? '') === 'retrait' ? 'selected' : '' ?>>Retrait</option>
                        <option value="transfert" <?= ($filters['type_operation'] ?? '') === 'transfert' ? 'selected' : '' ?>>Transfert</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Montant min</label>
                    <input type="number" name="montant_min" value="<?= esc($filters['montant_min'] ?? '') ?>" 
                           class="form-control" step="100">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Montant max</label>
                    <input type="number" name="montant_max" value="<?= esc($filters['montant_max'] ?? '') ?>" 
                           class="form-control" step="100">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Frais min</label>
                    <input type="number" name="frais_min" value="<?= esc($filters['frais_min'] ?? '') ?>" 
                           class="form-control" step="10">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Frais max</label>
                    <input type="number" name="frais_max" value="<?= esc($filters['frais_max'] ?? '') ?>" 
                           class="form-control" step="10">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correspondant</label>
                    <input type="text" name="correspondant" value="<?= esc($filters['correspondant'] ?? '') ?>" 
                           class="form-control" placeholder="Numéro du destinataire">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">Appliquer les filtres</button>
                    <a href="<?= site_url('client/historique') ?>" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des résultats -->
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (empty($transactions)): ?>
                <p class="text-center text-muted py-5">Aucune transaction ne correspond aux critères.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
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
                            <?php foreach ($transactions as $t): ?>
                            <?php 
                                $impact = $t['impact_solde'] ?? ($t['montant'] - ($t['frais'] ?? 0)); 
                            ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($t['date_transaction'])) ?></td>
                                <td><code><?= esc($t['numero_transaction'] ?? '-') ?></code></td>
                                <td>
                                    <span class="badge bg-<?= $t['type_operation'] === 'depot' ? 'success' : ($t['type_operation'] === 'retrait' ? 'warning' : 'primary') ?>">
                                        <?= esc(ucfirst($t['type_operation'])) ?>
                                    </span>
                                </td>
                                <td class="fw-bold"><?= number_format($t['montant'], 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($t['frais'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                <td class="<?= $impact >= 0 ? 'text-success' : 'text-danger' ?> fw-medium">
                                    <?= $impact >= 0 ? '+' : '' ?><?= number_format($impact, 0, ',', ' ') ?> Ar
                                </td>
                                <td><?= esc($t['numero_correspondant'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>