<?php

use App\Http\Response;

loadTranslations();
date_default_timezone_set(loadTimeZone());

// Verifica se o script está sendo executado via CLI
function isCli() {
    return php_sapi_name() === 'cli' || defined('STDIN');
}

if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/favicon.ico') {
    exit;
}

// Carrega as configurações de produção ou desenvolvimento
$envPath = __DIR__ . "/";
$envFile = '.env';

if (file_exists($envPath . '.env.development')) {
    define("DEV_MODE", true);
    $envFile = '.env.development';
} elseif (file_exists($envPath . '.env')) {
    define("DEV_MODE", false);
    $envFile = '.env';
}

$dotenv = Dotenv\Dotenv::createImmutable($envPath, $envFile);
$dotenv->load();

// Carrega as configurações de CORS
$allowed_origin = $_ENV['ALLOWED_ORIGIN'];
$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (!isCli() && DEV_MODE) {
    setHeaders($allowed_origin);

} else if (!isCli() && !DEV_MODE) {
    if($requestOrigin !== $allowed_origin) {
        Response::json([
            "error" => true,
            "message" => "You are not authorized to access this API"
        ], 403);

        exit;
    }

    setHeaders($allowed_origin);
}

function setHeaders($allowed_origin) {
    header("Access-Control-Allow-Origin: $allowed_origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, userId, token");

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("HTTP/1.1 200 OK");
        exit;
    }
}

function loadTranslations() {
    $rootPath = realpath(__DIR__);
    $settings = loadFileSettings();

    if (!isset($settings['language'])) {
        throw new Exception("The language setting is not set.");
    }

    $language = $settings['language'];
    $translationFile = $rootPath . "/translations/$language.php";

    if (!file_exists($translationFile)) {
        throw new Exception("The translation file for language $language was not found.");
    }

    include_once $translationFile;
}

function loadTimeZone() {
    $settings = loadFileSettings();

    if (!isset($settings['timezone'])) {
        return "America/Sao_Paulo";
    }

    return $settings['timezone'];
}

function loadFileSettings() {
    $rootPath = realpath(__DIR__);
    $fileSettings = $rootPath . '/settings.json';

    if (!file_exists($fileSettings)) {
        throw new Exception("The settings file was not found.");
    }

    return json_decode(file_get_contents($fileSettings), true);
}