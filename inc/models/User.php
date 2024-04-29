<?php

namespace inc\models;

use database\DB;

session_start();

class User
{
    public static function login(int $type)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $conn = DB::db_connect();

            $sql = "SELECT * FROM users where username = ? and type = $type";
            $statement = $conn->prepare($sql);
            $statement->bind_param("s", $username);

            $statement->execute();
            $result = $statement->get_result();
            // Kiểm tra xem có bản ghi nào được trả về không
            if ($result->num_rows > 0) {
                // Lấy thông tin người dùng từ kết quả truy vấn
                $user = $result->fetch_assoc();

                // Kiểm tra mật khẩu được nhập vào với mật khẩu trong cơ sở dữ liệu
                if (password_verify($password, $user['password'])) {
                    $customUser = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'type' => $user['type'],
                    ];
                    unset($_SESSION['error_login_'.$type]);
                    $_SESSION['user_'.$type] = $customUser;
                    header("Location: /admin/post");
                } else {
                    $_SESSION['error_login_'.$type] = true;
                    self::redirectLogin($type);
                }
            } else {
                // Không tìm thấy tên đăng nhập trong cơ sở dữ liệu
                $_SESSION['error_login_'.$type] = true;
                self::redirectLogin($type);
            }

            // Đóng kết nối đến cơ sở dữ liệu
            $statement->close();
            $conn->close();
        }
    }

    public static function logout(int $type) {
        unset($_SESSION['error_login_'.$type]);
        unset($_SESSION['user_'.$type]);
        self::redirectLogin($type);
    }

    public static function redirectLogin($type) {
        if ($type == 1) {
            header("Location: /admin/login");
            return;
        }
        header("Location: /login");
        return;
    }
}
