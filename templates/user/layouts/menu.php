<?php

use inc\helpers\Common;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_user = $_SESSION['user_frontend'] ?? null;

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="<?= Common::getAssetPath('css/menu.css') ?>">
<header>
    <div id="google_translate_element"></div>
</header>
<div class="menu-navbar">
    <div class="menu-logo">
        <a href="<?= Common::get_url('/') ?>"><img src="<?= Common::getAssetPath('images/logo.webp') ?>" alt="Logo"></a>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Bài viết
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('post') ?>">Xem bài viết</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Danh mục
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('category') ?>">Xem bài viết theo danh mục</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Thẻ
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="<?= Common::get_url('tag') ?>">Xem bài viết theo thẻ</a>
        </div>
    </div>
    <div class="menu-searchbar">
        <form action="<?= Common::get_url('search') ?>" method="get">
            <input type="text" name="query" class="menu-search-input" placeholder="Tìm kiếm...">
            <button type="submit" class="menu-search-button"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Hồ sơ: <?= $current_user['username'] ?? 'Khách' ?>
            <i class="fa fa-caret-down"></i>
        </button>
        <?php if (isset($current_user['username'])) : ?>
            <div class="menu-dropdown-content">
                <a href="<?= Common::get_url('profile') ?>">Xem hồ sơ</a>
                <?php if (str_contains($_SERVER['REQUEST_URI'], '/admin')) : ?>
                    <a href="<?= Common::get_url('admin/logout') ?>?redirect_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Đăng xuất</a>
                <?php else : ?>
                    <a href="<?= Common::get_url('logout') ?>?redirect_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Đăng xuất</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="menu-dropdown-content">
                <a href="<?= Common::get_url('login') ?>?redirect_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Đăng nhập</a>
                <a href="<?= Common::get_url('register') ?>?redirect_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Đăng ký</a>
            </div>
        <?php endif; ?>
    </div>
</div>
