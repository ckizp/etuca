<?php 
    $this->title = "Etuca - publication"; 
    echo '<link rel="stylesheet" href="./web/css/form.css" type="text/css"/>';
?>

<div id="form_block">
    <form method="post" enctype="multipart/form-data" action="index.php?action=publish">
        <h2>Votre publication</h2>
        <label for="titre">Titre</label>
        <input name="titre" type="text" required>
        <label for="description">Description</label>
        <textarea name="description" type="text" pattern="" required ></textarea>

        <label for="image">Photo</label>
        <input name="image" type="file" accept="image/*" required>

        <label for="range">Porté de la Publication :</label>
        <div class="radio-list">
            <div>
                <input type="radio" name="range" value="private" checked>
                <label for="private">Privé</label>
            </div>
            <div>
                <input type="radio" name="range" value="public">
                <label for="public">Public</label>
            </div>
        </div>

        <button type="submit">Publier</button>
    </form>
    <?php
    if(!empty($errors))
    {
        foreach($errors as $error)
        {
            echo "<p>" . $error . "</p>";
        }
    }?>
</div>