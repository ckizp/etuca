<?php 
    $this->title = "Etuca"; 
    echo '<link rel="stylesheet" href="./web/css/publications.css" type="text/css"/>';
    echo '<link rel="stylesheet" href="./web/css/form.css" type="text/css"/>';

    use \Model\UserModel;
    use \data_base\DataBase;

    $connexion = DataBase::connect();
    $user = new UserModel($_SESSION['user'], $connexion);
?>
<script src="web/js/comment.js"></script>

<div class="container">
    <?php
    foreach ($publications as $publication) :
        $pubid = $publication->getPublicationId();
    ?>
        <article pubid="<?= htmlspecialchars($pubid); ?>">
             <?php
                $author = new UserModel($publication->getUserId(), DataBase::connect());
                echo "<div class='author'><a href='index.php?action=profile&user=" . $author->getUserName(). "'>";
                echo "<img src='" . $author->getProfilePicture()->toURI() . "'><p>" . $author->getUserName() . "</p></a></div>"
            ?>
            <h2 class="publication-title"><?= $publication->getTitle() ?></h2>
            <?php echo "<p>" . $publication->getDescription() . "</p>"; ?>

            <div class="article-img">
                <img src="<?= $publication->getImage()->toURI() ?>" alt="Image de la publication">
            </div>
            <div class="reactions">
                <div class="like" id="<?= $pubid; ?>">
                    <img src="./web/img/<?= $user->hasLiked($publication); ?>.png">
                    <?= $publication->getLikes() ?>
                </div>
                <div class="dislike" id="<?= $pubid; ?>">
                    <img src="./web/img/<?= $user->hasDisliked($publication); ?>.png">
                    <?= $publication->getDislikes() ?>
                </div>
            </div>
            <a id="<?= $pubid ?>" class="show-comment">Voir les commentaires</a>
        </article>
    <?php endforeach; ?>
    <div id="overlay" style="display: none;">
        <div id="action"></div>
    </div>
</div>