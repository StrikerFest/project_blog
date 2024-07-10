<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Category;

Category::save_category();

$category = null;

if(isset($_GET['id'])){
    $category = Category::getCategoryById($_GET['id']);
}

Common::requireTemplate('admin/category/edit.php', [
    'category' => $category
]);
