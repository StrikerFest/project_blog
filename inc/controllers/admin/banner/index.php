<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Banner;

$banners = Banner::getBanners(false);

Common::requireTemplate('admin/banner/index.php', [
    'banners' => $banners,
]);
