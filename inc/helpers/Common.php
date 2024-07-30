<?php

namespace inc\helpers;

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
        if ($currentUser === null) {
            header("Location: /admin/logout");
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
    
    public static function getFrontendUser(){
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
}
