<?php
require 'vendor/autoload.php';
/**
 * Hàm autoload
 * Hỗ trợ cho việc nhập lớp
 * Không cần để ý đến hàm này lắm
 */
class Autoloader {
    public static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    public static function autoload($className) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';

        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        $file = __DIR__ . '/' . $fileName;
        if (file_exists($file)) {
            require $file;
        } else {
            $fileName = 'vendor\\' . $fileName;
            $file = __DIR__ . '/' . $fileName;
            if (file_exists($file)) {
                require $file;
            } else {
                throw new Exception("Class file for $className not found.");
            }
            throw new Exception("Class file for $className not found.");
        }
    }
}

Autoloader::register();
