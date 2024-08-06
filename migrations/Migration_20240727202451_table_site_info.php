<?php

use App\Models\Database;

class Migration_20240727202451_table_site_info extends Database{
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
            $sql = "CREATE TABLE IF NOT EXISTS site_Info (
                id INT AUTO_INCREMENT PRIMARY KEY,
                webSiteName VARCHAR(255),
                email VARCHAR(100),
                phone VARCHAR(100),
                city VARCHAR(100),
                state VARCHAR(100),
                address VARCHAR(255),
                instagram VARCHAR(255),
                facebook VARCHAR(255),
                twitter VARCHAR(255),
                description LONGTEXT,
                keywords LONGTEXT,
                ico VARCHAR(255),
                logoImage VARCHAR(50),
                coverImage VARCHAR(50),
                defaultImage VARCHAR(50),
                createdAt DATETIME,
                updatedAt DATETIME);
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            showAlertLog("ERROR: ". $e->getMessage());
            throw $e;
        }
    }

    public function down() {
        // Migration implementation (rollback)
    }
}
