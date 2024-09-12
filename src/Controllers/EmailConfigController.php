<?php

namespace App\Controllers;

use App\Class\EmailConfig;
use App\Http\Request;
use App\Http\Response;
use App\Validators\EmailSettingsValidator;
use Exception;

class EmailConfigController {
    public function create(Request $request, Response $response) {
        try {
            $data = $request->body();

            $fieldsValid = EmailSettingsValidator::create($data);
            
            if(!$fieldsValid['isValid']) {
                return $response->json([
                    "message" => $fieldsValid['message']
                ], 400);
            }

            $emailConfig = new EmailConfig($data);
            $emailConfig->fetch();

            if(!empty($emailConfig->getId())) {
                $emailConfig->update($data);
            }

            $emailConfig->save();
            
            $response->json([
                'success' => true,
                'message' => SAVE_SETTINGS
            ], 201);

        }  catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'error'   => true,
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function fetch(Request $request, Response $response) {
        try {
            $emailConfig = new EmailConfig();
            $result = $emailConfig->fetch();

            if(!empty($result)) {
                unset($result['password']);
            }

            $response->json($result);

        }  catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'error'   => true,
                'message' => FATAL_ERROR
            ], 500);
        }
    }
}
