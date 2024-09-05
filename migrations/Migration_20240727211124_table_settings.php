<?php

use App\Class\Settings;
use App\Models\Database;

class Migration_20240727211124_table_settings extends Database{
    protected $db;

    public function __construct() {
        try {
            $this->db = self::getConnection();
        } catch (PDOException $e) {
            showAlertLog("ERROR: ". $e->getMessage());
            throw $e;
        }
    }

    public function up() {
        // Migration implementation (up)
        try {
            $sql = "CREATE TABLE IF NOT EXISTS settings (
                name VARCHAR(255) PRIMARY KEY,
                value LONGTEXT
            )";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }

        $settings = [
            [
                "name" => "language",
                "value" => "pt-br"
            ],
            [
                "name" => "emailSending",
                "value" => "off"
            ],
            [
                "name" => "timezone",
                "value" => "America/Sao_Paulo"
            ],
            [
                "name" => "dateFormat",
                "value" => "DD-MM-YYYY"
            ],
            [
                "name" => "compressImage",
                "value" => "on"
            ],
            [
                "name" => "maintenance",
                "value" => "off"
            ],
            [
                "name" => "timeFormat",
                "value" => "HH:mm:ss"
            ]
        ];

        
        foreach ($settings as $value) {
            $setting = new Settings($value);
            $setting->save();
        }
    }

    public function down() {
        // Migration implementation (rollback)
    }
}
