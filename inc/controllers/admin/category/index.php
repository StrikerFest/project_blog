<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Category;

$filters = [
    'id' => $_GET['id'] ?? '',
    'name' => $_GET['name'] ?? '',
    'slug' => $_GET['slug'] ?? '',
    'status' => $_GET['status'] ?? '',
    'position' => $_GET['position'] ?? ''
];

$includeDeleted = isset($_GET['include_deleted']);

$categories = Category::getFilteredCategories($filters, $includeDeleted);

Common::requireTemplate('admin/category/index.php', [
    'categories' => $categories,
]);

exit;
