<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Database\DbConnexion;
use App\Entities\UserEntity;

class DeletePostController extends AbstractController
{
    public function process(Request $request): Response
    {
        return $this->deletePost($request);
    }

    private function deletePost(Request $request): Response
    {
        $payload = json_decode($request->getPayload(), true);
        $userEntity = new UserEntity();
        
        $result = $userEntity->delete($payload['id']);

        return new Response(
            json_encode($result),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}