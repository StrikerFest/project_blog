<?php
require $_ENV['AUTOLOAD'];

use inc\models\User;

$userId = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if ($userId && $action) {
    if ($action === 'delete') {
        $result = User::softDeleteUser($userId);
        $message = $result ? "User has been successfully deleted." : "Failed to delete the user.";
        $toastType = $result ? "success" : "error";
    } elseif ($action === 'recover') {
        $result = User::recoverUser($userId);
        $message = $result ? "User has been successfully recovered." : "Failed to recover the user.";
        $toastType = $result ? "success" : "error";
    } else {
        $message = "Invalid action.";
        $toastType = "error";
    }

    $_SESSION['toast_message'] = $message;
    $_SESSION['toast_type'] = $toastType;
}

header("Location: /admin/user/index");
exit();
