<?php
namespace data_base;
use PDO;
use PDOException;

class DataBase {
    static $servername = "localhost";
    static $port = "5432";
    static $username = "student01";
    static $password = "<jvtwV!48515>";
    static $dbname = "student01";

    static $conn = null;

    static function connect(): ?PDO {
        try {
            if (DataBase::$conn != null) {
                return DataBase::$conn;
            }
            DataBase::$conn = new PDO("pgsql:host=" . self::$servername . ";port=" . self::$port . ";dbname=" . self::$dbname, self::$username, self::$password);
            DataBase::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            DataBase::$conn = null;
        } finally {
            return DataBase::$conn;
        }
    }
}