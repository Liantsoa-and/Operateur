<?= $this->extend('layouts/client') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="bi bi-people me-2"></i>Envoi multiple</h4>
                    <small class="text-muted">Le montant est divisé entre les destinataires (même opérateur uniquement).</small>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('client/transfert-multiple') ?>" method="post" id="formMulti">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Montant total (Ar)</label>
                            <input type="number" name="montant" id="montant" class="form-control form-control-lg"
                                   min="500" step="100" required>
                        </div>

                        <label class="form-label">Destinataires</label>
                        <div id="listeDestinataires">
                            <div class="input-group mb-2">
                                <input type="text" name="destinataires[]" class="form-control destinataire-input" placeholder="034 XX XXX XX" required>
                                <button type="button" class="btn btn-outline-danger btn-remove" tabindex="-1">&times;</button>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" name="destinataires[]" class="form-control destinataire-input" placeholder="033 XX XXX XX" required>
                                <button type="button" class="btn btn-outline-danger btn-remove" tabindex="-1">&times;</button>
                            </div>
                        </div>
                        <button type="button" id="btnAjouter" class="btn btn-sm btn-outline-primary mb-3">
                            <i class="bi bi-plus"></i> Ajouter un destinataire
                        </button>

                        <div id="recap" class="alert alert-secondary d-none">
                            <div class="d-flex justify-content-between"><span>Nb destinataires :</span><strong id="recapNb"></strong></div>
                            <div class="d-flex justify-content-between"><span>Montant par destinataire :</span><strong id="recapPart"></strong></div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Confirmer l'envoi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const liste       = document.getElementById('listeDestinataires');
    const btnAjouter   = document.getElementById('btnAjouter');
    const montantInput = document.getElementById('montant');
    const recapDiv      = document.getElementById('recap');
    const recapNb        = document.getElementById('recapNb');
    const recapPart      = document.getElementById('recapPart');

    function creerLigne() {
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" name="destinataires[]" class="form-control destinataire-input" placeholder="0XX XX XXX XX" required>
            <button type="button" class="btn btn-outline-danger btn-remove" tabindex="-1">&times;</button>
        `;
        liste.appendChild(div);
    }

    function majRecap() {
        const nb = liste.querySelectorAll('.destinataire-input').length;
        const montant = parseFloat(montantInput.value) || 0;

        if (nb < 2 || montant <= 0) {
            recapDiv.classList.add('d-none');
            return;
        }

        recapNb.textContent = nb;
        recapPart.textContent = (montant / nb).toLocaleString('fr-FR', { maximumFractionDigits: 2 }) + ' Ar (+ frais par envoi)';
        recapDiv.classList.remove('d-none');
    }

    btnAjouter.addEventListener('click', () => { creerLigne(); majRecap(); });

    liste.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-remove')) {
            if (liste.querySelectorAll('.input-group').length > 2) {
                e.target.closest('.input-group').remove();
                majRecap();
            }
        }
    });

    liste.addEventListener('input', majRecap);
    montantInput.addEventListener('input', majRecap);
})();
</script>
<?= $this->endSection() ?>