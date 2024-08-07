<?php

namespace App\Validators;

use App\Http\Response;
use App\Validators\Validator;
use Exception;

class UserValidators {
    public static function create(array $data) {
        try {
            $fields = [
                NAME_LABEL       => $data['name'] ?? '',
                LAST_NAME_LABEL  => $data['lastName'] ?? '',
                EMAIL_LABEL      => $data['email'] ?? '',
                PASSWORD_LABEL   => $data['password'] ?? ''
            ];

            Validator::validate($fields);
            
            foreach ($fields as $key => $value) {
                if($key === EMAIL_LABEL) {
                    if(!TextValidator::email($value)) {
                        Response::json([
                            'error'     => true,
                            'message'   => INVALID_EMAIL
                        ], 400);

                        return false;
                    }

                    continue;
                }

                if($key === PASSWORD_LABEL) {
                    continue;
                }

                if(!TextValidator::simpleText($value)) {
                    Response::json([
                        'error'     => true,
                        'message'   => sprintf(FIELD_INVALID_CHARACTERS, $key)
                    ], 400);

                    return false;
                }
            }

            return true;
        }
        catch (Exception $e) {
            Response::json([
                'message'   => $e->getMessage()
            ], 400);

            return false;
        }
    }

    public static function update(array $data) {
        try {
            $fields = [
                NAME_LABEL       => $data['name'] ?? '',
                LAST_NAME_LABEL  => $data['lastName'] ?? '',
                EMAIL_LABEL      => $data['email'] ?? '',
                DESCRIPTION_LABEL => $data['description'] ?? '',
            ];

            if(!TextValidator::isNumeric($data['phone'])) {
                Response::json([
                    'message'   => INVALID_PHONE
                ], 400);

                return false;
            }

            Validator::validate($fields);

            foreach ($fields as $key => $value) {
                if($key === EMAIL_LABEL) {
                    if(!TextValidator::email($value)) {
                        Response::json([
                            'error'     => true,
                            'message'   => INVALID_EMAIL
                        ], 400);

                        return false;
                    }

                    continue;
                }

                if(!TextValidator::simpleText($value)) {
                    Response::json([
                        'message'   => sprintf(FIELD_INVALID_CHARACTERS, $key)
                    ], 400);

                    return false;
                }
            }

            return true;
        }
        catch (Exception $e) {
            Response::json([
                'message'   => $e->getMessage()
            ], 400);

            return false;
        }
    }

    public static function login(array $data) {
        try {
            $fields = [
                EMAIL_LABEL => $data['email'] ?? '',
                PASSWORD_LABEL  => $data['password'] ?? ''
            ];

            Validator::validate($fields);

            if(!TextValidator::email($data['email'])) {
                Response::json([
                    'message'   => INVALID_EMAIL
                ], 400);

                return false;
            }

            return true;
        } catch (Exception $e) {
            Response::json([
                'message'   => $e->getMessage()
            ], 400);

            return false;
        }
    }

    public static function recoverPassword(array $data) {
        try {
            $fields = [
                EMAIL_LABEL => $data['email'] ?? ''
            ];

            Validator::validate($fields);

            if(!TextValidator::email($data['email'])) {
                Response::json([
                    'message'   => INVALID_EMAIL
                ], 400);

                return false;
            }

            return true;
        } catch (Exception $e) {
            Response::json([
                'message'   => $e->getMessage()
            ], 400);

            return false;
        }
    }
}