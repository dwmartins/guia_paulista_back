<?php

namespace App\Models;

use App\Class\Settings;
use App\Models\Database;
use Exception;
use PDO;
use PDOException;

class SettingsDAO extends Database{
    public static function save(Settings $settings): void {
        try {
            $pdo = self::getConnection();

            $columns = [];
            $placeholders = [];
            $values = [];

            foreach ($settings->toArray() as $key => $value) {
                $columns[] = $key;
                $placeholders[] = "?";
                $values[] = $value;
            }

            $columns = implode(", ", $columns);
            $placeholders = implode(", ", $placeholders);

            $stmt = $pdo->prepare(
                "INSERT INTO settings ($columns)
                VALUES ($placeholders)"
            );

            $stmt->execute($values);
        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to create setting");
        }
    }

    public static function update(Settings $settings): int {
        try {
            $pdo = self::getConnection();

            $columns = [];
            $values = [];

            foreach ($settings->toArray() as $key => $value) {
                $columns[] = "$key = ?";
                $values[] = $value;
            }

            $columns = implode(", ", $columns);
            $values[] = $settings->getName();

            $stmt = $pdo->prepare(
                "UPDATE settings 
                SET $columns
                WHERE name = ?"
            );

            $stmt->execute($values);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to update setting");
        }
    }

    public static function fetch(): array {
        try {
            $pdo = self::getConnection();

            $stmt = $pdo->prepare(
                "SELECT * FROM settings"
            );

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ?: [];

        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to search for settings.");
        }
    }

    public static function fetchByName(string $name): array {
        try {
            $pdo = self::getConnection();

            $stmt = $pdo->prepare(
                "SELECT * FROM settings WHERE name = ?"
            );

            $stmt->execute([$name]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: [];
        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to search for settings by name.");
        }
    }

}