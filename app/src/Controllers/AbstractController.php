<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

abstract class AbstractController{
    abstract public function process(Request $request): Response;

    protected function render(string $template, array $data = []): Response
    {
        $response = new Response();
        extract($data);
        ob_start();
        require_once __DIR__ . "/../Views/html/start.html";
        require_once __DIR__ . "/../Views/$template.html";
        require_once __DIR__ . "/../Views/html/end.html";
        $response->setContent(ob_get_clean());
        $response->addHeader('Content-Type', 'text/html');

        return $response;
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }
}