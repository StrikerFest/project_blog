<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Category;

$categories = Category::getCategories();

Common::requireTemplate('admin/category/index.php', [
    'categories' => $categories,
]);

exit;