<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./web/css/style.css" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="web/js/search.js"></script>
    <script src="web/js/request.js"></script>
    <title><?= $title ?></title>
</head>
<body>
    <div id="topbar"></div>
    <nav class="navbar">
        <div class="logo">
            <img src="./web/img/etuca.png" alt="logo">
        </div>
        <div class="links">
            <uL>
                <li><a href="index.php?action=home">Accueil</a></li>
                <li><a href="index.php?action=friends">Mes amis</a></li>
                <li><a href="index.php?action=publish">Publier</a></li>
                <li><a href="index.php?action=profile">Mon profil</a></li>
            </uL>
        </div>
    </nav>
    <menu>
        <?= $content ?>
    </menu>
    <footer>
        <!-- https://github.com/ckizp -->
        <p>Réalisé avec ♥ par Ibraguim Temirkhaev</p>
    </footer>
</body>
</html>