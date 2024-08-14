<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Category;

$categories = Category::getCategories(true);

Common::requireTemplate('admin/category/index.php', [
    'categories' => $categories,
]);

exit;