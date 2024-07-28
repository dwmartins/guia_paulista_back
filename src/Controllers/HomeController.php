<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class HomeController {
    public function index(Request $request, Response $response) {
        return $response->json([
            "message" => WELCOME_TO_API
        ]);
    }

    public function test(Request $request, Response $response) {
        return $response->json([
            "message" => "finish"
        ]);
    }
}