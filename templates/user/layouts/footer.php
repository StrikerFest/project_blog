<?php
use inc\helpers\Common;
?>


<!-- Post Detail -->
<?php if (str_contains($_SERVER['REQUEST_URI'], 'post/show')) : ?>
    <script src="<?= Common::getAssetPath('js/user/comment.js') ?>"></script>
<?php endif; ?>
