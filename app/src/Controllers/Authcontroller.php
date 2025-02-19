<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\Session;

class AuthController {
    public function register(array $data): bool {
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            throw new \Exception('Tous les champs sont requis');
        }

        $user = new User(
            $data['username'],
            $data['email'],
            $data['password']
        );

        $result = $user->register();
        
        if ($result) {
            // Stocker un message de succès dans la session
            Session::set('registration_success', true);
            Session::set('redirect_to', '/login.php');
            return true;
        }

        return false;
    }

    public function login(array $data): bool {
        $user = new User('', $data['email'] ?? '', $data['password'] ?? '');
        
        if ($user->login()) {
            Session::set('user_id', $user->getId());
            Session::set('user_name', $user->getName());
            header('Location: /');
            exit;
        }

        return false;
    }

    public function logout(): void {
        Session::destroy();
        header('Location: /');
        exit;
    }
}
