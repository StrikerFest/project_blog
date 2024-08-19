<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Banner;

$banner = null;
$bannerTypes = Banner::getBannerTypes();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $_POST['id'] ?? null,
        'title' => $_POST['title'] ?? '',
        'text' => $_POST['text'] ?? '',
        'link' => $_POST['link'] ?? '',
        'start_date' => $_POST['start_date'] ?? '',
        'end_date' => $_POST['end_date'] ?? '',
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'type_id' => $_POST['type_id'] ?? null,
        'existing_image_path' => $_POST['existing_image_path'] ?? ''
    ];

    Banner::saveBanner($data, $_FILES);
    header("Location: /admin/banner");
    exit();
}

if (isset($_GET['id'])) {
    $banner = Banner::getBannerById($_GET['id'],false);
}

Common::requireTemplate('admin/banner/edit.php', [
    'banner' => $banner,
    'banner_types' => $bannerTypes
]);
