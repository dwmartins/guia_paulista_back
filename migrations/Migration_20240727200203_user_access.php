<?php

use App\Models\Database;

class Migration_20240727200203_user_access extends Database{
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
            $sql = "CREATE TABLE IF NOT EXISTS user_access(
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                ip VARCHAR(100),
                createdAt DATETIME,
                CONSTRAINT fk_userAccessId FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
