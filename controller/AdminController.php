<?php
namespace controller;
use data_base\DataBase;
use Model\UserModel;
use vue\Vue;

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
        $response = "<div class='infos'><img src='$profileURI' alt='Image de profil'>";
        $response .= "<ul><li><strong>Nom d'utilisateur :</strong> " . $user->getUserName() . "</li>";
        $response .= "<li><strong>Nom :</strong> " . $user->getFirstName() . " " . $user->getLastName() . "</li>";
        $response .= "<li><strong>Email :</strong> " . $user->getEmail() . "</li>";
        $response .= "<li><strong>Numéro de téléphone :</strong> " . $user->getPhoneNumber() . "</li>";
        $response .= "<li><strong>Date d'inscription :</strong> " . $user->getRegistrationDate() . "</li></ul></div>";
    
        $publications = $user->getPublications();
        $response .= "<div class='container'>";
        foreach ($publications as $publication) {
            $response .= "<div class='publication'>";
            $response .= "<h3>" . $publication->getTitle() . "</h3>";
            $response .= "<p>" . $publication->getDescription() . "</p>";
            $response .= "<div class='article-img'><img src='" . $publication->getImage()->toURI() . "' alt='Image de la publication'></div>";
            $response .= "</div>";
        }
        $response .= "</div>";

        echo $response;
    }
}