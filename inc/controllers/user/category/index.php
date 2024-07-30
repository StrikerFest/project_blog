<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Category;
use inc\models\Post;

// Import template của index
Common::requireTemplate('user/category/index.php', [
    'categories' => Category::getCategories(),
]);

exit;