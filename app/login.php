<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Auth\Login;
use App\Services\Session;

// Démarrer la session au tout début
Session::start();

$success = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = new Login(
            $_POST['email'] ?? '',
            $_POST['password'] ?? ''
        );
        
        $success = $login->execute();
        if ($success) {
            header('Location: /');
            exit;
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

function getDbConnexion(): PDO {
    $host = 'php-oop-exercice-db';
    $db = 'blog';
    $user = 'root';
    $password = 'password';

    $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

    return new PDO($dsn, $user, $password);
}

function login(string $email, string $password) {
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = getDbConnexion()->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false;
    }

    if (!password_verify($password, $user['password'])) {
        return false;
    }

    $_SESSION['user_id'] = $user['id'];
    header('Location: /profile.php');
    exit;
}

?>

<!doctype html>
<html class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body  class="min-h-full">
    <main class="min-h-full">
        <div class="flex flex-col min-h-full w-full items-center justify-start">
            <div class="flex flex-row w-full h-24 bg-gray-900 items-center justify-center">
                <div class="w-11/12 flex flex-row items-center justify-end space-x-4">
                    <a href="/" class="text-white">Homepage</a>
                    <?php if (Session::isLoggedIn()): ?>
                        <a href="/blogs/new.php" class="text-white">Create post</a>
                        <a href="/profile.php" class="text-white">Profile</a>
                        <a href="/logout.php" class="text-white">Logout</a>
                    <?php else: ?>
                        <a href="/login.php"  class="text-white">Login</a>
                        <a href="/register.php"  class="text-white">Register</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex flex-col w-11/12 items-center justify-start">
                <h1 class="text-4xl">Login</h1>
                <form action="/login.php" method="post" class="flex flex-col w-1/2 space-y-4">
                    <?php if ($success === false): ?>
                        <p class="text-red-500">Invalid credentials</p>
                    <?php endif; ?>

                    <input type="email" name="email" placeholder="Email" class="p-2 border border-gray-300 rounded">
                    <input type="password" name="password" placeholder="Password" class="p-2 border border-gray-300 rounded">
                    <button type="submit" class="p-2 bg-blue-500 text-white rounded">Login</button>
                </form>

            </div>
        </div>        
    </main>
</body>
</html>