<?php

namespace inc\models;

use database\DB;

class Post
{
    public static function save_post()
    {
        // Kiểm tra xem form có đang được gửi hay không
        // Và phải gửi với method POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Dữ liệu của form được gửi
            $id = $_POST['id'] ?? '';
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';

            // Kết nối DB
            $conn = DB::db_connect();

            // Kiểm tra xem đang tạo mới blog hay cập nhật blog cũ
            if ($id == '') {
                $sql = "INSERT INTO posts (title, content) VALUES (?, ?)";
                $statement = $conn->prepare($sql);
                $statement->bind_param("ss", $title, $content);
            } else {
                $sql = "UPDATE posts SET posts.title = ?, posts.content = ? WHERE posts.id = ?";
                $statement = $conn->prepare($sql);
                $statement->bind_param("sss", $title, $content, $id);
            }

            // Chạy lệnh SQL
            $statement->execute();

            // Kiểm tra câu lệnh SQL có chạy thành công không
            if (!$statement->errno) {
                $conn->close();
                // Chuyển hướng về trang post
                header("Location: /admin/post");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                $conn->close();
            }
        }
    }

    // Lấy tất cả bài post
    public static function getPosts()
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM posts ORDER BY id desc";
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
    public static function getPostById($id)
    {
        if (!isset($id)) {
            return null;
        }
        
        $conn = DB::db_connect();
        $sql = "SELECT * FROM posts WHERE id=$id";
        $result = $conn->query($sql);
        $post = null;
        if ($result->num_rows == 1) {
            $post = $result->fetch_assoc();
        }
        $conn->close();
        return $post;
    }

    // Xóa bài post theo mã post
    public static function deletePost($id)
    {
        $conn = DB::db_connect();
        $sql = "DELETE FROM posts WHERE id=$id";
        $conn->query($sql);
        $conn->close();
    }
}