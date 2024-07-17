<?php

namespace inc\models;

use database\DB;
use inc\helpers\Common;

class Tag
{
    public static function save_tag(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $status = $_POST['status'] ?? '';
            $conn = DB::db_connect();

            if ($id == '') {
                $sql = "INSERT INTO tags (name, status) VALUES (?, ?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ss", $name, $status);
            } else {
                $sql = "UPDATE tags SET name = ?, status = ? WHERE tag_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("sss", $name, $status, $id);
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