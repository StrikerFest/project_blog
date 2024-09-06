<?php

use inc\helpers\Common;

?>
<div class="footer-banner">
    <?php if (!empty($args['banner_image']) && !empty($args['banner_link'])): ?>
        <a target="_blank" href="<?= $args['banner_link'] ?>">
            <img src="<?= $args['banner_image'] ?>" alt="Footer Banner">
        </a>
    <?php else: ?>
        <img src="<?= Common::getAssetPath('images/placeholder-banner.webp') ?>" alt="Footer Banner">
    <?php endif; ?>
</div>
