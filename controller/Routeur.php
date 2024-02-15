<?php
namespace controller;
use controller\HomeController;
use controller\LoginController;
use controller\RegisterController;
use controller\PublicationsController;
use controller\FriendsController;
use controller\SearchController;
use controller\ProfileController;
use Exception;
use vue\Vue;

require_once __DIR__ . "/../controller/HomeController.php";
require_once __DIR__ . "/../controller/LoginController.php";
require_once __DIR__ . "/../controller/RegisterController.php";
require_once __DIR__ . "/../controller/PublicationsController.php";
require_once __DIR__ . "/../controller/FriendsController.php";
require_once __DIR__ . "/../controller/SearchController.php";
require_once __DIR__ . "/../controller/ProfileController.php";

class Routeur {
    private $homeController;
    private $loginController;
    private $registerController;
    private $publicationsController;
    private $friendsController;
    private $searchController;
    private $profileController;

    public function __construct() {
        $this->homeController = new HomeController();
        $this->loginController = new LoginController();
        $this->registerController = new RegisterController();
        $this->publicationsController = new PublicationsController();
        $this->friendsController = new FriendsController();
        $this->searchController = new SearchController();
        $this->profileController = new ProfileController();
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