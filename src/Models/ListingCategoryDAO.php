<?php

namespace App\Models;

use App\Class\ListingCategory;
use App\Models\Database;
use Exception;
use PDO;
use PDOException;

class ListingCategoryDAO extends Database {
    public static function save (ListingCategory $listingCategory): int {
        try {
            $pdo = self::getConnection();

            $listingCategory->setCreatedAt(date('Y-m-d H:i:s'));
            $listingCategory->setUpdatedAt(date('Y-m-d H:i:s'));

            $columns = [];
            $placeholders = [];
            $values = [];

            foreach ($listingCategory->toArray() as $key => $value) {
                $columns[] = $key;
                $placeholders[] = "?";
                $values[] = $value;
            }

            $columns = implode(", ", $columns);
            $placeholders = implode(", ", $placeholders);

            $stmt = $pdo->prepare(
                "INSERT INTO listing_category ($columns)
                VALUES ($placeholders)"
            );

            $stmt->execute($values);

            return $pdo->lastInsertId();

        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to create category");
        }
    }

    public static function update(ListingCategory $listingCategory): int {
        try {
            $pdo = self::getConnection();

            $listingCategory->setUpdatedAt(date('Y-m-d H:i:s'));

            $columns = [];
            $values = [];

            foreach ($listingCategory->toArray() as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                $columns[] = "$key = ?";
                $values[] = $value;
            }

            $columns = implode(", ", $columns);
            $values[] = $listingCategory->getId();

            $stmt = $pdo->prepare(
                "UPDATE listing_category 
                SET $columns
                WHERE id = ?"
            );

            $stmt->execute($values);

            return $stmt->rowCount();

        } catch (PDOException $e) {
            logError($e->getMessage());
            throw new Exception("Error when executing query to update category");
        }
    }
}