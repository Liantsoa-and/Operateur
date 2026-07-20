<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert d'argent</title>
</head>
<body>
    <h1>Transferer </h1>
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
    <form action="transfert" method="post">
        <input type="text" name="numero_destinataire">
        <input type="text" name="montant">
        <input type="submit" value="Valider">
    </form>
    
</body>
</html>