<?php 
    $this->title = "Etuca - mon profil"; 
?>
<link rel="stylesheet" href="./web/css/profile.css" type="text/css"/>
<script src="web/js/request.js"></script>

<div class="profile">
    <h1><?php echo $user->getUserName(); ?></h1>
    <div>
        <img id='user-picture' src='<?= $user->getProfilePicture()->toURI(); ?>' alt='Image de profil'>
        <img id="edit-picture" src='./web/img/edit.png' alt='Icône de modification'>
    </div>
    <h2><?php echo $user->getFirstName() . " " . $user->getLastName() ?></h2>
    <h3>Adresse email</h3>
    <?php echo "<p>" . $user->getEmail() . "</p>" ?>    
    <h3>Numéro de téléphone</h3>
    <?php echo "<p>" . $user->getPhoneNumber() . "</p>" ?>
    <button type="button" class="submitButton" name="logout" value="<?php echo $user->getUserName(); ?>">Se déconnecter</button>
</div>
<div id="overlay" style="display: none;">
    <div id="action"></div>
</div>