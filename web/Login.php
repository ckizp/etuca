<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="web/css/style.css" type="text/css"/>
    <link rel="stylesheet" href="web/css/form.css" type="text/css"/>
    <title>Se connecter sur Etuca</title>
</head>
<body>
    <div id="topbar"></div>
    <nav class="navbar">
        <div class="logo">
            <img src="./web/img/etuca.png" alt="logo">
        </div>
        <div class="links">
            <uL>
                <li><a href="#">Se connecter</a></li>
                <li><a href="index.php?action=register">S'inscrire</a></li>
            </uL>
        </div>
    </nav>
    <main>
        <form method="post" enctype="multipart/form-data" action="index.php?action=login&redirect=<?php echo $redirect; ?>">
            <h1>Créer votre compte</h1>
            <label for="username">Nom d'utilisateur</label>
            <input name="username" type="text" pattern="[A-Za-z0-9_]+" required value="<?php echo htmlspecialchars($username); ?>">
            <label for="password">Mot de passe</label>
            <input name="password" type="password" required>
            <p><?php if(!empty($errors)) echo $errors ?></p>
            <button type="submit">Se connecter</button>
            <div>
                Vous n'avez pas de compte ?
                <a href="index.php?action=register">S'inscrire'</a>
            </div>
        </form>
    </main>
    <footer>
        <!-- https://github.com/ckizp -->
        <p>Réalisé avec ♥ par Ibraguim Temirkhaev</p>
    </footer>
</body>
</html>

