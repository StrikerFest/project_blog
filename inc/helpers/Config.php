<?php

namespace inc\helpers;

/**
 * Lớp này dùng để config dự án
 * Không cần chú ý đến lớp này lắm
 */
class Config{

    public static function redirectRouter($routes): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Lấy đường dẫn URI
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $route_args = [];
        $matchedRoute = null;
        
        foreach ($routes as $routePattern => $routePath) {
            if (self::matchRoute($routePattern, $requestUri, $route_args)) {
                $matchedRoute = $routePath;
                break;
            }
        }

        // Kiểm tra nếu đường dẫn tồn tại trong mảng
        if ($matchedRoute !== null) {
            // Nếu đường dẫn trang tồn tại sẽ chuyển hướng về trang được yêu cầu
            if (file_exists($matchedRoute)) {
                require_once $matchedRoute;
            } else {
                echo "File does not exist: " . $matchedRoute;
            }
        } else {
            header("Location: /post");
        }
    }

    // Function to match the route pattern
    private static function matchRoute($pattern, $uri, &$route_args): bool
    {
        $pattern = preg_replace('/\{([a-z_]+)}/', '(?P<$1>[^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $route_args[$key] = $value;
                }
            }
            return true;
        }
        return false;
    }

}