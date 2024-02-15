<?php
namespace controller;
use data_base\DataBase;
use PDO;
use vue\Vue;

require_once __DIR__ . "/../web/Vue.php";
require_once __DIR__ . "/../model/UserModel.php";
require_once __DIR__ . "/../data_base/DataBase.php";

class LoginController {
    // Fonction pour afficher la page de connexion
    public function displayLogin() {
        $vue = new Vue("Login");

        // Si l'utilisateur est déjà connecté, on le redirige vers la page d'accueil
        if (!isset($_SESSION['user'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $error = $this->login();
                if(isset($_POST['username'])) {
                    $username = $_POST['username'];
                } else {
                    $username = "";
                }
                $redirect = "home";
                if(isset($_GET['redirect']))
                    $redirect = $_GET['redirect'];
                $vue->displayWithoutTemplate(["errors" => $error, "username" => $username, "redirect" => $redirect]);
                exit();
            } else {
                $redirect = "home";
                if(isset($_GET['redirect']))
                    $redirect = $_GET['redirect'];
                $vue->displayWithoutTemplate(["errors" => [], "username" => "", "redirect" => $redirect]);
                exit();
            }
        } else {
            header("Location: index.php?action=home");
            exit();
        }
    }

    // Fonction pour se déconnecter
    public function displayLogout() {
        session_unset();
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }

    // Fonction pour se connecter
    // Retourne un message d'erreur si l'authentification a échoué et null si l'authentification a réussi
    private function login() {
        $connexion = DataBase::connect();
        $commande_verification = $connexion->prepare("SELECT password, user_id FROM users WHERE username = ?");
        $commande_verification->execute([$_POST['username']]);
        
        // Vérifier si un utilisateur correspondant a été trouvé
        $user = $commande_verification->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return "Identifiant incorrect";
        }

        // Vérifier si le mot de passe est correct
        if (!password_verify($_POST['password'], $user['password'])) {
            return "Mot de passe incorrect";
        }
    
        // Authentification réussie, définir la session utilisateur
        $_SESSION['user'] = $user['user_id'];
    
        // Redirection
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'home';
        header('Location: index.php?action=' . $redirect);
        exit();
    }
}