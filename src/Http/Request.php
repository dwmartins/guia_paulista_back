<?php

namespace App\Http;

class Request {

    private static array $attributes = [];

    public static function method() {
        return $_SERVER["REQUEST_METHOD"];
    }

    public static function body() {
        $method = self::method();

        switch ($method) {
            case 'GET':
                return $_GET;

            case 'POST':
                if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
                    return json_decode(file_get_contents("php://input"), true) ?? [];
                } else {
                    return $_POST;
                }

            case 'PUT':
            case 'DELETE':
                return json_decode(file_get_contents("php://input"), true) ?? [];

            default:
                return [];
        }
    }

    public static function queryParams() {
        return $_GET;
    }

    public static function files() {
        return $_FILES;
    }

    public static function authorization() {
        $headers = getallheaders();
    
        if (!isset($headers['Authorization'])) {
            return false;
        }
    
        $bearer = explode(" ", $headers['Authorization']);

        if (count($bearer) !== 2 || $bearer[0] !== 'Bearer') {
            return false;
        }

        $token = $bearer[1];

        $payload = JWTManager::getPayload($token);

        $userId = $payload['user_id'];
    
        return [
            "userId" => $userId,
            "token"  => $token
        ];
    }

    public static function getIp() {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    public static function setAttribute($key, $value) {
        self::$attributes[$key] = $value;
    }

    public static function getAttribute($key) {
        return self::$attributes[$key] ?? null;
    }
}