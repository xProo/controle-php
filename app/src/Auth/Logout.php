<?php

namespace App\Auth;

use App\Services\Session;

class Logout {
    public function execute(): void {
  
        session_start();
        session_unset();
        session_destroy();

    
        header('Location: /');
        exit;
    }
}