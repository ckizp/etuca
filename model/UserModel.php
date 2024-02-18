<?php
namespace Model;
use PDO;

require_once __DIR__ . "/AbstractModel.php";
require_once __DIR__ . "/Image.php";
require_once __DIR__ . "/PublicationModel.php";

class UserModel extends AbstractModel {
    private int $id;

    public function __construct(int $id, PDO $dataBase, string $userName = null) {
        parent::__construct($dataBase);
        if ($userName != null) {
            $query = "SELECT user_id FROM users WHERE username = :pseudo";
            $parameters = [":pseudo" => $userName];
            $statement = $this->runQuery($query, $parameters);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $id = $result["user_id"];
        }
        $this->id = $id;
    }

    public function getUserId() : int {
        return $this->id;
    }

    public function getUserName() {
        return $this->runQuery("SELECT username FROM users WHERE user_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getFirstName() {
        return $this->runQuery("SELECT firstname FROM users WHERE user_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getLastName() {
        return $this->runQuery("SELECT lastname FROM users WHERE user_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getPassword() {
        return $this->runQuery("SELECT password FROM users WHERE user_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getEmail() {
        return $this->runQuery("SELECT email FROM users WHERE user_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getPhoneNumber() {
        return $this->runQuery("SELECT phone_number FROM users WHERE user_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getProfilePicture() {
        return new Image($this->runQuery("SELECT profile_picture FROM users WHERE user_id = :id", [":id" => $this->id])->fetchColumn());
    }

    public function isAdmin() {
        return $this->runQuery("SELECT admin FROM users WHERE user_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getRegistrationDate() {
        return $this->runQuery("SELECT registration_date FROM users WHERE user_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function isBanned() {
        return $this->runQuery("SELECT is_banned FROM users WHERE user_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function setUserName($userName) {
        $query = "UPDATE users SET username = :pseudo WHERE user_id = :id";
        $parameters = [":pseudo" => $userName, ":id" => $this->id];
        $this->runQuery($query, $parameters);
    }

    public function setFirstName($firstName) {
        $query = "UPDATE users SET firstname = :firstname WHERE user_id = :id";
        $parameters = [":firstname" => $firstName, ":id" => $this->id];
        $this->runQuery($query, $parameters);
    }

    public function setLastName($lastName) {
        $query = "UPDATE users SET lastname = :lastname WHERE user_id = :id";
        $parameters = [":lastname" => $lastName, ":id" => $this->id];
        $this->runQuery($query, $parameters);
    }

    public function setEmail($email) {
        $query = "UPDATE users SET email = :email WHERE user_id = :id";
        $parameters = [":email" => $email, ":id" => $this->id];
        $this->runQuery($query, $parameters);
    }

    public function setBan(bool $ban) {
        $banValue = $ban ? 'TRUE' : 'FALSE';
        $query = "UPDATE users SET is_banned = :ban WHERE user_id = :id";
        $parameters = [":ban" => $banValue, ":id" => $this->id];
        $this->runQuery($query, $parameters);
    }

    public function setProfilePicture($photo) {
        $query = "UPDATE users SET profile_picture = :photo WHERE user_id = :id";
        $parameters = [":photo" => $photo, ":id" => $this->id];
        $this->runQuery($query, $parameters, PDO::PARAM_LOB);
    }

    public function setPhoneNumber($number) {
        $query = "UPDATE users SET phone_number = :number WHERE user_id = :id";
        $parameters = [":number" => $number, ":id" => $this->id];
        $this->runQuery($query, $parameters);
    }
    
    public function getPublications() : array {
        $query = "SELECT * FROM publications WHERE user_id = :id";
        $parameters = [":id" => $this->id];
        $statement = $this->runQuery($query, $parameters);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $publications = [];
        foreach ($result as $publication) {
            $publications[] = new PublicationModel($publication["publication_id"], $this->getDataBase());
        }
        return $publications;
    }

    public function getFriends() : array {
        $query = "SELECT DISTINCT * FROM friends WHERE friend_a_id = :id OR friend_b_id = :id";
        $parameters = [":id" => $this->id];
        $statement = $this->runQuery($query, $parameters);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $friends = [];
        foreach ($result as $friend) {
            $friends[] = new UserModel($friend[$friend["friend_a_id"] == $this->id ? "friend_b_id" : "friend_a_id"], $this->getDataBase());
        }

        return $friends;
    }

    public function hasLiked(PublicationModel $publication) {
        $query = "SELECT * FROM reactions WHERE user_id = :id AND publication_id = :pubid AND is_like = true";
        $parameters = [":id" => $this->id, ":pubid" => $publication->getPublicationId()];
        $statement = $this->runQuery($query, $parameters);
        return $statement->rowCount() > 0 ? "1" : "0";
    }

    public function hasDisliked(PublicationModel $publication) {
        $query = "SELECT * FROM reactions WHERE user_id = :id AND publication_id = :pubid AND is_like = false";
        $parameters = [":id" => $this->id, ":pubid" => $publication->getPublicationId()];
        $statement = $this->runQuery($query, $parameters);
        return $statement->rowCount() > 0 ? "1" : "0";
    }

    public function isFriendWith(UserModel $user) : bool {
        $query = "SELECT * FROM friends WHERE (friend_a_id = :id_a AND friend_b_id = :id_b) OR (friend_a_id = :id_b AND friend_b_id = :id_a)";
        $parameters = [":id_a" => $this->id, ":id_b" => $user->getUserId()];
        $statement = $this->runQuery($query, $parameters);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return count($result) > 0;
    }

    public function asSendFriendRequestTo(UserModel $user) : bool {
        $query = "SELECT * FROM friend_requests WHERE requester_id = :id_a AND receiver_id = :id_b";
        $parameters = [":id_b" => $user->getUserId(), ":id_a" => $this->id];
        $statement = $this->runQuery($query, $parameters);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return count($result) > 0;
    }

    public function isWaitingToAcceptFriendRequest(UserModel $user) : bool {
        $query = "SELECT * FROM friend_requests WHERE requester_id = :id_b AND receiver_id = :id_a";
        $parameters = [":id_b" => $this->id, ":id_a" => $user->getUserId()];
        $statement = $this->runQuery($query, $parameters);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return count($result) > 0;
    }

    public function isAnyFriendRequestWith(UserModel $user) : bool {
        return $this->asSendFriendRequestTo($user) || $this->isWaitingToAcceptFriendRequest($user);
    }

    public function addFriend(UserModel $user) {
        $query = "INSERT INTO friend_requests(requester_id, receiver_id) VALUES (:id_a, :id_b)";
        $parameters = [":id_a" => $this->id, ":id_b" => $user->getUserId()];
        $this->runQuery($query, $parameters);
    }

    public function cancelFriendRequest(UserModel $user) {
        $query = "DELETE FROM friend_requests WHERE requester_id = :id_a AND receiver_id = :id_b";
        $parameters = [":id_a" => $this->id, ":id_b" => $user->getUserId()];
        $this->runQuery($query, $parameters);
    }

    public function removeFriend(UserModel $user) {
        $query = "DELETE FROM friends WHERE (friend_a_id = :id_a AND friend_b_id = :id_b) OR (friend_a_id = :id_b AND friend_b_id = :id_a)";
        $parameters = [":id_a" => $this->id, ":id_b" => $user->getUserId()];
        $this->runQuery($query, $parameters);
        $query = "DELETE FROM friend_requests WHERE (requester_id = :id_a AND receiver_id = :id_b) OR (requester_id = :id_b AND receiver_id = :id_a)";
        $this->runQuery($query, $parameters);
    }

    public function acceptFriendRequest(UserModel $user) {
        $query = "INSERT INTO friends (friend_a_id, friend_b_id) VALUES (:id_a, :id_b)";
        $parameters = [":id_a" => $this->id, ":id_b" => $user->getUserId()];
        $this->runQuery($query, $parameters);
        $query = "UPDATE friend_requests SET status = 'Accepté' WHERE (requester_id = :id_b AND receiver_id = :id_a) OR (requester_id = :id_a AND receiver_id = :id_b)";
        $this->runQuery($query, $parameters);
    }

    public function declineFriendRequest(UserModel $user) {
        $query = "DELETE FROM friend_requests WHERE (requester_id = :id_b AND receiver_id = :id_a)";
        $parameters = [":id_a" => $this->id, ":id_b" => $user->getUserId()];
        $this->runQuery($query, $parameters);
    }

    public function react(PublicationModel $publication, bool $reaction) {
        $reactionValue = $reaction ? 'TRUE' : 'FALSE';

        $query = "
            INSERT INTO reactions (user_id, publication_id, is_like)
            VALUES (:user_id, :publication_id, :react)
            ON CONFLICT (user_id, publication_id) DO UPDATE
            SET is_like = :react
        ";
        $parameters = [
            ":user_id" => $this->id,
            ":publication_id" => $publication->getPublicationId(),
            ":react" => $reactionValue
        ];
        $this->runQuery($query, $parameters);
    }
}