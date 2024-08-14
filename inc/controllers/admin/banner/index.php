<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Banner;

$banners = Banner::getBanners(true);

Common::requireTemplate('admin/banner/index.php', [
    'banners' => $banners,
]);
