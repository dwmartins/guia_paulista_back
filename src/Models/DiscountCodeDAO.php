<?php

namespace App\Models;

use App\Class\DiscountCode;
use App\Models\Database;
use Exception;
use PDO;
use PDOException;

class DiscountCodeDAO extends Database {
    public static function save(DiscountCode $discountCode): int {
        try {
            $pdo = self::getConnection();

            $discountCode->setCreatedAt(date('Y-m-d H:i:s'));
            $discountCode->setUpdatedAt(date('Y-m-d H:i:s'));

            $columns = [];
            $placeholders = [];
            $values = [];

            foreach ($discountCode->toArray() as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                $columns[] = $key;
                $placeholders[] = "?";
                $values[] = $value;
            }

            $columns = implode(", ", $columns);
            $placeholders = implode(", ", $placeholders);

            $stmt = $pdo->prepare(
                "INSERT INTO discountCode ($columns)
                VALUES ($placeholders)"
            );

            $stmt->execute($values);
            return $pdo->lastInsertId();

        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error executing query to save discount code.");
        }
    }

    public static function update(DiscountCode $discountCode): int {
        try {
            $pdo = self::getConnection();

            $discountCode->setUpdatedAt(date('Y-m-d H:i:s'));

            $columns = [];
            $values = [];

            foreach ($discountCode->toArray() as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                $columns[] = "$key = ?";
                $values[] = $value;
            }

            $columns = implode(", ", $columns);
            $values[] = $discountCode->getId();

            $stmt = $pdo->prepare(
                "UPDATE discountCode 
                SET $columns
                WHERE id = ?"
            );

            $stmt->execute($values);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to update discount code.");
        }
    }

    public static function fetchAll(): array {
        try {
            $pdo = self::getConnection();

            $stmt = $pdo->prepare(
                "SELECT * FROM discountCode"
            );

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ?: [];

        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to search for discount code.");
        }
    }

    public static function fetchById(int $id): array {
        try {
            $pdo = self::getConnection();

            $stmt = $pdo->prepare(
                "SELECT * FROM discountCode WHERE id = ?"
            );

            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: [];
        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to search for discount code by id.");
        }
    }

    public static function fetchByCode(string $code): array {
        try {
            $pdo = self::getConnection();

            $stmt = $pdo->prepare(
                "SELECT * FROM discountCode WHERE code = ?"
            );

            $stmt->execute([$code]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: [];
        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to search for discount code by code.");
        }
    }

    public static function delete(int $id): int {
        try {
            $pdo = self::getConnection();

            $stmt = $pdo->prepare(
                "DELETE FROM discountCode
                WHERE id = ?"
            );

            $stmt->execute([$id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to delete discount code.");
        }
    } 
}