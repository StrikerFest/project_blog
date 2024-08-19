<?php

namespace inc\helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Lớp này được dùng để chứa các hàm được sử dụng nhiều lần trong dự án
 * Hàm chứa code có thể được dùng lại nhiều lần
 */
class Common
{
    /* Hàm lấy đường dẫn file template
    Template là phần frontend - file hiển thị HTML ra ngoài website */
    public static function getTemplatePath($path): string
    {
        return $_ENV['TEMPLATE_DIR'] . '/' . $path;
    }

    /* Hàm lấy đường dẫn assets
    Asset là các file hỗ trợ hiển thị frontend
    Như file CSS, JS, các file chứa hình ảnh, fonts,... */
    public static function getAssetPath($path): string
    {
        return $_ENV['ASSETS_DIR'] . $path;
    }

    /* Hàm lấy đường dẫn controller
    Controller là phần backend, xử lý dữ liệu và logic trước khi
    Đưa ra ngoài frontend */
    public static function getControllerPath($path): string
    {
        return $_ENV['CONTROLLER_DIR'] . $path;
    }

    /* Hàm bao gồm template
    Để hiển thị template ra ngoài frontend */
    public static function requireTemplate($path, $args = []): void
    {
        require self::getTemplatePath($path);
    }

    public static function getCurrentBackendUser()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $currentUser = $_SESSION['user_backend'];

        if ($currentUser === null || $currentUser['role'] === 'reader') {
            header("Location: /");
            exit();
        }

        return $currentUser;
    }

    public static function getUploadPath($path): string
    {
        return $_ENV['UPLOAD_DIR'] . $path;
    }

    public static function getArrayBySQL($sql, $stmt): array
    {

        $stmt->execute();
        $results = [];
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
        }

        $stmt->close();
        return $results;
    }

    public static function getFrontendUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['user_frontend'] ?? null;
    }

    public static function get_url($path): string
    {
        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        return rtrim($base_url, '/') . '/' . ltrim($path, '/');
    }

    public static function logApprovalAction($post_id, $user_id, $status_from, $status_to, $reason = null): void
    {
        $conn = DB::db_connect();
        $sql = "INSERT INTO approval_logs (post_id, user_id, status_from, status_to, reason) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $post_id, $user_id, $status_from, $status_to, $reason);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public static function checkUserPermission($permission): void
    {
        $currentUser = self::getCurrentBackendUser();
        $role = $currentUser['role'];
        $currentUrl = $_SERVER['REQUEST_URI'];
        $redirectUrl = "/admin/post"; // Default redirect URL

        switch ($permission) {
            case 'ADMIN_ONLY':
                if ($role !== 'admin') {
                    $_SESSION['toast_message'] = "Access denied: Admins only.";
                    $_SESSION['toast_type'] = "error";
                    header("Location: $redirectUrl");
                    exit();
                }
                break;

            case 'NO_EDITOR':
                if ($role === 'editor') {
                    $_SESSION['toast_message'] = "Access denied: Editors are not allowed here.";
                    $_SESSION['toast_type'] = "error";
                    header("Location: $redirectUrl");
                    exit();
                }
                break;

            case 'NO_AUTHOR':
                if ($role === 'author') {
                    $_SESSION['toast_message'] = "Access denied: Authors are not allowed here.";
                    $_SESSION['toast_type'] = "error";
                    header("Location: $redirectUrl");
                    exit();
                }
                break;

            case 'NO_EDITOR_CREATE':
                if ($role === 'editor' && preg_match('/\/create$/', $currentUrl)) {
                    $_SESSION['toast_message'] = "Access denied: Editors cannot create content.";
                    $_SESSION['toast_type'] = "error";
                    header("Location: $redirectUrl");
                    exit();
                }
                break;

            default:
                // If no specific permission is provided, do nothing
                break;
        }
    }

    public static function sendEmail($to, $subject, $body, $from = null): bool
    {

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USERNAME'];
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            // Sender and recipient settings
            $mail->setFrom($from ?? $_ENV['DEFAULT_FROM_EMAIL'], $_ENV['DEFAULT_FROM_NAME']);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            // Send the email
            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log the error if needed or handle it appropriately
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
