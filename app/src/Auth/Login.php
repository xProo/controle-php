<?php

namespace App\Auth;

use App\Models\User;
use App\Services\Session;
use App\Lib\Http\Response;

class Login {
    private string $email;
    private string $password;

    public function __construct(string $email, string $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function execute(Response $response): void {
        if ($this->validateInput()) {
            $user = new User('', $this->email, $this->password);
            
            if ($user->login()) {
                $this->setUserSession($user);
                $response->json(['message' => 'Login successful', 'user_id' => $user->getId()]);
            } else {
                $response->json(['message' => 'Invalid credentials'], 401);
            }
        } else {
            $response->json(['message' => 'Email and password are required'], 400);
        }
    }

    private function validateInput(): bool {
        if (empty($this->email) || empty($this->password)) {
            throw new \Exception('Email et mot de passe requis');
        }
        return true;
    }

    private function setUserSession(User $user): void {
        Session::set('user_id', $user->getId());
        Session::set('user_name', $user->getName());
    }
}