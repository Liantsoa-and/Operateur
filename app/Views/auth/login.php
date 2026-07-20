<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; 
               display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .card { background: #fff; padding: 40px; border-radius: 12px; 
                box-shadow: 0 2px 8px rgba(0,0,0,.1); width: 100%; max-width: 380px; }
        h1 { font-size: 1.3rem; margin-bottom: 8px; }
        p { color: #888; font-size: .9rem; margin-bottom: 24px; }
        label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: 6px; }
        input { width: 100%; padding: 10px 14px; border: 1px solid #ddd; 
                border-radius: 8px; font-size: 1rem; margin-bottom: 16px; }
        button { width: 100%; padding: 12px; background: #1a73e8; color: #fff; 
                 border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #1558b0; }
        .error { background: #fdecea; color: #e53935; padding: 10px 14px; 
                 border-radius: 8px; font-size: .85rem; margin-bottom: 16px; }
    </style>
</head>
<body>
<div class="card">
    <h1>Mobile Money</h1>
    <p>Entrez votre numéro pour vous connecter</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form action="/login" method="post">
        <?= csrf_field() ?>
        <label for="numero">Numéro de téléphone</label>
        <input type="text" id="numero" name="numero" 
               placeholder="034XXXXXXXX" autocomplete="off">
        <button type="submit">Se connecter</button>
    </form>
</div>
</body>
</html>