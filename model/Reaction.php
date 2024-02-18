<?php
namespace Model;
use PDO;

require_once __DIR__ . "/AbstractModel.php";

class Reaction extends AbstractModel {
    private int $id;

    public function __construct(int $id, PDO $dataBase) {
        $this->id = $id;
        parent::__construct($dataBase);
    }

    public function getUserId() {
        return $this->runQuery("SELECT user_id FROM reactions WHERE reaction_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function getPublicationId() {
        return $this->runQuery("SELECT publication_id FROM reactions WHERE reaction_id = :id", [":id" => $this->id])->fetchColumn();
    }

    public function isLike() {
        return $this->runQuery("SELECT is_like FROM reactions WHERE reaction_id = :id", [":id" => $this->id])->fetchColumn();
    }
}