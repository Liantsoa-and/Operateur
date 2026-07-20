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
    <title>Situation des gains</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #333; }

        .header { background: #1a73e8; color: #fff; padding: 20px 30px; display: flex; align-items: center; gap: 20px; }
        .header h1 { font-size: 1.4rem; font-weight: 600; }
        .header a { color: rgba(255,255,255,.8); text-decoration: none; font-size: .85rem; }
        .header a:hover { color: #fff; }

        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }

        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .card { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,.08); transition: transform .15s; }
        .card:hover { transform: translateY(-2px); }
        .card-label { font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; color: #888; margin-bottom: 8px; }
        .card-value { font-size: 1.8rem; font-weight: 700; }
        .card-sub { font-size: .8rem; color: #999; margin-top: 4px; }
        .card--retrait   .card-value { color: #e53935; }
        .card--transfert .card-value { color: #1a73e8; }
        .card--total     .card-value { color: #2e7d32; }

        /* Filtres */
        .filters { background: #fff; border-radius: 10px; padding: 20px 24px; box-shadow: 0 1px 4px rgba(0,0,0,.08); margin-bottom: 30px; }
        .filters-title { font-size: .85rem; font-weight: 600; color: #555; margin-bottom: 14px; display: flex; align-items: center; gap: 6px; }
        .filters-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; align-items: end; }
        .filter-group label { display: block; font-size: .75rem; color: #888; margin-bottom: 4px; text-transform: uppercase; letter-spacing: .3px; }
        .filter-group input,
        .filter-group select { width: 100%; padding: 8px 10px; border: 1px solid #ddd; border-radius: 6px; font-size: .85rem; background: #fafafa; transition: border .2s; }
        .filter-group input:focus,
        .filter-group select:focus { outline: none; border-color: #1a73e8; background: #fff; }
        .filters-actions { display: flex; gap: 10px; margin-top: 14px; }
        .btn { padding: 8px 18px; border: none; border-radius: 6px; font-size: .85rem; font-weight: 600; cursor: pointer; transition: background .2s; }
        .btn-primary { background: #1a73e8; color: #fff; }
        .btn-primary:hover { background: #1557b0; }
        .btn-secondary { background: #e0e0e0; color: #555; }
        .btn-secondary:hover { background: #d0d0d0; }

        .section-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 16px; }

        .table-wrapper { background: #fff; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.08); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #fafafa; text-align: left; padding: 12px 16px; font-size: .78rem;
             text-transform: uppercase; letter-spacing: .5px; color: #666; border-bottom: 1px solid #eee; }
        td { padding: 12px 16px; border-bottom: 1px solid #f5f5f5; font-size: .9rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8f9fa; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: .75rem; font-weight: 600; }
        .badge--retrait   { background: #fdecea; color: #e53935; }
        .badge--transfert { background: #e8f0fe; color: #1a73e8; }

        .text-right { text-align: right; }
        .empty-msg { padding: 40px; text-align: center; color: #aaa; }

        .spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid #ccc; border-top-color: #1a73e8;
                   border-radius: 50%; animation: spin .6s linear infinite; vertical-align: middle; margin-right: 6px; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

<div class="header">
    <h1>Situation des gains</h1>
    <a href="/operateur">← Retour au tableau de bord</a>
</div>

<div class="container">

    <!-- Cartes -->
    <div class="cards" id="cards">
        <div class="card card--retrait">
            <div class="card-label">Total retraits</div>
            <div class="card-value" id="card-retrait"><?= number_format($retrait_total, 2, ',', ' ') ?> Ar</div>
            <div class="card-sub" id="card-retrait-sub"><?= $retrait_nb ?> transaction<?= $retrait_nb > 1 ? 's' : '' ?></div>
        </div>
        <div class="card card--transfert">
            <div class="card-label">Total transferts</div>
            <div class="card-value" id="card-transfert"><?= number_format($transfert_total, 2, ',', ' ') ?> Ar</div>
            <div class="card-sub" id="card-transfert-sub"><?= $transfert_nb ?> transaction<?= $transfert_nb > 1 ? 's' : '' ?></div>
        </div>
        <div class="card card--total">
            <div class="card-label">Total général</div>
            <div class="card-value" id="card-total"><?= number_format($total_gains, 2, ',', ' ') ?> Ar</div>
            <div class="card-sub" id="card-total-sub"><?= $retrait_nb + $transfert_nb ?> transaction<?= ($retrait_nb + $transfert_nb) > 1 ? 's' : '' ?> au total</div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters">
        <div class="filters-title">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/></svg>
            Filtres
        </div>
        <form id="filtreForm" onsubmit="return false;">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="">Tous</option>
                        <option value="retrait">Retrait</option>
                        <option value="transfert">Transfert</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date_debut">Date début</label>
                    <input type="date" id="date_debut" name="date_debut">
                </div>
                <div class="filter-group">
                    <label for="date_fin">Date fin</label>
                    <input type="date" id="date_fin" name="date_fin">
                </div>
                <div class="filter-group">
                    <label for="client">N° Client</label>
                    <input type="text" id="client" name="client" placeholder="Ex: 034...">
                </div>
                <div class="filter-group">
                    <label for="montant_min">Montant min (Ar)</label>
                    <input type="number" id="montant_min" name="montant_min" min="0" step="100" placeholder="0">
                </div>
                <div class="filter-group">
                    <label for="montant_max">Montant max (Ar)</label>
                    <input type="number" id="montant_max" name="montant_max" min="0" step="100" placeholder="∞">
                </div>
            </div>
            <div class="filters-actions">
                <button type="submit" class="btn btn-primary" id="btnFiltrer" onclick="appliquerFiltre()">
                    Appliquer
                </button>
                <button type="button" class="btn btn-secondary" onclick="resetFiltre()">Réinitialiser</button>
            </div>
        </form>
    </div>

    <!-- Historique -->
    <div class="section-title">Historique des transactions</div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>N° Transaction</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th class="text-right">Frais</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($historique)): ?>
                    <tr><td colspan="6" class="empty-msg">Aucune transaction enregistrée.</td></tr>
                <?php else: ?>
                    <?php foreach ($historique as $t): ?>
                        <?= renderRow($t) ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php
function renderRow(array $t): string {
    $date = date('d/m/Y H:i', strtotime($t['date_transaction']));
    $type = esc($t['type_operation']);
    $badgeClass = 'badge--' . $type;
    $montant = number_format((float)$t['montant'], 2, ',', ' ');
    $frais   = number_format((float)$t['frais'], 2, ',', ' ');

    return <<<HTML
    <tr>
        <td>{$t['numero_transaction']}</td>
        <td>{$date}</td>
        <td><span class="badge {$badgeClass}">{$type}</span></td>
        <td>{$t['numero_client']}</td>
        <td>{$montant} Ar</td>
        <td class="text-right">{$frais} Ar</td>
    </tr>
HTML;
}
?>

<script>
const CSRF_TOKEN = '<?= csrf_hash() ?>';
let CSRF_NAME   = '<?= csrf_token() ?>';

function buildFormData() {
    const data = new URLSearchParams();
    document.querySelectorAll('#filtreForm select, #filtreForm input').forEach(el => {
        if (el.value.trim() !== '') data.append(el.name, el.value.trim());
    });
    data.append(CSRF_NAME, CSRF_TOKEN);
    return data;
}

function formatNumber(n) {
    return n.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function renderRows(historique) {
    const tbody = document.getElementById('tableBody');
    if (historique.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="empty-msg">Aucune transaction ne correspond aux filtres.</td></tr>';
        return;
    }
    const rows = historique.map(t => {
        const d   = new Date(t.date_transaction);
        const dd  = String(d.getDate()).padStart(2,'0');
        const mm  = String(d.getMonth()+1).padStart(2,'0');
        const yyyy = d.getFullYear();
        const hh  = String(d.getHours()).padStart(2,'0');
        const mi  = String(d.getMinutes()).padStart(2,'0');
        const dateStr = `${dd}/${mm}/${yyyy} ${hh}:${mi}`;
        const montant = formatNumber(parseFloat(t.montant));
        const frais   = formatNumber(parseFloat(t.frais));
        return `<tr>
            <td>${t.numero_transaction}</td>
            <td>${dateStr}</td>
            <td><span class="badge badge--${t.type_operation}">${t.type_operation}</span></td>
            <td>${t.numero_client}</td>
            <td>${montant} Ar</td>
            <td class="text-right">${frais} Ar</td>
        </tr>`;
    }).join('');
    tbody.innerHTML = rows;
}

function updateCards(stats) {
    document.getElementById('card-retrait').textContent      = formatNumber(stats.retrait_total) + ' Ar';
    document.getElementById('card-retrait-sub').textContent   = stats.retrait_nb + ' transaction' + (stats.retrait_nb > 1 ? 's' : '');
    document.getElementById('card-transfert').textContent     = formatNumber(stats.transfert_total) + ' Ar';
    document.getElementById('card-transfert-sub').textContent = stats.transfert_nb + ' transaction' + (stats.transfert_nb > 1 ? 's' : '');
    document.getElementById('card-total').textContent         = formatNumber(stats.total_gains) + ' Ar';
    document.getElementById('card-total-sub').textContent     = stats.total_nb + ' transaction' + (stats.total_nb > 1 ? 's' : '') + ' au total';
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
        btn.innerHTML = 'Appliquer';
    }
}

function resetFiltre() {
    document.querySelectorAll('#filtreForm select, #filtreForm input').forEach(el => {
        el.value = '';
    });
    appliquerFiltre();
}

document.querySelectorAll('#filtreForm select, #filtreForm input').forEach(el => {
    el.addEventListener('change', appliquerFiltre);
});
</script>

</body>
</html>
