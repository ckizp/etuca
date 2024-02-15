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
                        <?php
                            $imageData = $user->getProfilePicture();
                            $imageString = stream_get_contents($imageData);
                            if ($imageData && !empty($imageString)) {
                                $imageBase64 = base64_encode($imageString);
                                $imageSrc = "data:image/png;base64," . $imageBase64;
                                echo "<img src='$imageSrc' alt='Image de profil'>";
                            } else {
                                echo "<img src='web/img/default_profile_picture.png' alt='Image de profil par défaut'>";
                            }
                        ?>
                    <?= $user->getUserName(); ?>
                    </a>
                </li>
            <?php endforeach;
            echo '</ul>';
        }
        ?>
    </div>
</div>