<?php
namespace Model;
use PDO;

class AbstractModel {
    private PDO $dataBase;

    public function __construct(PDO $dataBase) {
        $this->dataBase = $dataBase;
    }

    protected function getDataBase() : PDO {
        return $this->dataBase;
    }

    protected function runQuery(string $query, array $parameters = []) {
        if(empty($parameters))
        {
            return $this->dataBase->query($query);
        }
        $statement = $this->dataBase->prepare($query);
        $statement->execute($parameters);
        return $statement;
    }
}