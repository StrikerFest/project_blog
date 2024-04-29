<?php

use inc\helpers\Common;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$currentPage = $_SERVER['REQUEST_URI'];
if (isset($_SESSION['user_1'])) {
    header("Location: /admin/post");
}
?>


<body>
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/login.css') ?>">
    <div class="container">
        <div class="form">
            <h1 class="title">Login</h1>
            <div>
                <form method="POST">
                    <div class="field">
                        <input placeholder="Username" type="text" name="username" required="required"/>
                    </div>
                    <div class="field">
                        <input placeholder="Password" type="password" name="password" required="required"/>
                    </div>
                    <?php if (isset($_SESSION['error_login_1'])) {  ?>
                        <div class="error">Username or password incorrect</div>
                    <?php } ?>
                    <button class="btn">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</body>