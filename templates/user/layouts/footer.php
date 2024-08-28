<?php
use inc\helpers\Common;
?>


<!-- Post Detail -->
<?php if (str_contains($_SERVER['REQUEST_URI'], 'post/show')) : ?>
    <script src="<?= Common::getAssetPath('js/user/comment.js') ?>"></script>
<?php endif; ?>
<!-- Google Translate script -->
<!--<script type="text/javascript">-->
<!--    function googleTranslateElementInit() {-->
<!--        new google.translate.TranslateElement({-->
<!--            pageLanguage: 'en',-->
<!--            includedLanguages: 'vi',-->
<!--        }, 'google_translate_element');-->
<!--    }-->
<!--</script>-->
<!--<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>-->
