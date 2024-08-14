<?php

use inc\helpers\Common;

?>
<div class="footer-banner">
    <!-- This is where the footer banner will be displayed -->
    <img src="<?= $args['banner_image'] ?? Common::getAssetPath('images/placeholder-banner.webp') ?>" alt="Footer Banner">
</div>
