<?php

namespace PTW\Services;

use PDO;
use PDOException;

class DBService {

    private static $host = 'localhost';
    private static $dbname = 'default_database';
    private static $username = 'default_user';
    private static $password = 'default_password';

    private function connect() {
        try {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname;
            $pdo = new PDO($dsn, self::$username, self::$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }

    private function disconnect($pdo) {
        $pdo = null;
    }

    public static function query($sql, $params = []) {
        $instance = new self();
        $pdo = $instance->connect();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Query failed: " . $e->getMessage();
                return null;
            } finally {
                $instance->disconnect($pdo);
            }
        }
        return null;
    }

    public static function getAllAnimals($fields = []) {
        $sql = "SELECT ";
        if(empty($fields)) {
            $sql .= "* FROM animal";
        } else {
            $sql .= implode(", ", array_map('trim', $fields)) . " FROM animal";
        }
        return self::query($sql);
    }

    public static function getAnimalByID($id) {
        $sql = "SELECT * FROM animal WHERE id = :id";
        $params = [':id' => $id];
        return self::query($sql, $params);
    }

    public static function getUser($username) {
        $sql = "SELECT * FROM user WHERE name = :username";
        $params = [':username' => $username];
        $result = self::query($sql, $params); 
        return $result ? $result[0] : null; // Return the first result or null if not found
    }

}

?>