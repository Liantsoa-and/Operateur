<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Espace Opérateur - Mobile Money') ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

    <style>
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1a2e44;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }
        .main {
            margin-left: 240px;
            padding: 2rem;
            min-height: 100vh;
            background: #f0f4f8;
        }
        .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .section-title {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="p-3 border-bottom border-light border-opacity-10">
        <h4 class="text-white mb-0">
            <i class="bi bi-phone-fill me-2"></i>Mobile Money
        </h4>
        <small class="text-light opacity-75">Espace Opérateur</small>
    </div>
    
    <nav class="nav flex-column mt-3">
        <a href="<?= site_url('operateur') ?>" class="nav-link <?= url_is('operateur') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i> Tableau de bord
        </a>
        
        <div class="px-3 mt-4 mb-1 text-uppercase small text-light opacity-50">Configuration</div>
        <a href="<?= site_url('operateur/prefixes') ?>" class="nav-link <?= url_is('operateur/prefixes*') ? 'active' : '' ?>">
            <i class="bi bi-hash"></i> Préfixes
        </a>
        <a href="<?= site_url('operateur/types') ?>" class="nav-link <?= url_is('operateur/types*') || url_is('operateur/baremes*') ? 'active' : '' ?>">
            <i class="bi bi-sliders"></i> Types & Barèmes
        </a>
        
        <div class="px-3 mt-4 mb-1 text-uppercase small text-light opacity-50">Supervision</div>
        <a href="<?= site_url('operateur/comptes') ?>" class="nav-link <?= url_is('operateur/comptes') ? 'active' : '' ?>">
            <i class="bi bi-people-fill"></i> Comptes Clients
        </a>
        <a href="<?= site_url('operateur/gains') ?>" class="nav-link <?= url_is('operateur/gains') ? 'active' : '' ?>">
            <i class="bi bi-graph-up-arrow"></i> Gains
        </a>
    </nav>
</aside>

<!-- Contenu principal -->
<main class="main">
    <?= $this->include('partials/flash_messages') ?>
    <?= $this->renderSection('content') ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>