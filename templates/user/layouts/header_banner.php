<?php

use inc\helpers\Common;

?>
<div class="header-banner">
    <?php if (!empty($args['banner_image']) && !empty($args['banner_link'])): ?>
        <a target="_blank" href="<?= $args['banner_link'] ?>">
            <img src="<?= $args['banner_image'] ?>" alt="Header Banner">
        </a>
    <?php else: ?>
        <img src="<?= Common::getAssetPath('images/placeholder-banner.webp') ?>" alt="Header Banner">
    <?php endif; ?>
</div>
