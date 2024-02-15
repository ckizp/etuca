<?php $this->title = "Etuca - vos amis"; ?>

<div class="searchFriends">
    <div>
        <label for="inputSearch">Rechercher un utilisateur : </label>
        <input type="text" id="inputSearch" placeholder="Chercher...">
        <div id="resultSearch" style="display: none;"></div>
    </div>

    <div>
        <?php
        if (empty($friends)) {
            echo "<p>Vous n'avez pas d'amis</p>";
        } else {
            echo "<h3>Vos amis :</h3>";
            echo '<ul>';
            foreach ($friends as $user) : ?>
                <li class="friend">
                    <a href="index.php?action=profile&user=<?= $user->getUserName(); ?>">
                    <img src='<?= $user->getProfilePicture()->toURI(); ?>' alt='Image de profil'>
                    <?= $user->getUserName(); ?>
                    </a>
                </li>
            <?php endforeach;
            echo '</ul>';
        }
        ?>
    </div>
</div>