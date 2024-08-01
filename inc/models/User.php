<?php

namespace inc\models;

use inc\helpers\Common;
use inc\helpers\DB;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class User
{
    const ROLE_ADMIN = "admin";
    const ROLE_USER = "reader";

    public static function login(string $role): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!isset($role)) {
                die('No role were passed to login logic');
            }
            $conn = DB::db_connect();

            if ($role == self::ROLE_USER) {
                $sql = "SELECT * FROM users where username = ? and `role` = '" . self::ROLE_USER . "' ";
            } else {
                $sql = "SELECT * FROM users where username = ? and `role` != '" . self::ROLE_USER . "' ";
            }
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
                        'profile_picture' => $user['profile_picture'] ?? Common::getAssetPath('images/avatar'),
                    ];
                    unset($_SESSION['error_login_'.$role]);
                    
                    // Check lưu dữ liệu user bên người đọc hay bên quản lý
                    if ($user['role'] === self::ROLE_USER) {
                        $_SESSION['user_frontend'] = $customUser;
                        $redirectUrl = $_SESSION['redirect_url'] ?? $_GET['redirect_url'] ?? '/post';
                        unset($_SESSION['redirect_url']);
                        header("Location: $redirectUrl");
                        exit();
                    } else {
                        $_SESSION['user_backend'] = $customUser;
                        header("Location: /admin/post");
                    }
                } else {
                    $_SESSION['error_login_' . $role] = true;
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Store the redirect URL in the session
        if (isset($_GET['redirect_url'])) {
            $_SESSION['redirect_url'] = $_GET['redirect_url'];
        }

        unset($_SESSION['error_login_' . $role]);

        if ($role === self::ROLE_USER) {
            unset($_SESSION['user_frontend']);
        } else {
            unset($_SESSION['user_backend']);
        }

        self::redirectLogin($role);
    }

    public static function redirectLogin(string $role): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $redirectUrl = $_SESSION['redirect_url'] ?? null;
        unset($_SESSION['redirect_url']);

        if ($role === self::ROLE_USER) {
            if ($redirectUrl) {
                header("Location: $redirectUrl");
            } else {
                header("Location: /login");
            }
        } else {
            header("Location: /admin/login");
        }
        exit();
    }

    public static function getUserByAuthorId($author_id)
    {
        $conn = DB::db_connect();
        $sql = 'SELECT * FROM users WHERE user_id = ?';
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param('i', $author_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $user;
    }

    public static function register(string $role): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($password !== $confirmPassword) {
                $_SESSION['error_signup'] = 'Passwords do not match.';
                header("Location: register");
                exit();
            }

            $conn = DB::db_connect();

            // Check if username or email already exists
            $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['error_signup'] = 'Username or email already exists.';
                header("Location: register");
                exit();
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert the new user into the database
            $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                $_SESSION['success_signup'] = 'Account created successfully. Please log in.';
                header("Location: login");
            } else {
                $_SESSION['error_signup'] = 'Failed to create account. Please try again.';
                header("Location: register");
            }

            $stmt->close();
            $conn->close();
            exit();
        }
    }
}
