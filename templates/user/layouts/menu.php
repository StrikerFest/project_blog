<?php

use inc\helpers\Common;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_user = $_SESSION['user_frontend'] ?? null;

?>
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
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Profile: <?= $current_user['username'] ?? 'Guest' ?>
            <i class="fa fa-caret-down"></i>
        </button>
        <?php if (isset($current_user['username'])) : ?>
            <div class="menu-dropdown-content">
                <?php if (strstr($_SERVER['REQUEST_URI'], '/admin')) : ?>
                    <a href="<?= Common::get_url('admin/logout') ?>">Logout</a>
                <?php else : ?>
                    <a href="<?= Common::get_url('logout') ?>">Logout</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="menu-dropdown-content">
                <a href="<?= Common::get_url('user/login') ?>">Login</a>
                <a href="<?= Common::get_url('user/register') ?>">Sign in</a>
            </div>
        <?php endif; ?>
    </div>
</div>
