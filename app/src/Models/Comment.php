<?php

namespace App\Models;

use PDO;

class Comment {
    private string $content;
    private int $postId;
    private int $userId;

    public function __construct(string $content, int $postId, int $userId) {
        $this->content = $content;
        $this->postId = $postId;
        $this->userId = $userId;
    }

    public function save(): bool {
        $pdo = $this->getDbConnexion();
        $sql = "INSERT INTO comments (content, post_id, user_id) VALUES (:content, :post_id, :user_id)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['content' => $this->content, 'post_id' => $this->postId, 'user_id' => $this->userId]);
    }

    public static function getDbConnexion(): PDO {
        $host = 'php-oop-exercice-db';
        $db = 'blog';
        $user = 'root';
        $password = 'password';
        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
        return new PDO($dsn, $user, $password);
    }
}