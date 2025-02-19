<?php

namespace App\Auth;

use App\Models\User;
use App\Services\Session;

class Register {
    private string $username;
    private string $email;
    private string $password;

    public function __construct(string $username, string $email, string $password) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function execute(): bool {
        if ($this->validateInput()) {
            $user = new User($this->username, $this->email, $this->password);
            
            if ($user->register()) {
                $this->setSuccessSession();
                return true;
            }
        }
        return false;
    }

    private function validateInput(): bool {
        if (empty($this->username) || empty($this->email) || empty($this->password)) {
            throw new \Exception('Tous les champs sont requis');
        }
        return true;
    }

    private function setSuccessSession(): void {
        Session::set('registration_success', true);
        Session::set('redirect_to', '/login.php');
    }
}