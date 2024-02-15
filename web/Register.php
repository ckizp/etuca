<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./web/css/style.css" type="text/css"/>
    <link rel="stylesheet" href="./web/css/form.css" type="text/css"/>
    <title>S'inscrire sur Etuca</title>
</head>
<body>
    <div id="topbar"></div>
    <nav class="navbar">
        <div class="logo">
            <img src="./web/img/etuca.png" alt="logo">
        </div>
        <div class="links">
            <uL>
                <li><a href="index.php?action=login">Se connecter</a></li>
                <li><a href="#">S'inscrire</a></li>
            </uL>
        </div>
    </nav>
    <main>
        <form method="post" enctype="multipart/form-data" action="index.php?action=register">
            <h1>Inscription</h1>
            <label for="username">Nom d'utilisateur</label>
            <input name="username" type="text" pattern="[A-Za-z0-9_]+" required>
            <label for="firstname">Prénom</label>
            <input name="firstname" type="text" pattern="[A-Za-z]+" required>
            <label for="lastname">Nom de famille</label>
            <input name="lastname" type="text" pattern="[A-Za-z]+" required>
            <label for="email">Adresse email</label>
            <input name="email" type="email" required>
            <label for="phone">Numéro de téléphone</label>
            <input name="phone" type="text" required>
            <label for="password">Mot de passe</label>
            <input name="password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,}" required>
            <label for="picture">Photo de profil</label>
            <input name="picture" type="file" accept="image/*">
            <button type="submit">S'inscrire</button>
            <div>
                Vous avez déjà un compte ?
                <a href="index.php?action=login">Se connecter</a>
            </div>
        </form>
    </main>
    <footer>
        <!-- https://github.com/ckizp -->
        <p>Réalisé avec ♥ par Ibraguim Temirkhaev</p>
    </footer>
</body>
</html>