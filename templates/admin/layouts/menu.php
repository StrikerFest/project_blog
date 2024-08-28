<?php

use inc\helpers\Common;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_user = $_SESSION['user_backend'] ?? null;
if ($current_user === null) {
    exit('Không có phiên người dùng');
}

$role = $current_user['role'];
?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/menu.css') ?>">
<div class="menu-navbar">
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Bài viết
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/post') ?>">Xem bài viết</a>
            <?php if ($role === 'admin' || $role === 'author'): ?>
                <a href="<?= Common::get_url('admin/post/create') ?>">Tạo bài viết</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Danh mục
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/category') ?>">Xem danh mục</a>
            <?php if ($role === 'admin' || $role === 'author'): ?>
                <a href="<?= Common::get_url('admin/category/create') ?>">Tạo danh mục</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Thẻ
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/tag') ?>">Xem thẻ</a>
            <?php if ($role === 'admin' || $role === 'author'): ?>
                <a href="<?= Common::get_url('admin/tag/create') ?>">Tạo thẻ</a>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($role === 'admin'): ?>
        <div class="menu-dropdown">
            <button class="menu-dropbtn">Người dùng
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="menu-dropdown-content">
                <a href="<?= Common::get_url('admin/user') ?>">Xem người dùng</a>
                <a href="<?= Common::get_url('admin/user/create') ?>">Tạo người dùng</a>
            </div>
        </div>
        <div class="menu-dropdown">
            <button class="menu-dropbtn">Banner
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="menu-dropdown-content">
                <a href="<?= Common::get_url('admin/banner') ?>">Xem banner</a>
                <a href="<?= Common::get_url('admin/banner/create') ?>">Tạo banner</a>
            </div>
        </div>
    <?php endif; ?>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Nhật ký
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/approval-log') ?>">Nhật ký phê duyệt</a>
            <!--            <a href="--><?php //= Common::get_url('admin/activity-log') ?><!--">Nhật ký hoạt động</a>-->
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Hồ sơ: <?= htmlspecialchars($current_user['username']) ?>
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('admin/profile') ?>">Xem hồ sơ</a>
            <a href="<?= Common::get_url('admin/logout') ?>">Đăng xuất</a>
        </div>
    </div>
</div>
