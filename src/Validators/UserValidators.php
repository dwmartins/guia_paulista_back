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

                if(!TextValidator::text($value)) {
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
                NAME_LABEL        => $data['name'] ?? '',
                LAST_NAME_LABEL   => $data['lastName'] ?? '',
                EMAIL_LABEL       => $data['email'] ?? '',
                DESCRIPTION_LABEL => $data['description'] ?? '',
                PHONE_LABEL       => $data['phone'] ?? '',
                ADDRESS_LABEL     => $data['address'] ?? '',
                CITY_LABEL        => $data['city'] ?? '',
                ZIP_CODE_LABEL    => $data['zipCode'] ?? '',
                STATE_LABEL       => $data['state'] ?? '',
            ];

            $requiredFields = [
                NAME_LABEL       => $data['name'] ?? '',
                LAST_NAME_LABEL  => $data['lastName'] ?? '',
                EMAIL_LABEL      => $data['email'] ?? '',
            ];

            Validator::validate($requiredFields);

            foreach ($fields as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                if($key === EMAIL_LABEL) {
                    if(!TextValidator::email($value)) {
                        Response::json([
                            'message'   => INVALID_EMAIL
                        ], 400);

                        return false;
                    }

                    continue;
                }

                if($key === PHONE_LABEL || $key === ZIP_CODE_LABEL) {
                    if(!is_numeric($value)) {
                        Response::json([
                            'message'   => $key === PHONE_LABEL ? INVALID_PHONE : INVALID_ZIP_CODE
                        ], 400);

                        return false;
                    }

                    continue;
                }

                if($key === NAME_LABEL || $key === LAST_NAME_LABEL) {
                    if(!TextValidator::name($value)) {
                        Response::json([
                            'message'   => sprintf(FIELD_INVALID_CHARACTERS, $key)
                        ], 400);

                        return false;
                    }

                    continue;
                }

                if(!TextValidator::text($value)) {
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

    public static function updateAddress(array $data) {
        $fields = [
            ADDRESS_LABEL     => $data['address'] ?? '',
            CITY_LABEL        => $data['city'] ?? '',
            ZIP_CODE_LABEL    => $data['zipCode'] ?? '',
            STATE_LABEL       => $data['state'] ?? '',
        ];

        foreach ($fields as $key => $value) {
            if(empty($value)) {
                continue;
            }

            if($key === ZIP_CODE_LABEL) {
                if(!is_numeric($value)) {
                    Response::json([
                        'message'   => INVALID_ZIP_CODE
                    ], 400);

                    return false;
                }

                continue;
            }

            if(!TextValidator::text($value)) {
                Response::json([
                    'message'   => sprintf(FIELD_INVALID_CHARACTERS, $key)
                ], 400);

                return false;
            }
        }

        return true;
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