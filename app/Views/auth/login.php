<?= $this->extend('layouts/client_login') ?>   <!-- Layout spécial sans sidebar -->

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card shadow-sm mt-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold"><i class="bi bi-phone-fill text-primary"></i> Mobile Money</h2>
                        <p class="text-muted">Connexion Client</p>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                    <?php endif; ?>

                    <form action="<?= site_url('login') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Numéro de téléphone</label>
                            <input type="text" name="numero" class="form-control form-control-lg" 
                                   placeholder="034 XX XXX XX" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Se connecter
                        </button>
                    </form>

                    <div class="text-center mt-4 text-muted small">
                        Première connexion ? Un compte sera créé automatiquement.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>