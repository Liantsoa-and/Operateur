<?php
// app/Views/operateur/prefixes/index.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préfixes – Opérateur</title>
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

        .table thead th { background: #f8f9fa; color: #495057; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; border-bottom: 2px solid #e9ecef; padding: .75rem 1.25rem; white-space: nowrap; }
        .table tbody td { padding: .8rem 1.25rem; font-size: 0.9rem; vertical-align: middle; border-color: #f0f4f8; }
        .table tbody tr:hover { background: #f8fbff; }

        .badge-prefixe { background: #e8f0fe; color: #1a56db; font-size: 0.85rem; font-weight: 700; padding: .3rem .7rem; border-radius: 8px; letter-spacing: .05em; font-family: monospace; }

        .btn-delete { background: none; border: none; color: #adb5bd; padding: .2rem .4rem; cursor: pointer; border-radius: 6px; transition: color .15s, background .15s; }
        .btn-delete:hover { color: #dc3545; background: #fce8e8; }
        .btn-edit { background: none; border: none; color: #adb5bd; padding: .2rem .4rem; cursor: pointer; border-radius: 6px; transition: color .15s, background .15s; }
        .btn-edit:hover { color: #0891b2; background: #e8f5ff; }
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
        <a href="/operateur/prefixes" class="active"><i class="bi bi-hash"></i> Préfixes</a>
        <a href="/operateur/types"><i class="bi bi-sliders"></i> Types & barèmes</a>
        <div class="nav-section-label">Supervision</div>
        <a href="/operateur/comptes"><i class="bi bi-people-fill"></i> Comptes clients</a>
        <a href="/operateur/gains"><i class="bi bi-graph-up-arrow"></i> Gains</a>
    </nav>
</aside>

<main class="main">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h1 class="page-title"><i class="bi bi-hash me-2"></i>Préfixes</h1>
            <div class="page-sub">Gérez les préfixes valides de l'opérateur</div>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjouter">
            <i class="bi bi-plus-lg me-1"></i> Ajouter un préfixe
        </button>
    </div>

    <!-- Alertes flash -->
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

    <!-- Tableau -->
    <div class="card-box">
        <div class="card-box-header">
            <h2><i class="bi bi-list-ul me-2"></i>Liste des préfixes</h2>
            <span class="badge bg-secondary"><?= count($prefixes) ?> préfixe(s)</span>
        </div>

        <?php if (empty($prefixes)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                Aucun préfixe configuré.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Préfixe</th>
                        <th>Opérateur</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prefixes as $p): ?>
                    <tr>
                        <td><span class="badge-prefixe"><?= esc($p['debut_numero']) ?></span></td>
                        <td><?= esc($p['nom_operateur']) ?></td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <!-- Modifier -->
                                <button class="btn-edit"
                                        title="Modifier"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalModifier"
                                        data-id="<?= $p['id'] ?>"
                                        data-debut="<?= esc($p['debut_numero']) ?>"
                                        data-operateur="<?= $p['id_operateur'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <!-- Supprimer -->
                                <form method="post" action="/operateur/prefixes/supprimer/<?= $p['id'] ?>"
                                      onsubmit="return confirm('Supprimer le préfixe <?= esc($p['debut_numero']) ?> ?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-delete" title="Supprimer">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal ajout -->
<div class="modal fade" id="modalAjouter" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Ajouter un préfixe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="/operateur/prefixes/ajouter">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Préfixe <small class="text-muted">(3 chiffres)</small></label>
                        <input type="text" name="debut_numero" class="form-control"
                               placeholder="ex: 034" maxlength="3" pattern="\d{3}"
                               required autofocus>
                        <div class="form-text">Ex : 033, 034, 037…</div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold">Opérateur</label>
                        <select name="id_operateur" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($operateurs as $op): ?>
                                <option value="<?= $op['id'] ?>"><?= esc($op['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
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

<!-- Modal modifier -->
<div class="modal fade" id="modalModifier" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier le préfixe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" id="formModifier" action="">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Préfixe <small class="text-muted">(3 chiffres)</small></label>
                        <input type="text" name="debut_numero" id="editDebut" class="form-control"
                               maxlength="3" pattern="\d{3}" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold">Opérateur</label>
                        <select name="id_operateur" id="editOperateur" class="form-select" required>
                            <?php foreach ($operateurs as $op): ?>
                                <option value="<?= $op['id'] ?>"><?= esc($op['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
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
// Pré-remplir le modal modifier
document.getElementById('modalModifier').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('formModifier').action = '/operateur/prefixes/modifier/' + btn.dataset.id;
    document.getElementById('editDebut').value      = btn.dataset.debut;
    document.getElementById('editOperateur').value  = btn.dataset.operateur;
});
</script>
<?php if (session()->getFlashdata('error')): ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new bootstrap.Modal(document.getElementById('modalAjouter')).show();
    });
</script>
<?php endif; ?>
</body>
</html>