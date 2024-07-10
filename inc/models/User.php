<?php

namespace inc\models;

use database\DB;

session_start();

class User
{

    const ROLE_ADMIN = "admin";
    const ROLE_USER = "reader";

    public static function login(string $role)
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
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role'],
                    ];
                    unset($_SESSION['error_login_'.$role]);
                    $_SESSION['user_'.$role] = $customUser;
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

    public static function logout(string $role) {
        unset($_SESSION['error_login_'.$role]);
        unset($_SESSION['user_'.$role]);
        self::redirectLogin($role);
    }

    public static function redirectLogin(string $role) {
        if ($role == User::ROLE_ADMIN) {
            header("Location: /admin/login");
            return;
        }
        header("Location: /login");
        return;
    }
}
