<?php

namespace inc\models;

use database\DB;
use inc\helpers\Common;

class Category
{
    public static function save_category(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $status = $_POST['status'] ?? '';
            $description = $_POST['description'] ?? '';
            $conn = DB::db_connect();

            if ($id == '') {
                $sql = "INSERT INTO categories (name, status, description) VALUES (?,?,?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("sss", $name, $status, $description);
            } else {
                $sql = "UPDATE categories SET categories.name = ?, categories.status = ?, categories.description = ? WHERE category_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ssss", $name, $status, $description, $id);
            }
            $statement->execute();

            if (!$statement->errno) {
                $conn->close();
                header("Location: /admin/category");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                $conn->close();
            }
        }
    }

    public static function getCategories(): array
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM categories ORDER BY category_id asc";
        $result = $conn->query($sql);
        $categories = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        $conn->close();
        return $categories;
    }

    public static function getCategoryById($id): bool|array|null
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM categories WHERE category_id=$id";
        $result = $conn->query($sql);
        $category = null;
        if ($result->num_rows == 1) {
            $category = $result->fetch_assoc();
        }
        $conn->close();
        return $category;
    }

    public static function deleteCategory($id): void
    {
        $conn = DB::db_connect();
        $sql = "DELETE FROM categories WHERE category_id=$id";
        $conn->query($sql);
        $conn->close();
    }

    public static function getPostCategoryIds($postId): array
    {
        $conn = DB::db_connect();
        $sql = "SELECT c.category_id, c.name, c.status, c.description 
            FROM categories c
            INNER JOIN post_categories pc ON c.category_id = pc.category_id
            WHERE pc.post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $postId);
        $result = Common::getArrayBySQL($sql,$stmt);
        $conn->close();
        
        return $result;
    }
}