<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Database\DbConnexion;
use App\Entities\UserEntity;

class UpdateProfileController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->registerC($request);
    }

    private function registerC(Request $request)
    {
        $success = null;

        $payload = json_decode($request->getPayload(), true);

        $id = $payload['id'];
        $data = [];

        if (isset($payload['name'])) {
            $data['name'] = $payload['name'];
        }

        if (isset($payload['email'])) {
            $data['email'] = $payload['email'];
        }

        if (isset($payload['password'])) {
            $data['password'] = $payload['password'];
        }

        $user = new UserEntity();


        $success = $user->update($id, $data);

        return new Response(json_encode($success), 201, ['Content-Type' => 'application/json']);
    }
}
