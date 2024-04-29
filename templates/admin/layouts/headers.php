<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$currentPage = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['user_1']) && !isset($_SESSION['user_2'])) {
    if (strstr($currentPage, "/admin")) {
        header("Location: /admin/login");
        return;
    }
    header("Location: /login");
    return;
} else {
    if (isset($_SESSION['user_2']) && strstr($currentPage, "/admin")) {
        header("Location: /admin/login");
    }
    if (isset($_SESSION['user_1']) && !strstr($currentPage, "/admin")) {
        header("Location: /login");
    }
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $args['title'] ?? '' ?></title>
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/style.css') ?>">
</head>
