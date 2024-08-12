<?php

use App\Models\Database;

class Migration_20240812184811_table_listing_category extends Database{
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
            $sql = "CREATE TABLE IF NOT EXISTS listing_category (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                photo VARCHAR(255),
                icon VARCHAR(255),
                slugUrl VARCHAR(255),
                status ENUM('Y', 'N'),
                createdAt DATETIME,
                updatedAt DATETIME
            );";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function down() {
        // Migration implementation (rollback)
    }
}
