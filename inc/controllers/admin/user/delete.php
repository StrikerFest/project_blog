<?php
require $_ENV['AUTOLOAD'];

use inc\models\User;

$userId = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if ($userId && $action) {
    if ($action === 'delete') {
        $result = User::softDeleteUser($userId);
        $message = $result ? "Người dùng đã được xóa thành công." : "Xóa người dùng không thành công.";
        $toastType = $result ? "success" : "error";
    } elseif ($action === 'recover') {
        $result = User::recoverUser($userId);
        $message = $result ? "Người dùng đã được phục hồi thành công." : "Phục hồi người dùng không thành công.";
        $toastType = $result ? "success" : "error";
    } else {
        $message = "Hành động không hợp lệ.";
        $toastType = "error";
    }

    $_SESSION['toast_message'] = $message;
    $_SESSION['toast_type'] = $toastType;
}

header("Location: /admin/user/index");
exit();
