<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Transfert d'argent</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Le destinataire recevra uniquement le montant transféré.</p>

                    <form action="<?= site_url('client/transfert') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Numéro du destinataire</label>
                            <input type="text" name="destinataire" class="form-control" 
                                   placeholder="034 XX XXX XX" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Montant (Ar)</label>
                            <input type="number" name="montant" class="form-control form-control-lg" 
                                   min="500" step="100" placeholder="Ex: 10000" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Confirmer le transfert
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>