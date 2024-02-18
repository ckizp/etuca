<?php
namespace controller;
use data_base\DataBase;
use Model\UserModel;
use vue\Vue;
use PDO;

require_once __DIR__ . "/../web/Vue.php";
require_once __DIR__ . "/../model/UserModel.php";
require_once __DIR__ . "/../data_base/DataBase.php";

class AdminController {
    public function displayAdmin() {
        // Creation de la vue
        $vue = new Vue("Admin");

        $users = $this->getUsers();

        // afficher la vue
        $vue->display(['users' => $users]);
    }

    private function getUsers() : array {
        $connection = DataBase::connect();
        $query = $connection->prepare("SELECT * FROM users ORDER BY user_id DESC LIMIT 10");
        $query->execute();
        $users = [];

        while ($row = $query->fetch()) {
            $user = new UserModel($row["user_id"], $connection);
            $users[] = $user;
        }
        return $users;
    }

    public function displayUserInfos() {
        if (!isset($_GET['user'])) {
            echo "Erreur lors de la récupération de l'utilisateur";
            return;
        }

        // Connexion à la base de données
        $connexion = DataBase::connect();
        $userId = $_GET['user'];

        // On crée l'utilisateur courante
        $user = new UserModel($userId, $connexion);

        // On crée la réponse
        $profileURI = $user->getProfilePicture()->toURI();
        $response = "<div class='infos' id='$userId'>";
        $response .= "<div class='profile-picture'><img src='$profileURI' alt='Image de profil'>" . $this->createImg("delete-photo", "web/img/cross.png", "Icône de suppression") . "</div>";
        $response .= "<ul><li>" . $this->createImg("edit-username", "web/img/edit.png", "Icône d'édition") . "<strong>Nom d'utilisateur :</strong> " . $user->getUserName() . "</li>";
        $response .= "<li>" . $this->createImg("edit-name", "web/img/edit.png", "Icône d'édition") . "<strong>Nom :</strong> " . $user->getFirstName() . " " . $user->getLastName() . "</li>";
        $response .= "<li>" . $this->createImg("edit-email", "web/img/edit.png", "Icône d'édition") . $this->createImg("write-mail", "web/img/mail.png", "Icône de courrier") . "<strong>Email :</strong> " . $user->getEmail() . "</li>";
        $response .= "<li>" . $this->createImg("delete-phone", "web/img/can.png", "Icône de suppression") . "<strong>Numéro de téléphone :</strong> " . $user->getPhoneNumber() . "</li>";
        $response .= "<li>" . $this->createImg("copy", "web/img/copy.png", "Icône de copy") . "<strong>Date d'inscription :</strong> <span id='date'>" . $user->getRegistrationDate() . "</span></li></ul></div>";
    
        $publications = $user->getPublications();
        $response .= "<div class='container'>";
        foreach ($publications as $publication) {
            $response .= "<div class='publication'>";
            $response .= "<h3>" . $publication->getTitle() . "</h3>";
            $response .= "<p>" . $publication->getDescription() . "</p>";
            $response .= "<div class='article-img'><img src='" . $publication->getImage()->toURI() . "' alt='Image de la publication'></div>";
            $response .= "<img class='can' id='" . $publication->getPublicationId() . "' src='web/img/can.png' alt='Icône de suppression'></div>";
        }
        $response .= "</div>";
        if ($user->isBanned())
            $response .= "<button id='unban'>Débannir l'utilisateur</button>";
        else
            $response .= "<button id='ban' class='permanent'>Bannir l'utilisateur</button>";
            
        echo $response;
    }
    
    private function createImg($id, $src, $alt) {
        return "<img class='adminAction' id='$id' src='$src' alt='$alt'>";
    }

    public function deletePhoto() {
        // On récupère l'utilisateur courant
        $user = $this->getUser();
        $user->setProfilePicture('');
        echo $user->getUserName();
    }

    public function deletePhone() {
        // On récupère l'utilisateur courant
        $user = $this->getUser();
        $user->setPhoneNumber('');
        echo $user->getUserName();
    }

    public function writeMail() {
        // On récupère l'utilisateur courant
        $user = $this->getUser();
        $userMail = $user->getEmail();

        $connexion = DataBase::connect();
        $admin = new UserModel($_SESSION['user'], $connexion);
        $adminMail= $admin->getEmail();

        $response = "<form action='index.php?action=send-mail' method='post'>";
        $response .= "<label for='mailFrom'>Mail envoyé par : </label>";
        $response .= "<input type='text' id='mailFrom' name='mailFrom' placeholder='$adminMail' required readonly>";
        $response .= "<label for='mailTo'>Mail envoyé à : </label>";
        $response .= "<input type='text' id='mailTo' name='mailTo' value='$userMail' placeholder='example@etuca.com' required>";
        $response .= "<label for='mailSubject'>Sujet : </label>";
        $response .= "<input type='text' id='mailSubject' name='mailSubject' placeholder='' required>";
        $response .= "<label for='mailContent'>Contenu : </label>";
        $response .= "<textarea id='mailContent' name='mailContent' placeholder='' required></textarea>";
        $response .= "<button type='submit'>Envoyer</button></form>";
        $response .= "<img class='close' src='web/img/cross.png' alt='Icône de fermeture'>";
        
        echo $response;
    }

    public function editEmail() {
        // On récupère l'utilisateur courant
        $user = $this->getUser();

        $response = "<h2>Modifier l'email de " . $user->getUserName() . "</h2>";
        $response .= "<form id='" . $user->getUserId() . "' action='index.php?action=new-mail' method='post'>";
        $response .= "<label for='mail'>Nouvel email : </label>";
        $response .= "<input type='text' id='mail' name='mail' placeholder='example@etuca.com' value='" . $user->getEmail() . "' required>";
        $response .= "<button type='submit' class='permanent'>Modifier</button></form>";
        $response .= "<img class='close' src='web/img/cross.png' alt='Icône de fermeture'>";

        echo $response;
    }
    
    public function editName() {
        // On récupère l'utilisateur courant
        $user = $this->getUser();

        $response = "<h2>Modifier le nom de " . $user->getUserName() . "</h2>";
        $response .= "<form id='" . $user->getUserId() . "' action='index.php?action=new-name' method='post'>";
        $response .= "<label for='firstName'>Nouveau prénom : </label>";
        $response .= "<input type='text' id='firstName' name='firstName' value='" . $user->getFirstName() . "' required>";
        $response .= "<label for='lastName'>Nouveau nom : </label>";
        $response .= "<input type='text' id='lastName' name='lastName' value='" . $user->getLastName() . "' required>";
        $response .= "<button type='submit' class='permanent'>Modifier</button></form>";
        $response .= "<img class='close' src='web/img/cross.png' alt='Icône de fermeture'>";

        echo $response;
    }
    
    public function editUsername() {
        // On récupère l'utilisateur courant
        $user = $this->getUser();

        $response = "<h2>Modifier le nom d'utilisateur de " . $user->getUserName() . "</h2>";
        $response .= "<form id='" . $user->getUserId() . "' action='index.php?action=new-username' method='post'>";
        $response .= "<label for='username'>Nouveau nom d'utilisateur : </label>";
        $response .= "<input type='text' id='username' name='username' value='" . $user->getUserName() . "' required>";
        $response .= "<button type='submit' class='permanent'>Modifier</button></form>";
        $response .= "<img class='close' src='web/img/cross.png' alt='Icône de fermeture'>";

        echo $response;
    }

    private function getUser() {
        if (!isset($_GET['user'])) {
            echo "Erreur lors de la récupération de l'utilisateur";
            return;
        }

        // On se connecté à la base de données
        $connexion = DataBase::connect();
        $userId = $_GET['user'];

        // On crée l'utilisateur courant
        $user = new UserModel($userId, $connexion);

        return $user;
    }

    public function deleteComment() {
        if (!isset($_GET['comment'])) {
            echo "Erreur lors de la récupération du commentaire";
            return;
        }

        $connexion = DataBase::connect();
        $commentId = $_GET['comment'];

        // on supprime le commentaire
        $query = $connexion->prepare("DELETE FROM comments WHERE comment_id = :comment_id");
        $query->bindParam(":comment_id", $commentId);
        $query->execute();
    }

    public function newName() {
        if (!isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['user'])) {
            echo "Erreur lors de la récupération des données";
            return;
        }

        $connexion = DataBase::connect();
        $userId = $_POST['user'];
        $user = new UserModel($userId, $connexion);
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        // on modifie le nom complet
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
    }

    public function newUsername() {
        if (!isset($_POST['username']) || !isset($_POST['user'])) {
            echo "Erreur lors de la récupération des données";
            return;
        }

        $connexion = DataBase::connect();
        $userId = $_POST['user'];
        $user = new UserModel($userId, $connexion);
        $username = $_POST['username'];

        // on modifie le nom d'utilisateur
        $user->setUserName($username);
    }

    public function newEmail() {
        if (!isset($_POST['mail']) || !isset($_POST['user'])) {
            echo "Erreur lors de la récupération des données";
            return;
        }

        $connexion = DataBase::connect();
        $userId = $_POST['user'];
        $user = new UserModel($userId, $connexion);
        $mail = $_POST['mail'];

        // on modifie l'email
        $user->setEmail($mail);
    }

    public function setBanned($b) {
        if (!isset($_GET['user'])) {
            echo "Erreur lors de la récupération de l'utilisateur";
            return;
        }

        $connexion = DataBase::connect();
        $userId = $_GET['user'];
        $user = new UserModel($userId, $connexion);

        // on ban l'utilisateur
        $user->setBan($b);
    }

    public function searchUsers() {
        if(empty($_GET['text'])) {
            echo "<div id='resultSearch' style='display: none;'></div>";
        } else {
            // Connexion à la base de données
            $connexion = DataBase::connect();

            // Créer l'utilisateur courant
            $currentUser = new UserModel($_SESSION['user'], $connexion);

            // Rechercher les utilisateurs
            $request = $connexion->prepare("SELECT * FROM users WHERE username LIKE :text");
            $request->bindValue(":text", "%" . $_GET['text'] . "%");
            $request->execute();
            $users = $request->fetchAll(PDO::FETCH_ASSOC);
            $usersUsername = [];

            // Récuperer les utilisateurs
            foreach($users as $user) {
                if($currentUser->getUserName() != $user['username'])
                    $usersUsername[] = $user['username'];
            }

            // Créer la réponse
            $response = "<ul class='user-list'>";

            foreach($usersUsername as $userName) {
                $query = "SELECT user_id FROM users WHERE username = :pseudo";
                $parameters = [":pseudo" => $userName];
                $statement = $connexion->prepare($query);
                $statement->execute($parameters);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $id = $result["user_id"];
                $user = new UserModel($id, $connexion);

                $response .= "<li id='" . $user->getUserId() . "' class='user " . ($user->isAdmin() ? "admin" : "") . "'>";
                $response .= "<img src='" . $user->getProfilePicture()->toURI() . "' alt='Image de profil'>";
                $response .= "<div><label>" . $user->getUserName() . "</label><p>" . $user->getRegistrationDate() . "</p></div></li>";
            }

            $response .= "</ul>";

            // Afficher les utilisateurs
            echo $response;
        }
    }
}