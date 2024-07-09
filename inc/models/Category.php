<?php

namespace inc\models;

use database\DB;

class Category
{
    public static function save_category()
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
                $sql = "UPDATE categories SET categories.name = ?, categories.status = ?, categories.description = ? WHERE id = ?";
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

    public static function getCategories()
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM categories ORDER BY id asc";
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

    public static function getCategoryById($id)
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM categories WHERE id=$id";
        $result = $conn->query($sql);
        $category = null;
        if ($result->num_rows == 1) {
            $category = $result->fetch_assoc();
        }
        $conn->close();
        return $category;
    }

    public static function deleteCategory($id)
    {
        $conn = DB::db_connect();
        $sql = "DELETE FROM categories WHERE id=$id";
        $conn->query($sql);
        $conn->close();
    }
}