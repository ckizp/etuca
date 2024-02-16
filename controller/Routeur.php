<?php
namespace controller;
use controller\HomeController;
use controller\LoginController;
use controller\RegisterController;
use controller\PublicationsController;
use controller\FriendsController;
use controller\SearchController;
use controller\ProfileController;
use controller\AdminController;
use data_base\DataBase;
use Exception;
use Model\UserModel;
use vue\Vue;

require_once __DIR__ . "/../controller/HomeController.php";
require_once __DIR__ . "/../controller/LoginController.php";
require_once __DIR__ . "/../controller/RegisterController.php";
require_once __DIR__ . "/../controller/PublicationsController.php";
require_once __DIR__ . "/../controller/FriendsController.php";
require_once __DIR__ . "/../controller/SearchController.php";
require_once __DIR__ . "/../controller/ProfileController.php";
require_once __DIR__ . "/../controller/AdminController.php";
require_once __DIR__ . "/../data_base/DataBase.php";

class Routeur {
    private $homeController;
    private $loginController;
    private $registerController;
    private $publicationsController;
    private $friendsController;
    private $searchController;
    private $profileController;
    private $adminController;

    public function __construct() {
        $this->homeController = new HomeController();
        $this->loginController = new LoginController();
        $this->registerController = new RegisterController();
        $this->publicationsController = new PublicationsController();
        $this->friendsController = new FriendsController();
        $this->searchController = new SearchController();
        $this->profileController = new ProfileController();
        $this->adminController = new AdminController();
    }

    // Fonction pour gérer les requêtes
    public function handleRequest() {
        try {
            if (!isset($_SESSION['user'])) {
                if (!empty($_GET["action"]) && $_GET["action"] == "register")
                    $this->registerController->displayRegister();
                else
                    $this->loginController->displayLogin();
                return;
            }

            if (!isset($_GET["action"])) {
                $vue = new Vue("404");
                $vue->displayWithoutTemplate([]);
                return;
            }

            $connexion = DataBase::connect();

            switch($_GET["action"]) {
                case "home":
                    $this->homeController->displayHome();
                    break;
                case "login":
                    $this->loginController->displayLogin();
                    break;
                case "logout":
                    $this->loginController->displayLogout();
                    break;
                case "register":
                    $this->registerController->displayRegister();
                    break;
                case "publish":
                    $this->publicationsController->displayPublish();
                    break;
                case "friends":
                    $this->friendsController->displayFriends();
                    break;
                case "search":
                    $this->searchController->searchUsers();
                    break;
                case "profile":
                    $this->profileController->displayProfile();
                    break;
                case "addfriend":
                    $this->friendsController->friendRequest(FriendsController::$ADD_FRIEND);
                    break;
                case "cancelrequest":
                    $this->friendsController->friendRequest(FriendsController::$CANCEL_FRIEND_REQUEST);
                    break;
                case "acceptfriend":
                    $this->friendsController->friendRequest(FriendsController::$ACCEPT_FRIEND_REQUEST);
                    break;
                case "declinefriend":
                    $this->friendsController->friendRequest(FriendsController::$DECLINE_FRIEND_REQUEST);
                    break;
                case "unfriend":
                    $this->friendsController->friendRequest(FriendsController::$REMOVE_FRIEND);
                    break;
                case "comments":
                    $this->publicationsController->displayComments();
                    break;
                case "comment":
                    $this->publicationsController->comment();
                    break;
                case "admin":
                    $user = new UserModel($_SESSION['user'], $connexion);
                    if ($user->isAdmin())
                        $this->adminController->displayAdmin();
                    else {
                        $vue = new Vue("404");
                        $vue->displayWithoutTemplate([]);
                    }
                    break;
                case "userinfos":
                    $user = new UserModel($_SESSION['user'], $connexion);
                    if ($user->isAdmin())
                        $this->adminController->displayUserInfos();
                    else {
                        $vue = new Vue("404");
                        $vue->displayWithoutTemplate([]);
                    }
                    break;
                default:
                    $vue = new Vue("404");
                    $vue->displayWithoutTemplate([]);
                    break;
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}