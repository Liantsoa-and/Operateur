<?= $this->extend('layouts/operateur') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 mb-1">
            <i class="bi bi-sliders me-2"></i>Barèmes — <?= esc(ucfirst($type['type'] ?? '')) ?>
        </h1>
        <a href="<?= site_url('operateur/types') ?>" class="text-decoration-none">
            ← Retour aux types
        </a>
    </div>
    
    <?php if (strtolower($type['type'] ?? '') !== 'depot'): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjouter">
        <i class="bi bi-plus-lg me-1"></i> Ajouter une tranche
    </button>
    <?php endif; ?>
</div>

<?= $this->include('partials/flash_messages') ?>

<?php if (strtolower($type['type'] ?? '') === 'depot'): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Le dépôt ne génère aucun frais. Aucune tranche n'est nécessaire.
    </div>
<?php else: ?>

<div class="card card-modern">
    <div class="card-header bg-white d-flex justify-content-between">
        <h5>Tranches de frais</h5>
        <span class="badge bg-secondary"><?= count($baremes ?? []) ?> tranche(s)</span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($baremes)): ?>
            <div class="text-center py-5 text-muted">
                Aucune tranche configurée pour ce type d'opération.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Description</th>
                        <th>Min (Ar)</th>
                        <th>Max (Ar)</th>
                        <th class="text-end">Frais (Ar)</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($baremes as $b): ?>
                    <tr>
                        <td><?= esc($b['description'] ?? '—') ?></td>
                        <td><?= number_format($b['min'], 0, ',', ' ') ?></td>
                        <td><?= number_format($b['max'], 0, ',', ' ') ?></td>
                        <td class="text-end fw-bold"><?= number_format($b['frais'], 0, ',', ' ') ?> Ar</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalModifier"
                                    data-id="<?= $b['id'] ?>"
                                    data-description="<?= esc($b['description'] ?? '') ?>"
                                    data-min="<?= $b['min'] ?>"
                                    data-max="<?= $b['max'] ?>"
                                    data-frais="<?= $b['frais'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="post" action="/operateur/baremes/supprimer/<?= $b['id'] ?>" class="d-inline"
                                  onsubmit="return confirm('Supprimer cette tranche ?')">
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
    </div>
</div>

<?php endif; ?>

<!-- ====================== MODAL AJOUT ====================== -->
<div class="modal fade" id="modalAjouter" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="/operateur/baremes/ajouter">
                <?= csrf_field() ?>
                <input type="hidden" name="id_type_operation" value="<?= $type['id'] ?? '' ?>">
                
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une tranche</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" 
                               placeholder="Ex: Entre 10 000 et 50 000 Ar">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Montant Minimum (Ar)</label>
                            <input type="number" name="min" class="form-control" min="0" step="100" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Montant Maximum (Ar)</label>
                            <input type="number" name="max" class="form-control" min="0" step="100" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Frais (Ar)</label>
                        <input type="number" name="frais" class="form-control" min="0" step="10" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter la tranche</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ====================== MODAL MODIFIER ====================== -->
<div class="modal fade" id="modalModifier" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formModifier" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la tranche</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" id="editDescription" name="description" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Montant Minimum</label>
                            <input type="number" id="editMin" name="min" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Montant Maximum</label>
                            <input type="number" id="editMax" name="max" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Frais (Ar)</label>
                        <input type="number" id="editFrais" name="frais" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Remplissage du modal Modifier
document.getElementById('modalModifier').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('formModifier').action = '/operateur/baremes/modifier/' + btn.dataset.id;
    
    document.getElementById('editDescription').value = btn.dataset.description;
    document.getElementById('editMin').value         = btn.dataset.min;
    document.getElementById('editMax').value         = btn.dataset.max;
    document.getElementById('editFrais').value       = btn.dataset.frais;
});
</script>

<?= $this->endSection() ?>