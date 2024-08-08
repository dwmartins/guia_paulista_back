<?php

namespace App\Validators;

class TextValidator {
    public static function fullText(string $field) {
        $fullText = "/^[\p{L}0-9\s\-_.,!?@#\$%&*()áéíóúãõç]+$/ui";

        if(!preg_match($fullText, $field)) {
            return false;
        }

        return true;
    }

    public static function simpleText(string $field) {
        $simpleText = "/^[\p{L}\s]+$/u";

        if(!empty($field) && !preg_match($simpleText, $field)) {
            return false;
        }

        return true;
    }

    public static function email(string $email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    public static function emailServer(string $server) {
        if (empty($server)) {
            return false;
        }

        if (!filter_var($server, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            return false;
        }

        return true;
    }

    public static function isValidUrl(string $url) {
        $pattern = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
    
        return preg_match($pattern, $url);
    }
}