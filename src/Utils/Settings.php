<?php

use App\class\FileCache;
use App\Class\Settings;
use App\Models\SettingsDAO;

function getSetting(string $name) {
    try {
        $setting = SettingsDAO::fetchByName($name);
        return $setting['value'] ?: [];
    } catch (Exception $e) {
        logError($e);
        throw new Exception("Error fetching setting");
    }
}

function getAllSettings() {
    try {
        $cache = new FileCache();
        $cacheData = $cache->get('settings');
        $data = [];

        if($cacheData) {
            $data = json_decode($cacheData, true);
        } else {
            $data = SettingsDAO::fetch();
            $cache->set('settings', json_encode($data));
        }

        return $data;
    } catch (Exception $e) {
        logError($e);
        throw new Exception("Error fetching settings");
    }
}

function updateSetting($name, $newValue) {
    $settingsToUpdateFile = ["language", "emailSending", "timezone"];

    try {
        $setting = new Settings();

        $values = [
            "name" => $name,
            "value" => $newValue
        ];

        $setting->update($values);

        if(in_array($name, $settingsToUpdateFile)) {
            updateSettingFile($name, $newValue);
        }

    } catch (Exception $e) {
        logError($e);
        throw new Exception("Error updating settings");
    }
}

function updateSettingFile($field, $value) {
    $rootPath = realpath(__DIR__ . '/../../');
    $fileSettings = $rootPath . '/settings.json';

    if(!file_exists($fileSettings)) {
        file_put_contents($fileSettings, json_encode([$field => $value], JSON_PRETTY_PRINT));
    } else {
        $data = json_decode(file_get_contents($fileSettings), true);
        $data[$field] = $value;
        file_put_contents($fileSettings, json_encode($data, JSON_PRETTY_PRINT));
    }
}

function loadFileSettings($field = null) {
    $rootPath = realpath(__DIR__ . '/../../');
    $fileSettings = $rootPath . '/settings.json';

    if (!file_exists($fileSettings)) {
        throw new Exception("The settings file was not found.");
    }

    $settings = json_decode(file_get_contents($fileSettings), true);

    if(!empty($field)) {
        return $settings[$field];
    }

    return $settings;
}