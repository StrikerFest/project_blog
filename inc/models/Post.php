<?php

namespace inc\models;

use inc\helpers\DB;

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
            $upload_dir = $_ENV['UPLOAD_DIR'];
            $relative_upload_dir = '/assets/uploads/';
            $thumbnail_path = '';
            $banner_path = '';

            if (!empty($_FILES['thumbnail']['name'])) {
                $thumbnail_filename = basename($_FILES['thumbnail']['name']);
                $thumbnail_full_path = $upload_dir . $thumbnail_filename;
                move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail_full_path);
                $thumbnail_path = $relative_upload_dir . $thumbnail_filename; // Store relative path
            }

            if (!empty($_FILES['banner']['name'])) {
                $banner_filename = basename($_FILES['banner']['name']);
                $banner_full_path = $upload_dir . $banner_filename;
                move_uploaded_file($_FILES['banner']['tmp_name'], $banner_full_path);
                $banner_path = $relative_upload_dir . $banner_filename; // Store relative path
            }

            // Connect to DB
            $conn = DB::db_connect();

            // Kiểm tra xem đang tạo mới blog hay cập nhật blog cũ
            if ($id == '') {
                $sql = "INSERT INTO posts (title, content, author_id, status, thumbnail_path, banner_path) VALUES (?, ?, ?, ?, ?, ?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ssssss", $title, $content, $author_id, $status, $thumbnail_path, $banner_path);
            } else {
                $sql = "UPDATE posts SET title = ?, content = ?, author_id = ?, status = ?, thumbnail_path = ?, banner_path = ? WHERE post_id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("sssssss", $title, $content, $author_id, $status, $thumbnail_path, $banner_path, $id);
            }

            // Chạy lệnh SQL
            $statement->execute();

            // Kiểm tra câu lệnh SQL có chạy thành công không
            if (!$statement->errno) {
                if ($id == '') {
                    $id = $statement->insert_id;
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
    public static function getPosts($sortOrder = 'asc', $published = true): array
    {
        $conn = DB::db_connect();

        if ($published){
            $sql = "SELECT * FROM posts WHERE status = 'published' ORDER BY post_id DESC";
        } else {
            $sql = "SELECT * FROM posts ORDER BY post_id $sortOrder";
        }

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
    
    public static function getPostsByCategoryId($categoryId, $limit = 3): array
    {
        $conn = DB::db_connect();
        $sql = "
        SELECT p.*
        FROM posts p
        INNER JOIN post_categories pc ON p.post_id = pc.post_id
        WHERE pc.category_id = ?
        ORDER BY p.updated_at DESC, p.post_id DESC
        LIMIT ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $categoryId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
        $stmt->close();
        $conn->close();
        return $posts;
    }

    public static function getPostsByCategoryIdWithPagination($categoryId, $offset, $limit = 3): array
    {
        $conn = DB::db_connect();
        $sql = "
        SELECT p.*
        FROM posts p
        INNER JOIN post_categories pc ON p.post_id = pc.post_id
        WHERE pc.category_id = ?
        ORDER BY p.post_id DESC
        LIMIT ?, ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $categoryId, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
        $stmt->close();
        $conn->close();
        return $posts;
    }

    public static function countPostsByCategoryId($categoryId): int
    {
        $conn = DB::db_connect();
        $sql = "
        SELECT COUNT(*) as total
        FROM posts p
        INNER JOIN post_categories pc ON p.post_id = pc.post_id
        WHERE pc.category_id = ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
        return $total;
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