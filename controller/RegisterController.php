<?php
namespace controller;
use data_base\DataBase;
use Exception;
use PDO;
use vue\Vue;

require_once __DIR__ . "/../web/Vue.php";
require_once __DIR__ . "/../model/UserModel.php";
require_once __DIR__ . "/../data_base/DataBase.php";

class RegisterController {
    public function displayRegister() {
        if (isset($_SESSION['user'])) {
            header("Location: index.php?action=home");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->register();
        } else {
            $vue = new Vue("Register");
            $vue->displayWithoutTemplate([]);
        }
    }

    private function register() {
        $connexion = DataBase::connect();
        $mdp_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // On vérifie si l'utilisateur a téléchargé une photo de profil
        $blob = null;
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
            $blob = file_get_contents($_FILES['picture']['tmp_name']);
        }

        // On récupère les informations de l'utilisateur du form et on insère dans la base de données.
        try {
            $commande_insertion = $connexion->prepare("INSERT INTO users (username, firstname, lastname, email, password, phone_number, profile_picture) VALUES (:username, :firstname, :lastname, :email, :password, :phone, :picture)");
            $commande_insertion->bindParam(':username', $_POST['username']);
            $commande_insertion->bindParam(':firstname', $_POST['firstname']);
            $commande_insertion->bindParam(':lastname', $_POST['lastname']);
            $commande_insertion->bindParam(':email', $_POST['email']);
            $commande_insertion->bindParam(':password', $mdp_hashed);
            $commande_insertion->bindParam(':phone', $_POST['phone']);
            $commande_insertion->bindParam(':picture', $blob, PDO::PARAM_LOB);
            $commande_insertion->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        // On récupère l'id de l'utilisateur pour le connecter
        try {
            $query = "SELECT user_id FROM users WHERE username = :username";
            $statement = $connexion->prepare($query);
            $pseudo = $_POST['username'];
            $statement->execute([":username" => $pseudo]);
            $_SESSION['user'] = $statement->fetch()['user_id'];
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        header('Location: index.php?action=register');
        exit();
    }
}