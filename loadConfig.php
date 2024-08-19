<?php

use App\Http\Response;

if (isFaviconRequest()) {
    exit;
}

// Load the appropriate .env file
$envFile = determineEnvFile();
loadEnv($envFile);

// Configure CORS permissions
handleCors();

if(!isCli()) {
    // Set the default language
    loadTranslations();

    // Sets the default time zone
    loadTimeZone();
}

function isFaviconRequest() {
    return isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/favicon.ico';
}

function loadTranslations() {
    $rootPath = realpath(__DIR__);

    $language = getSetting('language') ?? "pt-br";
    define("LANGUAGE", $language);

    $translationFile = $rootPath . "/translations/$language.php";

    if (!file_exists($translationFile)) {
        throw new Exception("The translation file for language $language was not found.");
    }

    include_once  $translationFile;
}

function loadTimeZone() {
    $timeZone = getSetting('timezone') ?? "America/Sao_Paulo";
    date_default_timezone_set($timeZone);
}

function determineEnvFile() {
    $envPath = __DIR__ . "/";

    if (file_exists($envPath . '.env.development')) {
        define("DEV_MODE", true);
        return '.env.development';
    }

    define("DEV_MODE", false);
    return '.env';
}

function loadEnv($envFile) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, $envFile);
    $dotenv->load();
}

function handleCors() {
    $allowed_origins = explode(',', $_ENV['ALLOWED_ORIGINS'] ?? '');
    $allowed_origins = array_map('trim', $allowed_origins);

    $requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if (!isCli()) {
        if (DEV_MODE || in_array($requestOrigin, $allowed_origins, true)) {
            setCorsHeaders($requestOrigin);
        } else {
            Response::json([
                "message" => "You are not authorized to access this API"
            ], 403);
            
            exit;
        }
    }
}

function setCorsHeaders($allowed_origin) {
    header("Access-Control-Allow-Origin: $allowed_origin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, token");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("HTTP/1.1 200 OK");
        exit;
    }
}

function isCli() {
    return php_sapi_name() === 'cli' || defined('STDIN');
}