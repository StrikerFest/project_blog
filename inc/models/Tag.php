<?php

namespace inc\models;

use database\DB;

class Tag
{
    public static function save_tag()
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
                $sql = "UPDATE tags SET name = ?, status = ? WHERE id = ?";
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

    public static function getTags()
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM tags ORDER BY id asc";
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

    public static function getTagById($id)
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM tags WHERE id=$id";
        $result = $conn->query($sql);
        $tag = null;
        if ($result->num_rows == 1) {
            $tag = $result->fetch_assoc();
        }
        $conn->close();
        return $tag;
    }

    public static function deleteTag($id)
    {
        $conn = DB::db_connect();
        $sql = "DELETE FROM tags WHERE id=$id";
        $conn->query($sql);
        $conn->close();
    }
}