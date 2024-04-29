<?php
// Nhập file biến môi trường vào để sử dụng
require_once 'env.php';
require_once 'inc/helpers/Common.php';
require_once 'inc/helpers/Config.php';

use inc\helpers\Common;
use inc\helpers\Config;

// Router - Chuyển hướng đường dẫn web đến file xử lý riêng của nó
// Đây chỉ chứa dữ liệu mảng của router
// Dữ liệu này sẽ được truyền vào hàm bên dưới để xử lý
$routes = [

    //login
    '/admin/login' => Common::getControllerPath('auth/admin/login.php'),
    '/admin/logout' => Common::getControllerPath('auth/admin/logout.php'),

    // Quản lý blog
    '/admin/post'        => Common::getControllerPath('post/index.php'),
    '/admin/post/create' => Common::getControllerPath('post/edit.php'),
    '/admin/post/edit'   => Common::getControllerPath('post/edit.php'),
    '/admin/post/delete' => Common::getControllerPath('post/delete.php'),
    
    // Blog - Người dùng
    '/login' => Common::getControllerPath('auth/user/login.php'),
    '/logout' => Common::getControllerPath('auth/user/logout.php'),
    '/post' => Common::getControllerPath('post/index.php'),

];

// Hàm chuyển hướng router
Config::redirectRouter($routes);
