<?php
namespace Model;
use PDO;

require_once __DIR__ . "/AbstractModel.php";
require_once __DIR__ . "/Reaction.php";
require_once __DIR__ . "/CommentModel.php";

class PublicationModel extends AbstractModel {
    private int $id;

    public function __construct(int $id, PDO $dataBase) {
        $this->id = $id;
        parent::__construct($dataBase);
    }

    public function getUserId() {
        return $this->runQuery("SELECT user_id FROM publications WHERE publication_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getPublicationId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->runQuery("SELECT title FROM publications WHERE publication_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getDescription() {
        return $this->runQuery("SELECT description FROM publications WHERE publication_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getImage() {
        return $this->runQuery("SELECT image FROM publications WHERE publication_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getReactions() {
        $query = "SELECT * FROM reactions WHERE publication_id = :id";
        $parameters = [":id" => $this->id];
        $statement = $this->runQuery($query, $parameters);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $reactions = [];
        foreach ($result as $reaction) {
            $reactions[] = new Reaction($reaction["reaction_id"], $this->getDataBase());
        }
        return $reactions;
    }

    public function getComments() {
        $query = "SELECT * FROM comments WHERE publication_id = :id";
        $parameters = [":id" => $this->id];
        $statement = $this->runQuery($query, $parameters);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $comments = [];
        foreach ($result as $comment) {
            $comments[] = new CommentModel($comment["comment_id"], $this->getDataBase());
        }
        return $comments;
    }

    public function isPublic() : bool {
        return (int)$this->runQuery("SELECT public FROM publications WHERE publication_id = :id", [":id" => $this->id])->fetchColumn() === 1;
    }

    public function getLikes() {
        $query = "SELECT * FROM reactions WHERE publication_id = :id AND like = 1";
        $parameters = [":id" => $this->id];
        $statement = $this->runQuery($query, $parameters);
        return $statement->rowCount();
    }

    public function getDislikes() {
        $query = "SELECT * FROM reactions WHERE publication_id = :id AND like = 0";
        $parameters = [":id" => $this->id];
        $statement = $this->runQuery($query, $parameters);
        return $statement->rowCount();
    }
}