<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Retrait d'argent</h1>
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

    <form method="post" action="/client/retrait">
        <label for="montant">Montant à retirer :</label>
        <input type="number" id="montant" name="montant" required>
        <button type="submit">Retirer</button>
    </form>
    
</body>
</html>