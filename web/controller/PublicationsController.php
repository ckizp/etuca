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

        // On crée la réponse
        $response = "<ul>";

        foreach($comments as $comment) {
            $user = new UserModel($comment->getUserId(), $connexion);
            $response .= "<li class='commenter'>";
            $username = $user->getUserName();
            $response .= "<a href='index.php?action=profile&user=$username'>";
            $imageData = $user->getProfilePicture();
            $imageString = stream_get_contents($imageData);
            if ($imageData) {
                $imageBase64 = base64_encode($imageString);
                $imageSrc = "data:image/png;base64," . $imageBase64;
                $response .= "<img src='$imageSrc' alt='Image de profil de $username'>";        
            } else {
                $response .= "<img src='web/img/default_profile_picture.png' alt='Image de profil par défaut'>";
            }
            //taille de l'image
            $response .= "<style>.commenter img {width: 50px; height: 50px;}</style>";
            $response .= "</a>";
            $content = $comment->getContent();
            $response .= "<div><a href='index.php?action=profile&user=$username'><p class='username'>$username</p></a>";
            $response .= "<p>$content</p></div></li>";
        }

        $response .= "</ul>";

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

        header("Location: index.php?action=home");
        exit();
    }
}