<?php
if ($user->isFriendWith($currentUser)) {
    ?>
        <button type="button" class="submitButton" name="unfriend" value="<?php echo $user->getUserName(); ?>">Retirer de mes amis</button>
    <?php
} elseif ($user->isWaitingToAcceptFriendRequest($currentUser)) {
    ?>
        <button type="button" class="submitButton" id="acceptButton" name="acceptfriend" value="<?php echo $user->getUserName(); ?>">Accepter</button>
        <button type="button" class="submitButton" id="declineButton" name="declinefriend" value="<?php echo $user->getUserName(); ?>">Refuser</button>
    <?php
} elseif ($currentUser->asSendFriendRequestTo($user)) {
    ?>
        <button type="button" class="submitButton" name="cancelrequest" value="<?php echo $user->getUserName(); ?>">Annuler la demande d'ami</button>
    <?php
} else {
    ?>
        <button type="button" class="submitButton" name="addfriend" value="<?php echo $user->getUserName(); ?>">Demander en ami</button>
    <?php
}
?>
<p id="error"><?php if(!empty($errors)) echo $errors ?></p>