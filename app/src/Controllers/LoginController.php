<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Database\DbConnexion;
use App\Entities\UserEntity;

class LoginController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->loginC($request);
    }

    private function loginC(Request $request)
    {
        $payload = json_decode($request->getPayload(), true);
        $email = $payload['email'];
        $password = $payload['password'];

        $user = new UserEntity();
        $isValidUser = $user->check($email, $password);

        if ($isValidUser) {
            return new Response(json_encode(['success' => true]), 200, ['Content-Type' => 'application/json']);
        } else {
            return new Response(json_encode(['success' => false, 'message' => 'Invalid credentials']), 401, ['Content-Type' => 'application/json']);
        }
    }
}
