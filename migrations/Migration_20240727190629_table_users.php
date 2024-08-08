<?php

use App\Models\Database;
use App\Http\JWTManager;

class Migration_20240727190629_table_users extends Database{
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
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                lastName VARCHAR(50) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                token TEXT NOT NULL,
                active ENUM('Y', 'N'),
                role VARCHAR(50) NOT NULL,
                description LONGTEXT,
                phone VARCHAR(50),
                dateOfBirth DATE NULL,
                address VARCHAR(255),
                city VARCHAR(100),
                zipCode VARCHAR(20),
                state VARCHAR(50),
                photo VARCHAR(255),
                acceptsEmails ENUM('Y', 'N'),
                createdAt DATETIME,
                updatedAt DATETIME
            );";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            //Cria um usuário support
            $sql = $sql = "INSERT INTO users (name, email, password, token, active, role, acceptsEmails, createdAt, updatedAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $stmt = $this->db->prepare($sql);

            $values = [
                "Suporte",
                $_ENV['DEVEMAIL'],
                password_hash("aguip2707", PASSWORD_DEFAULT),
                JWTManager::newCrypto(),
                "Y",
                "support",
                "Y",
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s')
            ];

            $stmt->execute($values);

        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function down() {
        // Migration implementation (rollback)
    }
}
