<?php

namespace App\Validators;

use App\Http\Response;
use App\Validators\Validator;
use Exception;

class EmailConfigValidator {
    public static function create(array $data) {
        try {
            $fields = [
                SERVER => $data['server'] ?? '',
                EMAIL_ADDRESS => $data['emailAddress'] ?? '',
                USER_NAME => $data['username'] ?? ''
            ];

            Validator::validate($fields);

            foreach ($fields as $key => $value) {
                if($key === SERVER) {
                    if(!TextValidator::emailServer($value)) {
                        Response::json([
                            'error'     => true,
                            'message'   => sprintf(INVALID_FIELD_ERROR, $key)
                        ], 400);

                        return false;
                    }

                    continue;
                }

                if(!TextValidator::email($value)) {
                    Response::json([
                        'error'     => true,
                        'message'   =>  sprintf(INVALID_FIELD_ERROR, $key)
                    ], 400);

                    return false;
                }
            }

            return true;
        } catch (Exception $e) {
            Response::json([
                'error'     => true,
                'message'   => $e->getMessage()
            ], 400);

            return false;
        }
    }
}