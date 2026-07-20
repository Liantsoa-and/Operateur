<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrait d'argent</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container { max-width: 500px; margin: 0 auto; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { color: white; font-size: 24px; }
        .nav-btn {
            background: rgba(255,255,255,.2); color: #fff; border: 1px solid rgba(255,255,255,.3);
            padding: 8px 16px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 13px;
            transition: all .3s ease;
        }
        .nav-btn:hover { background: rgba(255,255,255,.3); }

        .card {
            background: #fff; border-radius: 8px; padding: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,.2); margin-bottom: 20px;
        }

        .card-title { font-size: 20px; font-weight: 600; color: #333; margin-bottom: 6px; }
        .card-subtitle { font-size: 14px; color: #999; margin-bottom: 25px; }

        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px; }
        input[type="number"] {
            width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px;
            font-size: 16px; transition: border-color .3s ease;
        }
        input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.1); }

        .submit-btn {
            width: 100%; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; border: none; border-radius: 4px; font-size: 16px; font-weight: 600;
            cursor: pointer; transition: transform .2s ease, box-shadow .2s ease;
        }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102,126,234,.4); }

        .alert { padding: 12px 15px; border-radius: 4px; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background-color: #fee; color: #c33; border: 1px solid #fcc; }
        .alert-success { background-color: #efe; color: #3c3; border: 1px solid #cfc; }

        .info-box {
            background: #f0f4ff; border-left: 4px solid #667eea; padding: 12px;
            border-radius: 4px; margin-bottom: 20px; font-size: 13px; color: #666; line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mobile Money</h1>
            <a href="/client/solde" class="nav-btn">← Retour</a>
        </div>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-error">
                <strong>Erreur :</strong> <?= esc(session('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                <?= esc(session('success')) ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-title">Retrait d'argent</div>
            <div class="card-subtitle">Retirer de l'argent de votre compte</div>

            <div class="info-box">
                <strong>Info :</strong> Des frais seront déduits selon le barème en vigueur. Le total débité sera montant + frais.
            </div>

            <form action="/client/retrait" method="post">
                <div class="form-group">
                    <label for="montant">Montant à retirer (Ar)</label>
                    <input type="number" id="montant" name="montant" placeholder="Ex: 50000" required min="100" step="100">
                </div>
                <button type="submit" class="submit-btn">Confirmer le retrait</button>
            </form>
        </div>
    </div>
</body>
</html>
