<?php

use inc\helpers\Common;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_user = $_SESSION['user_frontend'] ?? null;
if ($current_user === null) {
    exit;
}

?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/menu.css') ?>">
<div class="menu-navbar">
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Posts
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="post">View posts</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Categories
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="category">View categories</a>
            <a href="category/create">Create category</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Tags
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="tag">View tags</a>
            <a href="tag/create">Create tag</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Profile: <?= $current_user['username'] ?>
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <?php if (strstr($_SERVER['REQUEST_URI'], '/admin')) : ?>
                <a href="/admin/logout">Logout</a>
            <?php else : ?>
                <a href="/logout">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</div>
