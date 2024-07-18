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
    '/admin/login' => Common::getControllerPath('admin/auth/login.php'),
    '/admin/logout' => Common::getControllerPath('admin/auth/logout.php'),

    // Quản lý blog
    '/admin/post'        => Common::getControllerPath('admin/post/index.php'),
    '/admin/post/create' => Common::getControllerPath('admin/post/edit.php'),
    '/admin/post/edit'   => Common::getControllerPath('admin/post/edit.php'),
    '/admin/post/delete' => Common::getControllerPath('admin/post/delete.php'),

    // Quản lý danh mục
    '/admin/category'        => Common::getControllerPath('admin/category/index.php'),
    '/admin/category/create' => Common::getControllerPath('admin/category/edit.php'),
    '/admin/category/edit'   => Common::getControllerPath('admin/category/edit.php'),
    '/admin/category/delete' => Common::getControllerPath('admin/category/delete.php'),

    // Quản lý nhãn
    '/admin/tag'        => Common::getControllerPath('admin/tag/index.php'),
    '/admin/tag/create' => Common::getControllerPath('admin/tag/edit.php'),
    '/admin/tag/edit'   => Common::getControllerPath('admin/tag/edit.php'),
    '/admin/tag/delete' => Common::getControllerPath('admin/tag/delete.php'),
    
    // Blog - Người dùng
    '/login' => Common::getControllerPath('user/auth/login.php'),
    '/logout' => Common::getControllerPath('user/auth/logout.php'),
    '/post' => Common::getControllerPath('user/post/index.php'),
    '/post/show' => Common::getControllerPath('user/post/detail.php'),

];

// Hàm chuyển hướng router
Config::redirectRouter($routes);
