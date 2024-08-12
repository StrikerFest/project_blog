<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\User;

$currentUser = Common::getCurrentBackendUser();
$currentUserId = $currentUser['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = User::getUserById($currentUserId);

    // Check if the old password is provided and correct before updating
    if (!empty($_POST['password']) && !password_verify($_POST['old_password'], $user['password'])) {
        $_SESSION['toast_message'] = "Old password is incorrect.";
        $_SESSION['toast_type'] = "error";
        header("Location: /admin/profile");
        exit;
    }

    $success = User::updateAdminProfile($currentUserId, $_POST, $_FILES);
    if ($success) {
        $_SESSION['toast_message'] = "Profile updated successfully.";
        $_SESSION['toast_type'] = "success";
    } else {
        $_SESSION['toast_message'] = "Failed to update profile.";
        $_SESSION['toast_type'] = "error";
    }
    header("Location: /admin/profile");
    exit;
}

$adminUser = User::getUserById($currentUserId);
Common::requireTemplate('admin/profile/detail.php', [
    'user' => $adminUser
]);
