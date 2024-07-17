<?php

namespace inc\models;

use database\DB;

class Post
{
    public static function save_post(): void
    {
        // Kiểm tra xem form có đang được gửi hay không
        // Và phải gửi với method POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Dữ liệu của form được gửi
            $id = $_POST['id'] ?? '';
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $author_id = $_POST['author_id'] ?? '';
            $categories = $_POST['categories'] ?? [];
            $tags = $_POST['tags'] ?? [];
            $status = $_POST['status'] ?? '';

            // Kết nối DB
            $conn = DB::db_connect();

            // Kiểm tra xem đang tạo mới blog hay cập nhật blog cũ
            if ($id == '') {
                $sql = "INSERT INTO posts (title, content, author_id, status) VALUES (?, ?, ?, ?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ssss", $title, $content, $author_id,  $status);
            } else {
                $sql = "UPDATE posts SET title = ?, content = ?, author_id = ?,  status = ? WHERE post_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("sssss", $title, $content, $author_id, $status, $id);
            }

            // Chạy lệnh SQL
            $statement->execute();

            // Kiểm tra câu lệnh SQL có chạy thành công không
            if (!$statement->errno) {
                if ($id == '') {
                    $id = $statement->insert_id; // Lấy ID post mới
                }

                // Xử lý category
                $sql = "DELETE FROM post_categories WHERE post_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("s", $id);
                $statement->execute();

                $sql = "INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)";
                $statement = $conn->prepare($sql);
                foreach ($categories as $category_id) {
                    $statement->bind_param("ss", $id, $category_id);
                    $statement->execute();
                }

                // Xử lý tags
                $sql = "DELETE FROM post_tags WHERE post_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("s", $id);
                $statement->execute();

                $sql = "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)";
                $statement = $conn->prepare($sql);
                foreach ($tags as $tag_id) {
                    $statement->bind_param("ss", $id, $tag_id);
                    $statement->execute();
                }

                $conn->close();
                header("Location: /admin/post");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                $conn->close();
            }
        }
    }

    // Lấy tất cả bài post
    public static function getPosts($sortOrder = 'asc', $cateAndTagToString = true): array
    {
        $conn = DB::db_connect();

        $sql = "SELECT * FROM posts ORDER BY post_id $sortOrder";

        $result = $conn->query($sql);
        $posts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
        $conn->close();
        return $posts;
    }

    // Lấy bài post theo mã post
    public static function getPostById($id): bool|array|null
    {
        if (!isset($id)) {
            return null;
        }
        
        $conn = DB::db_connect();
        $sql = "SELECT * FROM posts WHERE post_id=$id";
        $result = $conn->query($sql);
        $post = null;
        if ($result->num_rows == 1) {
            $post = $result->fetch_assoc();
        }
        $conn->close();
        return $post;
    }

    // Xóa bài post theo mã post
    public static function deletePost($id): void
    {
        $conn = DB::db_connect();
        $sql = "DELETE FROM posts WHERE post_id=$id";
        $conn->query($sql);
        $conn->close();
    }
}