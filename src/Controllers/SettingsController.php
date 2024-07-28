<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use Exception;

class SettingsController {
    public function update(Request $request, Response $response) {
        try {
            $requestData = $request->body();

            updateSetting($requestData['name'], $requestData['setting']);

            $response->json([
                "message" => "Configurações do site salvas com sucesso.",
            ], 201);
            
        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => "Falha ao atualizar as configurações do site."
            ], 500);
        }
    }
}