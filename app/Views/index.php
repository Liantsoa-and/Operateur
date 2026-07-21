<?= $this->extend('layouts/client_login') ?>   <!-- Layout spécial sans sidebar -->

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <div class="text-center mt-5 mb-4">
                <h1 class="fw-bold"><i class="bi bi-phone-fill text-primary"></i> Mobile Money</h1>
                <p class="text-muted">Choisissez votre espace pour continuer</p>
            </div>

            <?= $this->include('partials/flash_messages') ?>

            <div class="row g-4">
                <!-- Carte Opérateur -->
                <div class="col-md-6">
                    <a href="<?= site_url('operateur') ?>" class="text-decoration-none">
                        <div class="card shadow-sm h-100 border-0 text-center p-4" style="transition: transform .15s;">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="bi bi-grid-1x2-fill" style="font-size: 2.5rem; color: #1a2e44;"></i>
                                </div>
                                <h4 class="fw-bold text-dark">Espace Opérateur</h4>
                                <p class="text-muted small mb-3">
                                    Gestion des préfixes, barèmes, comptes clients et suivi des gains.
                                </p>
                                <span class="btn btn-outline-primary w-100">
                                    Accéder <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Carte Client -->
                <div class="col-md-6">
                    <a href="<?= site_url('client') ?>" class="text-decoration-none">
                        <div class="card shadow-sm h-100 border-0 text-center p-4" style="transition: transform .15s;">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="bi bi-wallet2" style="font-size: 2.5rem; color: #4e9af1;"></i>
                                </div>
                                <h4 class="fw-bold text-dark">Espace Client</h4>
                                <p class="text-muted small mb-3">
                                    Consultez votre solde, effectuez un dépôt, un retrait ou un transfert.
                                </p>
                                <span class="btn btn-primary w-100">
                                    Se connecter <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-4px);
    }
</style>
<?= $this->endSection() ?>
