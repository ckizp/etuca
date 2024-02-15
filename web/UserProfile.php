<?php 
    $this->title = "Etuca - profil de " . $user->getUserName();; 
    echo '<link rel="stylesheet" href="./web/css/profile.css" type="text/css"/>';
?>

<div class="profile">
    <h1><?php echo $user->getUserName(); ?></h1>
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
    <h2><?php echo $user->getFirstName() . " " . $user->getLastName() ?></h2>
    <div id='friendship'>
        <?php
            require __DIR__ . "/FriendShipRequest.php";
        ?>
    </div>
</div>