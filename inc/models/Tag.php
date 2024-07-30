<?php

namespace inc\models;

use inc\helpers\Common;
use inc\helpers\DB;

class Tag
{
    public static function save_tag(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $slug = self::generateSlug($slug); // Generate slug from name
            $status = $_POST['status'] ?? '';
            $position = $_POST['position'] ?? '';
            $conn = DB::db_connect();

            if ($id == '') {
                $sql = "INSERT INTO tags (name, slug, status, position) VALUES (?,?,?,?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("sssi", $name, $slug, $status, $position);
            } else {
                $sql = "UPDATE tags SET name = ?, slug = ?, status = ?, position = ? WHERE tag_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("sssii", $name, $slug, $status, $position, $id);
            }
            $statement->execute();

            if (!$statement->errno) {
                $conn->close();
                header("Location: /admin/tag");
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
    
    public static function getTags(): array
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM tags ORDER BY tag_id asc";
        $result = $conn->query($sql);
        $tags = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tags[] = $row;
            }
        }
        $conn->close();
        return $tags;
    }

    public static function getTagById($id): bool|array|null
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM tags WHERE tag_id=$id";
        $result = $conn->query($sql);
        $tag = null;
        if ($result->num_rows == 1) {
            $tag = $result->fetch_assoc();
        }
        $conn->close();
        return $tag;
    }

    public static function deleteTag($id): void
    {
        $conn = DB::db_connect();
        $sql = "DELETE FROM tags WHERE tag_id=$id";
        $conn->query($sql);
        $conn->close();
    }

    public static function getPostTagIds($postId): array
    {
        $conn = DB::db_connect();
        $sql = "SELECT t.tag_id, t.name, t.status
            FROM tags t
            INNER JOIN post_tags pt ON t.tag_id = pt.tag_id
            WHERE pt.post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $postId);
        $result = Common::getArrayBySQL($sql,$stmt);
        $conn->close();
        
        return $result;
    }

}