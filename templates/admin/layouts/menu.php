<?php

use inc\helpers\Common;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_user = $_SESSION['user_backend'] ?? null;
if ($current_user === null) {
    exit('No user session');
}

?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/menu.css') ?>">
<div class="menu-navbar">
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Posts
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/post') ?>">View posts</a>
            <a href="<?= Common::get_url('admin/post/create') ?>">Create post</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Categories
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/category') ?>">View categories</a>
            <a href="<?= Common::get_url('admin/category/create') ?>">Create category</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Tags
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/tag') ?>">View tags</a>
            <a href="<?= Common::get_url('admin/tag/create') ?>">Create tag</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Logs
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/approval-log') ?>">Approval logs</a>
            <a href="<?= Common::get_url('admin/activity-log') ?>">Activity logs</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Users
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/user') ?>">View users</a>
            <a href="<?= Common::get_url('admin/user/create') ?>">Create user</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Profile: <?= $current_user['username'] ?>
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/profile') ?>">View profile</a>
            <a href="<?= Common::get_url('admin/logout') ?>">Logout</a>
        </div>
    </div>
</div>
