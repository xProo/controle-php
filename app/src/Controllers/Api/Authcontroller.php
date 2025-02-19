<?php

namespace App\Controllers\Api;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Models\User;

class AuthController
{
    public function login(Request $request): Response
    {
        $data = json_decode($request->getPayload(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return new Response(
                json_encode(['error' => 'Email and password are required']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $user = new User('', $data['email'], $data['password']);
        
        if ($user->login()) {
            return new Response(
                json_encode([
                    'message' => 'Login successful',
                    'user_id' => $user->getId(),
                    'name' => $user->getName()
                ]),
                200,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            json_encode(['error' => 'Invalid credentials']),
            401,
            ['Content-Type' => 'application/json']
        );
    }

    public function register(Request $request): Response
    {
        $data = json_decode($request->getPayload(), true);

        if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
            return new Response(
                json_encode(['error' => 'Name, email and password are required']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        $user = new User($data['name'], $data['email'], $data['password']);
        
        if ($user->register()) {
            return new Response(
                json_encode(['message' => 'Registration successful']),
                201,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            json_encode(['error' => 'Could not register user']),
            500,
            ['Content-Type' => 'application/json']
        );
    }
}