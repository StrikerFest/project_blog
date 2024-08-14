<?php

use inc\helpers\Common;

?>
<div class="header-banner">
    <!-- This is where the header banner will be displayed -->
    <img src="<?= $args['banner_image'] ?? Common::getAssetPath('images/placeholder-banner.webp') ?>" alt="Header Banner">
</div>
