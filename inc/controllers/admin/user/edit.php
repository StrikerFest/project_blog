<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\User;

$userId = $_GET['id'] ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = User::saveUser($userId);
    if ($success) {
        header("Location: /admin/user");
        exit();
    } else {
        // Handle error (e.g., show an error message)
    }
}

Common::requireTemplate('admin/user/edit.php', [
    'user' => User::getUserById($userId),
]);
