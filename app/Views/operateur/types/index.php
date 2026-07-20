<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-sliders me-2"></i>Types & Barèmes</h1>
        <p class="text-muted">Gestion des frais par type d'opération</p>
    </div>
</div>

<?= $this->include('partials/flash_messages') ?>

<div class="row g-4">
    <?php foreach ($types as $type): 
        $key = strtolower($type['type']);
    ?>
    <div class="col-lg-4">
        <div class="card card-modern h-100">
            <div class="card-header text-white <?= $key === 'depot' ? 'bg-info' : ($key === 'retrait' ? 'bg-purple' : 'bg-warning') ?>">
                <h5 class="mb-0">
                    <i class="bi <?= $key === 'depot' ? 'bi-box-arrow-in-down' : ($key === 'retrait' ? 'bi-box-arrow-up' : 'bi-arrow-left-right') ?> me-2"></i>
                    <?= esc(ucfirst($type['type'])) ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if ($key === 'depot'): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-info-circle"></i> Aucun frais sur les dépôts.
                    </div>
                <?php elseif (empty($type['baremes'])): ?>
                    <p class="text-muted text-center py-4">Aucune tranche configurée.</p>
                <?php else: ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tranche</th>
                                <th class="text-end">Frais</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($type['baremes'] as $b): ?>
                            <tr>
                                <td><?= number_format($b['min'], 0, ',', ' ') ?> - <?= number_format($b['max'], 0, ',', ' ') ?></td>
                                <td class="text-end fw-bold"><?= number_format($b['frais'], 0, ',', ' ') ?> Ar</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white">
                <a href="<?= site_url('operateur/baremes/' . $type['id']) ?>" class="btn btn-outline-primary w-100">
                    Gérer les barèmes
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>