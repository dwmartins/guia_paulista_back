<?php

use App\Models\Database;

class Migration_20240813182823_discountCode extends Database{
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
            $sql = "CREATE TABLE IF NOT EXISTS discountCode (
                id INT AUTO_INCREMENT PRIMARY KEY,
                code VARCHAR(50) NOT NULL,
                discount INT NOT NULL,
                sponsor INT,
                startDate DATETIME NOT NULL,
                endDate DATETIME NOT NULL,
                active ENUM('Y', 'N'),
                module VARCHAR(50),
                createdAt DATETIME,
                updatedAt DATETIME,
                FOREIGN KEY (sponsor) REFERENCES users(id)
            )";

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
