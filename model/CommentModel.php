<?php
namespace Model;
use PDO;

require_once __DIR__ . "/AbstractModel.php";
require_once __DIR__ . "/Image.php";

class CommentModel extends AbstractModel {
    private int $id;

    public function __construct(int $id, PDO $dataBase) {
        $this->id = $id;
        parent::__construct($dataBase);
    }

    public function getUserId() {
        return $this->runQuery("SELECT user_id FROM comments WHERE comment_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getPublicationId() {
        return $this->runQuery("SELECT publication_id FROM comments WHERE comment_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getContent() {
        return $this->runQuery("SELECT content FROM comments WHERE comment_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getImage() : Image {
        return new Image($this->runQuery("SELECT image FROM comments WHERE comment_id = :id", [":id" => $this->id])->fetchColumn());
    }

    public function getTimeStamp() {
        return $this->runQuery("SELECT comment_date FROM comments WHERE comment_id = :id", [":id" => $this->id])->fetchColumn();
    }
}