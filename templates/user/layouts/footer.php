<?php
use inc\helpers\Common;
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Post Detail -->
<?php if (str_contains($_SERVER['REQUEST_URI'], 'post/show')) : ?>
    <script src="<?= Common::getAssetPath('js/user/comment.js') ?>"></script>
<?php endif; ?>
