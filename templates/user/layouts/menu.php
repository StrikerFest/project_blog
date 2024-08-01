<?php

use inc\helpers\Common;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_user = $_SESSION['user_frontend'] ?? null;

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="<?= Common::getAssetPath('css/menu.css') ?>">
<div class="menu-navbar">
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Posts
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('post') ?>">View posts</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Categories
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('category') ?>">View post by categories</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Tags
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('tag') ?>">View post by tags</a>
        </div>
    </div>
    <div class="menu-searchbar">
        <form action="<?= Common::get_url('search') ?>" method="get">
            <input type="text" name="query" class="menu-search-input" placeholder="Search...">
            <button type="submit" class="menu-search-button"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Profile: <?= $current_user['username'] ?? 'Guest' ?>
            <i class="fa fa-caret-down"></i>
        </button>
        <?php if (isset($current_user['username'])) : ?>
            <div class="menu-dropdown-content">
                <a href="<?= Common::get_url('profile') ?>">View profile</a>
                <?php if (str_contains($_SERVER['REQUEST_URI'], '/admin')) : ?>
                    <a href="<?= Common::get_url('admin/logout') ?>?redirect_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Logout</a>
                <?php else : ?>
                    <a href="<?= Common::get_url('logout') ?>?redirect_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Logout</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="menu-dropdown-content">
                <a href="<?= Common::get_url('login') ?>?redirect_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Login</a>
                <a href="<?= Common::get_url('register') ?>?redirect_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Sign in</a>
            </div>
        <?php endif; ?>
    </div>
</div>
