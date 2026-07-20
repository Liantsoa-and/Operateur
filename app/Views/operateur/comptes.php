<?php
// app/Views/operateur/comptes.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des comptes – Opérateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
        }

        .page-header {
            background: #1a2e44;
            color: #fff;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.03em;
        }

        .page-header .breadcrumb {
            margin: 0;
            font-size: 0.8rem;
            opacity: 0.65;
        }

        .page-header .breadcrumb-item a {
            color: #fff;
            text-decoration: none;
        }

        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-card .icon {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .stat-card .value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a2e44;
            line-height: 1;
        }

        .stat-card .label {
            font-size: 0.78rem;
            color: #6c757d;
            margin-top: 2px;
        }

        .card-table {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            overflow: hidden;
        }

        .card-table .card-header-custom {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .card-table .card-header-custom h2 {
            font-size: 1rem;
            font-weight: 600;
            color: #1a2e44;
            margin: 0;
        }

        .table thead th {
            background: #f8f9fa;
            color: #495057;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 2px solid #e9ecef;
            padding: .75rem 1.25rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: .8rem 1.25rem;
            font-size: 0.9rem;
            color: #212529;
            vertical-align: middle;
            border-color: #f0f4f8;
        }

        .table tbody tr:hover {
            background-color: #f8fbff;
        }

        .badge-prefixe {
            background: #e8f0fe;
            color: #1a56db;
            font-size: 0.78rem;
            font-weight: 600;
            padding: .25rem .55rem;
            border-radius: 6px;
        }

        .solde-value {
            font-weight: 700;
            color: #1a2e44;
        }

        .solde-zero {
            color: #adb5bd;
            font-weight: 500;
        }

        .search-box {
            width: 220px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #adb5bd;
        }

        .empty-state i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: .75rem;
        }

        .nav-back {
            color: #1a2e44;
            font-size: 0.85rem;
            text-decoration: none;
            font-weight: 500;
        }

        .nav-back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- En-tête -->
<div class="page-header">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1><i class="bi bi-people-fill me-2"></i>Situation des comptes clients</h1>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/operateur">Tableau de bord</a></li>
                        <li class="breadcrumb-item active text-white-50">Comptes clients</li>
                    </ol>
                </nav>
            </div>
            <a href="/operateur" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="container-fluid px-4">

    <!-- Messages flash -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistiques rapides -->
    <?php
        $nbClients   = count($comptes);
        $totalSoldes = array_sum(array_column($comptes, 'solde'));
        $nbActifs    = count(array_filter($comptes, fn($c) => $c['solde'] > 0));
    ?>
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="icon" style="background:#e8f0fe;">
                    <i class="bi bi-people-fill text-primary"></i>
                </div>
                <div>
                    <div class="value"><?= $nbClients ?></div>
                    <div class="label">Total clients</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="icon" style="background:#e6f9ee;">
                    <i class="bi bi-person-check-fill" style="color:#198754;"></i>
                </div>
                <div>
                    <div class="value"><?= $nbActifs ?></div>
                    <div class="label">Comptes avec solde</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="icon" style="background:#fff3cd;">
                    <i class="bi bi-wallet2" style="color:#d97706;"></i>
                </div>
                <div>
                    <div class="value"><?= number_format($totalSoldes, 0, ',', ' ') ?></div>
                    <div class="label">Solde total (Ar)</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="icon" style="background:#fce8e8;">
                    <i class="bi bi-person-x-fill" style="color:#dc3545;"></i>
                </div>
                <div>
                    <div class="value"><?= $nbClients - $nbActifs ?></div>
                    <div class="label">Comptes à zéro</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des comptes -->
    <div class="card-table mb-4">
        <div class="card-header-custom">
            <h2><i class="bi bi-table me-2"></i>Liste des comptes</h2>
            <input
                type="text"
                id="searchInput"
                class="form-control form-control-sm search-box"
                placeholder="Rechercher un numéro…"
                oninput="filterTable()"
            >
        </div>

        <?php if (empty($comptes)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                Aucun compte client enregistré.
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="comptesTable">
                <thead>
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
                        <td class="text-muted" style="font-size:.8rem;"><?= $i + 1 ?></td>
                        <td>
                            <i class="bi bi-phone me-1 text-secondary"></i>
                            <strong><?= esc($compte['numero']) ?></strong>
                        </td>
                        <td>
                            <span class="badge-prefixe"><?= esc($compte['prefixe']) ?></span>
                        </td>
                        <td><?= esc($compte['operateur'] ?? '—') ?></td>
                        <td class="text-end">
                            <?php if ($compte['solde'] > 0): ?>
                                <span class="solde-value">
                                    <?= number_format((float)$compte['solde'], 0, ',', ' ') ?> Ar
                                </span>
                            <?php else: ?>
                                <span class="solde-zero">0 Ar</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2 border-top text-muted" style="font-size:.8rem;">
            <span id="rowCount"><?= $nbClients ?></span> compte(s) affiché(s)
        </div>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function filterTable() {
    const val   = document.getElementById('searchInput').value.toLowerCase();
    const rows  = document.querySelectorAll('#comptesTable tbody tr');
    let visible = 0;

    rows.forEach(row => {
        const numero = row.cells[1].textContent.toLowerCase();
        const match  = numero.includes(val);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('rowCount').textContent = visible;
}
</script>
</body>
</html>