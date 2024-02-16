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
                <?php
                $imageData = $publication->getImage();

                if ($imageData) {
                    $imageString = stream_get_contents($imageData);
                    $imageBase64 = base64_encode($imageString);
                    $imageSrc = "data:image/png;base64," . $imageBase64;
                    echo "<img src='$imageSrc' alt='Image de la publication'>";
                } else {
                    echo "<p>Aucune image disponible pour cette publication</p>";
                }
                ?>
            </div>
            <div class="reactions">
                <button class="like" onclick="react('like', <?= $pubid ?>)">J'aime</button>
                <button class="dislike" onclick="react('dislike', <?= $pubid ?>)">Je n'aime pas</button>
            </div>
            
            <div> 
                <h3 id="show-comment-<?= $pubid ?>">Voir les commentaires</h3>
                <div id="comment-section">
                    <div id="comment-section-<?= $pubid ?>">
                        <div id="comments"></div>
                    </div>
                    <form action="index.php?action=comment" method="post">
                        <input type="hidden" name="publication" value="<?= $pubid ?>">
                        <textarea name="content" id="content" cols="30" rows="1"></textarea>
                        <input type="submit" value="Commenter">
                    </form>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</div>