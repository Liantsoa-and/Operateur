<?php
// app/Views/operateur/baremes/index.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barèmes – <?= esc($type['type']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }

        .sidebar {
            width: 240px; min-height: 100vh; background: #1a2e44;
            position: fixed; top: 0; left: 0; display: flex; flex-direction: column; z-index: 100;
        }
        .sidebar-brand { padding: 1.5rem 1.25rem 1rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-brand .brand-title { font-size: 1rem; font-weight: 700; color: #fff; letter-spacing: .04em; }
        .sidebar-brand .brand-sub { font-size: 0.72rem; color: rgba(255,255,255,.45); margin-top: 2px; }
        .sidebar-nav { padding: 1rem 0; flex: 1; }
        .nav-section-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,.35); padding: .75rem 1.25rem .25rem; }
        .sidebar-nav a { display: flex; align-items: center; gap: .65rem; padding: .6rem 1.25rem; color: rgba(255,255,255,.7); text-decoration: none; font-size: 0.88rem; transition: background .15s, color .15s; border-left: 3px solid transparent; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(255,255,255,.07); color: #fff; border-left-color: #4e9af1; }
        .sidebar-nav a i { font-size: 1rem; width: 18px; text-align: center; }

        .main { margin-left: 240px; padding: 2rem; }

        .page-title { font-size: 1.4rem; font-weight: 700; color: #1a2e44; margin: 0; }
        .page-sub { font-size: 0.85rem; color: #6c757d; margin-top: 2px; }

        .card-box { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); overflow: hidden; }
        .card-box-header { padding: 1rem 1.5rem; border-bottom: 1px solid #e9ecef; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; }
        .card-box-header h2 { font-size: 1rem; font-weight: 600; color: #1a2e44; margin: 0; }

        .table thead th { background: #f8f9fa; color: #495057; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; border-bottom: 2px solid #e9ecef; padding: .75rem 1.25rem; }
        .table tbody td { padding: .8rem 1.25rem; font-size: 0.9rem; vertical-align: middle; border-color: #f0f4f8; }
        .table tbody tr:hover { background: #f8fbff; }

        .frais-bold { font-weight: 700; color: #1a2e44; }

        .btn-sm-icon { background: none; border: none; padding: .25rem .45rem; cursor: pointer; border-radius: 6px; transition: color .15s, background .15s; font-size: .95rem; }
        .btn-edit { color: #adb5bd; }
        .btn-edit:hover { color: #0891b2; background: #e8f5ff; }
        .btn-del { color: #adb5bd; }
        .btn-del:hover { color: #dc3545; background: #fce8e8; }

        .depot-note { background: #e8f9ee; border-left: 3px solid #198754; padding: .75rem 1rem; border-radius: 0 6px 6px 0; font-size: 0.85rem; color: #155724; margin: 1.25rem; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-title"><i class="bi bi-phone-fill me-2"></i>Mobile Money</div>
        <div class="brand-sub">Espace opérateur</div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Général</div>
        <a href="/operateur"><i class="bi bi-grid-1x2-fill"></i> Tableau de bord</a>
        <div class="nav-section-label">Configuration</div>
        <a href="/operateur/prefixes"><i class="bi bi-hash"></i> Préfixes</a>
        <a href="/operateur/types" class="active"><i class="bi bi-sliders"></i> Types & barèmes</a>
        <div class="nav-section-label">Supervision</div>
        <a href="/operateur/comptes"><i class="bi bi-people-fill"></i> Comptes clients</a>
        <a href="/operateur/gains"><i class="bi bi-graph-up-arrow"></i> Gains</a>
    </nav>
</aside>

<main class="main">

    <!-- En-tête -->
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h1 class="page-title">
                <i class="bi bi-sliders me-2"></i>Barèmes — <?= esc(ucfirst($type['type'])) ?>
            </h1>
            <div class="page-sub">
                <a href="/operateur/types" class="text-decoration-none text-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Retour aux types
                </a>
            </div>
        </div>
        <?php if (strtolower($type['type']) !== 'depot'): ?>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjouter">
            <i class="bi bi-plus-lg me-1"></i> Ajouter une tranche
        </button>
        <?php endif; ?>
    </div>

    <!-- Alertes -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Cas dépôt : pas de frais -->
    <?php if (strtolower($type['type']) === 'depot'): ?>
        <div class="card-box">
            <div class="depot-note">
                <i class="bi bi-info-circle me-2"></i>
                Le dépôt ne génère aucun frais. Aucune tranche n'est applicable.
            </div>
        </div>

    <?php else: ?>

    <!-- Tableau des tranches -->
    <div class="card-box">
        <div class="card-box-header">
            <h2><i class="bi bi-table me-2"></i>Tranches de frais</h2>
            <span class="badge bg-secondary"><?= count($baremes) ?> tranche(s)</span>
        </div>

        <?php if (empty($baremes)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                Aucune tranche configurée. Ajoutez-en une.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
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
                        <td class="text-end"><span class="frais-bold"><?= number_format($b['frais'], 0, ',', ' ') ?></span></td>
                        <td class="text-end">
                            <!-- Modifier -->
                            <button class="btn-sm-icon btn-edit"
                                    title="Modifier"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalModifier"
                                    data-id="<?= $b['id'] ?>"
                                    data-description="<?= esc($b['description'] ?? '') ?>"
                                    data-min="<?= $b['min'] ?>"
                                    data-max="<?= $b['max'] ?>"
                                    data-frais="<?= $b['frais'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <!-- Supprimer -->
                            <form method="post" action="/operateur/baremes/supprimer/<?= $b['id'] ?>"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer cette tranche ?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-sm-icon btn-del" title="Supprimer">
                                    <i class="bi bi-trash3"></i>
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
    <?php endif; ?>

</main>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouter" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Ajouter une tranche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="/operateur/baremes/ajouter">
                <?= csrf_field() ?>
                <input type="hidden" name="id_type_operation" value="<?= $type['id'] ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <input type="text" name="description" class="form-control"
                               placeholder="ex: Montant compris entre 100 et 1 000 Ar">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Montant min (Ar)</label>
                            <input type="number" name="min" class="form-control" min="1" step="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Montant max (Ar)</label>
                            <input type="number" name="max" class="form-control" min="1" step="1" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label fw-semibold">Frais (Ar)</label>
                        <input type="number" name="frais" class="form-control" min="0" step="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifier" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier la tranche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" id="formModifier" action="">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <input type="text" name="description" id="editDescription" class="form-control">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Montant min (Ar)</label>
                            <input type="number" name="min" id="editMin" class="form-control" min="1" step="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Montant max (Ar)</label>
                            <input type="number" name="max" id="editMax" class="form-control" min="1" step="1" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label fw-semibold">Frais (Ar)</label>
                        <input type="number" name="frais" id="editFrais" class="form-control" min="0" step="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Pré-remplir le modal modifier avec les données de la ligne
document.getElementById('modalModifier').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('formModifier').action = '/operateur/baremes/modifier/' + btn.dataset.id;
    document.getElementById('editDescription').value = btn.dataset.description;
    document.getElementById('editMin').value   = btn.dataset.min;
    document.getElementById('editMax').value   = btn.dataset.max;
    document.getElementById('editFrais').value = btn.dataset.frais;
});

// Rouvrir modal ajouter si erreur
<?php if (session()->getFlashdata('error')): ?>
document.addEventListener('DOMContentLoaded', () => {
    new bootstrap.Modal(document.getElementById('modalAjouter')).show();
});
<?php endif; ?>
</script>
</body>
</html>