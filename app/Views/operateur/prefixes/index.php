<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-hash me-2"></i>Préfixes</h1>
        <p class="text-muted">Gérez les préfixes valides de l'opérateur et des opérateurs tiers</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjouter">
        <i class="bi bi-plus-lg me-1"></i>Ajouter un préfixe
    </button>
</div>

<?= $this->include('partials/flash_messages') ?>

<?php
    // Séparation notre opérateur (id = 1) / opérateurs tiers
    $prefixesNous   = array_filter($prefixes ?? [], fn($p) => (int) $p['id_operateur'] === 1);
    $prefixesAutres = array_filter($prefixes ?? [], fn($p) => (int) $p['id_operateur'] !== 1);
?>

<?php
    // Petite fonction locale d'affichage de tableau pour éviter la duplication
    function render_prefixe_table(array $liste, string $badgeClass): void {
?>
    <?php if (empty($liste)): ?>
        <div class="text-center py-5 text-muted">Aucun préfixe configuré.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Préfixe</th>
                        <th>Opérateur</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($liste as $p): ?>
                    <tr>
                        <td><span class="badge <?= $badgeClass ?> fs-6"><?= esc($p['debut_numero']) ?></span></td>
                        <td><?= esc($p['nom_operateur'] ?? '—') ?></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-1 btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalModifier"
                                    data-id="<?= $p['id'] ?>"
                                    data-debut="<?= esc($p['debut_numero']) ?>"
                                    data-operateur="<?= $p['id_operateur'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="post" action="/operateur/prefixes/supprimer/<?= $p['id'] ?>" class="d-inline"
                                  onsubmit="return confirm('Supprimer ce préfixe ?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php
    }
?>

<div class="card card-modern mb-4">
    <div class="card-header d-flex justify-content-between bg-white">
        <h5 class="mb-0"><i class="bi bi-star-fill text-primary me-1"></i>Nos préfixes</h5>
        <span class="badge bg-primary"><?= count($prefixesNous) ?> préfixe(s)</span>
    </div>
    <div class="card-body p-0">
        <?php render_prefixe_table($prefixesNous, 'bg-primary'); ?>
    </div>
</div>

<div class="card card-modern">
    <div class="card-header d-flex justify-content-between bg-white">
        <h5 class="mb-0"><i class="bi bi-diagram-3 text-secondary me-1"></i>Préfixes des autres opérateurs</h5>
        <span class="badge bg-secondary"><?= count($prefixesAutres) ?> préfixe(s)</span>
    </div>
    <div class="card-body p-0">
        <?php render_prefixe_table($prefixesAutres, 'bg-secondary'); ?>
    </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouter" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/operateur/prefixes/ajouter">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un préfixe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Préfixe (3 chiffres)</label>
                        <input type="text" name="debut_numero" class="form-control" maxlength="3" pattern="\d{3}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opérateur</label>
                        <select name="id_operateur" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($operateurs as $op): ?>
                                <option value="<?= $op['id'] ?>">
                                    <?= esc($op['nom']) ?><?= (int) $op['id'] === 1 ? ' (nous)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifier" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formModifier" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le préfixe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Préfixe</label>
                        <input type="text" id="editDebut" name="debut_numero" class="form-control" maxlength="3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opérateur</label>
                        <select id="editOperateur" name="id_operateur" class="form-select" required>
                            <?php foreach ($operateurs as $op): ?>
                                <option value="<?= $op['id'] ?>">
                                    <?= esc($op['nom']) ?><?= (int) $op['id'] === 1 ? ' (nous)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('modalModifier').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('formModifier').action = '/operateur/prefixes/modifier/' + btn.dataset.id;
    document.getElementById('editDebut').value = btn.dataset.debut;
    document.getElementById('editOperateur').value = btn.dataset.operateur;
});
</script>

<?= $this->endSection() ?>