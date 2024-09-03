<?php

use App\class\FileCache;
use App\Class\Settings;
use App\Models\SettingsDAO;

function getSetting(string $name) {
    try {
        $cache = new FileCache();
        $cacheData = $cache->get('settings');

        if($cacheData) {
            foreach ($cacheData as $setting) {
                if($setting['name'] === $name) {
                    return $setting['value'] ?? null;
                }
            }

        } else {
            $setting = SettingsDAO::fetchByName($name);
            $cache->set('settings', SettingsDAO::fetch());
            return $setting['value'] ?? null;
        }

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
            $data = $cacheData;
        } else {
            $data = SettingsDAO::fetch();
            $cache->set('settings', $data);
        }

        return $data;
    } catch (Exception $e) {
        logError($e);
        throw new Exception("Error fetching settings");
    }
}

function updateSetting($name, $newValue) {
    try {
        $setting = new Settings();

        $values = [
            "name" => $name,
            "value" => $newValue
        ];

        $setting->update($values);

        $cache = new FileCache();
        $cache->set('settings', SettingsDAO::fetch());

    } catch (Exception $e) {
        logError($e);
        throw new Exception("Error updating settings");
    }
}