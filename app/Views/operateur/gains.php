<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-graph-up-arrow me-2"></i>Situation des gains</h1>
        <p class="text-muted">Frais perçus sur les retraits et transferts</p>
    </div>
</div>

<!-- Statistiques générales -->
<p class="section-title">Vue d'ensemble</p>
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3">
                        <i class="bi bi-arrow-up-right fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0" id="card-retrait">
                            <?= number_format($retrait_total ?? 0, 0, ',', ' ') ?>
                        </h3>
                        <p class="text-muted mb-0">Gains retraits</p>
                        <small class="text-muted" id="card-retrait-sub">
                            <?= ($retrait_nb ?? 0) ?> transaction(s)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                        <i class="bi bi-arrow-left-right fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0" id="card-transfert">
                            <?= number_format($transfert_total ?? 0, 0, ',', ' ') ?>
                        </h3>
                        <p class="text-muted mb-0">Gains transferts</p>
                        <small class="text-muted" id="card-transfert-sub">
                            <?= ($transfert_nb ?? 0) ?> transaction(s)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern h-100 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-3">
                        <i class="bi bi-cash-coin fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0" id="card-total">
                            <?= number_format($total_gains ?? 0, 0, ',', ' ') ?>
                        </h3>
                        <p class="text-muted mb-0">Total des gains</p>
                        <small class="text-muted" id="card-total-sub">
                            <?= (($retrait_nb ?? 0) + ($transfert_nb ?? 0)) ?> transaction(s)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<p class="section-title">Filtres</p>
<div class="card card-modern mb-4">
    <div class="card-body">
        <form id="filtreForm" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select id="type" name="type" class="form-select">
                    <option value="">Tous</option>
                    <option value="retrait">Retrait</option>
                    <option value="transfert">Transfert</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date début</label>
                <input type="date" id="date_debut" name="date_debut" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date fin</label>
                <input type="date" id="date_fin" name="date_fin" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">N° Client</label>
                <input type="text" id="client" name="client" class="form-control" placeholder="034...">
            </div>
        </form>
    </div>
</div>

<!-- Tableau -->
<p class="section-title">Historique des transactions</p>
<div class="card card-modern">
    <div class="card-header d-flex justify-content-between bg-white">
        <h5 class="mb-0">Liste des transactions</h5>
        <span id="rowCount" class="text-muted"><?= count($historique ?? []) ?> ligne(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="gainsTable">
                <thead class="table-light">
                    <tr>
                        <th>N° Transaction</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Client</th>
                        <th class="text-end">Montant</th>
                        <th class="text-end">Frais</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($historique)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Aucune transaction enregistrée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($historique as $t): ?>
                            <?= renderRow($t) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
function renderRow(array $t): string {
    $date = date('d/m/Y H:i', strtotime($t['date_transaction'] ?? ''));
    $type = esc($t['type_operation'] ?? '');
    $montant = number_format((float)($t['montant'] ?? 0), 0, ',', ' ');
    $frais   = number_format((float)($t['frais'] ?? 0), 0, ',', ' ');

    return <<<HTML
    <tr>
        <td><code>{$t['numero_transaction']}</code></td>
        <td>{$date}</td>
        <td><span class="badge bg-{$type}">{$type}</span></td>
        <td><i class="bi bi-phone me-1"></i>{$t['numero_client']}</td>
        <td class="text-end">{$montant} Ar</td>
        <td class="text-end fw-bold text-success">{$frais} Ar</td>
    </tr>
HTML;
}
?>

<script>
// Filtrage simple (à améliorer avec AJAX plus tard)
document.getElementById('filtreForm').addEventListener('submit', function(e) {
    // Pour l'instant on laisse le formulaire classique
});
</script>

<?= $this->endSection() ?>