<?php 
    $this->title = "Etuca - mon profil"; 
    echo '<link rel="stylesheet" href="./web/css/profile.css" type="text/css"/>';
?>

<div class="profile">
    <h1><?php echo $user->getUserName(); ?></h1>
    <img src='<?= $user->getProfilePicture()->toURI(); ?>' alt='Image de profil'>
    <h2><?php echo $user->getFirstName() . " " . $user->getLastName() ?></h2>
    <h3>Adresse email</h3>
    <?php echo "<p>" . $user->getEmail() . "</p>" ?>    
    <h3>Numéro de téléphone</h3>
    <?php echo "<p>" . $user->getPhoneNumber() . "</p>" ?>
    <button type="button" class="submitButton" name="logout" value="<?php echo $user->getUserName(); ?>">Se déconnecter</button>
</div>