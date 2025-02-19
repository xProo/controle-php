<?php

namespace App\Models;

use PDO;

class Post {
    private int $id;
    private string $title;
    private string $content;
    private int $userId;

    public function __construct(string $title, string $content, int $userId, int $id = 0) {
        $this->title = $title;
        $this->content = $content;
        $this->userId = $userId;
        $this->id = $id;
    }

    public function save(): bool {
        $pdo = $this->getDbConnexion();
        $sql = "INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['title' => $this->title, 'content' => $this->content, 'user_id' => $this->userId]);
    }

    public static function getDbConnexion(): PDO {
        $host = 'php-oop-exercice-db';
        $db = 'blog';
        $user = 'root';
        $password = 'password';
        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
        return new PDO($dsn, $user, $password);
    }

    public static function getPostById(int $id): ?array {
        $pdo = self::getDbConnexion();
        $sql = "SELECT * FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ajoutez d'autres méthodes pour récupérer les posts, etc.
}