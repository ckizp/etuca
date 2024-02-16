<?php
namespace controller;
use data_base\DataBase;
use Model\PublicationModel;
use Model\UserModel;
use vue\Vue;

require_once __DIR__ . "/../web/Vue.php";
require_once __DIR__ . "/../model/UserModel.php";
require_once __DIR__ . "/../data_base/DataBase.php";

class HomeController {
    public function displayHome() {
        // Creation de la vue
        $vue = new Vue("Home");

        $publications = $this->getPublications();

        // afficher la vue
        $vue->display(['publications' => $publications]);
    }

    private function getPublications() : array {
        $connection = DataBase::connect();
        $query = $connection->prepare("SELECT * FROM publications ORDER BY publication_id DESC LIMIT 10");
        $query->execute();
        $publications = [];
        $current_user = new UserModel($_SESSION["user"], $connection);

        while ($row = $query->fetch()) {
            $temp = new PublicationModel($row["publication_id"], $connection);
            $user = new UserModel($temp->getUserId(), $connection);
            if($temp->isPublic() || $current_user->isAdmin() || $current_user->getUserId() == $user->getUserId() || $current_user->isFriendWith($user)) {
                $publications[] = $temp;
            }
        }
        return $publications;
    }
}