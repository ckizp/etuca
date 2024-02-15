<?php 
    $this->title = "Etuca - profil de " . $user->getUserName();; 
    echo '<link rel="stylesheet" href="./web/css/profile.css" type="text/css"/>';
?>

<div class="profile">
    <h1><?php echo $user->getUserName(); ?></h1>
    <img src='<?= $user->getProfilePicture()->toURI(); ?>' alt='Image de profil'>
    <h2><?php echo $user->getFirstName() . " " . $user->getLastName() ?></h2>
    <div id='friendship'>
        <?php
            require __DIR__ . "/FriendShipRequest.php";
        ?>
    </div>
</div>