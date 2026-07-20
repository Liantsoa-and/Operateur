<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 mb-1">Tableau de bord</h1>
        <p class="text-muted"><?= date('l d F Y') ?> — Vue Opérateur</p>
    </div>
</div>

<!-- Vue d'ensemble -->
<p class="section-title">Vue d'ensemble</p>
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= esc($nb_clients ?? 0) ?></h3>
                        <p class="text-muted mb-0">Clients enregistrés</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="card card-modern h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                        <i class="bi bi-cash-coin fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= number_format($total_gains ?? 0, 0, ',', ' ') ?> Ar</h3>
                        <p class="text-muted mb-0">Gains totaux</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accès rapide -->
<p class="section-title">Accès rapide</p>
<div class="row g-4">
    <div class="col-md-6 col-lg-3">
        <a href="<?= site_url('operateur/prefixes') ?>" class="text-decoration-none">
            <div class="card card-modern h-100 border-primary border-opacity-25">
                <div class="card-body text-center">
                    <i class="bi bi-hash display-5 text-primary mb-3"></i>
                    <h5>Préfixes</h5>
                    <p class="text-muted small">Gérer les numéros valides</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-3">
        <a href="<?= site_url('operateur/types') ?>" class="text-decoration-none">
            <div class="card card-modern h-100 border-info border-opacity-25">
                <div class="card-body text-center">
                    <i class="bi bi-sliders display-5 text-info mb-3"></i>
                    <h5>Types & Barèmes</h5>
                    <p class="text-muted small">Configurer les frais</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-3">
        <a href="<?= site_url('operateur/comptes') ?>" class="text-decoration-none">
            <div class="card card-modern h-100 border-success border-opacity-25">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill display-5 text-success mb-3"></i>
                    <h5>Comptes Clients</h5>
                    <p class="text-muted small">Voir les soldes</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 col-lg-3">
        <a href="<?= site_url('operateur/gains') ?>" class="text-decoration-none">
            <div class="card card-modern h-100 border-warning border-opacity-25">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up-arrow display-5 text-warning mb-3"></i>
                    <h5>Gains</h5>
                    <p class="text-muted small">Suivi des revenus</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Commission inter-opérateur -->
<p class="section-title">Commission inter-opérateur</p>
<div class="row g-4 mb-5">
    <div class="col-md-6 col-lg-4">
        <div class="card card-modern h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                        <i class="bi bi-percent fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= number_format($commission_actuelle ?? 0, 2, ',', ' ') ?> %</h3>
                        <p class="text-muted mb-0">Taux actuel</p>
                    </div>
                </div>
                <?= $this->include('partials/flash_messages') ?>
                <form method="post" action="<?= site_url('operateur/commission') ?>" class="d-flex gap-2">
                    <?= csrf_field() ?>
                    <input type="number" step="0.01" min="0" max="100" name="commission_inter"
                           class="form-control" placeholder="Nouveau %" required>
                    <button type="submit" class="btn btn-warning text-white">Modifier</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>