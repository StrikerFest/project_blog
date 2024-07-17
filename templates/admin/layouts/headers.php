<?php

use inc\helpers\Common;
/**
 * @var mixed $args
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$currentPage = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['user_backend']) && !isset($_SESSION['user_frontend'])) {
    if (strstr($currentPage, "/admin")) {
        header("Location: /admin/login");
        return;
    }
    header("Location: /login");
    return;
} else {
    if (isset($_SESSION['user_frontend']) && strstr($currentPage, "/admin") && !isset($_SESSION['user_backend'])) {
        header("Location: /admin/login");
    }
    if (isset($_SESSION['user_backend']) && !strstr($currentPage, "/admin") && !isset($_SESSION['user_frontend'])) {
        header("Location: /login");
    }
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $args['title'] ?? '' ?></title>
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/style.css') ?>">
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/listing.css') ?>">
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/form-table.css') ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>

<?php Common::requireTemplate('admin/layouts/menu.php', []);
