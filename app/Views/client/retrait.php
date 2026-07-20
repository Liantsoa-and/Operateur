<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Retrait d'argent</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Des frais seront appliqués selon le barème.</p>

                    <form action="<?= site_url('client/retrait') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Montant à retirer (Ar)</label>
                            <input type="number" name="montant" class="form-control form-control-lg" 
                                   min="1000" step="100" placeholder="Ex: 5000" required>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100">
                            Confirmer le retrait
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>