<?php

namespace inc\models;

use inc\helpers\Common;
use inc\helpers\DB;

class Category
{
    public static function save_category(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $status = $_POST['status'] ?? '';
            $description = $_POST['description'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $position = $_POST['position'] ?? '';
            $slug = self::generateSlug($slug);
            $conn = DB::db_connect();

            if ($id == '') {
                $sql = "INSERT INTO categories (name, status, description, position, slug) VALUES (?,?,?,?,?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("sssss", $name, $status, $description, $position, $slug);
            } else {
                $sql = "UPDATE categories SET name = ?, status = ?, description = ?, position = ?, slug = ? WHERE category_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("sssssi", $name, $status, $description, $position, $slug, $id);
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

    private static function generateSlug($name): string
    {
        // Convert to lowercase
        $slug = strtolower($name);
        // Remove non-alphanumeric characters
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        // Remove multiple dashes
        $slug = preg_replace('/-+/', '-', $slug);
        // Trim dashes from ends
        return trim($slug, '-');
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

    public static function getCategoriesByPosition($limit): array
    {
        $conn = DB::db_connect();
        $sql = "SELECT category_id FROM categories ORDER BY position ASC, category_id ASC LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $categoryIds = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categoryIds[] = $row['category_id'];
            }
        }
        $stmt->close();
        $conn->close();
        return $categoryIds;
    }

    public static function getCategoryIdBySlug($slug): ?int
    {
        $conn = DB::db_connect();
        $sql = "SELECT category_id FROM categories WHERE slug = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $stmt->bind_result($category_id);
        $stmt->fetch();
        $stmt->close();
        $conn->close();

        return $category_id ?: null;
    }
}