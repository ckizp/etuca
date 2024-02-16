<?php 
    $this->title = "Etuca - admin panel"; 
?>
<link rel="stylesheet" href="./web/css/admin.css" type="text/css"/>
<script src="web/js/admin.js"></script>

<div class="admin-panel">
    <div class="users">
        <label for="inputSearch">Rechercher un utilisateur : </label>
        <input type="text" id="inputSearch" placeholder="Chercher...">
        <div id="resultSearch" style="display: none;"></div>
        <ul class="user-list">
            <?php
                foreach ($users as $user) : ?>
                    <li id="<?= $user->getUserId() ?>" class="user <?php if ($user->isAdmin()) : "admin"; endif; ?>">
                        <img src='<?= $user->getProfilePicture()->toURI(); ?>' alt='Image de profil'>
                        <div>
                            <label><?= $user->getUserName(); ?></label>
                            <p><?= $user->getRegistrationDate(); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
        </ul>
    </div>
    <div id="user-activity"></div>
</div>
<div class="mail-sender"></div>