<?php 
    $this->title = "Etuca"; 
    echo '<link rel="stylesheet" href="./web/css/publications.css" type="text/css"/>';
?>
<script src="web/js/comment.js"></script>

<div class="container">
    <?php
    foreach ($publications as $publication) :
        $pubid = $publication->getPublicationId();
    ?>
        <article pubid="<?= htmlspecialchars($pubid); ?>">
             <?php
                $author = new \Model\UserModel($publication->getUserId(), \data_base\DataBase::connect());
                echo "posté par <a href='index.php?action=profile&user=" . $author->getUserName(). "'>" . $author->getUserName() . "</a>";
            ?>

            <h2><?= $publication->getTitle() ?></h2>
            <?php echo "<p>" . $publication->getDescription() . "</p>"; ?>

            <div class="article-img">
                <img src="<?= $publication->getImage()->toURI() ?>" alt="Image de la publication">
            </div>
            <div class="reactions">
                <button class="like" onclick="react('like', <?= $pubid ?>)">J'aime</button>
                <button class="dislike" onclick="react('dislike', <?= $pubid ?>)">Je n'aime pas</button>
            </div>
            
            <div> 
                <a href="#" class="show-comment" data-pubid="<?= $pubid ?>" style="text-decoration:none; color: inherit;">Voir les commentaires</a>
                <div class="comment-section" id="comment-section-<?= $pubid ?>" style="display:none;">
                    <div class="comments" id="comments-<?= $pubid ?>"></div>
                    <form action="index.php?action=comment" method="post">
                        <input type="hidden" name="publication" value="<?= $pubid ?>">
                        <textarea name="content" cols="20" rows="1"></textarea>
                        <input type="submit" value="Commenter">
                    </form>
                </div>
            </div>

        </article>
    <?php endforeach; ?>
</div>