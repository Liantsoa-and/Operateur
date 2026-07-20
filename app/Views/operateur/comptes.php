<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-people-fill me-2"></i>Situation des comptes clients</h1>
        <p class="text-muted">Consultation des soldes et des comptes</p>
    </div>
</div>

<!-- Messages flash -->
<?= $this->include('partials/flash_messages') ?>

<!-- Statistiques -->
<?php
    $nbClients   = count($comptes ?? []);
    $totalSoldes = array_sum(array_column($comptes ?? [], 'solde'));
    $nbActifs    = count(array_filter($comptes ?? [], fn($c) => ($c['solde'] ?? 0) > 0));
?>

<p class="section-title">Vue d'ensemble</p>
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= $nbClients ?></h3>
                        <p class="text-muted mb-0">Total clients</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-3">
                        <i class="bi bi-person-check-fill fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= $nbActifs ?></h3>
                        <p class="text-muted mb-0">Comptes avec solde</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= number_format($totalSoldes, 0, ',', ' ') ?> Ar</h3>
                        <p class="text-muted mb-0">Solde total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-modern h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3">
                        <i class="bi bi-person-x-fill fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= $nbClients - $nbActifs ?></h3>
                        <p class="text-muted mb-0">Comptes à zéro</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des comptes -->
<p class="section-title">Liste des comptes</p>
<div class="card card-modern">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <h5 class="mb-0">Comptes clients</h5>
        <input type="text" id="searchInput" class="form-control w-25" 
               placeholder="Rechercher un numéro..." onkeyup="filterTable()">
    </div>
    <div class="card-body p-0">
        <?php if (empty($comptes)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox display-4 mb-3"></i>
                <p>Aucun compte client enregistré.</p>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="comptesTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Numéro de téléphone</th>
                        <th>Préfixe</th>
                        <th>Opérateur</th>
                        <th class="text-end">Solde (Ar)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comptes as $i => $compte): ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td>
                            <i class="bi bi-phone me-2 text-secondary"></i>
                            <strong><?= esc($compte['numero']) ?></strong>
                        </td>
                        <td><span class="badge bg-primary"><?= esc($compte['prefixe'] ?? '') ?></span></td>
                        <td><?= esc($compte['operateur'] ?? '—') ?></td>
                        <td class="text-end fw-bold">
                            <?= number_format((float)($compte['solde'] ?? 0), 0, ',', ' ') ?> Ar
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#comptesTable tbody tr');
    
    rows.forEach(row => {
        const numero = row.cells[1].textContent.toLowerCase();
        row.style.display = numero.includes(input) ? '' : 'none';
    });
}
</script>

<?= $this->endSection() ?>