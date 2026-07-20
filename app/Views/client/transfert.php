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
                    <form action="<?= site_url('client/transfert') ?>" method="post" id="formTransfert">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Numéro du destinataire</label>
                            <input type="text" name="destinataire" id="destinataire" class="form-control"
                                   placeholder="034 XX XXX XX" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Montant (Ar)</label>
                            <input type="number" name="montant" id="montant" class="form-control form-control-lg"
                                   min="500" step="100" placeholder="Ex: 10000" required>
                        </div>

                        <div id="inclureFraisWrapper" class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="inclure_frais" id="inclure_frais" value="1">
                            <label class="form-check-label" for="inclure_frais">
                                Inclure les frais dans le montant saisi (le destinataire reçoit moins)
                            </label>
                        </div>

                        <div id="alerteCommission" class="alert alert-warning d-none">
                            <i class="bi bi-info-circle me-1"></i>
                            Transfert vers un autre opérateur : commission de
                            <strong id="tauxCommissionTexte"></strong>%, soit
                            <strong id="montantCommissionTexte"></strong> Ar.
                        </div>

                        <div id="recap" class="alert alert-secondary d-none">
                            <div class="d-flex justify-content-between"><span>Frais + commission :</span><strong id="recapFrais"></strong></div>
                            <div class="d-flex justify-content-between"><span>Montant débité :</span><strong id="recapDebit"></strong></div>
                            <div class="d-flex justify-content-between"><span>Montant reçu par le destinataire :</span><strong id="recapNet"></strong></div>
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

<script>
(function () {
    const destinataireInput = document.getElementById('destinataire');
    const montantInput      = document.getElementById('montant');
    const inclureFraisInput = document.getElementById('inclure_frais');
    const inclureFraisWrap  = document.getElementById('inclureFraisWrapper');
    const alerteDiv          = document.getElementById('alerteCommission');
    const tauxTexte          = document.getElementById('tauxCommissionTexte');
    const montantTexte       = document.getElementById('montantCommissionTexte');
    const recapDiv           = document.getElementById('recap');
    const recapFrais         = document.getElementById('recapFrais');
    const recapDebit         = document.getElementById('recapDebit');
    const recapNet           = document.getElementById('recapNet');

    let timer = null;

    function fmt(n) {
        return Number(n).toLocaleString('fr-FR');
    }

    function verifier() {
        const destinataire = destinataireInput.value.trim();
        const montant       = montantInput.value;

        if (!destinataire || !montant) {
            alerteDiv.classList.add('d-none');
            recapDiv.classList.add('d-none');
            inclureFraisWrap.classList.remove('d-none');
            inclureFraisInput.disabled = false;
            return;
        }

        const formData = new FormData();
        formData.append('destinataire', destinataire);
        formData.append('montant', montant);
        formData.append('inclure_frais', inclureFraisInput.checked ? '1' : '0');
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= site_url('client/transfert/commission') ?>', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (!data.valide) {
                    alerteDiv.classList.add('d-none');
                    recapDiv.classList.add('d-none');
                    return;
                }

                if (data.inter_operateur && data.commission > 0) {
                    tauxTexte.textContent    = data.taux_commission;
                    montantTexte.textContent = fmt(data.commission);
                    alerteDiv.classList.remove('d-none');
                    inclureFraisWrap.classList.add('d-none');
                    inclureFraisInput.checked = false;
                    inclureFraisInput.disabled = true;
                } else {
                    alerteDiv.classList.add('d-none');
                    inclureFraisWrap.classList.remove('d-none');
                    inclureFraisInput.disabled = false;
                }

                recapFrais.textContent = fmt(data.frais + data.commission) + ' Ar';
                recapDebit.textContent = fmt(data.total_debit) + ' Ar';
                recapNet.textContent   = fmt(data.montant_net) + ' Ar';
                recapDiv.classList.remove('d-none');
            })
            .catch(() => { alerteDiv.classList.add('d-none'); recapDiv.classList.add('d-none'); });
    }

    function debounce() {
        clearTimeout(timer);
        timer = setTimeout(verifier, 400);
    }

    destinataireInput.addEventListener('input', debounce);
    montantInput.addEventListener('input', debounce);
    inclureFraisInput.addEventListener('change', debounce);
})();
</script>
<?= $this->endSection() ?>