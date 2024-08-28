<?php

require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Banner;

if (isset($_GET['action']) && isset($_GET['id'])) {
    $bannerId = $_GET['id'];
    if ($_GET['action'] === 'delete') {
        Banner::softDeleteBanner($bannerId);
        $_SESSION['toast_message'] = "Banner xóa thành công.";
        $_SESSION['toast_type'] = "success";
    } elseif ($_GET['action'] === 'recover') {
        Banner::recoverBanner($bannerId);
        $_SESSION['toast_message'] = "Banner khôi phục thành công.";
        $_SESSION['toast_type'] = "success";
    }
    header("Location: /admin/banner");
    exit;
}

$banners = Banner::getBanners(false);

Common::requireTemplate('admin/banner/index.php', [
    'banners' => $banners
]);
