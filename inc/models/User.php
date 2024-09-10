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
                die('No role was passed to the login logic');
            }

            $conn = DB::db_connect();

            $sql = "SELECT * FROM users WHERE username = ?";
            if ($role == self::ROLE_USER) {
                $sql .= " AND `role` = '" . self::ROLE_USER . "' ";
            } else {
                $sql .= " AND `role` != '" . self::ROLE_USER . "' ";
            }

            $statement = $conn->prepare($sql);
            $statement->bind_param("s", $username);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if (!is_null($user['deleted_at'])) {
                    if ($role == self::ROLE_USER) {
                        $_SESSION['inactive_user_reader'] = true;
                    } else {
                        $_SESSION['inactive_user_admin'] = true;
                    }
                    self::redirectLogin($role);
                }

                if (password_verify($password, $user['password'])) {
                    $customUser = [
                        'id' => $user['user_id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'bio' => $user['bio'],
                        'profile_picture' => $user['profile_image'] ?? Common::getAssetPath('images/avatar.webp'),
                        'created_at' => $user['created_at'],
                        'updated_at' => $user['updated_at'],
                    ];
                    unset($_SESSION['error_login_' . $role]);

                    if ($user['role'] === self::ROLE_USER) {
                        $_SESSION['user_frontend'] = $customUser;
                        $redirectUrl = $_SESSION['redirect_url'] ?? $_GET['redirect_url'] ?? '/post';
                        unset($_SESSION['redirect_url']);
                        header("Location: $redirectUrl");
                        exit();
                    } else {
                        $_SESSION['user_backend'] = $customUser;
                        header("Location: /admin/post");
                        exit();
                    }
                } else {
                    $_SESSION['error_login_' . $role] = true;
                    self::redirectLogin($role);
                }
            } else {
                $_SESSION['error_login_' . $role] = true;
                self::redirectLogin($role);
            }

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

    public static function getUserByAuthorId($author_id) {
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

            // Server-side password validation
            if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9!@#$%^&*(),.?":{}|<>]/', $password)) {
                $_SESSION['error_signup'] = 'Password must be at least 8 characters long and include at least one uppercase letter and one number or special character.';
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

    public static function updateUserProfile(): bool
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = Common::getFrontendUser()['id'];
            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'bio' => $_POST['bio'] ?? '',
                'old_password' => $_POST['old_password'] ?? '',
                'password' => $_POST['password'] ?? '',
            ];

            $conn = DB::db_connect();

            // Verify the old password if a new password is provided
            if (!empty($data['password'])) {
                $sql = "SELECT password FROM users WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();

                if (!password_verify($data['old_password'], $user['password'])) {
                    $_SESSION['error_update'] = 'Old password is incorrect.';
                    return false;
                }
            }

            // Handle profile picture upload
            $upload_dir = $_ENV['UPLOAD_DIR'];
            $relative_upload_dir = '/assets/uploads/';
            $profilePicturePath = $user['profile_image'];
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
                $profilePictureFilename = basename($_FILES['profile_picture']['name']);
                $profilePictureFullPath = $upload_dir . $profilePictureFilename;
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profilePictureFullPath)) {
                    $profilePicturePath = $relative_upload_dir . $profilePictureFilename;
                } else {
                    $_SESSION['error_update'] = 'Failed to upload profile picture.';
                    return false;
                }
            }

            // Prepare the SQL statement for updating user information
            $sql = "UPDATE users SET username = ?, email = ?, bio = ?, profile_image = ?, updated_at = CURRENT_TIMESTAMP() WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }

            $stmt->bind_param('ssssi', $data['username'], $data['email'], $data['bio'], $profilePicturePath, $userId);

            $updateResult = $stmt->execute();

            // Update password if provided
            if (!empty($data['password'])) {
                $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
                $passwordSql = "UPDATE users SET password = ? WHERE user_id = ?";
                $passwordStmt = $conn->prepare($passwordSql);
                if ($passwordStmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                $passwordStmt->bind_param('si', $hashedPassword, $userId);
                $passwordStmt->execute();
                $passwordStmt->close();
            }

            $stmt->close();
            $conn->close();

            // Update the session data with the new information
            $_SESSION['user_frontend']['username'] = $data['username'];
            $_SESSION['user_frontend']['email'] = $data['email'];
            $_SESSION['user_frontend']['bio'] = $data['bio'];
            $_SESSION['user_frontend']['profile_picture'] = $profilePicturePath;
            $_SESSION['user_frontend']['updated_at'] = date('Y-m-d H:i:s');

            return $updateResult;
        }

        return false;
    }

    public static function getUsers($activeOnly = true, array $roles = [], $sortOrder = 'ASC'): array
    {
        $conn = DB::db_connect();

        // Base SQL query
        $sql = "SELECT * FROM users WHERE 1";

        // Add condition for active users if $activeOnly is true
        if ($activeOnly) {
            $sql .= " AND deleted_at IS NULL";
        }

        // Add condition for roles if roles array is not empty
        if (!empty($roles)) {
            // Generate placeholders for the IN clause (e.g., ?, ?, ?)
            $placeholders = implode(',', array_fill(0, count($roles), '?'));
            $sql .= " AND role IN ($placeholders)";
        }

        $sql .= " ORDER BY user_id $sortOrder";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters if roles are provided
        if (!empty($roles)) {
            // Prepare the types string for bind_param, all roles are strings
            $types = str_repeat('s', count($roles));
            // Merge the roles array as arguments for bind_param
            $stmt->bind_param($types, ...$roles);
        }

        // Execute the query
        $stmt->execute();

        $result = $stmt->get_result();
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        // Close the connection
        $conn->close();

        return $users;
    }

    public static function saveUser($userId = null): bool
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'bio' => $_POST['bio'] ?? '',
                'password' => $_POST['password'] ?? '',
                'role' => $_POST['role'] ?? 'reader',
            ];

            $conn = DB::db_connect();

            // Handle profile picture upload
            $upload_dir = $_ENV['UPLOAD_DIR'];
            $relative_upload_dir = '/assets/uploads/';
            $profilePicturePath = $userId ? self::getUserById($userId)['profile_image'] : Common::getAssetPath('images/default-avatar.png');

            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
                $profilePictureFilename = basename($_FILES['profile_image']['name']);
                $profilePictureFullPath = $upload_dir . $profilePictureFilename;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $profilePictureFullPath)) {
                    $profilePicturePath = $relative_upload_dir . $profilePictureFilename;
                } else {
                    $_SESSION['error_update'] = 'Failed to upload profile picture.';
                    return false;
                }
            }

            if ($userId) {
                // Update existing user
                $sql = "UPDATE users SET username = ?, email = ?, bio = ?, profile_image = ?, role = ?, updated_at = CURRENT_TIMESTAMP() WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssssi', $data['username'], $data['email'], $data['bio'], $profilePicturePath, $data['role'], $userId);
            } else {
                // Insert new user
                $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
                $sql = "INSERT INTO users (username, email, bio, profile_image, role, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssssss', $data['username'], $data['email'], $data['bio'], $profilePicturePath, $data['role'], $hashedPassword);
            }

            $result = $stmt->execute();

            if ($userId && !empty($data['password'])) {
                // Update password if provided
                $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
                $passwordSql = "UPDATE users SET password = ? WHERE user_id = ?";
                $passwordStmt = $conn->prepare($passwordSql);
                $passwordStmt->bind_param('si', $hashedPassword, $userId);
                $passwordStmt->execute();
                $passwordStmt->close();
            }

            $stmt->close();
            $conn->close();

            return $result;
        }

        return false;
    }

    public static function getUserById($userId)
    {
        if ($userId === null) {
            return null;
        }

        $conn = DB::db_connect();
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $user ?: null;
    }

    public static function softDeleteUser($userId): bool
    {
        if ($userId === null) {
            return false;
        }

        $conn = DB::db_connect();
        $sql = "UPDATE users SET deleted_at = CURRENT_TIMESTAMP WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('i', $userId);
        $result = $stmt->execute();

        $stmt->close();
        $conn->close();

        return $result;
    }

    public static function recoverUser($userId): bool
    {
        if ($userId === null) {
            return false;
        }

        $conn = DB::db_connect();
        $sql = "UPDATE users SET deleted_at = NULL WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('i', $userId);
        $result = $stmt->execute();

        $stmt->close();
        $conn->close();

        return $result;
    }

    public static function updateAdminProfile($userId, $data, $files): bool
    {
        // Use similar logic as the user's profile update, adjusting as necessary
        // Example:
        // - Validate and sanitize input
        // - Handle profile image upload
        // - Update the database with the new information

        // Example:
        $conn = DB::db_connect();

        // Handle profile picture upload
        $upload_dir = $_ENV['UPLOAD_DIR'];
        $relative_upload_dir = '/assets/uploads/';
        $profilePicturePath = $userId ? self::getUserById($userId)['profile_image'] : Common::getAssetPath('images/default-avatar.png');

        if (isset($files['profile_image']) && $files['profile_image']['error'] == UPLOAD_ERR_OK) {
            $profilePictureFilename = basename($files['profile_image']['name']);
            $profilePictureFullPath = $upload_dir . $profilePictureFilename;

            if (move_uploaded_file($files['profile_image']['tmp_name'], $profilePictureFullPath)) {
                $profilePicturePath = $relative_upload_dir . $profilePictureFilename;
            } else {
                $_SESSION['error_update'] = 'Failed to upload profile picture.';
                return false;
            }
        }

        $sql = "UPDATE users SET username = ?, email = ?, bio = ?, profile_image = ?, updated_at = CURRENT_TIMESTAMP() WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $data['username'], $data['email'], $data['bio'], $profilePicturePath, $userId);
        $result = $stmt->execute();

        if (!empty($data['password']) && $data['password'] === $data['confirm_password']) {
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $passwordSql = "UPDATE users SET password = ? WHERE user_id = ?";
            $passwordStmt = $conn->prepare($passwordSql);
            $passwordStmt->bind_param('si', $hashedPassword, $userId);
            $passwordStmt->execute();
            $passwordStmt->close();
        }

        $stmt->close();
        $conn->close();

        return $result;
    }

    public static function getUsersByRole(string $role): array
    {
        $conn = DB::db_connect();
        $sql = "SELECT user_id, username, email, role, bio, profile_image FROM users WHERE role = ? AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        $stmt->close();
        $conn->close();

        return $users;
    }
}
