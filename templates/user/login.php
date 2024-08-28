<?php

use inc\helpers\Common;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$currentPage = $_SERVER['REQUEST_URI'];
if (isset($_SESSION['user_frontend'])) {
    header("Location: /post");
    exit();
}

use inc\models\Banner;
$headerBanner = Banner::getBannerByType('Header');
$sideBanner = Banner::getBannerByType('Sidebar');
$footerBanner = Banner::getBannerByType('Footer');
Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'Đăng nhập'
]);
?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/login-user.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>
<div class="user-login-wrapper">
    <div class="user-login-container">
        <form class="user-login-form" method="POST">
            <div class="user-login-content">
                <h2 class="user-login-title">Đăng nhập</h2>
                <input class="user-login-input" type="text" name="username" placeholder="Tên người dùng" required="required"/>
                <input class="user-login-input" type="password" name="password" placeholder="Mật khẩu" required="required"/>
            </div>
            <?php if (isset($_SESSION['error_login_reader'])) : ?>
                <div class="user-login-error">Tên người dùng hoặc mật khẩu không chính xác</div>
                <?php unset($_SESSION['error_login_reader']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['inactive_user_reader'])) : ?>
                <div class="user-login-error">Tài khoản của bạn không hoạt động. Vui lòng liên hệ hỗ trợ.</div>
                <?php unset($_SESSION['inactive_user_reader']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['success_signup'])) : ?>
                <div class="user-login-success"><?= $_SESSION['success_signup']; ?></div>
                <?php unset($_SESSION['success_signup']); ?>
            <?php endif; ?>
            <div class="user-login-buttons">
                <button class="user-login-btn" type="submit">Đăng nhập</button>
            </div>
        </form>
    </div>
</div>
</body>
