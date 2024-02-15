<?php
namespace controller;

use data_base\DataBase;
use Model\UserModel;
use vue\Vue;

class FriendsController {
    static $ADD_FRIEND = 1;
    static $REMOVE_FRIEND = 2;
    static $ACCEPT_FRIEND_REQUEST = 3;
    static $DECLINE_FRIEND_REQUEST = 4;
    static $CANCEL_FRIEND_REQUEST = 5;

    public function displayFriends() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login&redirect=friends");
            exit();
        }
        $user = new UserModel($_SESSION['user'], DataBase::connect());
        $vue = new Vue("Friends");
        $friends = $user->getFriends();
        $vue->display(["friends" => $friends]);
    }

    public function friendRequest($action) {
        ob_start();
        $user1 = new UserModel($_SESSION['user'], DataBase::connect());
        $user2 = new UserModel(0, DataBase::connect(), $_POST['user']);
        $currentUser = $user1;
        $user = $user2;

        if (!isset($_POST['user'])) {
            $error = "user not found";
        } else {
            switch ($action) {
                case self::$ADD_FRIEND:
                    if (!$this->addFriend($user1, $user2)) {
                        $error = "Demande d'amis déjà envoyée";
                    }
                    break;
                case self::$REMOVE_FRIEND:
                    if (!$this->removeFriend($user1, $user2)) {
                        $error = "Erreur lors du retrait d'amis";
                    }
                    break;
                case self::$ACCEPT_FRIEND_REQUEST:
                    if (!$this->acceptFriendRequest($user1, $user2)) {
                        $error = "Erreur lors de l'acceptation de la demande d'amis";
                    }
                    break;
                case self::$DECLINE_FRIEND_REQUEST:
                    if (!$this->declineFriendRequest($user1, $user2)) {
                        $error = "Erreur lors du refus de la demande d'amis";
                    }
                    break;
                case self::$CANCEL_FRIEND_REQUEST:
                    if (!$this->cancelFriendRequest($user1, $user2)) {
                        $error = "Erreur lors de l'annulation de la demande d'amis";
                    }
                    break;
                default:
                    echo "action not found";
                    break;
            }
        }
        include __DIR__ . "/../web/FriendShipRequest.php";

        $content = ob_get_clean();

        echo $content;
    }

    public function addFriend(UserModel $user, UserModel $friend) : bool {
        if ($user->isAnyFriendRequestWith($friend)) {
            return false;
        }
        $user->addFriend($friend);
        return true;
    }

    public function cancelFriendRequest($user, $friend) : bool {
        if (!$user->isAnyFriendRequestWith($friend))
            return false;
        $user->cancelFriendRequest($friend);
        return true;
    }

    public function removeFriend($user, $friend) : bool {
        if (!$user->isFriendWith($friend))
            return false;
        $user->removeFriend($friend);
        return true;
    }

    public function acceptFriendRequest($user, $friend) : bool {
        if (!$friend->asSendFriendRequestTo($user)) {
            return false;
        }
        $user->acceptFriendRequest($friend);
        return true;
    }

    public function declineFriendRequest($user, $friend): bool {
        if (!$friend->asSendFriendRequestTo($user))
            return false;
        $user->declineFriendRequest($friend);
        return true;
    }
}