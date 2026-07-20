<?php
// app/Views/operateur/types/index.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Types d'opérations – Opérateur</title>
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

        /* Type cards */
        .type-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); overflow: hidden; }
        .type-card-header { padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
        .type-card-header .type-name { font-size: 1rem; font-weight: 700; color: #fff; margin: 0; }
        .type-card-header .nb-tranches { font-size: 0.75rem; color: rgba(255,255,255,.7); }

        .type-depot  .type-card-header { background: #0891b2; }
        .type-retrait .type-card-header { background: #7c3aed; }
        .type-transfert .type-card-header { background: #d97706; }

        .type-card .table thead th { background: #f8f9fa; color: #495057; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; border-bottom: 2px solid #e9ecef; padding: .6rem 1rem; }
        .type-card .table tbody td { padding: .65rem 1rem; font-size: 0.88rem; vertical-align: middle; border-color: #f0f4f8; }
        .type-card .table tbody tr:hover { background: #f8fbff; }

        .frais-badge { font-weight: 700; color: #1a2e44; }
        .no-frais { color: #adb5bd; font-style: italic; font-size: 0.82rem; }

        .btn-sm-icon { background: none; border: none; padding: .2rem .4rem; cursor: pointer; border-radius: 6px; transition: color .15s, background .15s; }
        .btn-edit:hover { color: #0891b2; background: #e8f5ff; }
        .btn-del:hover { color: #dc3545; background: #fce8e8; }
        .btn-edit { color: #adb5bd; }
        .btn-del { color: #adb5bd; }

        .depot-note { background: #e8f9ee; border-left: 3px solid #198754; padding: .6rem .9rem; border-radius: 0 6px 6px 0; font-size: 0.82rem; color: #155724; }
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
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h1 class="page-title"><i class="bi bi-sliders me-2"></i>Types & barèmes</h1>
            <div class="page-sub">Consultez les barèmes de frais — modifiez-les depuis la page de chaque type</div>
        </div>
    </div>

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

    <?php
    // Mapping nom → classe CSS et icône
    $typeStyle = [
        'depot'     => ['class' => 'type-depot',     'icon' => 'bi-box-arrow-in-down',  'label' => 'Dépôt'],
        'retrait'   => ['class' => 'type-retrait',   'icon' => 'bi-box-arrow-up',        'label' => 'Retrait'],
        'transfert' => ['class' => 'type-transfert', 'icon' => 'bi-arrow-left-right',    'label' => 'Transfert'],
    ];
    ?>

    <div class="row g-3">
        <?php foreach ($types as $type):
            $key   = strtolower($type['type']);
            $style = $typeStyle[$key] ?? ['class' => '', 'icon' => 'bi-circle', 'label' => $type['type']];
            $baremes = $type['baremes'] ?? [];
        ?>
        <div class="col-12 col-xl-4">
            <div class="type-card <?= $style['class'] ?>">
                <div class="type-card-header">
                    <div>
                        <div class="type-name"><i class="bi <?= $style['icon'] ?> me-2"></i><?= esc($style['label']) ?></div>
                        <div class="nb-tranches"><?= count($baremes) ?> tranche(s) configurée(s)</div>
                    </div>
                    <a href="/operateur/baremes/<?= $type['id'] ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-pencil me-1"></i> Gérer
                    </a>
                </div>

                <?php if ($key === 'depot'): ?>
                    <div class="p-3">
                        <div class="depot-note">
                            <i class="bi bi-info-circle me-1"></i>
                            Aucun frais appliqué sur les dépôts.
                        </div>
                    </div>
                <?php elseif (empty($baremes)): ?>
                    <div class="text-center py-4 text-muted" style="font-size:.85rem;">
                        <i class="bi bi-inbox d-block mb-1" style="font-size:1.5rem;"></i>
                        Aucune tranche configurée.
                    </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Tranche (Ar)</th>
                                <th class="text-end">Frais (Ar)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($baremes as $b): ?>
                            <tr>
                                <td>
                                    <?= number_format($b['min'], 0, ',', ' ') ?>
                                    – <?= number_format($b['max'], 0, ',', ' ') ?>
                                </td>
                                <td class="text-end">
                                    <span class="frais-badge"><?= number_format($b['frais'], 0, ',', ' ') ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>