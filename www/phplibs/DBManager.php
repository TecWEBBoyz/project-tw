<?php

class Database {

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

}

?>