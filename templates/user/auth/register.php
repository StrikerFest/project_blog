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
    'title' => 'Đăng Ký'
]);
?>

<link rel="stylesheet" href="<?= Common::getAssetPath('css/signup-user.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>
<div class="user-signup-wrapper">
    <div class="user-signup-container">
        <form id="signup-form" class="user-signup-form" method="POST">
            <div class="user-signup-content">
                <h2 class="user-signup-title">Đăng Ký</h2>
                <input class="user-signup-input" type="text" name="username" placeholder="Tên đăng nhập" required="required"/>
                <input class="user-signup-input" type="email" name="email" placeholder="Email" required="required"/>
                <input class="user-signup-input" type="password" id="password" name="password" placeholder="Mật khẩu" required="required"/>
                <input class="user-signup-input" type="password" id="confirm-password" name="confirm_password" placeholder="Xác nhận mật khẩu" required="required"/>
                <ul id="password-requirements" class="user-signup-error" style="display:none;"></ul>
                <span id="password-error" class="user-signup-error" style="display:none;"></span>
            </div>
            <?php if (isset($_SESSION['error_signup'])) { ?>
                <div class="user-signup-error"><?= $_SESSION['error_signup'];
                    unset($_SESSION['error_signup']); ?></div>
            <?php } ?>
            <div class="user-signup-buttons">
                <button class="user-signup-btn" type="submit">Đăng Ký</button>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const passwordRequirements = $('#password-requirements');
        const passwordInput = $('#password');
        const confirmPasswordInput = $('#confirm-password');
        const passwordError = $('#password-error');

        passwordInput.on('input', function () {
            const password = passwordInput.val();
            const requirements = [];

            if (password.length < 8) {
                requirements.push('Yêu cầu tối thiểu 8 ký tự');
            }

            if (!/[A-Z]/.test(password)) {
                requirements.push('Yêu cầu ít nhất một chữ cái viết hoa');
            }

            if (!/[0-9!@#$%^&*(),.?":{}|<>]/.test(password)) {
                requirements.push('Yêu cầu ít nhất một số hoặc ký tự đặc biệt');
            }

            if (requirements.length > 0) {
                passwordRequirements.html(requirements.map(req => `<li>${req}</li>`).join('')).show();
            } else {
                passwordRequirements.hide();
            }
        });

        $('#signup-form').submit(function (e) {
            const password = passwordInput.val();
            const confirmPassword = confirmPasswordInput.val();

            if (password !== confirmPassword) {
                e.preventDefault();
                passwordError.text('Mật khẩu không khớp.').show();
            } else {
                passwordError.hide();
            }
        });
    });
</script>
</body>
