<?php
// app/Views/operateur/dashboard.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord – Opérateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1a2e44;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .sidebar-brand .brand-title {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: .04em;
        }

        .sidebar-brand .brand-sub {
            font-size: 0.72rem;
            color: rgba(255,255,255,.45);
            margin-top: 2px;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .nav-section-label {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255,255,255,.35);
            padding: .75rem 1.25rem .25rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .6rem 1.25rem;
            color: rgba(255,255,255,.7);
            text-decoration: none;
            font-size: 0.88rem;
            transition: background .15s, color .15s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255,255,255,.07);
            color: #fff;
            border-left-color: #4e9af1;
        }

        .sidebar-nav a i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }

        .main {
            margin-left: 240px;
            padding: 2rem;
            min-height: 100vh;
        }

        .topbar {
            margin-bottom: 2rem;
        }

        .topbar h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a2e44;
            margin: 0;
        }

        .topbar .subtitle {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 2px;
        }

        /* Stat cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.07);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: box-shadow .2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 14px rgba(0,0,0,.1);
        }

        .stat-card .icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .stat-card .value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a2e44;
            line-height: 1;
        }

        .stat-card .label {
            font-size: 0.78rem;
            color: #6c757d;
            margin-top: 3px;
        }

        /* Nav cards (accès rapide) */
        .nav-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.07);
            padding: 1.5rem;
            text-decoration: none;
            color: #1a2e44;
            display: block;
            transition: box-shadow .2s, transform .2s;
            border-left: 4px solid transparent;
        }

        .nav-card:hover {
            box-shadow: 0 6px 18px rgba(0,0,0,.11);
            transform: translateY(-2px);
            color: #1a2e44;
        }

        .nav-card .nav-card-icon {
            font-size: 1.7rem;
            margin-bottom: .75rem;
        }

        .nav-card .nav-card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: .25rem;
        }

        .nav-card .nav-card-desc {
            font-size: 0.8rem;
            color: #6c757d;
            line-height: 1.4;
        }

        .nav-card .nav-card-arrow {
            margin-top: .75rem;
            font-size: 0.8rem;
            font-weight: 600;
            opacity: 0;
            transition: opacity .2s;
        }

        .nav-card:hover .nav-card-arrow {
            opacity: 1;
        }

        .section-title {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6c757d;
            margin-bottom: 1rem;
        }
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
        <a href="/operateur" class="active">
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
        <a href="/operateur/gains">
            <i class="bi bi-graph-up-arrow"></i> Gains
        </a>
    </nav>
</aside>

<!-- Contenu principal -->
<main class="main">

    <div class="topbar d-flex align-items-start justify-content-between flex-wrap gap-2">
        <div>
            <h1>Tableau de bord</h1>
            <div class="subtitle"><?= date('l d F Y') ?> — Vue opérateur</div>
        </div>
    </div>

    <!-- Stats -->
    <p class="section-title">Vue d'ensemble</p>
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="icon" style="background:#e8f0fe;">
                    <i class="bi bi-people-fill text-primary"></i>
                </div>
                <div>
                    <div class="value"><?= esc($nb_clients) ?></div>
                    <div class="label">Clients enregistrés</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="icon" style="background:#e6f9ee;">
                    <i class="bi bi-cash-coin" style="color:#198754;"></i>
                </div>
                <div>
                    <div class="value"><?= number_format((float)$total_gains, 0, ',', ' ') ?></div>
                    <div class="label">Gains totaux (Ar)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accès rapide -->
    <p class="section-title">Accès rapide</p>
    <div class="row g-3">

        <div class="col-sm-6 col-lg-3">
            <a href="/operateur/prefixes" class="nav-card" style="border-left-color:#4e9af1;">
                <div class="nav-card-icon" style="color:#4e9af1;">
                    <i class="bi bi-hash"></i>
                </div>
                <div class="nav-card-title">Préfixes</div>
                <div class="nav-card-desc">Gérer les préfixes valides de l'opérateur (033, 034…)</div>
                <div class="nav-card-arrow" style="color:#4e9af1;">Gérer <i class="bi bi-arrow-right"></i></div>
            </a>
        </div>

        <div class="col-sm-6 col-lg-3">
            <a href="/operateur/types" class="nav-card" style="border-left-color:#7c3aed;">
                <div class="nav-card-icon" style="color:#7c3aed;">
                    <i class="bi bi-sliders"></i>
                </div>
                <div class="nav-card-title">Types & barèmes</div>
                <div class="nav-card-desc">Configurer les frais par tranche pour dépôt, retrait, transfert</div>
                <div class="nav-card-arrow" style="color:#7c3aed;">Configurer <i class="bi bi-arrow-right"></i></div>
            </a>
        </div>

        <div class="col-sm-6 col-lg-3">
            <a href="/operateur/comptes" class="nav-card" style="border-left-color:#0891b2;">
                <div class="nav-card-icon" style="color:#0891b2;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="nav-card-title">Comptes clients</div>
                <div class="nav-card-desc">Consulter le solde de tous les comptes en temps réel</div>
                <div class="nav-card-arrow" style="color:#0891b2;">Consulter <i class="bi bi-arrow-right"></i></div>
            </a>
        </div>

        <div class="col-sm-6 col-lg-3">
            <a href="/operateur/gains" class="nav-card" style="border-left-color:#16a34a;">
                <div class="nav-card-icon" style="color:#16a34a;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="nav-card-title">Gains</div>
                <div class="nav-card-desc">Voir les gains issus des frais de retrait et de transfert</div>
                <div class="nav-card-arrow" style="color:#16a34a;">Voir <i class="bi bi-arrow-right"></i></div>
            </a>
        </div>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>