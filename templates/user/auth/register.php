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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/login-user.css') ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
</head>
<body>
<div class="sign-up">
    <div class="circle circle--red"></div>
    <div class="circle circle--yellow"></div>
    <div class="circle circle--green"></div>
    <div class="circle circle--purple"></div>
    <form id="signup-form" class="sign-up__form" method="POST">
        <div class="sign-up__content">
            <h2 class="sign-up__title">Sign Up</h2>
            <input class="sign-up__inp" type="text" name="username" placeholder="Username" required="required"/>
            <input class="sign-up__inp" type="email" name="email" placeholder="Email" required="required"/>
            <input class="sign-up__inp" type="password" id="password" name="password" placeholder="Password" required="required"/>
            <input class="sign-up__inp" type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required="required"/>
            <span id="password-error" class="error" style="display:none;"></span>
        </div>
        <?php if (isset($_SESSION['error_signup'])) { ?>
            <div class="error"><?= $_SESSION['error_signup'];
                unset($_SESSION['error_signup']); ?></div>
        <?php } ?>
        <div class="sign-up__buttons">
            <button class="btn btn--signup" type="submit">Sign Up</button>
        </div>
    </form>
</div>
</body>
</html>
