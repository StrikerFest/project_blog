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
        if ($cateAndTagToString){
            $cateField = 'name';
            $tagField = 'name';
        } else {
            $cateField = 'category_id';
            $tagField = 'tag_id';
        }
        
        $conn = DB::db_connect();

        $sql = "SELECT p.*,
                       GROUP_CONCAT(DISTINCT c.$cateField SEPARATOR ', ') AS categories,
                       GROUP_CONCAT(DISTINCT t.$tagField SEPARATOR ', ') AS tags
                FROM posts p
                LEFT JOIN post_categories pc ON p.post_id = pc.post_id
                LEFT JOIN categories c ON pc.category_id = c.category_id
                LEFT JOIN post_tags pt ON p.post_id = pt.post_id
                LEFT JOIN tags t ON pt.tag_id = t.tag_id
                GROUP BY p.post_id
                ORDER BY p.post_id $sortOrder";

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
        $sql = "SELECT p.*, 
            GROUP_CONCAT(c.category_id SEPARATOR ', ') AS categories, 
            GROUP_CONCAT(t.tag_id SEPARATOR ', ') AS tags
        FROM posts p
        LEFT JOIN post_categories pc ON p.post_id = pc.post_id
        LEFT JOIN categories c ON pc.category_id = c.category_id
        LEFT JOIN post_tags pt ON p.post_id = pt.post_id
        LEFT JOIN tags t ON pt.tag_id = t.tag_id
        WHERE p.post_id = $id
        GROUP BY p.post_id
        LIMIT 1"; // Limit to retrieve only one post
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