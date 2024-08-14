<?php

use inc\helpers\Common;

?>
<div class="side-banner-right">
    <!-- This is where the side banner on the right will be displayed -->
    <img src="<?= $args['banner_image'] ?? Common::getAssetPath('images/placeholder-thumbnail.webp') ?>" alt="Side Banner Right">
</div>
