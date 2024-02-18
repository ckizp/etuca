<?php
namespace controller;
use data_base\DataBase;
use Model\PublicationModel;
use Model\UserModel;
use vue\Vue;
use PDO;

class PublicationsController {
    public function displayPublish() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->publish();
        } else {
            $errors = [];
        }
        $vue = new Vue("Publish");
        $vue->display(["errors" => $errors]);
    }

    public function displayPublications() {

    }

    private function publish() {
        $errors = [];
        $public_post = 0;
        $blob = null;

        if (!isset($_POST['titre']) || empty($_POST['titre'])) {
            $errors[] = "Le titre est obligatoire";
        }

        if (!isset($_POST['description']) || empty($_POST['description'])) {
            $errors[] = "La description est obligatoire";
        }

        if (isset($_POST["range"])) {
            $public_post = (strcmp($_POST["range"], "public") == 0) ? 1 : 0;
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $blob = file_get_contents($_FILES['image']['tmp_name']);
        }

        if (!empty($errors)) {
            return $errors;
        }

        $connection = DataBase::connect();
        $query = $connection->prepare("INSERT INTO publications (title, description, image, public, user_id) VALUES (:titre, :description, :image, :public, :id_utilisateur)");
        $query->bindParam(":titre", $_POST['titre']);
        $query->bindParam(":description", $_POST['description']);
        $query->bindParam(":image", $blob, PDO::PARAM_LOB);
        $query->bindParam(":public", $public_post);
        $query->bindParam(":id_utilisateur", $_SESSION['user']);
        $query->execute();

        header("Location: index.php?action=home");
        exit();
    }

    public function displayComments() {
        if (!isset($_GET['publication'])) {
            echo "Erreur lors de la récupération de la publication";
            return;
        }

        // Connexion à la base de données
        $connexion = DataBase::connect();
        $publicationId = $_GET['publication'];

        // On crée la publication courante
        $publication = new PublicationModel($publicationId, $connexion);
        $comments = $publication->getComments();

        $session = new UserModel($_SESSION['user'], $connexion);

        // On crée la réponse
        $response = "<h2>Commentaires</h2><ul>";

        foreach($comments as $comment) {
            $user = new UserModel($comment->getUserId(), $connexion);
            $response .= "<li class='comment'>";
            $username = $user->getUserName();
            $response .= "<a href='index.php?action=profile&user=$username'>";
            $response .= "<img src='" . $user->getProfilePicture()->toURI() . "' alt='Image de profil de $username'>";        
            $response .= "</a>";
            $content = $comment->getContent();
            $response .= "<div>";
            if ($session->isAdmin()) {
                $response .= "<img class='delete-comment' id='" . $comment->getId() . "' src='web/img/can.png' alt='Icône de suppression'>";
            }
            $response .= "<a href='index.php?action=profile&user=$username'><p class='username'>$username</p></a>";
            $response .= "<p>$content</p><p class='date'>" . $comment->getTimeStamp() . "</p></div></li>";
        }

        $response .= "</ul>";

        $response .= "<form id='send-comment' pubid='" . $publicationId . "' action='index.php?action=comment' method='post'>";
        $response .= "<input type='hidden' name='publication' value='$publicationId'>";
        $response .= "<textarea name='content' placeholder='Votre commentaire'></textarea>";
        $response .= "<button id='" . $publicationId . "'type='submit'>Commenter</button></form>";

        $response .= "<img class='close' src='web/img/cross.png' alt='Icône de fermeture'>";

        // On affiche la réponse
        echo $response;
    }

    public function comment() {
        if (!isset($_POST['content']) || empty($_POST['content'])) {
            echo "Erreur lors de la récupération du commentaire";
            return;
        }

        if (!isset($_POST['publication']) || empty($_POST['publication'])) {
            echo "Erreur lors de la récupération de la publication";
            return;
        }

        $connexion = DataBase::connect();
        $publicationId = $_POST['publication'];
        $userId = $_SESSION['user'];
        $content = $_POST['content'];
        $comm_date = date("Y-m-d H:i:s");

        $query = $connexion->prepare("INSERT INTO comments (content, user_id, publication_id, comment_date) VALUES (:content, :user_id, :publication_id, :comment_date)");
        $query->bindParam(":content", $content);
        $query->bindParam(":user_id", $userId);
        $query->bindParam(":publication_id", $publicationId);
        $query->bindParam(":comment_date", $comm_date);
        $query->execute();

        exit();
    }

    public function react($reaction) {
        if (!isset($_GET['publication'])) {
            echo "Erreur lors de la récupération de la publication";
            return;
        }
    
        $connexion = DataBase::connect();
        $publicationId = $_GET['publication'];
        $publication = new PublicationModel($publicationId, $connexion);

        $userId = $_SESSION['user'];
        $user = new UserModel($userId, $connexion);
    
        $user->react($publication, $reaction);
    
        header("Location: index.php?action=home");
        exit();
    }
}