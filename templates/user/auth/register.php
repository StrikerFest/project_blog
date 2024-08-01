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
?>

<?php
Common::requireTemplate('user/layouts/headers.php', [
    'title' => 'Sign Up'
]);
?>

<link rel="stylesheet" href="<?= Common::getAssetPath('css/signup-user.css') ?>">
<body>
<?php Common::requireTemplate('user/layouts/menu.php', []); ?>
<div class="user-signup-wrapper">
    <div class="user-signup-container">
        <form id="signup-form" class="user-signup-form" method="POST">
            <div class="user-signup-content">
                <h2 class="user-signup-title">Sign Up</h2>
                <input class="user-signup-input" type="text" name="username" placeholder="Username" required="required"/>
                <input class="user-signup-input" type="email" name="email" placeholder="Email" required="required"/>
                <input class="user-signup-input" type="password" id="password" name="password" placeholder="Password" required="required"/>
                <input class="user-signup-input" type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required="required"/>
                <span id="password-error" class="user-signup-error" style="display:none;"></span>
            </div>
            <?php if (isset($_SESSION['error_signup'])) { ?>
                <div class="user-signup-error"><?= $_SESSION['error_signup'];
                    unset($_SESSION['error_signup']); ?></div>
            <?php } ?>
            <div class="user-signup-buttons">
                <button class="user-signup-btn" type="submit">Sign Up</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#signup-form').submit(function (e) {
            const password = $('#password').val();
            const confirmPassword = $('#confirm-password').val();
            if (password !== confirmPassword) {
                e.preventDefault();
                $('#password-error').text('Passwords do not match.').show();
            } else {
                $('#password-error').hide();
            }
        });
    });
</script>
</body>
