<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-4 text-center">
                    <h3 class="mb-0"><i class="bi bi-wallet2 text-success me-2"></i>Votre Solde</h3>
                </div>
                <div class="card-body text-center py-5">
                    <h1 class="display-1 fw-bold text-success mb-1">
                        <?= number_format($solde ?? 0, 0, ',', ' ') ?>
                    </h1>
                    <p class="fs-4 text-muted">Ariary (Ar)</p>
                    
                    <p class="mt-3 mb-5">
                        <strong>Numéro :</strong> <?= esc($numero ?? '') ?>
                    </p>

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <button class="btn btn-success btn-lg px-4" data-bs-toggle="modal" data-bs-target="#modalDepot">
                            <i class="bi bi-plus-circle me-2"></i> Dépôt
                        </button>
                        <a href="<?= site_url('client/retrait') ?>" class="btn btn-warning btn-lg px-4">
                            <i class="bi bi-cash-stack me-2"></i> Retrait
                        </a>
                        <a href="<?= site_url('client/transfert') ?>" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-arrow-left-right me-2"></i> Transfert
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ====================== MODAL DÉPÔT ====================== -->
<div class="modal fade" id="modalDepot" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle text-success"></i> Faire un Dépôt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Les dépôts n'ont pas de frais. Le montant sera crédité immédiatement.
                </div>
                
                <form id="formDepot" action="<?= site_url('client/depot') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Montant (Ar)</label>
                        <input type="number" name="montant" id="montant" 
                               class="form-control form-control-lg" 
                               min="1000" step="100" placeholder="Ex: 5000" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="btnConfirmerDepot" class="btn btn-success">
                    Confirmer le Dépôt
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btnConfirmerDepot').addEventListener('click', function() {
    const montant = document.getElementById('montant').value;
    
    if (!montant || montant < 1000) {
        alert("Le montant minimum est de 1000 Ar");
        return;
    }
    
    if (confirm(`Confirmer le dépôt de ${parseInt(montant).toLocaleString('fr-FR')} Ar ?`)) {
        document.getElementById('formDepot').submit();
    }
});
</script>

<?= $this->endSection() ?>