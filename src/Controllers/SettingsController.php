<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use Exception;

class SettingsController {
    public function update(Request $request, Response $response) {
        try {
            $requestData = $request->body();

            updateSetting($requestData['name'], $requestData['value']);

            $response->json([
                "message" => SAVED_SITE_SETTINGS,
            ], 201);
            
        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }

    public function getAll(Request $request, Response $response) {
        try {
            $response->json(getAllSettings());
        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }
}