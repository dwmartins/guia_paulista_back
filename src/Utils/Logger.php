<?php 

function logError($errorMessage) {
    $rootPath = realpath(__DIR__ . '/../..');
    $logFilePath = $rootPath . '/error.log';

    $formattedMessage = '[' . date('Y-m-d H:i:s') . '] ' . $errorMessage . "\n";
    file_put_contents($logFilePath, $formattedMessage, FILE_APPEND);
}