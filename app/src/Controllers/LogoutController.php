<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class LogoutController extends AbstractController
{
    public function process(Request $request): Response
    {
        session_start();
        session_destroy();
        header('Location: /homepage');
        return new Response();
    }
}
