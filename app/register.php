<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\AuthController;
use App\Services\Session;

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrer la session au tout début
Session::start();

$success = null;

try {
    $auth = new AuthController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $success = $auth->register($_POST);
        if ($success) {
            header('Location: /login.php');
            exit;
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
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
                <h1 class="text-4xl">Register</h1>
                
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <form action="/register.php" method="post" class="flex flex-col w-1/2 space-y-4">
                    <?php if ($success === false): ?>
                        <p class="text-red-500">Registration failed</p>
                    <?php endif; ?>

                    <input type="text" name="username" placeholder="Username" required 
                           class="p-2 border border-gray-300 rounded">
                    <input type="email" name="email" placeholder="Email" required 
                           class="p-2 border border-gray-300 rounded">
                    <input type="password" name="password" placeholder="Password" required 
                           class="p-2 border border-gray-300 rounded">
                    <button type="submit" class="p-2 bg-blue-500 text-white rounded">Register</button>
                </form>
            </div>
        </div>        
    </main>
</body>
</html>
