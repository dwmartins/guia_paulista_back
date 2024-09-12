<?php

namespace App\Validators;

class EmailSettingsValidator {
    public static function create(array $data) {
        $fields = [
            "server" => [
                "label" => SERVER_LABEL,
                "require" => true
            ],
            "emailAddress" => [
                "label" => EMAIL_ADDRESS_LABEL,
                "require" => true
            ],
            "username" => [
                "label" => USER_NAME_LABEL,
                "require" => true
            ],
            "password" => [
                "label" => PASSWORD_LABEL,
                "require" => false
            ],
            "port" => [
                "label" => PORT_LABEL,
                "require" => true
            ]
        ];

        $response = [
            "isValid" => true,
            "message" => ""
        ];

        foreach ($data as $key => $value) {
            if(empty($value) && !$fields[$key]['require']) {
                continue;
            }

            if(empty($value) && $fields[$key]['require']) {
                $response['isValid'] = false;
                $response['message'] = sprintf(REQUIRED_FIELD, $fields[$key]['label']);
                return $response;
            }

            if(!TextValidator::text($value)) {
                $response['isValid'] = false;
                $response['message'] = sprintf(FIELD_INVALID_CHARACTERS, $fields[$key]['label']);
                return $response;
            }
        }

        return $response;
    }
}