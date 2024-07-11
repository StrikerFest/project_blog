<?php

namespace inc\models;

use database\DB;

session_start();

class User
{
    const ROLE_ADMIN = "admin";
    const ROLE_USER = "reader";

    public static function login(string $role): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $conn = DB::db_connect();

            $sql = "SELECT * FROM users where username = ? and `role` = '$role'";
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
                        'id' => $user['user_id'],
                        'username' => $user['username'],
                        'role' => $user['role'],
                    ];
                    unset($_SESSION['error_login_'.$role]);
                    
                    // Check lưu dữ liệu user bên người đọc hay bên quản lý
                    if ($user['role'] === self::ROLE_USER) {
                        $_SESSION['user_frontend'] = $customUser;
                    } else {
                        $_SESSION['user_backend'] = $customUser;
                    }
                    
                    header("Location: /admin/post");
                } else {
                    $_SESSION['error_login_'.$role] = true;
                    self::redirectLogin($role);
                }
            } else {
                // Không tìm thấy tên đăng nhập trong cơ sở dữ liệu
                $_SESSION['error_login_'.$role] = true;
                self::redirectLogin($role);
            }

            // Đóng kết nối đến cơ sở dữ liệu
            $statement->close();
            $conn->close();
        }
    }

    public static function logout(string $role): void
    {
        unset($_SESSION['error_login_'.$role]);

        if ($role === self::ROLE_USER) {
            unset($_SESSION['user_frontend']);
        } else {
            unset($_SESSION['user_backend']);
        }
        self::redirectLogin($role);
    }

    public static function redirectLogin(string $role): void
    {
        
        if ($role === self::ROLE_USER) {
            header("Location: /login");
            return;
        }
        header("Location: /admin/login");
    }
}
