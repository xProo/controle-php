<?php

namespace App\Models;

use App\Services\Database;
use PDO;

class User {
    private ?int $id = null;
    private string $name;
    private string $email;
    private string $password;

    public function __construct(string $name = '', string $email = '', string $password = '') {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function register(): bool {
        try {
            if ($this->userExists()) {
                error_log("User already exists with email: " . $this->email);
                return false;
            }

            $pdo = Database::getInstance()->getConnection();
            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $pdo->prepare($sql);
            
            $result = $stmt->execute([
                'name' => $this->name,
                'email' => $this->email,
                'password' => password_hash($this->password, PASSWORD_DEFAULT)
            ]);

            if (!$result) {
                error_log("SQL Error: " . implode(", ", $stmt->errorInfo()));
            }

            return $result;
        } catch (\PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            error_log("General Error: " . $e->getMessage());
            return false;
        }
    }

    public function login(): bool {
        $pdo = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $this->email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($this->password, $user['password'])) {
            $this->id = $user['id'];
            $this->name = $user['name'];
            return true;
        }

        return false;
    }

    private function userExists(): bool {
        try {
            $pdo = Database::getInstance()->getConnection();
            $sql = "SELECT id FROM users WHERE email = :email OR name = :name";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'email' => $this->email,
                'name' => $this->name
            ]);
            return (bool) $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Database Error in userExists: " . $e->getMessage());
            return true; // Par sécurité, on retourne true en cas d'erreur
        }
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
}
