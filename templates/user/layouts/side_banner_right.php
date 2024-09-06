<?php

use inc\helpers\Common;

?>
<div class="side-banner-right">
    <?php if (!empty($args['banner_image']) && !empty($args['banner_link'])): ?>
        <a target="_blank" href="<?= $args['banner_link'] ?>">
            <img src="<?= $args['banner_image'] ?>" alt="Side Banner Right">
        </a>
    <?php else: ?>
        <img src="<?= Common::getAssetPath('images/placeholder-thumbnail.webp') ?>" alt="Side Banner Right">
    <?php endif; ?>
</div>
