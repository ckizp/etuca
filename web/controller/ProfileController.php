<?php
namespace controller;
use data_base\DataBase;
use Model\UserModel;
use vue\Vue;

class ProfileController {
    public function displayProfile() {
        $connexion = DataBase::connect();
        $user = new UserModel($_SESSION['user'], $connexion);
        if (isset($_GET['user']) && $_GET['user'] == $user->getUserName() || !isset($_GET['user'])) {
            $this->displayMyProfile();
            exit();
        }

        //Verifier si l'utilisateur existe dans la base de donnees
        $request = $connexion->prepare("SELECT * FROM users WHERE username = :username");
        $request->bindValue(":username", $_GET['user']);
        $request->execute();
        $user = $request->fetch();
        if (empty($user)) {
            header("Location: index.php?action=home");
            exit();
        }
        $user = new UserModel($user['user_id'], $connexion);
        $this->displayProfileUser($user);
    }

    public function displayMyProfile() {
        $vue = new Vue("MyProfile");
        $vue->display(["user" => new UserModel($_SESSION["user"], DataBase::connect())]);
    }

    public function displayProfileUser($user) {
        $vue = new Vue("UserProfile");
        $vue->display(["user" => $user, "currentUser" => new UserModel($_SESSION["user"], DataBase::connect())]);
    }
}