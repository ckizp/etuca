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

            <?php
                $author = new \Model\UserModel($publication->getUserId(), \data_base\DataBase::connect());
                echo "posté par <a href='index.php?action=profile&user=" . $author->getUserName(). "'>" . $author->getUserName() . "</a>";
            ?>
        </article>
    <?php endforeach; ?>
    <div id="comments"></div>
</div>