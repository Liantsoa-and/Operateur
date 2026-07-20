<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Mobile Money') ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

    <style>
        :root {
            --primary: #1a2e44;
            --accent: #4e9af1;
        }
        .sidebar-client {
            width: 220px;
            background: #1a2e44;
            min-height: 100vh;
            position: fixed;
            color: #ddd;
        }
        .main-client {
            margin-left: 220px;
            padding: 2rem;
            background: #f8f9fa;
            min-height: 100vh;
        }
        .nav-link {
            color: #ddd;
            padding: 0.75rem 1.25rem;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
    </style>
</head>
<body>

<!-- Sidebar Client -->
<aside class="sidebar-client">
    <div class="p-3 border-bottom border-light border-opacity-10">
        <h4 class="text-white mb-0">
            <i class="bi bi-phone-fill me-2"></i>Mobile Money
        </h4>
        <small class="text-light opacity-75">Espace Client</small>
    </div>
    
    <nav class="nav flex-column mt-3">
        <a href="<?= site_url('client/solde') ?>" class="nav-link <?= url_is('client/solde') ? 'active' : '' ?>">
            <i class="bi bi-wallet2 me-2"></i> Solde
        </a>
        <a href="<?= site_url('client/historique') ?>" class="nav-link <?= url_is('client/historique') ? 'active' : '' ?>">
            <i class="bi bi-clock-history me-2"></i> Historique
        </a>
        <a href="<?= site_url('client/retrait') ?>" class="nav-link <?= url_is('client/retrait') ? 'active' : '' ?>">
            <i class="bi bi-cash-stack me-2"></i> Retrait
        </a>
        <a href="<?= site_url('client/transfert') ?>" class="nav-link <?= url_is('client/transfert') ? 'active' : '' ?>">
            <i class="bi bi-arrow-left-right me-2"></i> Transfert
        </a>
    </nav>

    <div class="position-absolute bottom-0 w-100 p-3">
        <a href="<?= site_url('logout') ?>" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
        </a>
    </div>
</aside>

<!-- Contenu principal -->
<main class="main-client">
    <?= $this->include('partials/flash_messages') ?>
    <?= $this->renderSection('content') ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>