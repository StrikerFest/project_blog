<?php

namespace inc\helpers;

/**
 * Lớp này dùng để config dự án
 * Không cần chú ý đến lớp này lắm
 */
class Config{

    public static function redirectRouter($routes): void
    {
        // Lấy đường dẫn URI
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // Kiểm tra nếu đường dẫn tồn tại trong mảng
        if (array_key_exists($requestUri, $routes)) {
            // Nếu đường dẫn trang tồn tại sẽ chuyển hướng về trang được yêu cầu
            require_once $routes[$requestUri];
        } else {
            
            header("Location: /post");
        }
    }
}