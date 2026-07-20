<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Solde</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: white;
            font-size: 24px;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .info-client {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .info-client p {
            margin: 5px 0;
            color: #333;
        }

        .info-client strong {
            color: #667eea;
        }

        .solde-section {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
        }

        .solde-label {
            font-size: 14px;
            color: #999;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .solde-amount {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }

        .solde-currency {
            font-size: 18px;
            color: #999;
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }

        .action-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert-success {
            background-color: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            font-size: 22px;
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #999;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="number"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .loading {
            display: none;
            text-align: center;
            color: #667eea;
            font-size: 14px;
        }

        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .info-depot {
            background-color: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #666;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Mobile Money</h1>
            <a href="historique" class="logout-btn">Voir historique</a>
            <a href="/logout" class="logout-btn">Déconnexion</a>
        </div>



        <!-- Alerts -->
        <?php if (session()->has('error')): ?>
            <div class="alert alert-error">
                <strong>Erreur :</strong> <?= esc(session('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                ✓ <?= esc(session('success')) ?>
            </div>
        <?php endif; ?>

        <!-- Card Principal -->
        <div class="card">
            <!-- Info Client -->
            <div class="info-client">
                <p><strong>Numéro de téléphone :</strong> <?= esc($numero) ?></p>
            </div>

            <!-- Section Solde -->
            <div class="solde-section">
                <div class="solde-label">Votre Solde</div>
                <div class="solde-amount"><?= number_format($solde, 0, ',', ' ') ?></div>
                <div class="solde-currency">Ariary (Ar)</div>
            </div>

            <!-- Actions -->
            <div class="actions">
                <button class="action-btn" onclick="openDepotModal()">💰 Dépôt</button>
                <button class="action-btn" onclick="window.location='/client/retrait'">💸 Retrait</button>
                <button class="action-btn" onclick="window.location='/client/transfert'">📤 Transfert</button>
            </div>
        </div>
    </div>

    <!-- Modal Dépôt -->
    <div id="depotModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Faire un Dépôt</h2>
                <button class="close-btn" onclick="closeDepotModal()">&times;</button>
            </div>

            <div class="info-depot">
                <strong>ℹ️ Info :</strong> Les dépôts n'ont pas de frais. Le montant sera crédité immédiatement sur votre solde.
            </div>

            <form id="depotForm">
                <div class="form-group">
                    <label for="montant">Montant (Ar)</label>
                    <input 
                        type="number" 
                        id="montant" 
                        name="montant" 
                        placeholder="Ex: 50000" 
                        required 
                        step="100"
                        min="100"
                    >
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">Confirmer le Dépôt</button>

                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <span>Traitement en cours...</span>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('depotModal');
        const form = document.getElementById('depotForm');
        const submitBtn = document.getElementById('submitBtn');
        const loading = document.getElementById('loading');
        const montantInput = document.getElementById('montant');

        function openDepotModal() {
            modal.classList.add('show');
            montantInput.focus();
        }

        function closeDepotModal() {
            modal.classList.remove('show');
            form.reset();
            loading.style.display = 'none';
            submitBtn.style.display = 'block';
        }

        // Fermer la modal en cliquant en dehors
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeDepotModal();
            }
        });

        // Gérer la soumission du formulaire
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const montant = parseFloat(montantInput.value);

            if (!montant || montant <= 0) {
                alert('Veuillez saisir un montant valide.');
                return;
            }

            // Afficher le chargement
            submitBtn.style.display = 'none';
            loading.style.display = 'block';

            // Envoyer la requête AJAX
            fetch('/client/depot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'montant': montant
                })
            })
            .then(response => {
                // Si redirection (succès), laisser CodeIgniter faire la redirection
                if (response.ok) {
                    window.location.reload();
                }
                return response.text();
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur s\'est produite. Veuillez réessayer.');
                submitBtn.style.display = 'block';
                loading.style.display = 'none';
            });
        });
    </script>
</body>
</html>