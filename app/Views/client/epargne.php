<?php var_dump($taux_epargne); ?>
<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<p class="section-title">Epargne inter-opérateur</p>
<div class="row g-4 mb-5">
    <div class="col-md-6 col-lg-4">
        <div class="card card-modern h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                        <i class="bi bi-percent fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= number_format($taux_epargne ?? 0, 2, ',', ' ') ?> %</h3>
                        <p class="text-muted mb-0">Taux actuel</p>
                    </div>
                </div>
                <?= $this->include('partials/flash_messages') ?>
                <form method="post" action="<?= site_url('client/epargne') ?>" class="d-flex gap-2">
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
