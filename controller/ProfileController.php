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

    public function editPicture() {
        $response = "<h2>Modifier ma photo de profil</h2>";
        $response .= "<form method='post' enctype='multipart/form-data' id='picture-form' action='index.php?action=new-picture'>";
        $response .= "<input type='file' name='picture' id='picture' accept='image/*' required>";
        $response .= "<button type='submit' class='permanent'>Modifier</button></form>";
        $response .= "<img class='close' src='web/img/cross.png' alt='Icône de fermeture'>";

        echo $response;
    }

    public function newPicture() {
        $connexion = DataBase::connect();
        $user = new UserModel($_SESSION['user'], $connexion);

        $blob = null;
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
            $blob = file_get_contents($_FILES['picture']['tmp_name']);
        }
        
        $request = $connexion->prepare("UPDATE users SET profile_picture = :picture WHERE user_id = :user_id");
        $request->bindValue(":picture", $blob, \PDO::PARAM_LOB);
        $request->bindValue(":user_id", $user->getUserId());
        $request->execute();
    }   
}