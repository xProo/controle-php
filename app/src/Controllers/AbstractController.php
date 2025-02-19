<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

abstract class AbstractController{
    abstract public function process(Request $request): Response;

    protected function renderView(string $viewName, array $viewData = []): Response
    {
        $response = new Response();
        
        // Extraction des données dans la portée locale
        if (!empty($viewData)) {
            extract($viewData);
        }
        
        // Capture du contenu
        ob_start();

  
        
        // Configuration de la réponse
        $response->setContent(ob_get_clean());
        $response->addHeader('Content-Type', 'text/html');

        return $response;
    }

    protected function checkAuthentication(): bool 
    {
        return !empty($_SESSION['user_id']);
    }
}