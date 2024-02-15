<?php
namespace controller;
use data_base\DataBase;
use Model\UserModel;
use PDO;

class SearchController {
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
            $response = "<ul>";

            foreach($usersUsername as $userName) {
                $query = "SELECT user_id FROM users WHERE username = :pseudo";
                $parameters = [":pseudo" => $userName];
                $statement = $connexion->prepare($query);
                $statement->execute($parameters);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $id = $result["user_id"];
                $user = new UserModel($id, $connexion);
                $response .= "<li class='friend'><a href='index.php?action=profile&user=$userName'>";
                
                $imageData = $user->getProfilePicture();
                $imageString = stream_get_contents($imageData);
                if ($imageData && !empty($imageString)) {
                    $imageBase64 = base64_encode($imageString);
                    $imageSrc = "data:image/png;base64," . $imageBase64;
                    $response .= "<img src='$imageSrc' alt='Image de profil'>";
                } else {
                    $response .= "<img src='web/img/default_profile_picture.png' alt='Image de profil par défaut'>";
                }
    
                $response .= "$userName</a></li>";
            }

            $response .= "</ul>";

            // Afficher les utilisateurs
            echo $response;
        }
    }
}