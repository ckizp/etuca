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
}