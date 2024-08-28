<?php

use inc\helpers\Common;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$currentPage = $_SERVER['REQUEST_URI'];
if (isset($_SESSION['user_backend'])) {
    header("Location: /admin/post");
    exit();
}
?>

<body>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/login.css') ?>">
<div class="container">
    <div class="form">
        <h1 class="title">Đăng nhập</h1>
        <div>
            <form method="POST">
                <div class="field">
                    <input placeholder="Tên đăng nhập" type="text" name="username" required="required"/>
                </div>
                <div class="field">
                    <input placeholder="Mật khẩu" type="password" name="password" required="required"/>
                </div>
                <?php if (isset($_SESSION['error_login_admin'])) { ?>
                    <div class="error">Tên đăng nhập hoặc mật khẩu không chính xác</div>
                    <?php unset($_SESSION['error_login_admin']); ?>
                <?php } ?>
                <?php if (isset($_SESSION['inactive_user_admin'])) { ?>
                    <div class="error">Tài khoản của bạn không hoạt động. Vui lòng liên hệ với quản trị viên.</div>
                    <?php unset($_SESSION['inactive_user_admin']); ?>
                <?php } ?>
                <button class="btn">Đăng nhập</button>
            </form>
        </div>
    </div>
</div>
</body>
