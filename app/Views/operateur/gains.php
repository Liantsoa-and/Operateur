<?php
$retrait_total  = 0;
$retrait_nb     = 0;
$transfert_total = 0;
$transfert_nb   = 0;

foreach ($gains as $g) {
    if ($g['type_operation'] === 'retrait') {
        $retrait_total = (float) $g['total_frais'];
        $retrait_nb    = (int)   $g['nb_transactions'];
    } elseif ($g['type_operation'] === 'transfert') {
        $transfert_total = (float) $g['total_frais'];
        $transfert_nb    = (int)   $g['nb_transactions'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des gains – Opérateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f0f4f8; font-family: 'Segoe UI', sans-serif; }

        /* ── Sidebar ── */
        .sidebar { width: 240px; min-height: 100vh; background: #1a2e44; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; z-index: 100; }
        .sidebar-brand { padding: 1.5rem 1.25rem 1rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-brand .brand-title { font-size: 1rem; font-weight: 700; color: #fff; letter-spacing: .04em; }
        .sidebar-brand .brand-sub { font-size: 0.72rem; color: rgba(255,255,255,.45); margin-top: 2px; }
        .sidebar-nav { padding: 1rem 0; flex: 1; }
        .nav-section-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,.35); padding: .75rem 1.25rem .25rem; }
        .sidebar-nav a { display: flex; align-items: center; gap: .65rem; padding: .6rem 1.25rem; color: rgba(255,255,255,.7); text-decoration: none; font-size: 0.88rem; transition: background .15s, color .15s; border-left: 3px solid transparent; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: rgba(255,255,255,.07); color: #fff; border-left-color: #4e9af1; }
        .sidebar-nav a i { font-size: 1rem; width: 18px; text-align: center; }

        /* ── Main ── */
        .main { margin-left: 240px; padding: 2rem; min-height: 100vh; }
        .topbar h1 { font-size: 1.4rem; font-weight: 700; color: #1a2e44; margin: 0; }
        .topbar .subtitle { font-size: 0.85rem; color: #6c757d; margin-top: 2px; }

        /* ── Stat cards ── */
        .stat-card { background: #fff; border-radius: 12px; padding: 1.25rem 1.5rem; box-shadow: 0 1px 4px rgba(0,0,0,.07); display: flex; align-items: center; gap: 1rem; transition: box-shadow .2s; }
        .stat-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.1); }
        .stat-card .icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0; }
        .stat-card .value { font-size: 1.6rem; font-weight: 700; color: #1a2e44; line-height: 1; }
        .stat-card .label { font-size: 0.78rem; color: #6c757d; margin-top: 3px; }

        /* ── Filtres ── */
        .filter-panel { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); padding: 1.25rem 1.5rem; }
        .filter-panel .filter-title { font-size: 0.85rem; font-weight: 600; color: #6c757d; margin-bottom: 1rem; display: flex; align-items: center; gap: 6px; }

        /* ── Table card ── */
        .card-table { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); overflow: hidden; }
        .card-table .card-header-custom { padding: 1rem 1.5rem; border-bottom: 1px solid #e9ecef; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; }
        .card-table .card-header-custom h2 { font-size: 1rem; font-weight: 600; color: #1a2e44; margin: 0; }
        .table thead th { background: #f8f9fa; color: #495057; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 2px solid #e9ecef; padding: .75rem 1.25rem; white-space: nowrap; }
        .table tbody td { padding: .8rem 1.25rem; font-size: 0.9rem; color: #212529; vertical-align: middle; border-color: #f0f4f8; }
        .table tbody tr:hover { background-color: #f8fbff; }

        /* ── Badges ── */
        .badge-type { display: inline-block; padding: .25rem .65rem; border-radius: 6px; font-size: 0.78rem; font-weight: 600; }
        .badge-type.retrait { background: #fdecea; color: #e53935; }
        .badge-type.transfert { background: #e8f0fe; color: #1a56db; }

        .empty-state { text-align: center; padding: 3rem 1rem; color: #adb5bd; }
        .empty-state i { font-size: 2.5rem; display: block; margin-bottom: .75rem; }

        .section-title { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: #6c757d; margin-bottom: 1rem; }

        .spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid #ccc; border-top-color: #4e9af1; border-radius: 50%; animation: spin .6s linear infinite; vertical-align: middle; margin-right: 6px; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-title"><i class="bi bi-phone-fill me-2"></i>Mobile Money</div>
        <div class="brand-sub">Espace opérateur</div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Général</div>
        <a href="/operateur">
            <i class="bi bi-grid-1x2-fill"></i> Tableau de bord
        </a>
        <div class="nav-section-label">Configuration</div>
        <a href="/operateur/prefixes">
            <i class="bi bi-hash"></i> Préfixes
        </a>
        <a href="/operateur/types">
            <i class="bi bi-sliders"></i> Types & barèmes
        </a>
        <div class="nav-section-label">Supervision</div>
        <a href="/operateur/comptes">
            <i class="bi bi-people-fill"></i> Comptes clients
        </a>
        <a href="/operateur/gains" class="active">
            <i class="bi bi-graph-up-arrow"></i> Gains
        </a>
    </nav>
</aside>

<!-- Contenu principal -->
<main class="main">

    <div class="topbar d-flex align-items-start justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h1><i class="bi bi-graph-up-arrow me-2"></i>Situation des gains</h1>
            <div class="subtitle">Frais perçus sur les retraits et transferts</div>
        </div>
    </div>

    <!-- Cartes récapitulatives -->
    <p class="section-title">Vue d'ensemble</p>
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="icon" style="background:#fce8e8;">
                    <i class="bi bi-arrow-up-right" style="color:#e53935;"></i>
                </div>
                <div>
                    <div class="value" id="card-retrait"><?= number_format($retrait_total, 0, ',', ' ') ?></div>
                    <div class="label">Gains retraits (Ar)</div>
                    <div class="text-muted" style="font-size:.72rem;" id="card-retrait-sub"><?= $retrait_nb ?> transaction<?= $retrait_nb > 1 ? 's' : '' ?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="icon" style="background:#e8f0fe;">
                    <i class="bi bi-arrow-left-right" style="color:#1a56db;"></i>
                </div>
                <div>
                    <div class="value" id="card-transfert"><?= number_format($transfert_total, 0, ',', ' ') ?></div>
                    <div class="label">Gains transferts (Ar)</div>
                    <div class="text-muted" style="font-size:.72rem;" id="card-transfert-sub"><?= $transfert_nb ?> transaction<?= $transfert_nb > 1 ? 's' : '' ?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="icon" style="background:#e6f9ee;">
                    <i class="bi bi-cash-coin" style="color:#198754;"></i>
                </div>
                <div>
                    <div class="value" id="card-total"><?= number_format($total_gains, 0, ',', ' ') ?></div>
                    <div class="label">Total gains (Ar)</div>
                    <div class="text-muted" style="font-size:.72rem;" id="card-total-sub"><?= $retrait_nb + $transfert_nb ?> transaction<?= ($retrait_nb + $transfert_nb) > 1 ? 's' : '' ?> au total</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <p class="section-title">Filtres</p>
    <div class="filter-panel mb-4">
        <form id="filtreForm" onsubmit="return false;">
            <div class="row g-3 align-items-end">
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label" for="type">Type</label>
                    <select id="type" name="type" class="form-select form-select-sm">
                        <option value="">Tous</option>
                        <option value="retrait">Retrait</option>
                        <option value="transfert">Transfert</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label" for="date_debut">Date début</label>
                    <input type="date" id="date_debut" name="date_debut" class="form-control form-control-sm">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label" for="date_fin">Date fin</label>
                    <input type="date" id="date_fin" name="date_fin" class="form-control form-control-sm">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label" for="client">N° Client</label>
                    <input type="text" id="client" name="client" class="form-control form-control-sm" placeholder="034…">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label" for="montant_min">Montant min</label>
                    <input type="number" id="montant_min" name="montant_min" class="form-control form-control-sm" min="0" step="100" placeholder="0">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="form-label" for="montant_max">Montant max</label>
                    <input type="number" id="montant_max" name="montant_max" class="form-control form-control-sm" min="0" step="100" placeholder="∞">
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm" id="btnFiltrer" onclick="appliquerFiltre()">
                    <i class="bi bi-funnel me-1"></i>Appliquer
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFiltre()">
                    <i class="bi bi-x-circle me-1"></i>Réinitialiser
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau historique -->
    <p class="section-title">Historique des transactions</p>
    <div class="card-table mb-4">
        <div class="card-header-custom">
            <h2><i class="bi bi-table me-2"></i>Liste des transactions</h2>
            <span class="text-muted" style="font-size:.8rem;"><span id="rowCount"><?= count($historique) ?></span> ligne(s)</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="gainsTable">
                <thead>
                    <tr>
                        <th>N° Transaction</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Client</th>
                        <th class="text-end">Montant</th>
                        <th class="text-end">Frais</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($historique)): ?>
                        <tr><td colspan="6" class="empty-state"><i class="bi bi-inbox"></i>Aucune transaction enregistrée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($historique as $t): ?>
                            <?= renderRow($t) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<?php
function renderRow(array $t): string {
    $date = date('d/m/Y H:i', strtotime($t['date_transaction']));
    $type = esc($t['type_operation']);
    $montant = number_format((float)$t['montant'], 0, ',', ' ');
    $frais   = number_format((float)$t['frais'], 0, ',', ' ');

    return <<<HTML
    <tr>
        <td><code style="font-size:.8rem;">{$t['numero_transaction']}</code></td>
        <td>{$date}</td>
        <td><span class="badge-type {$type}">{$type}</span></td>
        <td><i class="bi bi-phone me-1 text-secondary"></i>{$t['numero_client']}</td>
        <td class="text-end">{$montant} Ar</td>
        <td class="text-end fw-bold">{$frais} Ar</td>
    </tr>
HTML;
}
?>

<script>
const CSRF_TOKEN = '<?= csrf_hash() ?>';
const CSRF_NAME  = '<?= csrf_token() ?>';

function buildFormData() {
    const data = new URLSearchParams();
    document.querySelectorAll('#filtreForm select, #filtreForm input').forEach(el => {
        if (el.value.trim() !== '') data.append(el.name, el.value.trim());
    });
    data.append(CSF_NAME, CSRF_TOKEN);
    return data;
}

function formatNumber(n) {
    return n.toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

function renderRows(historique) {
    const tbody = document.getElementById('tableBody');
    if (historique.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="empty-state"><i class="bi bi-inbox"></i>Aucune transaction ne correspond aux filtres.</td></tr>';
        document.getElementById('rowCount').textContent = '0';
        return;
    }
    const rows = historique.map(t => {
        const d = new Date(t.date_transaction);
        const dd = String(d.getDate()).padStart(2,'0');
        const mm = String(d.getMonth()+1).padStart(2,'0');
        const yyyy = d.getFullYear();
        const hh = String(d.getHours()).padStart(2,'0');
        const mi = String(d.getMinutes()).padStart(2,'0');
        const dateStr = `${dd}/${mm}/${yyyy} ${hh}:${mi}`;
        const montant = formatNumber(parseFloat(t.montant));
        const frais = formatNumber(parseFloat(t.frais));
        return `<tr>
            <td><code style="font-size:.8rem;">${t.numero_transaction}</code></td>
            <td>${dateStr}</td>
            <td><span class="badge-type ${t.type_operation}">${t.type_operation}</span></td>
            <td><i class="bi bi-phone me-1 text-secondary"></i>${t.numero_client}</td>
            <td class="text-end">${montant} Ar</td>
            <td class="text-end fw-bold">${frais} Ar</td>
        </tr>`;
    }).join('');
    tbody.innerHTML = rows;
    document.getElementById('rowCount').textContent = historique.length;
}

function updateCards(stats) {
    document.getElementById('card-retrait').textContent = formatNumber(stats.retrait_total);
    document.getElementById('card-retrait-sub').textContent = stats.retrait_nb + ' transaction' + (stats.retrait_nb > 1 ? 's' : '');
    document.getElementById('card-transfert').textContent = formatNumber(stats.transfert_total);
    document.getElementById('card-transfert-sub').textContent = stats.transfert_nb + ' transaction' + (stats.transfert_nb > 1 ? 's' : '');
    document.getElementById('card-total').textContent = formatNumber(stats.total_gains);
    document.getElementById('card-total-sub').textContent = stats.total_nb + ' transaction' + (stats.total_nb > 1 ? 's' : '') + ' au total';
}

async function appliquerFiltre() {
    const btn = document.getElementById('btnFiltrer');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span>Chargement…';

    try {
        const resp = await fetch('/operateur/gains/filtrer', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: buildFormData(),
        });
        const data = await resp.json();
        updateCards(data.stats);
        renderRows(data.historique);
    } catch (err) {
        console.error('Erreur AJAX filtrerGains:', err);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-funnel me-1"></i>Appliquer';
    }
}

function resetFiltre() {
    document.querySelectorAll('#filtreForm select, #filtreForm input').forEach(el => el.value = '');
    appliquerFiltre();
}

document.querySelectorAll('#filtreForm select, #filtreForm input').forEach(el => {
    el.addEventListener('change', appliquerFiltre);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
