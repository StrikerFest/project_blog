<?php

use inc\helpers\Common;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$currentPage = $_SERVER['REQUEST_URI'];
if (isset($_SESSION['user_2'])) {
    header("Location: /post");
}
?>


<body>
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/login-user.css') ?>">
    <div class="sign-up">
        <div class="circle circle--red"></div>
        <div class="circle circle--yellow"></div>
        <div class="circle circle--green"></div>
        <div class="circle circle--purple"></div>
        <form class="sign-up__form" method="POST">
            <div class="sign-up__content">
                <h2 class="sign-up__title">Login</h2>
                <input class="sign-up__inp" type="text" name="username" placeholder="Username" required="required" />
                <input class="sign-up__inp" type="password" name="password" placeholder="Password" required="required" />
            </div>
            <?php if (isset($_SESSION['error_login_2'])) {  ?>
                <div class="error">Username or password incorrect</div>
            <?php } ?>
            <div class="sign-up__buttons">
                <button class="btn btn--signin" type="submit">Sign in</button>
            </div>
        </form>
    </div>
</body>