<?php

namespace App\Services;

use PDO;

class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct() {
        $host = 'php-oop-exercice-db';
        $db = 'blog';
        $user = 'root';
        $password = 'password';
        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
        
        $this->connection = new PDO($dsn, $user, $password);
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
