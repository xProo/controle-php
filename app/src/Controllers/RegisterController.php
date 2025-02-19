<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Database\DbConnexion;
use App\Entities\UserEntity;

class RegisterController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->registerC($request);
    }

    private function registerC(Request $request)
    {
        $success = null;

        $payload = json_decode($request->getPayload(), true);

        $name = $payload['name'];
        $password = $payload['password'];
        $email = $payload['email'];

        $user = new UserEntity();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        $success = $user->create();

        return new Response(json_encode($success), 201, ['Content-Type' => 'application/json']);
    }
}
